<?php
error_reporting(0);
require_once("../cfg.php");
require_once(ROOT."/classes/adm_authorize.php");
// Load JsHttpRequest backend.
require_once(ROOT."/classes/JsHttpRequest/JsHttpRequest.php");

$sql = new mysql($sql);
$auth = new adm_authorize($sql);

// Create main library object. You MUST specify page encoding!
$JsHttpRequest = new JsHttpRequest("windows-1251");

if(!$auth->check()){
	$GLOBALS['_RESULT'] = array(
		"q" => "You are not logged in"
	);
	exit;
}

switch($_REQUEST['q']){
	case "newrow":
		$res = $sql->query("INSERT INTO `".PREF."keys` VALUES (NULL, '".$_REQUEST['rid']."', '".$_REQUEST['eng']."', '".$_REQUEST['rus']."')");
		$GLOBALS['_RESULT'] = array(
			"radioid" => mysql_insert_id(),
			"ok"=>true
		);
		break;
	case "deletekey":
		$sql->query("DELETE FROM `".PREF."keys` WHERE key_id='".$_REQUEST['pid']."'");
		$GLOBALS['_RESULT'] = array();
		break;
	case "visibility":
		if(($_REQUEST['state'] == 0) OR ($_REQUEST['state'] == 1)){
			$sql->query("UPDATE `".PREF."keys` SET key_show='".$_REQUEST['state']."' WHERE key_id='".$_REQUEST['pid']."' LIMIT 1");
		}
		break;
	case "editkeys":
		$q = array();
		if($_REQUEST['ke'] != ''){
			$q[] = "key_name_eng='".$_REQUEST['ke']."'";
		}
		if($_REQUEST['kr'] != ''){
			$q[] = "key_name_rus='".$_REQUEST['kr']."'";
		}
		$sql->query("UPDATE `".PREF."keys` SET ".(implode(", ", $q))." WHERE key_id='".$_REQUEST['pid']."' LIMIT 1");
		break;
}

?>
