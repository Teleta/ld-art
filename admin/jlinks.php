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
		$sort = $sql->fetch($sql->query("SELECT MAX(sort) AS max FROM `".PREF."links`"));
		$res = $sql->query("INSERT INTO `".PREF."links` VALUES (NULL, '".mysql_real_escape_string($_REQUEST['url'])."', '".mysql_real_escape_string($_REQUEST['eng'])."', '".mysql_real_escape_string($_REQUEST['rus'])."', '".($sort['max']+1)."')");
		$GLOBALS['_RESULT'] = array(
			"id" => mysql_insert_id(),
			"ok"=>true
		);
		break;
	case "delete":
		$sql->query("DELETE FROM `".PREF."links` WHERE id='".$_REQUEST['pid']."'");
		$GLOBALS['_RESULT'] = array();
		break;
	case "edit":
		$q = array();
		if($_REQUEST['url'] != ''){
			$q[] = "url='".mysql_real_escape_string($_REQUEST['url'])."'";
		}else{
			echo 'no url !!!';
			return false;
		}
		if($_REQUEST['e'] != ''){
			$q[] = "description_e='".mysql_real_escape_string($_REQUEST['e'])."'";
		}
		if($_REQUEST['r'] != ''){
			$q[] = "description_r='".mysql_real_escape_string($_REQUEST['r'])."'";
		}
		$sql->query("UPDATE `".PREF."links` SET ".(implode(", ", $q))." WHERE id='".$_REQUEST['pid']."' LIMIT 1");
		break;
	case "resort":
		$q1 = "SELECT `id` AS cat, `sort` FROM `".PREF."links` WHERE id='".$_REQUEST['re']."' LIMIT 1";
		$r = $sql->fetch($sql->query($q1));
		$q2 = "SELECT MAX(sort) AS max, MIN(sort) AS min
			FROM `".PREF."links`";
		$r2 = $sql->fetch($sql->query($q2));
		switch($_REQUEST['to']){
			case "up":
				if($r['sort'] != $r2['min']){
					$q4 = "UPDATE `".PREF."links` SET sort=sort-1 WHERE id='".$_REQUEST['re']."' LIMIT 1";
					$q3 = "UPDATE `".PREF."links` SET sort=sort+1
						WHERE sort='".($r['sort']-1)."' LIMIT 1";
				}
				break;
			case "down":
				if($r['sort'] != $r2['max']){
					$q4 = "UPDATE `".PREF."links` SET sort=sort+1 WHERE id='".$_REQUEST['re']."' LIMIT 1";
					$q3 = "UPDATE `".PREF."links` SET sort=sort-1
						WHERE sort='".($r['sort']+1)."' LIMIT 1";
				}
				break;
		}
		if(isset($q3) AND isset($q4)){
			$sql->query($q3);
			$sql->query($q4);
		}
		break;
}

?>