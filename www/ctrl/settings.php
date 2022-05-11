<?php
require_once('./core/controller.php');

class SettingsController extends Controller{

    public function show()
    {
        $settingsHtml = file_get_contents('./view/html/settings.html');
        echo($settingsHtml);
    }

}
?>