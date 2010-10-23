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

$sql->query("SELECT `img_project_id` AS cid, `img_filename` AS fname FROM `".PREF."img` WHERE img_id=".$_REQUEST['img']." LIMIT 1");
$r = $sql->fetch();
$sql->query("DELETE FROM `".PREF."img` WHERE img_id=".$_REQUEST['img']);
// kills just uploaded images
if($r['cid'] == '0'){
	unlink(ROOT."/images/unsorted/".$r['fname'].".jpg");
	unlink(ROOT."/images/unsorted/thumb_".$r['fname'].".jpg");
}else{
	$res = $sql->query("SELECT link_value AS v FROM `".PREF."kvi_links` WHERE link_img=".$_REQUEST['img']);
	if(mysql_num_rows($res) > 0){
		$v = array();
		while($row = $sql->fetch()){
			$v[] = $row['v'];
		}
		$sql->query("DELETE FROM `".PREF."kvi_links` WHERE link_img=".$_REQUEST['img']);
		$sql->query("DELETE FROM `".PREF."values` WHERE value_id IN (".implode(' ,', $v).")");
	}
	unlink(ROOT."/images/".$r['fname'].".jpg");
	unlink(ROOT."/images/thumb_".$r['fname'].".jpg");
}
?>
