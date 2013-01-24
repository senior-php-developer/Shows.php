<div id="watch" class="wrap block">
	<h2>{eptitle}</h2>
	<div class="video">{video_url}</div>
	<div class="info">
		<p class='sources-list'>{sources_list}</p>
		<p>Title:<b> {title}</b></p>
		<p>Director: {director}</p>
		<p>Aired: {air_date}</p>
		<h3>Description:</h3>
		<p>{descr}</p>
		<br><br>
		<p>{share_buttons}</p>
		<p>{sources}</p>
	</div>
	<div class="clear"></div>
</div>

<div id="series" class="wrap block clr">
	<div id="seasons">
		{season_list}
	</div>
	<div id="episodes">
		{episode_list}
	</div>
</div>

<link rel="stylesheet" href="/assets/shows.css">
<link rel="stylesheet" href="/assets/episodes.css">
<script src="/assets/shows.js"></script>
</body>
</html>