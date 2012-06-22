$(function(){
	info = {};
	$('.add-show button').click(lookupShow);
	$('button.add-show').live('click',addShow);

	$('button.lookup-episodes').click(lookupEpisode);
	$('button.add-episodes').live('click',addEpisodes);
	
	$('button.save-sources-eng').live('click',saveSourcesEng);
	$('button.save-sources-rus').live('click',saveSourcesRus);
	
	$('.episode-list input').click(function(){
		$('.episode-list input').removeClass('start');
		$(this).addClass('start');
	
	});
	
	$('button.show-import').click(function(){
		if ($('.add-episode textarea').css('display') == 'none') {
			$('.add-episode textarea').slideDown();
		} else {
			var urls = $('.add-episode textarea').val();
			urls = urls.split('\n');
			var el = $('.episode-list input.start');
			var en = el.hasClass('en');
			for(var k in urls) {
				el.val(urls[k].replace(/<iframe src="/,'').replace(/" width="607" height="360" frameborder="0"><\/iframe>/,''));
				if (en) el = el.parent().parent().next().find('.en');
				else		el = el.parent().parent().next().find('.ru');
			}
			$('.add-episode textarea').hide();
		}		
		
	});

});

function lookupEpisode() {
	i = 0;
	info.url = $('.add-episode input').val();
	info.show = $(this).attr('data-show');
	$.post('/get', {url: info.url}, function(r) {
		$('.edit-episodes').append('<table id="ep-table"><tr><th>Season</th><th>Episode</th><th>Title</th><th>Director</th><th>Air date</th><th>Description</th></tr>');
		$(r).find('div > div > div').each(function(){
			var title = $(this).parent().find('h1 a').text(); // 1 :01x01 - Dexter
			title = /(\d\d)x(\d\d) - (.*)/.exec(title);
			if (title && title.length > 0) {
				info.season = title[1];
				info.episode = title[2];
				info.title = title[3];
			} 
			info.descr = $(this).parent().find('p').text();
			info.director = $(this).find('span').filter(function(){
				return $(this).text() == 'Director:';			
			}).next().text();
			info.air_date = $.trim($(this).contents()[2].textContent);
			createEpisodeFields();
		});	
		$('.edit-episodes').append('</table><button class="add-episodes">Add episodes</button>');
	});
}


function createEpisodeFields() {
	i++;
	var html = '<tr><td><input type="text" size="2" name="season'+i+'" value="'+info.season+'"></td><td><input type="text" size="2" name="episode'+i+'" value="'+info.episode+'"></td><td><input type="text" name="title'+i+'" value="'+info.title+'"></td><td><input type="text" name="director'+i+'" value="'+info.director+'"></td><td><input type="text" name="air_date'+i+'" value="'+info.air_date+'"></td><td><textarea name="descr'+i+'">'+info.descr+'</textarea></td></tr>';
	$('#ep-table').append(html);
}

function addEpisodes() {
	var data = {};
	$(this).parent().find('input, textarea').each(function(){
		data[$(this).attr('name')] = $(this).val();	
	});
	data['show'] = info.show;
	data['i'] = i;
	$.post('/add/show/'+info.show, data, function(r) {
		alert(r);
	});


}

function lookupShow() {
	info.url = $('.add-show input').val();
	$.post('/get', {url: info.url}, function(r) {
		info.title = $(r).find('h1#firstHeading i').text();
		info.descr = $(r).find('#bodyContent p').eq(0).text() + '\n' + $(r).find('#bodyContent p').eq(1).text();
		info.wiki = info.url;
		info.genre = $(r).find('.infobox th').filter(function(){
			return $(this).text() == 'Genre';			
		}).siblings().find('a').eq(0).text();
		info.country = $(r).find('.infobox th').filter(function(){
			return $(this).text() == 'Country of origin';		
		}).siblings().text();
		info.channel = $(r).find('.infobox th').filter(function(){
			return $(this).text() == 'Original channel';		
		}).siblings().find('a').text();
		info.createdby = $(r).find('.infobox th').filter(function(){
			return $(this).text() == 'Created by';
		}).siblings().find('a').text();
		createShowFields();
	});

}

function createShowFields() {
	var html = '<div class="show"><table><tr><th>Title</th><th>IMDB</th><th>Wikipedia</th><th>EpGuides</th><th>Description</th></tr>';
	html += '<tr><td><input type="text" name="title" value="'+info.title+'"></td><td><input type="text" name="imdb"></td><td><input type="text" name="wiki" value="'+info.wiki+'"></td><td><input type="text" name="epguides"></td><td rowspan="3"><textarea name="description">'+info.descr+'</textarea></td></tr>';
	
	html+= '<th>Genre</th><th>Created By</th><th>Country</th><th>Channel</th></tr>';
	html+= '<tr><td><input type="text" name="genre" value="'+info.genre+'"></td><td><input type="text" name="createdby" value="'+info.createdby+'"></td><td><input type="text" name="country" value="'+info.country+'"></td><td><input type="text" name="channel"  value="'+info.channel+'"></td></tr>';
	html+= '</table><button class="add-show">Add show</button></div>';
	$('.edit-show').prepend(html);
}

function addShow() {
	var data = {};
	$(this).parent().find('input, textarea').each(function(){
		data[$(this).attr('name')] = $(this).val();	
	});
	$.post('/add/show', data, function(r) {
		alert(r);
	});

}


function saveSourcesEng() {
	var data = {};
	$(this).parent().find('input.en').each(function(){
		data[$(this).attr('name')] = $(this).val();	
	});
	data['lang'] = 'en';
	$.post('/add/sources', data, function(r) {
		alert(r);
	});
}

function saveSourcesRus() {
	var data = {};
	$(this).parent().find('input.ru').each(function(){
		data[$(this).attr('name')] = $(this).val();	
	});
	data['lang'] = 'ru';
	$.post('/add/sources', data, function(r) {
		alert(r);
	});
}