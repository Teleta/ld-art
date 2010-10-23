<?php
$t = new e_Template('adm_image', ROOT."/tpl/");
$t->parse('script');
$gt->assign('morehead', $t->out('script'));
$res = $DB->query("SELECT key_id AS id, key_name_eng AS name_e, key_name_rus AS name_r
	FROM ".PREF."keys
	ORDER BY key_id");
$html = array();
while($row = $DB->fetch($res)){
	$html[$row['id']] = array($row['name_e'], $row['name_r']);
}

$res = $DB->query("SELECT img.img_filename AS fname,
	img.img_width_b AS w, img.img_width_s AS ws, img.img_height_b AS h, img.img_height_s AS hs,
	k.key_id AS kid, v.value_id AS id,
	v.value_name_eng AS vn_e, v.value_name_rus AS vn_r
	FROM `".PREF."img` img
	LEFT JOIN `".PREF."kvi_links` links ON links.link_img = img.img_id
	LEFT JOIN `".PREF."keys` k ON links.link_key = k.key_id
	LEFT JOIN `".PREF."values` v ON links.link_value = v.value_id
	WHERE img_id = '".$_REQUEST['id']."'
	ORDER BY v.value_sort");
if(mysql_num_rows($res) > 0){
	while($row = $DB->fetch($res)){
		$file = $row['fname'];
		$w1 = $row['w'];
		$w2 = $row['ws'];
		$h1 = $row['h'];
		$h2 = $row['hs'];
		if(!$row['kid']){ continue; }
		$select = array();
		foreach($html as $key_id => $keys){
			$select[$key_id] = "<option value=".$key_id;
			if($key_id == $row['kid']){
				$select[$key_id] .= " selected=\"selected\"";
			}
			$select[$key_id] .= ">".$keys['0']." | ".$keys['1']."</option>";
		}
		$sel = "<select name=\"image_key_".$row['id']."\">".implode("\n", $select)."</select>";
		$t->assign('r', array(
			'id'=>$_REQUEST['id'],
			'vid'=>$row['id'],
			'k'=>$sel,
			'v_e'=>$row['vn_e'],
			'v_r'=>$row['vn_r'],
		));
		$t->parse('form.row');
	}
	$t->assign('file', $file);
	$t->assign('size', array(
		'w1'=>$w1,
		'w2'=>$w2,
		'h1'=>$h1,
		'h2'=>$h2
	));
}
$select = array();
foreach($html as $key_id => $keys){
	$select[$key_id] = "<option value=".$key_id.">".$keys['0']." | ".$keys['1']."</option>";
}
$sel = "<select name=\"image_key_new\">".implode("\n", $select)."</select>";
$t->assign('ns', $sel);
$t->assign('fileid', $_REQUEST['id']);
$t->parse('form');
$output .= $t->out('form');
$gt->assign('title', 'Editing Image Preferences');
?>
