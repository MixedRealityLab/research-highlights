
/**
 * Research Highlights engine
 *
 * Copyright(c) 2016 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

var RHRead = {
	HISTORY_ENABLED			: false,
	CURRENT_STATE			: {},
	CONTENT_HANDLERS		: {},
	INITIAL_LOAD			: 1,

	_register				: function() {
								RHRead.HISTORY_ENABLED = !!(window.history && history.pushState);

								// Legacy
								var hash = window.location.hash;
								var eq = hash.indexOf('=');
								if(eq > 0) {
									window.location.hash = '#/' + hash.substring(1, eq) + '/' + hash.substring(eq+1);
								}

								if(RHRead.HISTORY_ENABLED) {
									var path = window.location.pathname.replace($('html').data('path') + '/', '');
									window.onpopstate = RHRead.onStageChange;

									if(path == '') {
										hash = window.location.hash;
										var i = hash.indexOf('#/');
										if(i == 0) {
											path = hash.substring(2);
										}
										window.location.hash = '';
									}
								} else {
									var path = window.location.hash;
									var i = path.indexOf('#/');
									if(i == 0) {
										path = path.substring(2);
									}
									window.onhashchange = RHRead.onHashChange;
								}
								path = path.length == 0 ? 'home' : path;

								RHRead.CONTENT_HANDLERS.home = RHReadContent.home;
								RHRead.CONTENT_HANDLERS.read = RHReadContent.user;
								RHRead.CONTENT_HANDLERS.user = RHReadContent.user;
								RHRead.CONTENT_HANDLERS.cohort = RHReadContent.cohort;
								RHRead.CONTENT_HANDLERS.search = RHReadContent.search;

								RHReadContent.activateLinks('.home');
								RHRead.loadPath(path);
							},

	setTitle				: function(newTitle) {
								if(newTitle != RHRead.CURRENT_STATE.title) {
									document.title = newTitle + $('html').data('title_sep') + $('html').data('title');
									RHRead.CURRENT_STATE.title = document.title;
								}
							},

	loadPath				: function(newPath, title) {
								if(newPath.indexOf('#/') == 0) {
									newPath = newPath.substring(2);
								}

								if(RHRead.CURRENT_STATE.path == newPath) {
									return false;
								}

								if(title == undefined) {
									title = $('html').data('title');
								}

								if(newPath != 'home') {
									var newState = {path: newPath};

									if(RHRead.HISTORY_ENABLED) {
										window.history.pushState(newState, title, newPath);
									} else {
										document.title  = title;
										window.location.hash = "#/" + newPath;
									}
									
									RHRead.CURRENT_STATE = newState;
								}

								var i = newPath.indexOf('/');
								if(i < 0) {
									i = newPath.length;
								}

								var handler = newPath.substring(0,i);
								var handlerFound = false;
								$.each(RHRead.CONTENT_HANDLERS, function(name, fn) {
									if(name == handler) {
										fn(newPath.substring(i+1));
										handlerFound = true;
										return false;
									}
								});

								if(!handlerFound) {
									console.error("Can't load page: " + newPath + "!");
									return false;
								}
							},

	onStageChange			: function(e) {
								document.title = e.state.title;
								RHRead.loadPath(e.state.path);
							},

	onHashChange			: function(e) {
								RHRead.loadPath(window.location.hash);
							},


};

var RHReadSidebar = {

	VIEW_TITLE				: 'title',
	VIEW_NAME				: 'name',
	VIEWS					: [],
	CURRENT_VIEW			: -1,
	SELECT_ON_LOAD			: -1,

	_register				: function() {
								$('.sidebarMode').click(RHReadSidebar._onClick);
								RHReadSidebar.VIEWS = [RHReadSidebar.VIEW_TITLE, RHReadSidebar.VIEW_NAME];
							},

	_onClick				: function(e) {
								e.preventDefault();
								RHReadSidebar.setView($(this).data('mode'));
							},

	_selectLink				: function(elem) {
								if(RHReadSidebar.CURRENT_VIEW == -1) {
									RHReadSidebar.SELECT_ON_LOAD = elem;
									return;
								}

								var $tabbed = $(elem).closest('[role="tabpanel"]');
								if($tabbed.length > 0  && !$tabbed.hasClass('in')) {
									var match = $tabbed.attr('aria-labelledby') + '-links';
									$('#' + match).collapse('show');
								}

								$('#viewList .selected').removeClass('selected');
								$(elem).parent().addClass('selected');
							},

	setView					: function(view) {
								var id = -1;
								if(Number.isInteger(view)) {
									if(view >= 0 && view < RHReadSidebar.VIEWS.length) {
										id = view;
										view = RHReadSidebar.VIEWS[view];
									} else {
										console.error('No sidebar view found for that ID(' + view + ')');
										return false;
									}
								} else {
									id = RHReadSidebar.VIEWS.indexOf(view);
									if(id == -1) {
										console.error('No sidebar view found for that string(' + view + ')');
										return false;
									}
								}

								if(id == RHReadSidebar.CURRENT_VIEW) {
									return;
								}

								if(view == RHReadSidebar.VIEW_TITLE) {
									RH.sendData({
										dataType: 'json',
										url: $('html').data('uri_root') + '/cohorts.do',
										type: 'post',
										success: function(response, textStatus, jqXHR) {
													var html = '<div class="panel-body"><ul class="list-group subgroup">';
													for(var i = 0; i < response.length; i++) {
														var cohort = response[i];
														html += '<li class="list-group-item viewItem loadPage"><a href="#" data-cohort="' + cohort + '">' + cohort + ' Cohort</a></li>';
													}
													$('#viewList').html(html + '</ul></div>');
													RHReadSidebar.updateLinks(id, view);
												}
									});
								} else if(view == RHReadSidebar.VIEW_NAME) {
									RH.sendData({
										dataType: 'json',
										url: $('html').data('uri_root') + '/submitted.do',
										type: 'post',
										success: function(response, textStatus, jqXHR) {
													$('#viewList').empty();
													var prevCohort = '', addCohort = '';
													for(var i = 0; i < response.length; i++) {
														var user = response[i];
														if(prevCohort != '' && user.cohort != prevCohort) {
															$('#viewList').append(addCohort + '</ul></div></div></div>');
															addCohort = '';
														}
														if(prevCohort == '' || user.cohort != prevCohort) {
															addCohort += '<div class="panel panel-default"><div class="panel-heading pageGroup" role="tab" id="cohort-' + user.cohort + '"><h4 class="panel-title">';
															addCohort += '<a data-toggle="collapse" data-parent="#viewList" href="#cohort-' + user.cohort + '-links" data-listcohort="' + user.cohort + '" aria-expanded="true" aria-controls="cohort-' + user.cohort + '-links">' +  user.cohort + ' Cohort';
															addCohort += '</a></h4></div><div id="cohort-' + user.cohort + '-links" class="panel-collapse collapse" role="tabpanel" aria-labelledby="cohort-' + user.cohort + '"><div class="panel-body"><ul class="list-group subgroup">';
															prevCohort = user.cohort;
														}
														addCohort += '<li class="list-group-item viewItem loadPage"><a href="#" data-user="' + user.username + '">' + user.firstName + ' ' + user.surname + '</a></li>';

													}
													$('#viewList').append(addCohort + '</ul></li>');
													RHReadSidebar.updateLinks(id, view);
												}
									});
								}
							},

	updateLinks				: function(id, view) {
								$('.sidebarMode:not([data-mode='+ view + '])').removeClass('selected');
								$('.sidebarMode[data-mode='+ view + ']').addClass('selected');
								RHReadSidebar.CURRENT_VIEW = id;
								RHReadContent.activateLinks('#viewList', function() {RHReadSidebar._selectLink(this)});

								if(RHReadSidebar.SELECT_ON_LOAD != -1) {
									RHReadSidebar._selectLink(RHReadSidebar.SELECT_ON_LOAD);
									RHReadSidebar.SELECT_ON_LOAD = -1;
								}
							},

	selectUser				: function(user) {
								RHReadSidebar._selectLink('#viewList [data-user=' + user + ']');
							},

	selectCohort			: function(cohort) {
								RHReadSidebar._selectLink('#viewList [data-cohort=' + cohort + ']');
							},

};

var RHReadContent = {

	_showError				: function(title, text) {
								$('.home').hide();
								$('.read').empty();
								$('.headerOnly').unbind('click.headerOnly');

								var $submission = $('<section></section>');
								$submission.append([$('<h1 class="pagetitle">' + title + '</h1>'),$('<p></p>').addClass('error').html(text)]);
								$('.read').append($submission);

								$('.row-offcanvas').toggleClass('active');
							},

	_showSubmissions		: function(response, title) {
								$('.home').hide();
								$('.read').empty();
								$('.headerOnly').unbind('click.headerOnly');

								var headersOnly = response.length > 1 || response[0].html == undefined;

								if(title != undefined) {
									$('.read').append($('<h1 class="pagetitle"></h1>').html(title));
								}

								for(var i = 0; i < response.length; i++) {
									var data = response[i];
									if(data.length != 0) {

										// header
										var $submission = $('<section></section>');

										link = data.username;
										if(link == undefined) {
											link = data.username;
										}

										var linkedTitle = headersOnly ? 'headerOnly loadPage" href="#" data-title="' + data.firstName + ' ' + data.surname + '" data-user="' + data.username + '"' : '';

										var header = [];
										header.push($('<h1 class="' + linkedTitle + '"> ' + data.title + '</h1>'));

										var title = data.firstName + ' ' + data.surname + ' (' + data.cohort + ' cohort)';
										header.push($('<span class="name"></span>').text(title));

										if(!headersOnly) {
											RHRead.setTitle(title);

											if(data.twitter != '')
												header.push($('<span class="twitter"><span class="glyphicon glyphicon-user"></span><a href="https://twitter.com/' + data.twitter.substring(1) + '">' + data.twitter + '</a></span>'));
											if(data.website != '') {
												var visible = data.website.replace(/(http|https):\/\//, '');
												if(visible.slice(-1) == '/') {
													visible = visible.substring(0, visible.length - 1);
												}
												header.push($('<span class="website"><span class="glyphicon glyphicon-home"></span><a href="' + data.website + '">' + visible + '</a></span></span>'));
											}
										}

										if(data.keywords != undefined) {
											var colours = ["primary", "success", "info", "warning", "danger"]; var colK = 0;
											var kwHtml = '<div class=" ' + linkedTitle + '">';
											var keywords = data.keywords.split(',');
											for(var k = 0; k < keywords.length; k++) {
												colK = k % colours.length;
												kwHtml += ' <span class="label label-noColour label-noHover ' + linkedTitle + '">' + keywords[k] + '</span>';
											}
										}
										header.push($(kwHtml + '</div>'));
										var $header = $('<div class="well ' +(headersOnly ? 'headerOnly ' + linkedTitle : '') + '"></div>').html(header);

										// article
										if(!headersOnly) {
											var $article = $('<article></article>');
											var $body = $('<div></div>').addClass('body').html(data.html);
											var $fundingStatement = $('<small></small>').addClass('body');

											if(data.industryName != '') {
												var industry = ' and ' + data.industryName + '(' +(data.industryUrl == '' ? '(no website)' : data.industryUrl.replace(/(http|https):\/\//, '')) + ').';
												$fundingStatement.html(data.fundingStatement + industry);
											} else {
												$fundingStatement.html(data.fundingStatement + '.');
											}

											$article.html([$body, $fundingStatement]);

											$submission.append([$header, $article]);
										} else {
											$submission.append($header);
										}
										$('.read').append($submission);
										RHReadContent.activateLinks('.read', function() {});
									}
								};
							},

	_onClickUser			: function(e) {
								e.preventDefault();
								RHRead.loadPath('read/' + $(this).data('user'));
							},

	_onClickCohort			: function(e) {
								e.preventDefault();
								RHRead.loadPath('cohort/' + $(this).data('cohort'));
							},

	_load					: function(handler, data, successFn, failureFn) {
								RH.sendData({
									dataType: 'json',
									data: data,
									url: $('html').data('uri_root') + '/' + handler + '.do',
									type: 'post',
									beforeSend: function() {
										$('.read').fadeOut();
										$('.loading').fadeIn();
									},
									complete: function() {
										$('.loading').fadeOut();
										$('.read').fadeIn();
									},
									success: function(response, textStatus, jqXHR) {
										if(response.length > 0 && successFn != undefined) {
											successFn(response);
										} else if(failureFn != undefined) {
											failureFn(response);
										}
									}
								});
							},

	_activateLinks			: function(selector, fns) {
								$.each(fns, function(i, fn) {
									if(fn != undefined) {
										$(selector).click(fn);
									}
								});
							},

	activateLinks			: function(selector, customFn) {
								RHReadContent._activateLinks(selector + ' [data-user]', [RHReadContent._onClickUser, customFn]);
								RHReadContent._activateLinks(selector + ' [data-cohort]', [RHReadContent._onClickCohort, customFn]);
							},

	activateSearch			: function(formSelector, querySelector) {
								$(formSelector).submit(function(e) {
									e.preventDefault();
									RHRead.loadPath('search/' + $(querySelector).val());
								});
							},

	home					: function() {
								RHReadSidebar.setView(RHReadSidebar.VIEW_NAME);
								$('.home').fadeIn();
								$('.read').fadeOut();
							},

	search					: function(path) {
								RHRead.setTitle('Search results for ' + path);
								RHReadContent._load('search', 'q=' + path, function(response) {
										var title = 'Search results for <em>' + path + '</em>';
										RHReadContent._showSubmissions(response, title);
									}, function(response) {
										var title = 'No results found :-(';
										var message = 'Sorry, no submission were found for the keywords supplied.';
										RHReadContent._showError(title, message);
									});
							},

	cohort					: function(path) {
								RHReadContent._load('read', 'cohort=' + path, RHReadContent._showSubmissions);
								RHRead.setTitle(path + ' Cohort');
								if(RHRead.INITIAL_LOAD) {
									RHRead.INITIAL_LOAD = 0;
									RHReadSidebar.setView(RHReadSidebar.VIEW_TITLE);
								}
								RHReadSidebar.selectCohort(path);	
							},

	user					: function(path) {
								RHReadContent._load('read', 'user=' + path, RHReadContent._showSubmissions);
								if(RHRead.INITIAL_LOAD) {
									RHRead.INITIAL_LOAD = 0;
									RHReadSidebar.setView(RHReadSidebar.VIEW_NAME);
								}
								RHReadSidebar.selectUser(path);	
							},

};

$(function() {
	RHReadSidebar._register();
	RHRead._register();
	RHReadContent.activateSearch('.search-form', '#q');

	RH.fadePageIn();
	$('.loading').fadeOut();
});
