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
		$qu = "INSERT INTO `".PREF."exhibitions`
			VALUES (NULL, 
			".(!empty($_REQUEST['date1']) ? ("'".$_REQUEST['date1']."'") : "NOW()" ).",
			".(!empty($_REQUEST['date2']) ? ("'".$_REQUEST['date2']."'") : "NOW()" ).",
			'".mysql_real_escape_string($_REQUEST['loc_eng'])."',
			'".mysql_real_escape_string($_REQUEST['loc_rus'])."',
			'".mysql_real_escape_string($_REQUEST['eng'])."',
			'".mysql_real_escape_string($_REQUEST['rus'])."'
			)";
		$res = $sql->query($qu);
		$GLOBALS['_RESULT'] = array(
			"id" => mysql_insert_id(),
			"ok" => true,
			"query" => $qu,
			"err" => mysql_error()
		);
		break;
	case "delete":
		$sql->query("DELETE FROM `".PREF."exhibitions` WHERE id='".$_REQUEST['pid']."'");
		$GLOBALS['_RESULT'] = array();
		break;
	case "edit":
		$q = array();
		if($_REQUEST['date1'] != ''){
			$q[] = "date_from='".mysql_real_escape_string($_REQUEST['date1'])."'";
		}
		if($_REQUEST['date2'] != ''){
			$q[] = "date_to='".mysql_real_escape_string($_REQUEST['date2'])."'";
		}
		if($_REQUEST['eng'] != ''){
			$q[] = "description_e='".mysql_real_escape_string($_REQUEST['eng'])."'";
		}
		if($_REQUEST['rus'] != ''){
			$q[] = "description_r='".mysql_real_escape_string($_REQUEST['rus'])."'";
		}
		if($_REQUEST['loc_eng'] != ''){
			$q[] = "location_e='".mysql_real_escape_string($_REQUEST['loc_eng'])."'";
		}
		if($_REQUEST['loc_rus'] != ''){
			$q[] = "location_r='".mysql_real_escape_string($_REQUEST['loc_rus'])."'";
		}
		$sql->query("UPDATE `".PREF."exhibitions` SET ".(implode(", ", $q))." WHERE id='".$_REQUEST['pid']."' LIMIT 1");
		break;
}

?>