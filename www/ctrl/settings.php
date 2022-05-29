<?php
require_once('./core/controller.php');
require_once('./util/header.php');
require_once('./util/debug.php');

class SettingsController extends Controller{

    public function show($params)
    {
        console_log('sdf');
        $settingsHtml = file_get_contents('./view/html/settings.html');
        $settingsHtml = insertHeader($settingsHtml);
        echo($settingsHtml);
        console_log($settingsHtml);
        console_log('sdf');
    }

}
?>