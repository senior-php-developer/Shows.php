<?php
require 'includes/facebook.php';
$db = Factory::getDB();

$facebook = new Facebook(array('appId'  => '148223125225042','secret' => '7056ac511bca93e65d365422adf031c2','cookie' => true,));
$session = $facebook->getSession();

if ($session) try {$uid = $facebook->getUser();} catch (FacebookApiException $e) { error_log($e);}
$user = getUser($uid);

function getLoginLink() {
	global $facebook, $user;
	if ($user) {
		$html = 'Welcome, '.$user['name'];
		$html .= ' | <a href="'. $facebook->getLogoutUrl() .'">logout</a>';
	}	else 
		$html = '<fb:login-button></fb:login-button>';
	return $html;
}

function getUser($uid) {
	global $facebook, $db;
	try {
		$ret = $facebook->api('/me');
	} catch (FacebookApiException $e) {
		$ret = null;
  }
	return $ret;
}

?>