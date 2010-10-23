<?php
$page = ((isset($_REQUEST['page']) AND !empty($_REQUEST['page']) AND is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : '1');

$t = new e_Template('s_links', ROOT.'/tpl/');
$t->assign('language', ($lang == 'rus')? 'en' : 'rus');
$t->assign('image', 'link'.(($lang == 'rus')? '_r' : ''));
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
$t->assign('title', (($lang == 'rus') ? 'Ññûëêè':'Links'));
$t->parse();
echo $t->out();
?>