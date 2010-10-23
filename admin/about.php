<?php
error_reporting(0);
require_once("../cfg.php");
$sql = new mysql($sql);
$auth = new adm_authorize($sql);
if(!$auth->check()){
	$GLOBALS['_RESULT'] = array(
		"q" => "You are not logged in"
	);
	exit;
}
$gt->assign('title', 'About Author');
$tpl = new e_Template('adm_about', ROOT."/tpl/");
$tpl->parse('script');
$gt->assign('morehead', $tpl->out('script'));

if(isset($_REQUEST['text_r']) AND !empty($_REQUEST['text_r'])){
	$fp = fopen(ROOT.'/data/about_r.dat', 'w');
	fwrite($fp, stripslashes($_REQUEST['text_r']));
	fclose($fp);
}
if(isset($_REQUEST['text_e']) AND !empty($_REQUEST['text_e'])){
	$fp = fopen(ROOT.'/data/about.dat', 'w');
	fwrite($fp, stripslashes($_REQUEST['text_e']));
	fclose($fp);
}

$aboutr = htmlspecialchars(file_get_contents(ROOT."/data/about_r.dat"));
$aboute = htmlspecialchars(file_get_contents(ROOT."/data/about.dat"));

$tpl->assign('t', array(
	'about_e'=>$aboute,
	'about_r'=>$aboutr,
));

$tpl->parse('form');
$output .= $tpl->out('form');
?>