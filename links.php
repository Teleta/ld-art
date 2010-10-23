<?php
$page = ((isset($_REQUEST['page']) AND !empty($_REQUEST['page']) AND is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : '1');

$t = new e_Template('s_links', ROOT.'/tpl/');
$t->assign('language', ($lang == 'rus')? 'en' : 'rus');
$t->assign('image', 'link'.(($lang == 'rus')? '_r' : ''));
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

$total = $DB->fetch($DB->query("SELECT COUNT(*) AS cnt FROM ".PREF."links"));
$total = $total['cnt'];

$q = "SELECT url, description".(($lang == 'rus')? '_r' : '_e')." AS description
	FROM ".PREF."links
	ORDER BY sort
	LIMIT %s, 14";
$res = $DB->query(sprintf($q, (14*($page-1))));

for($i=1;$i<=ceil($total/14);$i++){
	$t->assign('p', array(
		'num'=>$i,
		'name'=>((($i-1)*14)+1)." - ".((($i-1)*14)+14)
	));
	$t->parse('links.paginator.page');
}
$t->parse('links.paginator');

while($row = $DB->fetch($res)){
	$url = $row['url'];
	$urls = explode(",", $url);
	foreach($urls as $url){
		$t->assign('url', trim($url));
		$t->parse('links.row.url');
	}
	$t->assign('description', $row['description']);
	$t->parse('links.row');
}
$t->parse('links');
$t->assign('title', (($lang == 'rus') ? '������':'Links'));
$t->parse();
echo $t->out();
?>