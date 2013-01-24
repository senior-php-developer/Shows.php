$(function(){
	info = {};
	$('.add-show button').click(lookupShow);
	$('button.add-show').live('click',addShow);

	$('button.lookup-episodes').click(lookupEpisode);
	$('button.add-episodes').live('click',addEpisodes);
	
	$('button.save-sources').live('click',saveSources);
	
	$('.episode-list input').click(function(){
		$('.episode-list input').removeClass('start');
		$(this).addClass('start');
	
	});
	
	$('button.show-import').click(function(){
		if ($('.add-episode textarea').css('display') == 'none') {
			$('.add-episode textarea').slideDown();
		} else {
			var imgs = $('.add-episode textarea').val();
			imgs = urls.split('\n');
			var el = $('.episode-list input.start');
			for(var k in urls) {
				el.val(url);
				el = el.parent().parent().next().find('.img');
			}
			$('.add-episode textarea').hide();
		}		
		
	});

});

function lookupEpisode() {
	i = 0;
	info.url = $(this).attr('data-url');
	info.show = $(this).attr('data-show');
	info.eps = $('.add-episode .eps').val().split(' ');
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
			if (info.eps[0] && info.eps[0] !== "" && parseInt(info.season, 10)<parseInt(info.eps[0])) {
				return true;
			}
			if (info.eps[1] && info.eps[1] !== "" && parseInt(info.season, 10)>parseInt(info.eps[1])) {
				return false;
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
	data['total'] = ($('#ep-table tr').size() - 1);
	$.post('/add/show/'+info.show, data, function(r) {
		alert(r);
		location.reload();
	});


}

function lookupShow() {
	var title = $('.add-show input').val();
	var html = '<div class="show"><table><tr><th>Title</th><th>EpGuides</th></tr>';
	html += '<tr><td><input type="text" name="title" value="'+title+'"></td><td><input type="text" name="url"></td></tr>';
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

function saveSources() {
	var data = {};
	$(this).parent().find('input.img').each(function(){
		data[$(this).attr('name')] = $(this).val();	
	});
	$.post('/add/sources', data, function(r) {
		alert(r);
	});
}