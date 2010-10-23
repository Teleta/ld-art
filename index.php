<?php
ob_start();
if(isset($_GET['lang'])){
	$lang = str_replace(";", '', substr($_GET['lang'], 0, 3));
	setcookie('language', $lang, 0, '/');
}elseif(isset($_COOKIE['language'])){
	$lang = $_COOKIE['language'];
}else{
	setcookie('language', 'en', 0, '/');
	$lang = 'en';
}
error_reporting(E_ALL);
require_once('cfg.php');
$DB = new mysql($sql);
$sess = new session();

$action = (isset($_REQUEST['action']) AND !empty($_REQUEST['action'])) ? $_REQUEST['action'] : 'home';

switch($action){
case "exhibitions":
	require_once(ROOT.'/exhibitions.php');
	break;
case "about":
	require_once(ROOT.'/about.php');
	break;
case "links":
	require_once(ROOT.'/links.php');
	break;
case "catalog":
	require_once(ROOT.'/catalog.php');
	break;
case "project":
	require_once(ROOT.'/project.php');
	break;
case "home":
default:
	require_once(ROOT.'/home.php');
	break;
}
ob_flush();
?>