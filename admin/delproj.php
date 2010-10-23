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

$sql->query("DELETE FROM `".PREF."projects` WHERE p_id=".$_REQUEST['pid']." LIMIT 1");
$res = $sql->query("SELECT img_id AS id, `img_filename` AS fname, `img_project_id` AS cid FROM `".PREF."img` WHERE img_project_id=".$_REQUEST['pid']);
if(mysql_num_rows($res) > 0){
	$r = array();
	while($row = $sql->fetch($res)){
		if($row['cid'] == '0'){
			unlink(ROOT."/images/unsorted/".$row['fname'].".jpg");
			unlink(ROOT."/images/unsorted/thumb_".$row['fname'].".jpg");
		}else{
			unlink(ROOT."/images/".$row['fname'].".jpg");
			unlink(ROOT."/images/thumb_".$row['fname'].".jpg");
		}
		$r[] = $row['id'];
	}
	$sql->query("DELETE FROM `".PREF."img` WHERE img_project_id=".$_REQUEST['pid']);
	$res = $sql->query("SELECT link_value AS v FROM `".PREF."kvi_links` WHERE link_img IN (".implode(', ', $r).")");
	if(mysql_num_rows($res) > 0){
		$v = array();
		while($row = $sql->fetch($res)){
			$v[] = $row['v'];
		}
		$sql->query("DELETE FROM `".PREF."kvi_links` WHERE link_img IN (".implode(', ', $r).")");
		$sql->query("DELETE FROM `".PREF."values` WHERE value_id IN (".implode(' ,', $v).")");
	}

}
?>
