<?php
require_once('./util/string.php');
require_once('./core/session.php');

function insertHeader($html, Session $session){
	$header_file = file_get_contents('./static/header.html');
	$header = '<header>';
	$header .= StringUtils::stringBetweenTwoStrings($header_file, '<header>', '</header>');
	$header .= '</header>';
	$styles = StringUtils::stringBetweenTwoStrings($header_file, '<!-- header styles start -->', '<!-- header styles end -->');

	$html = str_replace('<!-- header stylesheets -->', $styles, $html);
	$html = str_replace('<header></header>', $header, $html);

	if($session->isLoggedIn()){
		
		$sql  = 'SELECT Handle';
			$sql .= '  FROM User';
			$sql .= '  WHERE ID = :ID';
		
			$params = array(':ID' => $_SESSION['userID']);

			$cmd = new SQLCommand($sql, $params);
			$sqlResult = $cmd->execute();
			$row = $sqlResult->getRow();

			$pic = $_SESSION['user']->getProfilePictureURL();

		$html = str_replace('<i class="fa-solid fa-user dropbtn header-icon"></i>', '<img src="' . $pic . '" alt="profile picture" class="header-profile-pic">', $html);
		$html = str_replace('<!-- LogIn link -->', '<a href="/login/logout" class="login-logout">Log Out</a>', $html);
		$html = str_replace('<!-- Profile link -->', '<a href="/' . $row['Handle'] . '">Profile</a>', $html);
	}else{
		$html = str_replace('<!-- LogIn link -->', '<a href="/login" class="login-logout">Log In</a>', $html);
		$html = str_replace('<!-- Profile link -->', '<a href="/login">Profile</a>', $html);
	}

	return $html;
}

?>