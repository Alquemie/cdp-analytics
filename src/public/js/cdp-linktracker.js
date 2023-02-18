ajsTrackLinkCallback = function(destUrl) {
  window.location.href = destUrl;
}

class cdpAlqLinkTracker { 
  #settings;
  #defaults = {"taxonomy_context":"1","track_links":"1","social_selector":"[data-share]","force_new_window":"1","accordian_enable":"0","accordian_event":"Accordian Clicked","accordian_selector":"","enable_video":"0","categories":"","tags":""};

  constructor(siteSettings = {}) {
    this.#settings = { ...this.#defaults, ...siteSettings };
    this.enableLinkTracking();
    this.enableAccordianTracking();
    // console.log(this.#campaign);
  };

  trackSocialShare = function(href, lnk) {
		let shareLink = ( (href != '') && (href != '#') ) ? href : lnk.attr(this.#settings.social_selector.replace(/\[/g, '').replace(/\]/g, ''));

		let sharedTo = (shareLink.includes("facebook.com")) ? "facebook" : "unkown";
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
	}

	interactiveLink(href, lnk) {
		let elEv = {}; 
		elEv.non_interactive=false; 
		elEv.label = lnk.text().replace("’","'");
		elEv.link_url = href;
		elEv.link_type = 'engagement';

		analytics.track('Link Clicked', elEv );
		return true;
	}

	trackLinkClick(href, lnk) {
		const linkInfo = new URL(href);
		const filetypes = /\.(zip|exe|dmg|pdf|doc.*|xls.*|ppt.*|txt)$/i;
		const baseHref = (jQuery('base').attr('href') != undefined) ? jQuery('base').attr('href') : '';
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
			elEv.site_visited = linkInfo.host;
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

  enableLinkTracking() {
    
    if (this.#settings.track_links == "1") {
      console.log("CDP Analytics: Link Tracking Enabled");
      jQuery('a[href^="http"]').filter(function(){
        var fb = this.href.includes("facebook.com/sharer");
        var twt = this.href.includes("twitter.com/intent/tweet") || this.href.includes("twitter.com/share");
        var lnkd = this.href.includes("linkedin.com/shareArticle");
        var pin = this.href.includes("pinterest.com/pin/create/button");
        
        return fb || twt || lnkd || pin;
      }).attr({rel: "social" }); 
      // jQuery("[" + this.#settings.social_selector + "]").attr({rel: "social" });
      jQuery(this.#settings.social_selector ).attr({rel: "social" });

      if (this.#settings.force_new_window == "1") {
        console.log("CDP Analytics: Force New Window Enabled");
        jQuery('a[href^="http"]').filter(function(){
          return this.hostname && this.hostname !== location.hostname;
        }).attr({target: "_blank", rel: "external", }); 
      }

      var parent = this;
      jQuery("a").click( function() {
        let el = jQuery(this);
        let href = (typeof(el.attr('href')) != 'undefined' ) ? el.attr('href') : '';
        let isThisDomain = href.match(document.domain.split('.').reverse()[1] + '.' + document.domain.split('.').reverse()[0]);
        
        if (el.attr("rel") == "social") {
          return parent.trackSocialShare(href, el);
        } else if ( href.match(/^javascript:/i) || (href == '') || (href == '#') ) {
          return parent.interactiveLink(href, el);
        } else if ( (href.match(/^https?\:/i) && isThisDomain) ) {
          return true;
        } else {
          return parent.trackLinkClick(href, el)
        }
        
      });

    }
    // console.log("CDP Analytics: Link Tracking Completed");
  };

  enableAccordianTracking() {
    if (this.#settings.accordian_enable == "1") {
      jQuery(this.#settings.accordian_selector).click(function() {
        // let acord = jQuery(this);
        let clickText = jQuery(this).text().trim();
        if (clickText == "") {
          var children = jQuery(this).children;
          for (var i = 0; i < children.length; i++) {
            clickText = children[i].text().trim();
            if (clickText != "") break;
          }
        }
  
        analytics.track(this.#settings.accordian_event, {
          "Text Clicked": (clickText == "") ? "UNKNOWN" : clickText
        } );
            return true;
      });
      
      console.log("CDP Analytics: Accordian Tracking");
    }
  }
}

jQuery(document).ready(function($) {
	if ((typeof analytics !== 'undefined') && (cdp_analytics.campaign_click_tracking == "1" )) {
    alqLinkTrackingCDP = new cdpAlqLinkTracker(cdp_analytics);
  }
});
