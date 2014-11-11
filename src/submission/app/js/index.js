$(function() {
	ReHi.fadePageIn();

	$('#submit').click(function() {
		ReHi.fadePageOut(function() { window.location = 'submit'; });
	});
});