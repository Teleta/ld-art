<?php
$category = ((isset($_REQUEST['category']) AND !empty($_REQUEST['category']) AND is_numeric($_REQUEST['category'])) ? $_REQUEST['category'] : '1');
$page = ((isset($_REQUEST['page']) AND !empty($_REQUEST['page']) AND is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : '1');
if(!$res = $DB->fetch($DB->query("SELECT ".(($lang == 'rus') ? 'cat_name_rus' : 'cat_name_eng' )." AS name
	FROM `".PREF."category`
	WHERE cat_id='".mysql_real_escape_string($category)."'
	LIMIT 1"))){
	header("Location: http://".$_SERVER['SERVER_NAME']."/");
	exit;
}
$t = new e_Template('s_catalog', ROOT."/tpl/");
$title = $res['name'];
$cats = array(1=>'still-life', 2=>'portraits', 3=>'landscape', 4=>'silk', 5=>'interior');
$t->assign('language', ($lang == 'rus')? 'en' : 'rus');
$t->assign('image', $category.(($lang == 'rus')? '_r' : ''));
$t->assign('m', array(
	'about'=>(($lang == 'rus') ? 'ÎÁ ÀÂÒÎÐÅ':'ABOUT THE ARTIST'),
	'lang'=>(($lang == 'rus') ? 'ÀÍÃËÈÉÑÊÈÉ':'RUSSIAN'),
	'home'=>(($lang == 'rus') ? 'ÄÎÌÎÉ':'HOME'),
	'links'=>(($lang == 'rus') ? 'ÑÑÛËÊÈ':'LINKS'),
	'contact'=>(($lang == 'rus') ? 'ÊÎÍÒÀÊÒÛ':'CONTACTS'),
	'guest'=>(($lang == 'rus') ? 'ÕÓÄÎÆÅÑÒÂÅÍÍÀß ÎÄÅÆÄÀ':'WEARABLE ART'),
	'ex'=>(($lang == 'rus') ? 'ÂÛÑÒÀÂÊÈ':'EXHIBITIONS'),
	'portraits'=>(($lang == 'rus') ? 'ÏÎÐÒÐÅÒÛ':'PORTRAITS'),
	'stillife'=>(($lang == 'rus') ? 'ÍÀÒÞÐÌÎÐÒÛ':'STILL-LIFES'),
	'landscape'=>(($lang == 'rus') ? 'ÏÅÉÇÀÆÈ':'LANDSCAPES'),
	'silk'=>(($lang == 'rus') ? 'ÐÎÑÏÈÑÜ ÏÎ ØÅËÊÓ':'PAINTINGS ON SILK'),
	'interior'=>(($lang == 'rus') ? 'ÐÀÁÎÒÛ Â ÈÍÒÅÐÜÅÐÅ':'INTERIOR DECORATION'),
));
$t->parse('topmenu');

$ress = $DB->query("SELECT `pr`.`p_id` AS PID, ".(($lang == 'rus') ? "`pr`.`p_name_r`" : "`pr`.`p_name_e`")." AS pname,
			(SELECT COUNT(*)
				FROM `".PREF."img`
				WHERE img_project_id=pr.p_id) AS cnt,
			img_filename AS file, img_width_s AS width, img_height_s AS height, img_id AS imid,
			img_width_b AS bwidth, img_height_b AS bheight
			FROM `".PREF."projects` AS pr
				INNER JOIN `".PREF."img`
				ON img_project_id=pr.p_id
			WHERE pr.p_cat_id='".mysql_real_escape_string($category)."'
				AND img_order=1
			GROUP BY `pr`.`p_id`
			ORDER BY pr.p_order ASC");
if($num = mysql_num_rows($ress)){
	$pages = ceil($num / 4);
	if($pages > 0){
		for($i=1;$i<=$pages;$i++){
			$t->assign('p', array(
				'category' => $cats[$category],
				'num' => $i,
			));
			if($i == $page){
				$t->assign('class', ' class="active"');
			}else{
				$t->assign('class', '');
			}
			$t->parse('catalog.paginator.page');
		}
		$t->parse('catalog.paginator');
	}
	if($pages >= $page){
		$offset = (4*($page-1));
		mysql_data_seek($ress, $offset);
	}
	$i = (isset($offset) ? $offset+1 : 1);
	while($row = $DB->fetch($ress)){
		$q = "SELECT V.".(($lang == 'rus') ? 'value_name_rus' : 'value_name_eng' )." AS value,
			K.".(($lang == 'rus') ? 'key_name_rus' : 'key_name_eng' )." AS keyname,
			K.key_show AS keyshow
			FROM ".PREF."kvi_links AS L
			INNER JOIN ".PREF."values AS V
				ON L.link_value=V.value_id
			INNER JOIN ".PREF."keys AS K
				ON K.key_id=L.link_key
			WHERE L.link_img='".$row['imid']."'
		ORDER BY V.value_sort";
		$params = $DB->query($q);
		while($param = $DB->fetch($params)){
			$t->assign('d', array(
				'key'=> ($param['keyshow'] ? $param['keyname'].": " : ''),
				'value'=>$param['value']
			));
			$t->parse('catalog.row.thumb.description');
		}
		$t->assign('i', array(
			'thumb' => $row['file'],
			'width' => $row['width'],
			'height' => $row['height'],
			'bwidth' => $row['bwidth'],
			'bheight' => $row['bheight'],
			'project' => $row['pname'],
			'imid' => $row['imid'],
			'pid' => $row['PID']
		));
		$t->parse('catalog.row.thumb');
		if(($i%2 == 0) OR ($i == $num)){
			$t->parse('catalog.row');
		}
		++$i;
		if($i > (4*($page-1)+4)){
			break;
		}
	}
	$t->parse('catalog');
}
$t->assign('title', $title);
$t->parse();
echo $t->out();
?>