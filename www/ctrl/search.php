<?php
require_once('./core/controller.php');
require_once('./util/header.php');
require_once('./util/uuid.php');
require_once('./util/element.php');
require_once('./util/footer.php');

class SearchController extends Controller{

    public function show($params)
    {
        $searchHtml = file_get_contents('./view/html/search.html');
        $searchHtml = insertHeader($searchHtml, $this->session);
        $searchHtml = Footer::insert($searchHtml);

        if($params == ""){
            $paramsSearch = $_POST['search'];
            $url = $_SERVER['REQUEST_URI'] . '/show/' . $paramsSearch;
            header("Location: $url");
        }else{
            $paramsSearch = $params;
        }
        
        $searchHtml = str_replace('<!-- searchParams -->', $paramsSearch, $searchHtml);
        

        



        

        if(!empty($_POST['sorting'])){
            $sorting = $_POST['sorting'];
        }else{
            $sorting = 1;
        }
        $searchHtml = str_replace("#" . $sorting, "selected", $searchHtml);
        $sqlUser = "";
        $sqlPosts = "";
        $sqlSticker = "Select Sticker.*, User.Handle From Sticker Left Outer Join User On User.ID = Sticker.CreatorID Where ( Sticker.Name + Sticker.Description) Like '%:SearchParam%' ;";
        switch($sorting){

            case 1:
                #Trending
                $sqlUser = 'Select U.*, Count(Point.UserID) From User AS U Left Outer Join Post AS P On U.ID = P.CreatorID Left Outer Join Point On Point.PostID = P.ID Where Point.Time  >= (now() - interval 30 minute) AND ( U.Handle + U.Name + U.CreationTime) Like "%:SearchParam%"  Group by U.ID Order by Count(Point.UserID) DESC;';
                $sqlPosts = 'Select P.*, Count(Point.UserID) From Post AS P Left Outer Join Point On Point.PostID = P.ID Where Point.Time  >= (now() - interval 30 minute) AND ( P.Content + P.CreationTime) Like "%:SearchParam%"  Group by P.ID Order by Count(Point.UserID) DESC;';
            case 2:
                #Likes
                $sqlPosts ='Select * From Post AS P Left Outer Join Point On Point.PostID = P.ID Where ( P.Content + P.CreationTime) Like "%:SearchParam%"  Group by P.ID Order by Count(Point.UserID) DESC;';
                $sqlUser = 'Select U.*, Count(Point.UserID) From User AS U Left Outer Join Post AS P On U.ID = P.CreatorID Left Outer Join Point On Point.PostID = P.ID Where ( U.Handle + U.Name + U.CreationTime) Like "%:SearchParam%"  Group by U.ID Order by Count(Point.UserID) DESC;';
            case 3:
                #Newest
                $sqlUser = 'Select * From User AS U Where ( U.Handle + U.Name + U.CreationTime) Like "%:SearchParam%" Order By U.CreationTime DESC;';
                $sqlPosts = 'Select * From Post AS P Where ( P.Content + P.CreationTime) Like "%:SearchParam%" Order By P.CreationTime DESC;';
            case 4:
                #Oldest
                $sqlUser = 'Select * From User AS U Where ( U.Handle + U.Name + U.CreationTime) Like "%:SearchParam%" Order By U.CreationTime ASC;';
                $sqlPosts = 'Select * From Post AS P Where ( P.Content + P.CreationTime) Like "%:SearchParam%" Order By P.CreationTime ASC;';
        }   

        $params = array(':SearchParam' => $paramsSearch);

		$cmdUser = new SQLCommand($sqlUser, $params);
		$sqlResultUser = $cmdUser->execute();

        if($sqlResultUser->isEmpty()){
            $searchHtml = str_replace( "<!-- user elements -->", '<p>No results found</p>', $searchHtml);
        }else{
            $html = '';
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
                            <a href="/' . $handle . '"><img loading="lazy"  class="profilePic" src="' . $imgUrl . '" alt="User"></a>
                            <div class="info">
                                <p>' . $creationTime . '</p>
                                <p>handle: <a href="/' . $handle . '">' . $handle . '</a></p>
                            </div>
                        </div>';

            }while($sqlResultUser->next());
            $searchHtml = str_replace( "<!-- user elements -->", $html, $searchHtml);
        }

        $sqlPosts = "Select * From Post";

        $cmdPosts = new SQLCommand($sqlPosts, $params);
		$sqlResultPosts = $cmdPosts->execute();

        if($sqlResultPosts->isEmpty()){
            $searchHtml = str_replace( "<!-- post elements -->", '<p>No results found</p>', $searchHtml);
        }else{
            $postElements = "<!-- element placeholder -->";
            do{
                $row = $sqlResultPosts->getRow();

                $postElements = ElementUtils::insertElement($row['ID'], $postElements);

            }while($sqlResultPosts->next());
            $searchHtml = str_replace( "<!-- post elements -->", $postElements, $searchHtml);
        }


        $cmdSticker = new SQLCommand($sqlSticker, $params);
		$sqlResultSticker = $cmdSticker->execute();

        if($sqlResultSticker->isEmpty()){
            $searchHtml = str_replace( "<!-- sticker elements -->", '<p>No results found</p>', $searchHtml);
        }else{
            $html = '';
            do{
                $row = $sqlResultSticker->getRow();

                $name = $row['Name'];
                $creator = $row['Handle'];
                $creationTime = $row['CreationTime'];
                $imgUrl = FileUtils::generateStickerURL(null, $row['ID'], true); 

                $html .= '<div class="sticker">
                             <div class="info">
                                <p>' . $name . '</p>
                             </div>
                             <a href=""><img loading="lazy" src="' .  $imgUrl. '" alt="Sticker"></a>
                            <div class="info">
                                <p>' . $creationTime . '</p>
                                <p>Creator: <a href="/' . $creator . '">' . $creator . '</a></p>
                            </div>
                        </div>';

            }while($sqlResultSticker->next());
            $searchHtml = str_replace( "<!-- sticker elements -->", $html, $searchHtml);
        }
        

        echo($searchHtml);
    }

}
?>