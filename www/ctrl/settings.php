<?php
require_once('./core/controller.php');
require_once('./util/header.php');

class SettingsController extends Controller{

    public function show($params)
    {
        $settingsHtml = file_get_contents('./view/html/settings.html');
        $settingsHtml = insertHeader($settingsHtml, $this->session);

        $sql  = 'SELECT Handle, ProfilePicture';
        $sql .= '  FROM User';
        $sql .= '  WHERE ID = :ID';
    
        $params = array(':ID' => $_SESSION['userID']);

        $cmd = new SQLCommand($sql, $params);
        $sqlResult = $cmd->execute();
        $row = $sqlResult->getRow();

        if($row['ProfilePicture']){
            $pic = $row['ProfilePicture'];
        }else{
            $pic = '/static/img/preload-background.png';
        }

        $settingsHtml = str_replace('profile-pic-source-placeholder', $pic, $settingsHtml);
        $settingsHtml = str_replace('<!-- handle link  -->', '<a href="/' . $row['Handle'] . '"><p>@' . $row['Handle'] . '</p></a>', $settingsHtml);
        
        echo($settingsHtml);
    }

}
?>