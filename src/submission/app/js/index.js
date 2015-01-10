
/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

 $(function() {
	ReHi.fadePageIn();

	$('#submit').click(function() {
		ReHi.fadePageOut(function() { window.location = ReHi.urlPrefix + 'submit'; });
	});
});