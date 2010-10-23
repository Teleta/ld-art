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
$f = $sql->fetch($sql->query("SELECT img_filename AS fname
	FROM `".PREF."img`
	WHERE img_id='".$_REQUEST['img']."' LIMIT 1"));
$oldname = $f['fname'];
$r = $sql->fetch($sql->query("SELECT MAX(img_order) AS num
	FROM `".PREF."img`
	WHERE img_project_id='".$_REQUEST['pid']."'"));
while(file_exists(ROOT."/images/".$f['fname'].".jpg")){
	$f['fname'] = md5(md5(rand(0, 1000000)).$f['fname'].md5(rand(0, 100000)));
}
$query = "UPDATE `".PREF."img`
	SET img_project_id='".$_REQUEST['pid']."', img_order='".($r['num']+1)."', img_filename='".$f['fname']."'
	WHERE img_id='".$_REQUEST['img']."'";
if(!$sql->query($query)){
	echo mysql_error();
}else{
	rename(ROOT."/images/unsorted/".$oldname.".jpg", ROOT."/images/".$f['fname'].".jpg");
	rename(ROOT."/images/unsorted/thumb_".$oldname.".jpg", ROOT."/images/thumb_".$f['fname'].".jpg");
}
?>
