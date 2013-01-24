$(function(){

	$('#seasons .season').live('click', function(){
		$('#seasons .season').removeClass('active');
		$(this).addClass('active');
		var show = $(this).attr('data-show');
		var season = $(this).attr('data-season');
		$('#episodes').load('/get/season/'+show+'/'+season);
	
	});
	
	$('.sources-list a.lang').click(function(){
		var ep = $(this).attr('data-episode');
		var lang = $(this).attr('data-lang');
		$.get('/get/source/'+ep+'/'+lang, function(r){
			$('.video iframe').attr('src',r);
		});	
	});
	
	$('#show-comments').click(function(){
		$('#watch .info').toggle();
		$('#watch .comments').toggle();
	});


	$('a.sources').click(function(){
		var url = $(this).data('url');
		$('.video iframe').attr('src',url);
	});

	$('a.sources').eq(0).click();


});