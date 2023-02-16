(()=>{function e(t){return e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},e(t)}function t(t,a,r){return(a=function(t){var a=function(t,a){if("object"!==e(t)||null===t)return t;var r=t[Symbol.toPrimitive];if(void 0!==r){var n=r.call(t,"string");if("object"!==e(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return String(t)}(t);return"symbol"===e(a)?a:String(a)}(a))in t?Object.defineProperty(t,a,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[a]=r,t}function a(e,t){if(e){if("string"==typeof e)return r(e,t);var a=Object.prototype.toString.call(e).slice(8,-1);return"Object"===a&&e.constructor&&(a=e.constructor.name),"Map"===a||"Set"===a?Array.from(e):"Arguments"===a||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a)?r(e,t):void 0}}function r(e,t){(null==t||t>e.length)&&(t=e.length);for(var a=0,r=new Array(t);a<t;a++)r[a]=e[a];return r}jQuery(document).ready((function(e){if(ajsTrackLinkCallback=function(e){window.location.href=e},ajsTrackSocialShare=function(t,a){var r=""!=t&&"#"!=t?t:a.attr(cdp_analytics.social_selector.replace(/\[/g,"").replace(/\]/g,"")),n=r.includes("facebook.com")?"facebook":"unkown";return n=r.includes("twitter.com")?"twitter":n,n=r.includes("linkedin.com")?"linkedin":n,n=r.includes("pinterest.com")?"pinterest":n,analytics.track("Content Shared",{channel:n,title:e("title").text(),url:e(location).attr("href"),path:e(location).attr("pathname"),description:e('meta[name="description"]').attr("content")}),!1},ajsInteractiveLink=function(e,t){var a={non_interactive:!1};return a.label=t.text().replace("’","'"),a.link_url=e,a.link_type="engagement",analytics.track("Link Clicked",a),!0},ajsTrackLinkClick=function(t,a){var r=new URL(t),n=null!=e("base").attr("href")?e("base").attr("href"):"",o=!0,i=!1,c={non_interactive:!1};if(c.label=a.text().replace("’","'"),c.link_url=t,t.match(/^mailto\:/i))c.link_type="email",c.email_recipient=r.path[0],r.searchParams.has("subject")&&(c.subject=r.searchParams.get("subject"));else if(t.match(/\.(zip|exe|dmg|pdf|doc.*|xls.*|ppt.*|txt)$/i)){var l=/[.]/.exec(t)?/[^.]+$/.exec(t):void 0;c.link_type="download",c.file_type=l[0],c.link_url=n+t,i=null==a.attr("target")||"_blank"!=a.attr("target").toLowerCase()}else t.match(/^https?\:/i)?(c.link_type="external",c.site_referred=r.host,i=null==a.attr("target")||"_blank"!=a.attr("target").toLowerCase()):t.match(/^tel\:/i)?(c.link_type="telephone",c.number_dialed=t.replace(/^tel\:/i,"")):o=!1;return!o||(i?(analytics.track("Link Clicked",c,null,ajsTrackLinkCallback(c.link_url)),!1):(analytics.track("Link Clicked",c),!0))},console.log("CDP Analytics: Load Link Tracking"),"1"==cdp_analytics.track_links&&(console.log("CDP Analytics: Tracking Enabled"),e('a[href^="http"]').filter((function(){var e=this.href.includes("facebook.com/sharer"),t=this.href.includes("twitter.com/intent/tweet")||this.href.includes("twitter.com/share"),a=this.href.includes("linkedin.com/shareArticle"),r=this.href.includes("pinterest.com/pin/create/button");return e||t||a||r})).attr({rel:"social"}),e(cdp_analytics.social_selector).attr({rel:"social"}),"1"==cdp_analytics.force_new_window&&(console.log("CDP Analytics: Force New Window Enabled"),e('a[href^="http"]').filter((function(){return this.hostname&&this.hostname!==location.hostname})).attr({target:"_blank",rel:"external"})),e("a").click((function(){var t=e(this),a=void 0!==t.attr("href")?t.attr("href"):"",r=a.match(document.domain.split(".").reverse()[1]+"."+document.domain.split(".").reverse()[0]);return"social"==t.attr("rel")?ajsTrackSocialShare(a,t):a.match(/^javascript:/i)||""==a||"#"==a?ajsInteractiveLink(a,t):!(!a.match(/^https?\:/i)||!r)||ajsTrackLinkClick(a,t)}))),console.log("CDP Analytics: Link Tracking Completed"),"1"==cdp_analytics.accordian_enable&&(e(cdp_analytics.accordian_selector).click((function(){var t=e(this).text().trim();if(""==t)for(var a=e(this).children,r=0;r<a.length&&""==(t=a[r].text().trim());r++);return analytics.track(cdp_analytics.accordian_event,{"Text Clicked":""==t?"UNKNOWN":t}),!0})),console.log("CDP Analytics: Accordian Tracking")),"undefined"!=typeof analytics&&"1"==cdp_analytics.taxonomy_context&&(analytics.addSourceMiddleware((function(e){var t=e.payload,a=e.next;e.integrations,"page"==t.obj.type&&(""!=cdp_analytics.categories&&(t.obj.properties.categories=cdp_analytics.categories),""!=cdp_analytics.tags&&(t.obj.properties.tags=cdp_analytics.tags)),a(t)})),console.log("CDP Analytics: Taxonomy Tracking Enabled")),"undefined"!=typeof analytics&&("1"==cdp_analytics.campaign_click_tracking||"1"==cdp_analytics.campaign_context)){if(isExpired=!1,expires=localStorage.getItem("cdp_analytics_expires"),null!=expires){var r=new Date;isExpired=r>new Date(expires)}(isExpired||"0"==cdp_analytics.campaign_context)&&(localStorage.removeItem("cdp_analytics_properties"),localStorage.removeItem("cdp_analytics_referrer"),localStorage.removeItem("cdp_analytics_campaign"),localStorage.removeItem("cdp_analytics_expires"),console.log("Referral Expired "+expires));var n,o={},i={},c={},l=new URL(location.href),s=function(e,t){var r="undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(!r){if(Array.isArray(e)||(r=a(e))){r&&(e=r);var n=0,o=function(){};return{s:o,n:function(){return n>=e.length?{done:!0}:{done:!1,value:e[n++]}},e:function(e){throw e},f:o}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,c=!0,l=!1;return{s:function(){r=r.call(e)},n:function(){var e=r.next();return c=e.done,e},e:function(e){l=!0,i=e},f:function(){try{c||null==r.return||r.return()}finally{if(l)throw i}}}}(l.searchParams);try{for(s.s();!(n=s.n()).done;){var p=(y=n.value,m=2,function(e){if(Array.isArray(e))return e}(y)||function(e,t){var a=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null!=a){var r,n,o,i,c=[],l=!0,s=!1;try{if(o=(a=a.call(e)).next,0===t){if(Object(a)!==a)return;l=!1}else for(;!(l=(r=o.call(a)).done)&&(c.push(r.value),c.length!==t);l=!0);}catch(e){s=!0,n=e}finally{try{if(!l&&null!=a.return&&(i=a.return(),Object(i)!==i))return}finally{if(s)throw n}}return c}}(y,m)||a(y,m)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()),u=p[0],d=p[1];console.log("".concat(u,", ").concat(d)),"utm_medium"==u&&(o.medium=d),"utm_source"==u&&(o.source=d),"utm_campaign"==u&&(o.campaign=d),"utm_content"==u&&(o.content=d),"utm_term"==u&&(o.term=d),"utm_id"==u&&(o.id=d)}}catch(e){s.e(e)}finally{s.f()}"1"==cdp_analytics.campaign_click_tracking&&cdp_analytics.click_ids.forEach((function(e){if(l.searchParams.has(e["opt-qs-param"])){var a;Object.keys(o).length>0&&(o[e["opt-qs-param"]]=l.searchParams.get(e["opt-qs-param"])),"properties"==e["opt-location"]?(t(a={},e["opt-qs-param"],l.searchParams.get(e["opt-qs-param"])),t(a,"id",l.searchParams.get(e["opt-qs-param"])),t(a,"param",e["opt-qs-param"]),t(a,"type",e["opt-ad-platform"]),c=a,localStorage.setItem("cdp_analytics_properties",JSON.stringify(c))):(i={id:l.searchParams.get(e["opt-qs-param"]),type:e["opt-ad-platform"]},localStorage.setItem("cdp_analytics_referrer",JSON.stringify(i)));var r=new Date;r.setDate(r.getDate()+30),localStorage.setItem("cdp_analytics_expires",r)}})),Object.keys(o).length>0&&localStorage.setItem("cdp_analytics_campaign",JSON.stringify(o)),analytics.addSourceMiddleware((function(e){var t=e.payload,a=e.next;e.integrations,"page"==t.obj.type?(Object.keys(o).length>0&&(void 0===t.obj.context&&(t.obj.context={}),t.obj.context.campaign=o),Object.keys(i).length>0&&(void 0===t.obj.context&&(t.obj.context={}),t.obj.context.referrer=i),Object.keys(c).length>0&&(void 0===t.obj.properties&&(t.obj.properties={}),t.obj.properties.referred_by=c)):"1"==cdp_analytics.campaign_context&&"track"==t.obj.type&&"1"==cdp_analytics.campaign_track?(referralValues=JSON.parse(localStorage.getItem("cdp_analytics_referrer")),null!=referralValues&&void 0!==referralValues.type&&(void 0===t.obj.context&&(t.obj.context={}),t.obj.context.referrer=referralValues),campaginValues=JSON.parse(localStorage.getItem("cdp_analytics_campaign")),null!=campaginValues&&(void 0===t.obj.context&&(t.obj.context={}),t.obj.context.campaign=campaginValues),propertyValues=JSON.parse(localStorage.getItem("cdp_analytics_properties")),null!=propertyValues&&void 0!==propertyValues.type&&(void 0===t.obj.properties&&(t.obj.properties={}),t.obj.properties.referred_by=propertyValues)):("1"==cdp_analytics.campaign_context&&"identify"==t.obj.type&&"1"==cdp_analytics.campaign_identify||"1"==cdp_analytics.campaign_context&&"group"==t.obj.type&&"1"==cdp_analytics.campaign_group)&&(referralValues=JSON.parse(localStorage.getItem("cdp_analytics_referrer")),null!=referralValues&&void 0!==referralValues.type&&(void 0===t.obj.context&&(t.obj.context={}),t.obj.context.referrer=referralValues),campaginValues=JSON.parse(localStorage.getItem("cdp_analytics_campaign")),null!=campaginValues&&(void 0===t.obj.context&&(t.obj.context={}),t.obj.context.campaign=campaginValues),propertyValues=JSON.parse(localStorage.getItem("cdp_analytics_properties")),null!=propertyValues&&void 0!==propertyValues.type&&(void 0===t.obj.traits&&(t.obj.traits={}),t.obj.traits.referred_by=propertyValues)),a(t)})),console.log("CDP Analytics: Campaign Enhancement Enabled")}var y,m;"1"==cdp_analytics.enable_video&&(window.onYouTubeIframeAPIReady=function(){console.log("Prepare YouTube")},console.log("CDP Analytics: Video Tracking Enabled"))}))})();