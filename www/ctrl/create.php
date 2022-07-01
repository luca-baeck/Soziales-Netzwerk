<?php
require_once('./core/controller.php');
require_once('./util/header.php');
require_once('./util/uuid.php');

class CreateController extends Controller{

    private IDFactory $idFactory;

    public function show($errorCode)
    {
        $createHtml = file_get_contents('./view/html/create.html');
        $createHtml = insertHeader($createHtml, $this->session);
        $createHtml = FileUtils::insertUploadArea($createHtml, 'profilepicture');

        $sql  = 'Select Sticker.*, User.Handle';
		$sql .= '  From Sticker ';
        $sql .= '   Left Outer Join User On Sticker.CreatorID = User.ID';
        $sql .= '  ORDER BY Sticker.CreationTime DESC;';
          
        $params = array();
		$cmd = new SQLCommand($sql, $params);
		$sqlResult = $cmd->execute();
        $html = "";
        if($sqlResult->isEmpty()){
            echo("No Stickers available");
        }else{
            do{
                
                $row = $sqlResult->getRow();
                $imgUrl = FileUtils::generateStickerURL(null, $row['ID']); 
                $stripID = UUIDUtils::strip($row['ID']);
                $id = '"' . $stripID . '"';
                $html .= "<div class='sticker' onclick='selectSticker(" . $id . " , this);'>
                            <img  loading='lazy' src='" . $imgUrl . "' alt='" . $row['ID'] . "'>
                        </div>
                        <script>
                            stickerInfo[" . $id . "] = " . json_encode($row) . ";
                        </script>";

            }while($sqlResult->next());
        }

        $createHtml = str_replace('<!-- Stickers -->', $html, $createHtml);
        echo($createHtml);
    }

	public function create($params){

        if(!isset($_POST['StickerID']) or !isset($_POST['contentPost'])){
            header('Location: /create');
        }

        $this->idFactory = new UUIDFactory();
		$uuid = $this->idFactory->create();
		$creatorID = $_SESSION['userID'];
        $stickerID = $_POST['StickerID'];
        $content = $_POST['contentPost'];
		
        $sql  = 'INSERT INTO Post (ID, CreatorID, StickerID, Content)';
		$sql .= '   VALUES ( :uuid, :userID, :StickerID, :content);';
		
		$params = array(':uuid' => $uuid, ':userID' => $creatorID, ':StickerID' => $stickerID, ':content' => $content,);

		$cmd = new SQLCommand($sql, $params);
		$sqlResult = $cmd->execute();

		header('Location: /land');

	}


}
?>