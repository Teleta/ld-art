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
	case "delete":
		$q = "DELETE FROM `".PREF."values`
		WHERE value_id='".$_REQUEST['pid']."'
		LIMIT 1";
		$sql->query($q);
		$q = "DELETE FROM `".PREF."kvi_links`
		WHERE link_value='".$_REQUEST['pid']."'
		LIMIT 1";
		$sql->query($q);
		$GLOBALS['_RESULT'] = array(
			'q'=>mysql_error()
		);
		break;
	case "newrow":
		$q = "SELECT MAX(v.value_sort) AS max
			FROM `".PREF."kvi_links` AS ln, `".PREF."values` AS v
			WHERE ln.link_value=v.value_id AND ln.link_img='".$_REQUEST['id']."'";
		$max = $sql->fetch($sql->query($q));
		$q = "INSERT INTO `".PREF."values`
		VALUES (NULL, '".mysql_real_escape_string($_REQUEST['eng'])."', '".mysql_real_escape_string($_REQUEST['rus'])."', '".($max['max'] ? ($max['max']+1) : '1')."')";
		$res = $sql->query($q);
		$my_id = mysql_insert_id();
		$q = "INSERT INTO `".PREF."kvi_links`
		VALUES ('".$_REQUEST['id']."', '".$_REQUEST['key']."', '".$my_id."')";
		$res = $sql->query($q);
		$GLOBALS['_RESULT'] = array(
			'id'=>$my_id,
			'ok'=>true
		);
		break;
	case "edit":
		$q = "UPDATE `".PREF."values` SET
			value_name_eng='".mysql_real_escape_string($_REQUEST['e'])."', value_name_rus='".mysql_real_escape_string($_REQUEST['r'])."'
			WHERE value_id='".$_REQUEST['id']."'
			LIMIT 1";
		$res = $sql->query($q);
		$q = "UPDATE `".PREF."kvi_links` SET link_key='".$_REQUEST['key']."'
			WHERE link_value='".$_REQUEST['id']."'
			LIMIT 1";
		$res = $sql->query($q);
		break;
	case "resize":
		//var_dump($_REQUEST);
		require_once(ROOT."/classes/img_edit.php");
		$q = "SELECT img.img_filename AS fname, img.img_project_id AS pid,
			img.img_width_b AS w, img.img_width_s AS ws, img.img_height_b AS h, img.img_height_s AS hs
			FROM `".PREF."img` img
			WHERE img.img_id='".$_REQUEST['id']."'
			LIMIT 1";
		$r = $sql->fetch($sql->query($q));
		if($r['pid']){
			$fp = ROOT."/images/";
		}else{
			$fp = ROOT."/images/unsorted/";
		}
		// resizing
		$file = new img_edit($fp.$r['fname'].".jpg");
		($_REQUEST['h1'] == '') ? $file->set_wh($_REQUEST['w1']) : $file->set_wh($_REQUEST['w1'], $_REQUEST['h1']);
		$file->out('save', 4, $fp.$r['fname'].".jpg");
		// thumbnailing
		$file = new img_edit($fp.$r['fname'].".jpg");
		($_REQUEST['h2'] == '') ? $file->set_wh($_REQUEST['w2']) : $file->set_wh($_REQUEST['w2'], $_REQUEST['h2']);
		$file->out('save', 4, $fp."thumb_".$r['fname'].".jpg");
		$big = getimagesize($fp.$r['fname'].".jpg");
		$small = getimagesize($fp."thumb_".$r['fname'].".jpg");
		$q = "UPDATE `".PREF."img` SET
			`img_width_b`='".$big['0']."', `img_width_s`='".$small['0']."',
			`img_height_b`='".$big['1']."', `img_height_s`='".$small['1']."'
			WHERE img_id='".$_REQUEST['id']."'
			LIMIT 1";
		$res = $sql->query($q);
		$GLOBALS['_RESULT'] = array(
			'w1'=>$big['0'],
			'w2'=>$small['0'],
			'h1'=>$big['1'],
			'h2'=>$small['1'],
		);
		break;
	case "resort_vals":
		$q1 = "SELECT v.`value_sort` AS sort, ln.link_img AS iid
			FROM `".PREF."values` AS v, `".PREF."kvi_links` AS ln
			WHERE v.value_id='".$_REQUEST['id']."' AND ln.link_value=v.value_id
			LIMIT 1";
		$r = $sql->fetch($sql->query($q1));
		$q2 = "SELECT MAX(v.value_sort) AS max, MIN(v.value_sort) AS min
			FROM `".PREF."kvi_links` AS ln, `".PREF."values` AS v
			WHERE ln.link_value=v.value_id AND ln.link_img='".$r['iid']."'";
		$r2 = $sql->fetch($sql->query($q2));
		switch($_REQUEST['to']){
			case "up":
				if($r['sort'] != $r2['min']){
					$q4 = "UPDATE `".PREF."values` SET value_sort=value_sort-1
						WHERE value_id='".$_REQUEST['id']."'
						LIMIT 1";
					$q3 = "UPDATE `".PREF."values` AS v
						LEFT JOIN `".PREF."kvi_links` AS ln
						ON ln.link_value=v.value_id
						SET v.value_sort=v.value_sort+1
						WHERE ln.link_img='".$r['iid']."' AND v.value_sort='".($r['sort']-1)."'";
				}
				break;
			case "down":
				if($r['sort'] != $r2['max']){
					$q4 = "UPDATE `".PREF."values` SET value_sort=value_sort+1
						WHERE value_id='".$_REQUEST['id']."'
						LIMIT 1";
					$q3 = "UPDATE `".PREF."values` AS v
						LEFT JOIN `".PREF."kvi_links` AS ln
						ON ln.link_value=v.value_id
						SET v.value_sort=v.value_sort-1
						WHERE ln.link_img='".$r['iid']."' AND v.value_sort='".($r['sort']+1)."'";
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
