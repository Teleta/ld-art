<?php
if(!defined("SITE")){
	header("Location: http://".$_SERVER['SERVER_NAME']."/");
	exit;
}
$t = new e_Template('s_home', ROOT."/tpl/");
switch($lang){
case "rus":
	$t->assign('lang', 'en');
	$t->assign('i', array(
		'lang'=>'Eng_1', 'lang_h'=>'Eng_2',
		'about'=>'LD_2_r',
		'copy'=>'Авторское право на все представленные на данной странице работы принадлежит Лене Дик',
		'guest'=>'Guest_1_r', 'guest_h'=>'Guest_2_r',
		'links'=>'Links_1_r', 'links_h'=>'Links_2_r',
		'ex'=>'Exhibitions1_r', 'ex_h'=>'Exhibitions2_r',
		'stillife'=>'Still-lifes_r', 'stillife_h'=>'Still-lifes2_r',
		'portraits'=>'Portraits_r', 'portraits_h'=>'Portraits2_r',
		'landscape'=>'Landscapes_r', 'landscape_h'=>'Landscapes2_r',
		'silkpaint'=>'Paintings_on_silk_r', 'silkpaint_h'=>'Paintings_on_silk2_r',
		'decorate'=>'Interior_Decoration_r', 'decorate_h'=>'Interior_Decoration2_r',
	));
	$t->assign('t', array(
		'lang' => 'Английский',
		'about' => 'Об Авторе',
		'links' => 'Ссылки',
		'ex' => 'Выставки',
		'guest' => 'Художественная одежда',
		'stillife' => 'Натюрморты',
		'portraits' => 'Портреты',
		'landscape' => 'Пейзажи',
		'silkpaint' => 'Роспись по шелку',
		'decorate' => 'Работы в интерьере',
	));
	break;
case "en":
default:
	$t->assign('lang', 'rus');
	$t->assign('i', array(
		'lang'=>'Rus_1', 'lang_h'=>'Rus_2',
		'about'=>'LD_2',
		'copy'=>'All work on this page - Copyright&copy; - Liena Dieck',
		'guest'=>'Guest_1', 'guest_h'=>'Guest_2',
		'links'=>'Links_1', 'links_h'=>'Links_2',
		'ex'=>'Exhibitions1', 'ex_h'=>'Exhibitions2',
		'stillife'=>'Still-lifes', 'stillife_h'=>'Still-lifes2',
		'portraits'=>'Portraits', 'portraits_h'=>'Portraits2',
		'landscape'=>'Landscapes', 'landscape_h'=>'Landscapes2', 
		'silkpaint'=>'Paintings_on_silk', 'silkpaint_h'=>'Paintings_on_silk2',
		'decorate'=>'Interior_Decoration', 'decorate_h'=>'Interior_Decoration2',
	));
	$t->assign('t', array(
		'lang' => 'Russian',
		'about' => 'About Author',
		'links' => 'Links',
		'ex' => 'Exhibitions',
		'guest' => 'Wearable Art',
		'stillife' => 'Still-Lifes',
		'portraits' => 'Portraits',
		'landscape' => 'Landscapes',
		'silkpaint' => 'Paintings on Silk',
		'decorate' => 'Interior Decoration',
	));
	break;
}
$t->parse();
echo $t->out();
?>