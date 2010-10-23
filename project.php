<?php
$project = ((isset($_REQUEST['id']) AND !empty($_REQUEST['id']) AND is_numeric($_REQUEST['id'])) ? $_REQUEST['id'] : '1');
$q1 = "SELECT ".(($lang == 'rus') ? "`pr`.`p_name_r`" : "`pr`.`p_name_e`")." AS pname
	FROM `".PREF."projects` AS pr
	WHERE `pr`.`p_id`='".mysql_real_escape_string($project)."'
	LIMIT 1";
$q2 = "SELECT I.img_id AS id, I.img_filename AS file,
	I.img_width_s AS width, I.img_height_s AS height,
	I.img_width_b AS bwidth, I.img_height_b AS bheight
	FROM `".PREF."img` AS I
	WHERE I.img_project_id='".mysql_real_escape_string($project)."'
	ORDER BY I.img_order";
if(!$pname = $DB->fetch($DB->query($q1))){
	exit;
}
$pname = $pname['pname'];
$t = new e_Template('s_project', ROOT."/tpl/");
$res = $DB->query($q2);
$total = mysql_num_rows($res);
$row = $DB->fetch($res);
$t->assign('b', array(
	'file' => $row['file'],
	'width' => $row['bwidth'],
	'height' => $row['bheight']
));
$t->assign('title', $pname);
$t->parse('projectLeader');
mysql_data_seek($res, 0);
$i = 1;
while($row = $DB->fetch($res)){
		$q = "SELECT V.".(($lang == 'rus') ? 'value_name_rus' : 'value_name_eng' )." AS value,
			K.".(($lang == 'rus') ? 'key_name_rus' : 'key_name_eng' )." AS keyname,
			K.key_show AS keyshow
			FROM ".PREF."kvi_links AS L
			INNER JOIN ".PREF."values AS V
				ON L.link_value=V.value_id
			INNER JOIN ".PREF."keys AS K
				ON K.key_id=L.link_key
			WHERE L.link_img='".$row['id']."'
		ORDER BY V.value_sort";
		$params = $DB->query($q);
		while($param = $DB->fetch($params)){
			$t->assign('d', array(
				'key'=> ($param['keyshow'] ? $param['keyname'].": " : ''),
				'value'=>$param['value']
			));
			$t->parse('images.row.thumb.description');
		}
	$t->assign('i', array(
		'thumb' => $row['file'],
		'width' => $row['width'],
		'height' => $row['height'],
		'bwidth' => $row['bwidth'],
		'bheight' => $row['bheight'],
	));
	$t->parse('images.row.thumb');
	if(($i%3 == 0) OR ($i == $total)){
		$t->parse('images.row');
	}
	++$i;
}
$t->parse('images');
$t->assign('title', $pname);
$t->parse();
echo $t->out();
?>