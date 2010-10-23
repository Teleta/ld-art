<?php
$page = ((isset($_REQUEST['page']) AND !empty($_REQUEST['page'])) ? $_REQUEST['page'] : '');

$t = new e_Template('s_exhibitions', ROOT.'/tpl/');
$t->assign('language', ($lang == 'rus')? 'en' : 'rus');
$t->assign('image', 'exhibitions'.(($lang == 'rus')? '_r' : ''));
$t->assign('m', array(
	'about'=>(($lang == 'rus') ? '�� ������':'ABOUT THE ARTIST'),
	'lang'=>(($lang == 'rus') ? '����������':'RUSSIAN'),
	'home'=>(($lang == 'rus') ? '�����':'HOME'),
	'links'=>(($lang == 'rus') ? '������':'LINKS'),
	'contact'=>(($lang == 'rus') ? '��������':'CONTACTS'),
	'guest'=>(($lang == 'rus') ? '�������������� ������':'WEARABLE ART'),
	'ex'=>(($lang == 'rus') ? '��������':'EXHIBITIONS'),
	'portraits'=>(($lang == 'rus') ? '��������':'PORTRAITS'),
	'stillife'=>(($lang == 'rus') ? '����������':'STILL-LIFES'),
	'landscape'=>(($lang == 'rus') ? '�������':'LANDSCAPES'),
	'silk'=>(($lang == 'rus') ? '������� �� �����':'PAINTINGS�ON�SILK'),
	'interior'=>(($lang == 'rus') ? '������ � ���������':'INTERIOR DECORATION'),
));
$t->parse('topmenu');

$q = "(
SELECT YEAR( `date_from` ) AS y
FROM ".PREF."exhibitions
GROUP BY `date_from`
) UNION (
SELECT YEAR( `date_to` ) AS y
FROM ".PREF."exhibitions
GROUP BY `date_to`
)
ORDER BY `y` DESC";
$res = $DB->query($q);

$total = mysql_num_rows($res);
if(($total >= 1) AND ($total%2 != 0)){
	$row = $DB->fetch($res);
	$t->assign('p', array(
		'name'=>$row['y'],
		'num'=>$row['y']."|".$row['y']
	));
	$t->parse('exhibitions.paginator.page');
}
while($row = $DB->fetch($res)){
	if($row2 = $DB->fetch($res)){
		$row['n'] = $row2['y'];
	}else{
		$row['n'] = $row['y'];
	}
	$t->assign('p', array(
		'name'=>$row['y']." - ".$row['n'],
		'num'=>$row['y']."|".$row['n'],
	));
	$t->parse('exhibitions.paginator.page');
}

$t->parse('exhibitions.paginator');

$q = "SET lc_time_names = '".(($lang == 'rus')? 'ru_RU' : 'en_EN')."'";
$DB->query($q);

if($lang != 'rus'){
	$localdate = "
		IF((YEAR(date_from)=YEAR(date_to))
		,
			IF((MONTH(date_from)=MONTH(date_to)) AND (DAY(date_from)=DAY(date_to))
			,
				DATE_FORMAT(date_to, '%M %e, %Y')
			,
				CONCAT(DATE_FORMAT(date_from, '%M %e'), ' - ', DATE_FORMAT(date_to, '%M %e, %Y'))
			)
		,
			CONCAT(DATE_FORMAT(date_from, '%M %e, %Y'), ' - ', DATE_FORMAT(date_to, '%M %e, %Y'))
		)
	";
}else{
	$localdate = "
		IF((YEAR(date_from)=YEAR(date_to))
		,
			IF((MONTH(date_from)=MONTH(date_to))
			,
				IF((DAY(date_from)=DAY(date_to))
				,
					DATE_FORMAT(date_from, '%e %M %Y')
				,
					CONCAT(DATE_FORMAT(date_from, '%e'), ' - ', DATE_FORMAT(date_to, '%e %M %Y'))
				)
			,
				CONCAT(DATE_FORMAT(date_from, '%e %M'), ' - ', DATE_FORMAT(date_to, '%e %M %Y'))
			)
		,
			CONCAT(DATE_FORMAT(date_from, '%e %M %Y'), ' - ', DATE_FORMAT(date_to, '%e %M %Y'))
		)
	";
}
if(!empty($page)){
	list($year1, $year2) = explode("|", $page);
}else{
	$q = "SELECT MAX(date_from) AS yf, MAX(date_to) AS yt
		FROM ".PREF."exhibitions";
	$years = $DB->fetch($DB->query($q));
	$year1 = $years['yf'];
	$year2 = $years['yt'];
}
$q = "SELECT YEAR(date_from) AS y1, YEAR(date_to) AS y2,
".$localdate." AS date_total
	, description".(($lang == 'rus')? '_r' : '_e')." AS description
	, location".(($lang == 'rus')? '_r' : '_e')." AS location
	FROM ".PREF."exhibitions
	WHERE YEAR(date_from) <= '".$year1."' AND YEAR(date_to) >= '".$year2."'
	ORDER BY date_from DESC, date_to DESC";
$res = $DB->query($q);
while($row = $DB->fetch($res)){
	$t->assign('e', array(
		'description'=>$row['description'],
		'date_total'=>$row['date_total'],
		'location'=>$row['location']
	));
	$t->parse('exhibitions.row');
}
$t->parse('exhibitions');

$t->assign('title', (($lang == 'rus') ? '��������':'Exhibitions'));
$t->parse();
echo $t->out();
?>