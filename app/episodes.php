<?php
$db = Factory::getDB();

function episodes_show($showname, $season, $episode) {
	global $db, $user;
	$show = getShowByName($showname);
	$id = getEpisodeId($show, $season, $episode);
	$vars = array();
	$vars = array_merge($vars, getEpisodeInfo($id));
	$vars['account_link'] = "<a href='/account'>account</a> | ";
	$vars['login_link'] = getLoginLink();
	$vars['share_buttons'] = getSocialShare();
	$vars['video_url'] = getEpisodeSource($id, substr($user['locale'],0,2));
	$vars['season_list'] = getSeasons($show, $season);
	$vars['episode_list'] = getEpisodes($show, $season);
	$vars['descr'] = clean($vars['descr']);
	$vars['title'] = clean($vars['title']);
	$vars['eptitle'] = $season .'x'. $episode .' - '. $vars['title'];
	$vars['comments_box'] = '<fb:comments numposts="10" width="380" css="http://apps.airy.me/tvnerd/assets/facebook8.css"></fb:comments>';
	Slim::render('episodes.tpl', $vars);

}

function getEpisodeId($show, $season, $episode) {
	global $db;
	$db->select('episodes', array('id'), array('show'=>$show, 'season'=>$season, 'episode'=>$episode));
	return $db->loadResult();
}

function getEpisodeInfo($id) {
	global $db;
	$db->select('episodes', array('season','title','descr','director','air_date'), array('id'=>$id));
	return $db->loadRow();
}

function getEpisodeSource($id) {
	global $db;
	$db->select('sources', array('url'), array('episode'=>$id), 'id', 1, false);
	$url = $db->loadResult();
	return "<iframe src='{$url}' width='607' height='360' frameborder='0'></iframe>";
}

function getSocialShare() {
	return '<fb:like layout="button_count" show_faces="false" width="100" font="lucida grande"></fb:like>';

}



?>