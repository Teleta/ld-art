<?php
$_REQUEST['part'] = ((isset($_REQUEST['part']) AND !empty($_REQUEST['part'])) ? $_REQUEST['part'] : 'list');
switch($_REQUEST['part']){
	case "new":
		$np = new e_Template('adm_projectnew', ROOT."/tpl/");
		$np->parse('script');
		$gt->assign('morehead', $np->out('script'));
		$np->parse('form');
		$output .= $np->out('form');
		$gt->assign('title', 'Adding project');
		break;
	case "edit":
		$ep =  new e_Template('adm_projectedit', ROOT."/tpl/");
		$res = $DB->query("SELECT `p_id`, `p_name_e`, `p_name_r`, `p_cat_id`
			FROM `".PREF."projects`
			WHERE p_id='".$_REQUEST['id']."'
			LIMIT 1");
		$r = $DB->fetch($res);
		$ep->assign('catid', $r['p_cat_id']);
		$ep->parse('script');
		$gt->assign('morehead', $ep->out('script'));
		$res = $DB->query("SELECT img_id, img_filename FROM `".PREF."img`
			WHERE img_project_id='".$r['p_id']."' ORDER BY img_order");
		while($row = $DB->fetch($res)){
			$ep->assign('i', array(
				'id'=>$row['img_id'],
				'fname'=>$row['img_filename']
			));
			$ep->parse('form.images');
		}
		$ep->assign('pname_r', htmlspecialchars($r['p_name_r']));
		$ep->assign('pname_e', htmlspecialchars($r['p_name_e']));
		$ep->assign('pid', $r['p_id']);
		$ep->parse('form');
		$output .= $ep->out('form');
		$gt->assign('title', 'Editing project');
		break;
	case "list":
	default:
		$list = new e_Template('adm_projectlist', ROOT."/tpl/");
		$list->parse('script');
		$gt->assign('morehead', $list->out('script'));
		$res = $DB->query("SELECT `pr`.`p_id`, `pr`.`p_name_e`, `pr`.`p_name_r`, `cat`.`cat_name_eng`, `cat`.`cat_name_rus`, `cat`.`cat_id`, (SELECT COUNT(*) FROM `".PREF."img` AS img WHERE img.img_project_id=pr.p_id) AS cnt
			FROM `".PREF."projects` AS pr, `".PREF."category` AS cat
			WHERE pr.p_cat_id=cat.cat_id
			ORDER BY pr.p_cat_id, pr.p_order ASC");
		if(mysql_num_rows($res)){
			$projects = array();
			while($row = $DB->fetch($res)){
				$projects[$row['cat_id']][$row['p_id']] = $row;
			}
			foreach($projects as $category => $project){
				foreach($project as $row){
					$list->assign('p', array(
						'id'=>$row['p_id'],
						'name_eng'=>$row['p_name_e'],
						'name_rus'=>$row['p_name_r'],
						'cat_name_eng'=>$row['cat_name_eng'],
						'cat_name_rus'=>$row['cat_name_rus'],
						'count' => $row['cnt']
					));
					$list->parse('projects.plist.row', 1);
				}
				$list->parse('projects.plist', 1);
			}
		}
		$list->parse('projects');
		$output .= $list->out('projects');
		$gt->assign('title', 'All your projects');
		break;
}
?>
