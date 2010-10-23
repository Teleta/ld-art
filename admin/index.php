<?php
ob_start();
//var_dump($_SERVER);
require_once('../cfg.php');
require_once(ROOT."/classes/adm_authorize.php");
$DB = new mysql($sql);
$sess = new session();
$auth = new adm_authorize($DB);
if(!$auth->check()){ require_once(ROOT.'/admin/login.php'); }

$_REQUEST['action'] = (isset($_REQUEST['action']) AND !empty($_REQUEST['action'])) ? $_REQUEST['action'] : 'home';

$tmenu = new e_Template('adm_menu', ROOT."/tpl/");
$tmenu->parse();
$output = $tmenu->out();

$gt = new e_Template('adm', ROOT."/tpl/");
switch($_REQUEST['action']){
	case "image":
		require_once(ROOT."/admin/image.php");
		break;
	case "links":
		require_once(ROOT."/admin/links.php");
		break;
	case "exhibitions":
		require_once(ROOT."/admin/exh.php");
		break;
	case "about":
		require_once(ROOT."/admin/about.php");
		break;
	case "keys":
		require_once(ROOT."/admin/keys.php");
		break;
	case "pr":
		require_once(ROOT."/admin/project.php");
		break;
	// uploading files
	case "upi":
		require_once(ROOT."/admin/upload.php");
		break;
	case "home":
	default:
		$gt->assign('title', 'Welcome to administrator interface');
		break;
}

//$gt->assign('title', $title);
$gt->assign('content', $output);
$gt->parse();
echo $gt->out();
ob_flush();
?>