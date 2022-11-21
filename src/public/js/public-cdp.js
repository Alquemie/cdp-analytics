
if ( (typeof analytics == 'undefined') && (typeof jQuery != 'undefined') ) {
	var filetypes = /\.(zip|exe|dmg|pdf|doc.*|xls.*|ppt.*|txt)$/i;
	var baseHref = '';
	if (jQuery('base').attr('href') != undefined) baseHref = jQuery('base').attr('href');
	
	jQuery(document).ready( function() { 

		jQuery('a[href^="http"]').filter(function(){
				var fb = this.href.includes("facebook.com/sharer");
				var twt = this.href.includes("twitter.com/intent/tweet") || this.href.includes("twitter.com/share");
				var lnkd = this.href.includes("linkedin.com/shareArticle");
				var pin = this.href.includes("pinterest.com/pin/create/button");
				
				return fb || twt || lnkd || pin;
		}).attr({rel: "social" }); 
		jQuery(cdpAnalyticsSocialLinks).attr({rel: "social" });

		if (cdpAnalyticsForceExtLinks) {
			jQuery('a[href^="http"]').filter(function(){
					return this.hostname && this.hostname !== location.hostname;
			}).attr({target: "_blank", rel: "external", }); 
		}
		

		jQuery("a").click( function() {
	// jQuery('body').on('click', 'a', function(event) {
			var el = jQuery(this);
			var track = true;
			var href = (typeof(el.attr('href')) != 'undefined' ) ? el.attr('href') : '';
			var isThisDomain = href.match(document.domain.split('.').reverse()[1] + '.' + document.domain.split('.').reverse()[0]);
			if (el.attr('rel') == 'social') {
				var shareLink = ( (href != '') && (href != '#') ) ? href : jQuery(cdpAnalyticsSocialLinks);

				var sharedTo = (shareLink.includes("facebook.com")) ? "facebook" : "unkown";
				sharedTo = (shareLink.includes("twitter.com")) ? "twitter" : sharedTo;
				sharedTo = (shareLink.includes("linkedin.com")) ? "linkedin" : sharedTo;
				sharedTo = (shareLink.includes("pinterest.com")) ? "pinterest" : sharedTo;

				analytics.track('Content Shared', {
					"channel": sharedTo,
					"title": jQuery("title").text(),
					"url": jQuery(location).attr("href"),
					"path": jQuery(location).attr("pathname"),
					"description": jQuery('meta[name="description"]').attr('content')
				} );

				return false;
			} else if (!href.match(/^javascript:/i)) {
				var elEv = []; elEv.value=0, elEv.non_i=false;
				elEv.name = 'Link Clicked';
				if (href.match(/^mailto\:/i)) {
					//elEv.name = 'Mailto Clicked';
					elEv.category = 'email';
					elEv.action = 'click';
					elEv.label = el.text();
					elEv.recipient = href.replace(/^mailto\:/i, '');
					elEv.loc = href;
				}
				else if (href.match(filetypes)) {
					var extension = (/[.]/.exec(href)) ? /[^.]+$/.exec(href) : undefined;
					//elEv.name = 'File Downloaded';
					elEv.category = 'download';
					// elEv.action = 'click-' + extension[0];
					elEv.action = 'click';
					elEv.file_type = extension[0];
					elEv.label = el.text();
					elEv.click_url = href.replace(/ /g,'-');
					elEv.loc = baseHref + href;
				}
				else if (href.match(/^https?\:/i) && !isThisDomain) {
					// elEv.name = 'Outbound Link Clicked';
					elEv.category = 'external';
					elEv.action = 'click';
					elEv.click_url = href.replace(/^https?\:\/\//i, '');
					elEv.label = el.text();
					elEv.non_i = true;
					elEv.loc = href;
				}
				else if (href.match(/^tel\:/i)) {
					// elEv.name = 'Telephone Clicked';
					elEv.category = 'telephone';
					elEv.action = 'click';
					elEv.label = el.text();
					elEv.recipient = href.replace(/^tel\:/i, '');
					elEv.loc = href;
				}
				else track = false;

				if (track) {
					var ret = true;

					if ( (elEv.category == 'external' || elEv.category == 'download') && (el.attr('target') == undefined || el.attr('target').toLowerCase() != '_blank') ) { //  
						/*
						ga('send','event', elEv.category.toLowerCase(),elEv.action.toLowerCase(),elEv.label.toLowerCase(),elEv.value,{
							'nonInteraction': elEv.non_i ,
							'hitCallback':gaHitCallbackHandler
						});
						*/
						analytics.track(elEv.name, {
							"label": elEv.label,
							"url": elEv.click_url,
							"link_type": elEv.category,
							"file_type": elEv.file_type,
							"recipient": elEv.recipient,
							"non_interactive": elEv.non_i,
							"action": elEv.action
						}, null, segmentTrackLinkCallback(elEv.loc) );
						
						ret = false;
					}
					else {
						/*ga('send','event', elEv.category.toLowerCase(),elEv.action.toLowerCase(),elEv.label.toLowerCase(),elEv.value,{
							'nonInteraction': elEv.non_i
						});*/

						analytics.track(elEv.name, {
							"label": elEv.label,
							"url": elEv.click_url,
							"link_type": elEv.category,
							"file_type": elEv.file_type,
							"recipient": elEv.recipient,
							"non_interactive": elEv.non_i,
							"action": elEv.action
						} );
					}

					return ret;
				}
			}
		});
	
	});

	segmentTrackLinkCallback = function(destUrl) {
			window.location.href = destUrl;
	}

} else {
	console.log("CDP Analytics: Dependancy Missing - check jQuery and Analytics.js install");
}