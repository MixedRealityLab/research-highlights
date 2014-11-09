$.fn.reverse = [].reverse;

$(function() {
	$('.collapse').each(function(i) {
		$(this).delay(200 * i).fadeIn();
	});

	$('#submit').click(function() {
		var c = $('.collapse').length - 1; 
		$('.collapse').reverse().each(function(i, v) {
			$(v).delay(200 * i).fadeOut();
			if (i == c) {
				setTimeout(function() { window.location = 'submit'; }, 400 * (i+1));
			}
		});
	});

});