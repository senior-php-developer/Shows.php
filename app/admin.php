<?php
$db = Factory::getDB();

/** template functions **/

// shows all shows and add dialog
function admin_showShows() {
	$vars = array();
	$vars['add_show'] = '<input type="text" size="40"><button>Add</button>';
	$vars['shows_list'] = getShowsList();
	Slim::render('adm_shows.tpl', $vars);
}

// shows episodes for selected show
function admin_showEpisodes($show) {
	$url = getShowsUrl($show);
	$vars = array();
	$vars['add_episode'] = "<input style='width:30px' type='text' class='eps'/><button class='lookup-episodes' data-url='{$url}' data-show='{$show}'>Lookup</button> <button class='show-import'>Import</button><textarea class='hide'></textarea>";
	$vars['episodes_list'] = getEpisodesList($show);
	Slim::render('adm_episodes.tpl', $vars);

}


function getShowsUrl($show){
	global $db;
	$db->select('shows', array('url'), array('id'=>$show));
	return $db->loadResult();
}

/** ajax fetching pages **/
function admin_getShowsList() {
	echo getShowsList();
}

function admin_getEpisodesList() {

}



/** content fetching functions **/

function getShowsList() {
	global $db;
	$db->select('shows', array('id', 'title', 'epguides'));
	$res = '<table><tr><th>ID</th><th>Title</th><th>EpGuides</th><th>&nbsp;</th></tr>';
	foreach($db->loadAll() as $v) {
		$res .= "<tr><td>{$v['id']}</td><td><a href='/admin/show/{$v['id']}'>{$v['title']}</a></td><td><a href='{$v['epguides']}'>Link</a><td><a href='#'>Edit</a></td></tr>";
	}
	$res .= '</table>';
	return $res;

}

function getEpisodesList($show) {
	global $db;
	$db->select('episodes', array('id','title','season','episode','air_date'), array('show'=>$show), 'id', 400);
	$res = '<button class="save-sources">Save sources</button> 
	<table><tr><th>ID</th><th>SE</th><th>Air</th><th>Title</th><th>Res</th><th>&nbsp;</th><th>&nbsp;</th></tr>';
	foreach($db->loadAll() as $v) {
		$db->select('sources', array('img'), array('episode'=>$v['id']), 'id', 1, false);
		$img = $db->loadResult();
		if (isset($img) && !is_null($img) && !empty($img)) {
			$src = 'Yes';
		} else {
			$src = '--';
		}
		$res .= "<tr><td>{$v['id']}</td><td>{$v['season']}x{$v['episode']}</td><td>{$v['air_date']}</td><td>{$v['title']}</td><td>{$src}</td>
										<td><input type='text' name='{$v['id']}' class='img' size='20'></td>
										<td><a href='#'>Save</a></td></tr>";
	}
	$res .= '</table>';
	return $res;

}


/** post operations functions **/

// get remote url to local server
function admin_fetch() {
	echo file_get_contents($_POST['url']);
}


// adding new show to database
function admin_addShow() {
	global $db;
	if ($db->insert('shows', $_POST)) echo 'Show added';

}

function admin_addEpisodes($show) {
	global $db;
	$data = array();
	for($i=1; $i<= $_POST['total']; $i++) {
		$data['show'] = $show;
		$data['season'] = $_POST['season'.$i];
		$data['episode'] = $_POST['episode'.$i];
		$data['title'] = $_POST['title'.$i];
		$data['descr'] = $_POST['descr'.$i];
		$data['director'] = $_POST['director'.$i];
		$data['air_date'] = $_POST['air_date'.$i];
		$data['added'] = date('Y-m-d H:i:s');	
		$db->insert('episodes', $data);
	}
	echo 'Episodes added';
}

function admin_saveSources() {
	global $db;
	foreach($_POST as $k=>$v) {
		if (empty($v)) continue;
		$db->delete('sources', array('episode'=>$k));
		$db->insert('sources', array('episode'=>$k, 'img'=>$v));
	}
	echo 'Sources updated';

}






?>