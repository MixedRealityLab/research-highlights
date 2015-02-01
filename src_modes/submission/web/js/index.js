
/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

 $(function () {
	ReHi.fadePageIn ();

	$('#submit').click (function () {
		ReHi.fadePageOut (function () { window.location = '@@@URI_ROOT@@@/login'; });
	});
});