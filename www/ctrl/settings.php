<?php
require_once('./core/controller.php');
require_once('./util/header.php');

class SettingsController extends Controller{

    public function show($errorMsg)
    {
        $settingsHtml = file_get_contents('./view/html/settings.html');
        $settingsHtml = insertHeader($settingsHtml, $this->session);
        $settingsHtml = FileUtils::insertUploadArea($settingsHtml, 'profilepicture');

        $sql  = 'SELECT *';
        $sql .= '  FROM User';
        $sql .= '  WHERE ID = :ID;';
    
        $params = array(':ID' => $_SESSION['userID']);

        $cmd = new SQLCommand($sql, $params);
        $sqlResult = $cmd->execute();
        $row = $sqlResult->getRow();

        if($row['ProfilePicture']){
            $pic = $row['ProfilePicture'];
        }else{
            $pic = '/static/img/preload-background.png';
        }

        $settingsHtml = str_replace('<!-- user name  -->', '<p>' . $row['Name'] . '</p>', $settingsHtml);
        $settingsHtml = str_replace('<!-- user creationtime  -->', '<p>' . $row['CreationTime'] . '</p>', $settingsHtml);
        $settingsHtml = str_replace('profile-pic-source-placeholder', $pic, $settingsHtml);
        $settingsHtml = str_replace('user-name-placeholder', $row['Name'], $settingsHtml);
        $settingsHtml = str_replace('<!-- handle link  -->', '<a href="/' . $row['Handle'] . '"><p>@' . $row['Handle'] . '</p></a>', $settingsHtml);
        
        switch($errorMsg){
            case "same":
				$errorMsgHtml = '<p class="errorMessage">Old password can not be the new password</p>';
				$settingsHtml = str_replace('<!-- error Message old pw invalid -->', $errorMsgHtml, $settingsHtml);
			case "wrongPw":
				$errorMsgHtml = '<p class="errorMessage">wrong password</p>';
				$settingsHtml = str_replace('<!-- error Message old pw invalid -->', $errorMsgHtml, $settingsHtml);
			case "failed":
				$errorMsgHtml = '<p class="errorMessage">Sorry, something did not work right...</p>';
				$settingsHtml = str_replace('<!-- error Message old pw invalid -->', $errorMsgHtml, $settingsHtml);
		}

        echo($settingsHtml);
    }

    public function changeProfilePicture($params){
        header("Location: /settings?tab=0");
    }

    public function changeName($params){
        if(isset($_POST['name_settings'])){
            $sql  = 'UPDATE User';
            $sql .= '  SET Name = :Name';
            $sql .= '  WHERE ID = :ID;';
        
            $params = array(':ID' => $_SESSION['userID'], ':Name' => $_POST['name_settings']);
    
            $cmd = new SQLCommand($sql, $params);
            $sqlResult = $cmd->execute();
        }
        header("Location: /settings?tab=1");
    }

    public function changePassword($params){
        if(!($_POST['old_password_settings'] == $_POST['new_password_settings'])){

            $sql  = 'SELECT ID';
            $sql .= '  FROM User';
            $sql .= '  WHERE ID = :ID';
            $sql .= ' AND Password = :Password;';

                
            $pwHash = hash('sha256', $_POST['old_password_settings']); 
            $params = array(':ID' => $_SESSION['userID'], ':Password' => $pwHash);

            $cmd = new SQLCommand($sql, $params);
            $sqlResult = $cmd->execute();


            if($sqlResult->isEmpty()){
                header('Location: /settings/show/wrongPw?tab=2');
            }else{
        
                $newPwHash = hash('sha256', $_POST['new_password_settings']); 
                $sql  = 'UPDATE User';
                $sql .= '  SET Password = :Password';
                $sql .= '  WHERE ID = :ID;';
                    
                $params = array(':ID' => $_SESSION['userID'], ':Password' => $newPwHash);
        
                $cmd = new SQLCommand($sql, $params);
                $cmd->execute();

                header("Location: /login/logout");
                
            }
        }else{
            header("Location: /settings/show/same?tab=2;");
        }
    }



}
?>