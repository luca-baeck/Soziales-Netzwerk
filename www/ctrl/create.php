<?php
require_once('./core/controller.php');
require_once('./util/header.php');
require_once('./util/uuid.php');

class CreateController extends Controller{

    public function show($errorCode)
    {
        $createHtml = file_get_contents('./view/html/create.html');
        $createHtml = insertHeader($createHtml, $this->session);

        $sql  = 'SELECT *';
		$sql .= '  FROM Sticker';

        $params = array();
		$cmd = new SQLCommand($sql, $params);
		$sqlResult = $cmd->execute();
        $row = $sqlResult->getRow();
        
        $stripID = UUIDUtils::strip($row['ID']);

        $html = "<div class='sticker' onclick='selectSticker(" . $stripID . " , this);'>
                    <img src='/static/img/preload-background.png' alt='" . $stripID . "'>
                </div>
                <script>
                    stickerInfo.append(" . json_encode($row) . ");
                </script>";

        $createHtml = str_replace('<!-- Stickers -->', $html, $createHtml);
        echo($createHtml);
    }

}
?>