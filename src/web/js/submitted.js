
/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

 $(function () {
	$('.container').empty ();
	$('.loading').fadeIn ();

	RH.sendData ({
	  dataType: 'json',
	  url: $('html').data('uri_root') + '/submitted.do',
	  type: 'post',
	  success: function (response, textStatus, jqXHR) {
					$('.loading').fadeOut ({complete: function () { }});
					var $table = -1;

					var prevCohort = '';
					for (var i = 0; i < response.length; i++) {
						var data = response[i];
						if (data.length != 0) {
							if (prevCohort == '' || prevCohort != data.cohort) {
								if ($table != -1) {
									$('.submitted').append ($table.fadeIn ()); 
								}
								
								var $thead = $('<thead></thead>').append ([$('<th></th>').addClass ('center').text ('Year'), $('<th></th>').addClass ('center').text ('Cohort'), $('<th></th>').text ('Name'), $('<th></th>').text ('Username'),  $('<th></th>').text ('Email Address')]);
								$table = $('<table></table>').addClass ('row-fluid').append ($thead);
								prevCohort = data.cohort;
							}

							var $year = $('<td></td>').addClass ('center').text (data.year);
							var $cohort = $('<td></td>').addClass ('center').text (data.cohort);
							var $name = $('<td></td>').text (data.firstName + ' ' + data.surname);
							var $username = $('<td></td>').text (data.username);
							var $email = $('<td></td>').text (data.email);

							$table.append ($('<tr></tr>').append ([$year, $cohort, $name, $username, $email]));
						}
					}
					if ($table != -1) {
						$('.submitted').append ($table.fadeIn ()); 
					}
				}
	});
});