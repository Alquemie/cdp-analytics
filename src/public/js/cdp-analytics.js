if (typeof analytics == 'undefined') {
	// Insert Server Side Implementation
}

jQuery(document).ready(function($) {
	ajsTrackLinkCallback = function(destUrl) {
		window.location.href = destUrl;
	}

	ajsTrackSocialShare = function(href, lnk) {
		let shareLink = ( (href != '') && (href != '#') ) ? href : lnk.attr(cdp_analytics.social_selector.replace(/\[/g, '').replace(/\]/g, ''));

		let sharedTo = (shareLink.includes("facebook.com")) ? "facebook" : "unkown";
		sharedTo = (shareLink.includes("twitter.com")) ? "twitter" : sharedTo;
		sharedTo = (shareLink.includes("linkedin.com")) ? "linkedin" : sharedTo;
		sharedTo = (shareLink.includes("pinterest.com")) ? "pinterest" : sharedTo;

		analytics.track('Content Shared', {
			"channel": sharedTo,
			"title": $("title").text(),
			"url": $(location).attr("href"),
			"path": $(location).attr("pathname"),
			"description": $('meta[name="description"]').attr('content')
		} );

		return false;
	}

	ajsInteractiveLink = function(href, lnk) {
		let elEv = {}; 
		elEv.non_interactive=false; 
		elEv.label = lnk.text().replace("’","'");
		elEv.link_url = href;
		elEv.link_type = 'engagement';

		analytics.track('Link Clicked', elEv );
		return true;
	}

	ajsTrackLinkClick = function(href, lnk) {
		const linkInfo = new URL(href);
		const filetypes = /\.(zip|exe|dmg|pdf|doc.*|xls.*|ppt.*|txt)$/i;
		const baseHref = ($('base').attr('href') != undefined) ? $('base').attr('href') : '';
		let sendTrack = true;
		let requiresCallback = false;

		
		let elEv = {}; 
		elEv.non_interactive=false; 
		// elEv.name = 'Link Clicked';
		elEv.label = lnk.text().replace("’","'");
		elEv.link_url = href;
		if (href.match(/^mailto\:/i)) {
			elEv.link_type = 'email';
			//elEv.email_recipient = href.replace(/^mailto\:/i, '');
			elEv.email_recipient = linkInfo.path[0];
			if (linkInfo.searchParams.has("subject")) elEv.subject = linkInfo.searchParams.get("subject");

		} else if (href.match(filetypes)) {
			const extension = (/[.]/.exec(href)) ? /[^.]+$/.exec(href) : undefined;
			elEv.link_type = 'download';
			elEv.file_type = extension[0];
			// elEv.click_url = href.replace(/ /g,'-');
			elEv.link_url = baseHref + href;
			requiresCallback = (lnk.attr('target') == undefined || lnk.attr('target').toLowerCase() != '_blank');
		} else if (href.match(/^https?\:/i)) {
			elEv.link_type = 'external';
			elEv.site_referred = linkInfo.host;
			// elEv.click_url = href.replace(/^https?\:\/\//i, '');
			// elEv.non_interactive = true;
			requiresCallback = (lnk.attr('target') == undefined || lnk.attr('target').toLowerCase() != '_blank');
		} else if (href.match(/^tel\:/i)) {
			elEv.link_type = 'telephone';
			elEv.number_dialed = href.replace(/^tel\:/i, '');
		} else {
			sendTrack = false;
		}

		if (sendTrack) {
			if (requiresCallback) {
				analytics.track("Link Clicked", elEv, null, ajsTrackLinkCallback(elEv.link_url) );
				return false;
			} else {
				analytics.track("Link Clicked", elEv );
				return true;
			}
		} else { return true; }
	}

	console.log("CDP Analytics: Load Link Tracking");
	if (cdp_analytics.track_links == "1") {
		console.log("CDP Analytics: Tracking Enabled");
		$('a[href^="http"]').filter(function(){
			var fb = this.href.includes("facebook.com/sharer");
			var twt = this.href.includes("twitter.com/intent/tweet") || this.href.includes("twitter.com/share");
			var lnkd = this.href.includes("linkedin.com/shareArticle");
			var pin = this.href.includes("pinterest.com/pin/create/button");
			
			return fb || twt || lnkd || pin;
		}).attr({rel: "social" }); 
		// $("[" + cdp_analytics.social_selector + "]").attr({rel: "social" });
		$(cdp_analytics.social_selector ).attr({rel: "social" });

		if (cdp_analytics.force_new_window == "1") {
			console.log("CDP Analytics: Force New Window Enabled");
			$('a[href^="http"]').filter(function(){
				return this.hostname && this.hostname !== location.hostname;
			}).attr({target: "_blank", rel: "external", }); 
		}

		$("a").click( function() {
			let el = $(this);
			let href = (typeof(el.attr('href')) != 'undefined' ) ? el.attr('href') : '';
			let isThisDomain = href.match(document.domain.split('.').reverse()[1] + '.' + document.domain.split('.').reverse()[0]);
			
			if (el.attr("rel") == "social") {
				return ajsTrackSocialShare(href, el);
			} else if ( href.match(/^javascript:/i) || (href == '') || (href == '#') ) {
				return ajsInteractiveLink(href, el);
			} else if ( (href.match(/^https?\:/i) && isThisDomain) ) {
				return true;
			} else {
				return ajsTrackLinkClick(href, el)
			}
			
		});

	}
	console.log("CDP Analytics: Link Tracking Completed");

	if (cdp_analytics.accordian_enable == "1") {
		$(cdp_analytics.accordian_selector).click(function() {
			// let acord = $(this);
			let clickText = $(this).text().trim();
			if (clickText == "") {
				var children = $(this).children;
				for (var i = 0; i < children.length; i++) {
					clickText = children[i].text().trim();
					if (clickText != "") break;
				}
			}

			
			analytics.track(cdp_analytics.accordian_event, {
				"Text Clicked": (clickText == "") ? "UNKNOWN" : clickText
			} );
					return true;
		});
		
		console.log("CDP Analytics: Accordian Tracking");
	}

});



if ( (typeof analytics !== 'undefined') && (cdp_analytics.taxonomy_context == "1" ) ) {
	
	const ADDWPTAX = function({ payload, next, integrations }) {
		if (payload.obj.type == "page") {
			if (cdp_analytics.categories != "") payload.obj.properties.categories = cdp_analytics.categories;
			if (cdp_analytics.tags != "") payload.obj.properties.tags = cdp_analytics.tags;
		}
		next(payload);
	};
	analytics.addSourceMiddleware(ADDWPTAX);

}