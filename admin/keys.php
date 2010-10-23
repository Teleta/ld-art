<?php
$t = new e_Template('adm_keys', ROOT."/tpl/");
$t->parse('script');
$gt->assign('morehead', $t->out('script'));
$res = $DB->query("SELECT key_id AS id, key_show AS sh, key_name_eng AS name_e, key_name_rus AS name_r
	FROM ".PREF."keys");
if(mysql_num_rows($res) > 0){
	while($row = $DB->fetch($res)){
		$t->assign('r', array(
			'id'=>$row['id'],
			'checked_0'=>(($row['sh'] == 0) ? ' checked="checked"' : ''),
			'checked_1'=>(($row['sh'] == 1) ? ' checked="checked"' : ''),
			'name_e'=>$row['name_e'],
			'name_r'=>$row['name_r'],
		));
		$t->parse('form.row');
	}
}
$t->parse('form');
$output .= $t->out('form');
$gt->assign('title', 'Editing Keys for image data');
?>
