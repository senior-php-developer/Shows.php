<?php
$db = Factory::getDB();

function home_show() {
	$vars = array();
	$vars['latest_shows'] = getLatestShows(4);
	$vars['account_link'] = '<a href="/account">account</a> | ';
	$vars['login_link'] = getLoginLink();
	$vars['all_shows'] = getAllShows();
	Slim::render('home.tpl', $vars);
}

function getLatestShows($count) {
	global $db;
	$html = '';
	$db->select('shows', array('id','title'), null, 'id', $count, false);
	foreach($db->loadAll() as $k=>$v) {
		$seotitle = clean_seo($v['title']);
		$html .= "<a href='/watch/{$seotitle}'><div class='show' data-id='{$v['id']}'><img src='files/shows/m{$v['id']}.png'><div class='overlay'><div>{$v['title']}</div></div></div></a>";
	}
	return $html;	
}

function getAllShows() {
	global $db;
	$html = '';
	$db->select('shows', array('id','title'), null, 'title');
	foreach($db->loadAll() as $k=>$v) {
		$seotitle = clean_seo($v['title']);
		$html .= "<a href='/watch/{$seotitle}'><div class='show'><img src='files/shows/s{$v['id']}.png'><b>{$v['title']}</b></div></a>";	
	}
	return $html;

}




?>