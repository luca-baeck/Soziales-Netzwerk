<?php
require_once('./core/controller.php');
require_once('./util/header.php');
require_once('./util/uuid.php');

class SearchController extends Controller{

    public function show($params)
    {
        if(!isset($_POST["search"])){
            header("Location: /land");
        }

        $searchHtml = file_get_contents('./view/html/search.html');
        $searchHtml = insertHeader($searchHtml, $this->session);

        $searchHtml = str_replace('#searchParamPlaceholder', $_POST["search"], $searchHtml);

        $searchParam = $_POST["search"];
        if(isset($_POST['sorting'])){
            $sorting = $_POST['sorting'];
        }else{
            $sorting = 1;
        }
        $sqlUser = "";
        $sqlPosts = "";
        $sqlSticker = "Select * From Sticker;";
        switch($sorting){

            case 1:
                #Trending
                $sqlUser = 'Select U.*, Count(Point.UserID) From User AS U Left Outer Join Post AS P On U.ID = P.CreatorID Left Outer Join Point On Point.PostID = P.ID Where Point.Time  >= (now() - interval 30 minute) AND ( U.Handle + U.Name + U.CreationTime) Like "%:SearchParam%"  Group by U.ID Order by Count(Point.UserID) DESC;';
                $sqlPosts = 'Select P.*, Count(Point.UserID) From Post AS P Left Outer Join Point On Point.PostID = P.ID Where Point.Time  >= (now() - interval 30 minute) AND ( P.Content + P.CreationTime) Like "%:SearchParam%"  Group by P.ID Order by Count(Point.UserID) DESC;';
            case 2:
                #Likes
                $sqlUser = 'Select U.*, Count(Point.UserID) From User AS U Left Outer Join Post AS P On U.ID = P.CreatorID Left Outer Join Point On Point.PostID = P.ID Where ( U.Handle + U.Name + U.CreationTime) Like "%:SearchParam%"  Group by U.ID Order by Count(Point.UserID) DESC;';
                $sqlPosts = 'Select P.*, Count(Point.UserID) From Post AS P Left Outer Join Point On Point.PostID = P.ID Where ( P.Content + P.CreationTime) Like "%:SearchParam%"  Group by P.ID Order by Count(Point.UserID) DESC;';
            case 3:
                #Newest
                $sqlUser = 'Select * From User AS U Where ( U.Handle + U.Name + U.CreationTime) Like "%:SearchParam%" Order By U.CreationTime DESC;';
                $sqlPosts = 'Select * From Post AS P Where ( P.Content + P.CreationTime) Like "%:SearchParam%" Order By P.CreationTime DESC;';
            case 4:
                #Oldest
                $sqlUser = 'Select * From User AS U Where ( U.Handle + U.Name + U.CreationTime) Like "%:SearchParam%" Order By U.CreationTime ASC;';
                $sqlPosts = 'Select * From Post AS P Where ( P.Content + P.CreationTime) Like "%:SearchParam%" Order By P.CreationTime ASC;';
        }   

        $params = array(':SearchParam' => $searchParam);

		$cmdUser = new SQLCommand($sqlUser, $params);
		$sqlResultUser = $cmdUser->execute();

        if($sqlResultUser->isEmpty()){
            $searchHtml = str_replace( " <!-- post elements -->", '<p>No results found</p>', $searchHtml);
        }else{
            do{
                $row = $sqlResultUser->getRow();

                $name = $row['Name'];
                $handle = $row['Handle'];
                $creationTime = $row['CreationTime'];
                $imgUrl = FileUtils::generateProfilePictureURL($row['ID']); 

                $html .= '<div class="sticker">
                                <div class="info">
                                    <p>' . $name . '</p>
                                </div>
                            <a href="/' . $handle . '"><img class="profilePic" src="' . $imgUrl . '" alt="User"></a>
                            <div class="info">
                                <p></p>
                                <p>handle: <a href="#handleLinkPlaceholder">#handlePlaceholder</a></p>
                            </div>
                        </div>';

            }while($sqlResultUser->next());
        }



        $cmdPosts = new SQLCommand($sqlPosts, $params);
		$sqlResultPosts = $cmdPosts->execute();
		$row = $sqlResultPosts->getRow();


        $cmdSticker = new SQLCommand($sqlSticker, $params);
		$sqlResultSticker = $cmdSticker->execute();
		$row = $sqlResultSticker->getRow();


        echo($searchHtml);
    }

}
?>