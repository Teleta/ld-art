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

$error = 0;

switch($_REQUEST['q']){
	case "update":
		$r = $sql->fetch($sql->query("SELECT MAX(p_order) AS max FROM `".PREF."projects` WHERE p_cat_id='".mysql_real_escape_string($_REQUEST['cat'])."'"));
		$r2 = $sql->fetch($sql->query("SELECT p_cat_id AS cat FROM `".PREF."projects` WHERE p_id='".mysql_real_escape_string($_REQUEST['id'])."' LIMIT 1"));
		$q = "UPDATE `".PREF."projects`
			SET p_name_r='".mysql_real_escape_string($_REQUEST['nr'])."',
			p_name_e='".mysql_real_escape_string($_REQUEST['ne'])."',
			p_cat_id='".mysql_real_escape_string($_REQUEST['cat'])."'".
			(($r2['cat'] != $_REQUEST['cat']) ? ", p_order='".($r['max']+1)."'" : '')."
			WHERE p_id='".mysql_real_escape_string($_REQUEST['id'])."' LIMIT 1";
		$sql->query($q);
		echo mysql_error();
		echo $q;
		break;
	case "fillselect":
		$keys = $values = array();
		$sql->query("SELECT * FROM `".PREF."category` ORDER BY `cat_id`");
		while($row = $sql->fetch()){
			$keys[] = $row['cat_id'];
			$values[] = $row['cat_name_eng']." | ".$row['cat_name_rus'];
		}
		$GLOBALS['_RESULT'] = array(
			'k' => $keys,
			'v' => $values
		);
		break;
	case "loadimages":
		$keys = $values = array();
		$sql->query("SELECT `img_id`, `img_filename` FROM `".PREF."img` WHERE `img_project_id`='0'");
		while($row = $sql->fetch()){
			$keys[] = $row['img_id'];
			$values[] = $row['img_filename'];
		}
		$GLOBALS['_RESULT'] = array(
			'k' => $keys,
			'v' => $values
		);
		break;
	case "saveform":
		$sql->query("SELECT MAX(p_order) AS max FROM `".PREF."projects` WHERE p_cat_id='".$_REQUEST['category']."' GROUP BY p_cat_id LIMIT 1");
		$r = $sql->fetch();
		$sql->query("INSERT INTO `".PREF."projects` VALUES (NULL, '".$_REQUEST['category']."', '".
			$_REQUEST['pname_e']."', '".$_REQUEST['pname_r']."', NOW(), '".((isset($r['max']) AND !empty($r['max']))? $r['max']+1 : 1 )."', '0')");
		$id = mysql_insert_id();
		$f = $sql->fetch($sql->query("SELECT img_filename AS fname
			FROM `".PREF."img`
			WHERE img_id='".$_REQUEST['image']."' LIMIT 1"));
		$oldname = $f['fname'];
		while(file_exists(ROOT."/images/".$f['fname'].".jpg")){
			$f['fname'] = md5(md5(rand(0, 1000000)).$f['fname'].md5(rand(0, 100000)));
		}
		$sql->query("UPDATE `".PREF."img`
			SET img_project_id='".$id."', img_order=1, img_filename='".$f['fname']."'
			WHERE img_id='".$_REQUEST['image']."'");
		echo mysql_error();
		rename(ROOT."/images/unsorted/".$oldname.".jpg", ROOT."/images/".$f['fname'].".jpg");
		rename(ROOT."/images/unsorted/thumb_".$oldname.".jpg", ROOT."/images/thumb_".$f['fname'].".jpg");
		break;
	case "resort":
		$q1 = "SELECT `p_cat_id` AS cat, `p_order` FROM `".PREF."projects` WHERE p_id='".$_REQUEST['re']."' LIMIT 1";
		$r = $sql->fetch($sql->query($q1));
		$q2 = "SELECT MAX(p_order) AS max, MIN(p_order) AS min
			FROM `".PREF."projects`
			WHERE p_cat_id='".$r['cat']."'";
		$r2 = $sql->fetch($sql->query($q2));
		switch($_REQUEST['to']){
			case "up":
				if($r['p_order'] != $r2['min']){
					$q4 = "UPDATE `".PREF."projects` SET p_order=p_order-1 WHERE p_id='".$_REQUEST['re']."' LIMIT 1";
					$q3 = "UPDATE `".PREF."projects` SET p_order=p_order+1
						WHERE p_cat_id='".$r['cat']."' AND p_order='".($r['p_order']-1)."' LIMIT 1";
				}
				break;
			case "down":
				if($r['p_order'] != $r2['max']){
					$q4 = "UPDATE `".PREF."projects` SET p_order=p_order+1 WHERE p_id='".$_REQUEST['re']."' LIMIT 1";
					$q3 = "UPDATE `".PREF."projects` SET p_order=p_order-1
						WHERE p_cat_id='".$r['cat']."' AND p_order='".($r['p_order']+1)."' LIMIT 1";
				}
				break;
		}
		if(isset($q3) AND isset($q4)){
			$sql->query($q3);
			echo mysql_error();
			$sql->query($q4);
			echo mysql_error();
		}
		break;
}
?>
