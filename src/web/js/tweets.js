
/**
 * Research Highlights engine
 *
 * Copyright (c) 2016 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

var RHTweets = {
	SELECTOR_DIALOG			: 0,
	SELECTOR_TITLE			: 1,
	SELECTOR_TWEET			: 2,
	SELECTOR_USERNAME		: 3,
	SELECTOR_AUTHOR			: 4,
	SELECTOR_COHORT			: 5,

	DATA					: [],
	SELECTORS				: [],
	DISPLAY_FOR				: 15000,
	NEXT_INDEX				: 0,

	register				: function(dialogSelector, titleSelector, tweetSelector, usernameSelector, authorSelector, cohortSelector) {
								RHTweets.SELECTORS[RHTweets.SELECTOR_DIALOG] = dialogSelector;
								RHTweets.SELECTORS[RHTweets.SELECTOR_TITLE] = titleSelector;
								RHTweets.SELECTORS[RHTweets.SELECTOR_TWEET] = tweetSelector;
								RHTweets.SELECTORS[RHTweets.SELECTOR_USERNAME] = usernameSelector;
								RHTweets.SELECTORS[RHTweets.SELECTOR_AUTHOR] = authorSelector;
								RHTweets.SELECTORS[RHTweets.SELECTOR_COHORT] = cohortSelector;

								RH.sendData({
									dataType: 'json',
									url: $('html').data('uri_root') + '/tweets.do',
									type: 'post',
									success: function(response, textStatus, jqXHR) {
										if(response.length > 0) {
											RHTweets.DATA = response;
											RHTweets.showNextSubmission();
										} else {
											alert('Could not load tweets!');
										}
									}
								});
							},

	showNextSubmission		: function() {
								$(RHTweets.SELECTORS[RHTweets.SELECTOR_DIALOG]).fadeOut(function() {
									//$(RHTweets.SELECTORS[RHTweets.SELECTOR_TITLE]).text(RHTweets.DATA[RHTweets.NEXT_INDEX].title);
									$(RHTweets.SELECTORS[RHTweets.SELECTOR_TWEET]).text(RHTweets.DATA[RHTweets.NEXT_INDEX].tweet);
									//$(RHTweets.SELECTORS[RHTweets.SELECTOR_USERNAME]).text(RHTweets.DATA[RHTweets.NEXT_INDEX].username);
									$(RHTweets.SELECTORS[RHTweets.SELECTOR_AUTHOR]).text(RHTweets.DATA[RHTweets.NEXT_INDEX].author);
									$(RHTweets.SELECTORS[RHTweets.SELECTOR_COHORT]).text(RHTweets.DATA[RHTweets.NEXT_INDEX].cohort);
									
									if(RHTweets.NEXT_INDEX == 0) {
										$(RHTweets.SELECTORS[RHTweets.SELECTOR_DIALOG]).removeClass('hide')
									}
									
									RHTweets.NEXT_INDEX++;

									$(RHTweets.SELECTORS[RHTweets.SELECTOR_DIALOG]).fadeIn(function() {
										setTimeout(RHTweets.showNextSubmission, RHTweets.DISPLAY_FOR);
									});
								});

							},
};


$(function() {
	RHTweets.register('#dialog', '#title', '#tweet', '#username', '#author', '#cohort');
});