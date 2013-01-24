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
	$vars['season_list'] = getSeasons($show, $season);
	$vars['episode_list'] = getEpisodes($show, $season);
	$vars['descr'] = clean($vars['descr']);
	$vars['title'] = clean($vars['title']);
	$vars['eptitle'] = $season .'x'. $episode .' - '. $vars['title'];
	$sources = getSources($showname.' '.$vars['title']);
	$vars['sources'] = $sources['links'];
	$vars['video_url'] = $sources['video'];
	Slim::render('episodes.tpl', $vars);

}

function curl($url, $cookie = false,  $post = false, $header = false, $follow = true, $referer = false) { 
    $user_agent = ' Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:14.0) Gecko/20100101 Firefox/14.0.1';

    $ch = curl_init($url); 
    if ($referer !== false) {
        curl_setopt($ch, CURLOPT_REFERER,$referer);     
    }
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_FAILONERROR, true); 
    curl_setopt($ch, CURLOPT_HEADER, $header); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow); 
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    if ($cookie) { 
        curl_setopt ($ch, CURLOPT_COOKIE, $cookie); 
    } 
    if ($post) { 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
    } 
    $response = curl_exec ($ch); 
    curl_close($ch); 
    return $response; 
} 


function getSources($name) {

	$name = str_replace(' ','+',$name);
	$cookies = 'remixsid=0985ab06c714c487f82bd6ae7fbd295153526e80fd0e581f70747';
	$url = 'http://vk.com/al_video.php?act=search_video&al=1&offset=0&length=2&show_adult=1&q='.strtolower($name);

	$response = curl($url, $cookies);
	preg_match_all('/\[(\d|-)*, (\d)*,/', $response, $m);
	$sources = array();

	foreach($m[0] as $v) {
	    $info = str_replace(array('[',','),array('',' '),$v);
	    $parts = explode(' ',$info);
	    $url = 'http://vk.com/al_video.php?act=show&al=1&autoplay=0&list=&module=video&video='.$parts[0].'_'.$parts[2];
	    $response = curl($url, $cookies);
	    preg_match('/var vars = {(.*)}/', $response, $m2);
	    $arr = explode(',',$m2[1]);
	    $title = explode(':',$arr[7]);
	    $title = str_replace('"','',$title[1]);
	    $hash = explode(':',$arr[16]);
	    $hash = str_replace('"','',$hash[1]);
	    $sources[] = 'http://vk.com/video_ext.php?oid='.$parts[0].'&id='.$parts[2].'&hash='.$hash.'&hd=2';
	}
	$ret = '';
	$i = 0;
	if (count($sources) == 0) return array(
		'links' => '',
		'video' => '<iframe src="http://cs12940.vkontakte.ru/u3028321/videos/7054115f49.360.mp4" height=360 width=600></iframe>'
	);
	foreach($sources as $k => $v) {
		$i++;
		$ret .= ' <a href="#" class="sources" data-url="'.$v.'">Source #'.$i.'</a><br>';
	}
	return array(
		'links' => $ret,
		'video' => '<iframe src="'.$sources[0].'" height=360 width=600></iframe>'
	);
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

function getSocialShare() {
	return '<fb:like layout="button_count" show_faces="false" width="100" font="lucida grande"></fb:like>';

}



?>