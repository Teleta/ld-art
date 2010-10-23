<?php
error_reporting(0);
require_once("../cfg.php");
require_once(ROOT."/classes/adm_authorize.php");
require_once(ROOT."/classes/img_edit.php");
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
if(isset($_FILES['img']) AND !$_FILES['img']['error']){
	while(file_exists(ROOT."/images/unsorted/".md5($_FILES['img']['name']).".jpg")){
		$_FILES['img']['name'] = $_FILES['img']['name'].md5(rand(0, 10000));
	}
	if(move_uploaded_file($_FILES['img']['tmp_name'], ROOT."/images/unsorted/".md5($_FILES['img']['name']).".jpg")){
		$real = new img_edit(ROOT."/images/unsorted/".md5($_FILES['img']['name']).".jpg");
		//$real->set_wh(800);
		$real->out('save', 1, ROOT."/images/unsorted/".md5($_FILES['img']['name']).".jpg");
		$thumb = new img_edit(ROOT."/images/unsorted/".md5($_FILES['img']['name']).".jpg");
		$thumb->set_wh(120);
		$thumb->out('save', 4, ROOT."/images/unsorted/thumb_".md5($_FILES['img']['name']).".jpg");
		$big = getimagesize(ROOT."/images/unsorted/".md5($_FILES['img']['name']).".jpg");
		$small = getimagesize(ROOT."/images/unsorted/thumb_".md5($_FILES['img']['name']).".jpg");
		if(!$sql->query("INSERT INTO `".PREF."img` (
			`img_filename`,
			`img_filesize_b`,
			`img_date`,
			`img_project_id`,
			`img_order`,
			`img_width_b`, `img_width_s`, `img_height_b`, `img_height_s`, `img_filesize_s`
		) VALUES (
			'".md5($_FILES['img']['name'])."',
			'".$_FILES['img']['size']."',
			NOW(),
			'0',
			'0',
			'".$big['0']."', '".$small['0']."', '".$big['1']."', '".$small['1']."', '".filesize(ROOT."/images/unsorted/thumb_".md5($_FILES['img']['name']).".jpg")."'
			)")){
				unlink(ROOT."/images/unsorted/".md5($_FILES['img']['name']).".jpg");
				unlink(ROOT."/images/unsorted/thumb_".md5($_FILES['img']['name']).".jpg");
				$error = 1;
			}
		// Store resulting data in $_RESULT array (will appear in req.responseJs).
		$GLOBALS['_RESULT'] = array(
			"q" => md5($_FILES['img']['name']),
			'id' => mysql_insert_id()
		);
	}else{
		$error = 1;
	}
}else{
	$error = 1;
}

if($error){
	$GLOBALS['_RESULT'] = array(
		"q" => "error ".mysql_error()." ".mysql_errno(),
		"id"=>''
	);
}
?>
