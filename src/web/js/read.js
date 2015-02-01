
/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

 function read () {
	$('.container').empty ();
	$('.loading').fadeIn ();
	var d = (window.location.hash != '' ? 'user=' + window.location.hash.substr (1) : '');

	ReHi.sendData ({
	  dataType: 'json',
	  data: d,
	  url: '@@@URI_ROOT@@@/do/read',
	  type: 'post',
	  success: function (response, textStatus, jqXHR) {
					for (var i = 0; i < response.length; i++) {
						var data = response[i];
						if (data.length != 0) {
							var $content = $('<div></div>').addClass ('row-fluid');

							var $submission = $('<section></section>');

							var $title = $('<h1></h1>').text (data.title);
							var $aside = $('<aside></aside>');

							// author details
							$aside.append ($('<span></span>').text (data.firstName + ' ' + data.surname + ' (' + data.cohort + ' cohort)'));	
							$aside.append ($('<span></span>').text (data.username));						
							$aside.append ($('<span></span>').text (data.email));

							if (data.twitter != '') {
								$aside.append ($('<span></span>').text (data.twitter));
							}

							if (data.website != '') {
								$aside.append ($('<span></span>').text (data.website.replace (/(http|https):\/\//, '')));
							}

							// article
							var $article = $('<article></article>');

							var $tweet = $('<p></p>').addClass ('tweet').text (data.tweet);
							var $body = $('<div></div>').addClass ('body').html (data.html);

							var $fundingStatement = $('<small></small>').addClass ('body');
							
							// if (data.references != '') {
							// 	var $referencesTitle = $('<h1></h1>').text ('References');
							// 	var $referencesText = $('<div></div>').html (data.references);
							// 	$body.append ([$referencesTitle, $referencesText]);
							// }

							if (data.industryName != '') {
								var industry = ' and ' + data.industryName + ' (' + (data.industryUrl == '' ? '(no website)' : data.industryUrl.replace (/(http|https):\/\//, '')) + ').';
								
								$fundingStatement.html (data.fundingStatement + industry);
							} else {
								$fundingStatement.html (data.fundingStatement + '.');
							}

							if (data.keywords != undefined) {
								var $keywords = $('<div></div>').addClass ('keywords');
								
								var keywords = data.keywords.split (',');
								for (var k = 0; k < keywords.length; k++) {
									var $keyword = $('<span></span>').text (keywords[k]);
									$keywords.append ($keyword);
								}
							}

							$article.html ([$tweet, $keywords, $('<hr>'), $body, $('<hr>'), $fundingStatement]);

							// write to page
							$submission.hide ().append ([$title, $('<hr>'), $aside, $('<hr>'), $article]);
							$('.read').append ($submission);
						}
					}

					$('a').click (function (e) {
						var href = $(this).attr ('href');
						if (href.substring (0, 4) == 'http') {
							e.preventDefault ();
							window.open (href, '_blank');
						}
					});

					$('.loading').fadeOut ({complete: function () { $('.read *').fadeIn (); }});
				}
	});
}

$(function () {
	read ();
	$(window).hashchange ( function (){
		read ();
	});
});