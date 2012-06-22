<?php
$db = Factory::getDB();

function shows_show($name) {
	$id = getShowByName($name);
	$vars = array();
	$vars = array_merge($vars, getShowInfo($id));
	$vars['account_link'] = '<a href="/account">account</a> | ';
	$vars['login_link'] = getLoginLink();
	$vars['big_image'] = "style='background: url(/files/shows/b{$id}.png) no-repeat 0 0;'";
	$vars['title'] = clean($vars['title']);
	$vars['description'] = clean($vars['description']);
	$vars['season_list'] = getSeasons($id);
	$vars['episode_list'] = getEpisodes($id);
	$vars['page_title'] = $vars['title'];
	Slim::render('shows.tpl', $vars);
}

function shows_season($show, $season) {
	echo getEpisodes($show, $season);
}

function getShowInfo($id) {
	global $db;
	$db->select('shows', array(), array('id'=>$id));
	return $db->loadRow();

}


function getShowByName($show) {
	global $db;
	$db->select('shows', array('id'), array('title'=>str_replace('-',' ',$show)));
	return $db->loadResult();
}

function getSeasons($id, $cur = 1) {
	global $db;
	$db->query("SELECT DISTINCT season FROM `episodes` WHERE `show` = '$id'");
	$seasons = $db->loadColumn('season');
	$ret = '';
	foreach($seasons as $k=>$v)  {
		if ($v == $cur) $act = 'active'; else $act = '';
		$ret .= "<div class='season $act' data-show='$id' data-season='$v'>Season {$v}</div>";
	}
	return $ret;
}

// episode image ->  500x300 >>> 200x120
function getEpisodes($show, $season = 1) {
	global $db;
	$show_info = getShowInfo($show);
	$seoshow = clean_seo($show_info['title']);
	$db->select('episodes', array('id','season','episode','title'), array('show'=>$show, 'season'=>$season), 'episode');
	$ret = '';
	foreach($db->loadAll() as $v) {
			$title = clean($v['title']);
			if (file_exists($_SERVER['DOCUMENT_ROOT']."/files/episodes/s{$v['id']}.png"))
				$img = "/files/episodes/s{$v['id']}.png";
			else
				$img = "/files/episodes/notfound.png";
			$seotitle = clean_seo($title);
			$ret .= "<div class='episode' data-episode='{$v['id']}'><a href='/watch/{$seoshow}/Season/${season}/Episode/{$v['episode']}/{$seotitle}.html'><img src='$img'><div>Episode {$v['episode']} - {$title}</div></a></div>";
	}
	return $ret;
}





?>