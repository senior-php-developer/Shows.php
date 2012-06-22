<div id="show" class="wrap block">
	<h2>{title}</h2>
	<div class="image" {big_image}>
		
	</div>
	<div class="clr">
		<div class="descr">
			<h3>Description:</h3>
			<p>{description}</p>
		</div>
		<div class="other">
			<table>
				<tr><th>Genre</th><td>{genre}</td></tr>
				<tr><th>Director</th><td>{createdby}</td></tr>
				<tr><th>Country</th><td>{country}</td></tr>
				<tr><th>Channel</th><td>{channel}</td></tr>
			</table>
			<div class="links">
				<a href="{imdb}"><img src="/assets/img/imdb.png"></a> <a href="{wiki}"><img src="/assets/img/wiki.png"></a> <a href="{epguides}"><img src="/assets/img/epguides.png"></a>	
			</div>
		</div>
	</div>
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
<script src="/assets/shows.js"></script>
</body>
</html>