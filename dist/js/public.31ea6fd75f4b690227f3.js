jQuery(document).ready((function(t){ajsTrackLinkCallback=function(t){window.location.href=t},ajsTrackSocialShare=function(e,a){var n=""!=e&&"#"!=e?e:a.attr(cdp_analytics.social_selector.replace(/\[/g,"").replace(/\]/g,"")),i=n.includes("facebook.com")?"facebook":"unkown";return i=n.includes("twitter.com")?"twitter":i,i=n.includes("linkedin.com")?"linkedin":i,i=n.includes("pinterest.com")?"pinterest":i,analytics.track("Content Shared",{channel:i,title:t("title").text(),url:t(location).attr("href"),path:t(location).attr("pathname"),description:t('meta[name="description"]').attr("content")}),!1},ajsInteractiveLink=function(t,e){var a={non_interactive:!1};return a.label=e.text().replace("’","'"),a.link_url=t,a.link_type="engagement",analytics.track("Link Clicked",a),!0},ajsTrackLinkClick=function(e,a){var n=new URL(e),i=null!=t("base").attr("href")?t("base").attr("href"):"",c=!0,r=!1,l={non_interactive:!1};if(l.label=a.text().replace("’","'"),l.link_url=e,e.match(/^mailto\:/i))l.link_type="email",l.email_recipient=n.path[0],n.searchParams.has("subject")&&(l.subject=n.searchParams.get("subject"));else if(e.match(/\.(zip|exe|dmg|pdf|doc.*|xls.*|ppt.*|txt)$/i)){var o=/[.]/.exec(e)?/[^.]+$/.exec(e):void 0;l.link_type="download",l.file_type=o[0],l.link_url=i+e,r=null==a.attr("target")||"_blank"!=a.attr("target").toLowerCase()}else e.match(/^https?\:/i)?(l.link_type="external",l.site_referred=n.host,r=null==a.attr("target")||"_blank"!=a.attr("target").toLowerCase()):e.match(/^tel\:/i)?(l.link_type="telephone",l.number_dialed=e.replace(/^tel\:/i,"")):c=!1;return!c||(r?(analytics.track("Link Clicked",l,null,ajsTrackLinkCallback(l.link_url)),!1):(analytics.track("Link Clicked",l),!0))},console.log("CDP Analytics: Load Link Tracking"),"1"==cdp_analytics.track_links&&(console.log("CDP Analytics: Tracking Enabled"),t('a[href^="http"]').filter((function(){var t=this.href.includes("facebook.com/sharer"),e=this.href.includes("twitter.com/intent/tweet")||this.href.includes("twitter.com/share"),a=this.href.includes("linkedin.com/shareArticle"),n=this.href.includes("pinterest.com/pin/create/button");return t||e||a||n})).attr({rel:"social"}),t(cdp_analytics.social_selector).attr({rel:"social"}),"1"==cdp_analytics.force_new_window&&(console.log("CDP Analytics: Force New Window Enabled"),t('a[href^="http"]').filter((function(){return this.hostname&&this.hostname!==location.hostname})).attr({target:"_blank",rel:"external"})),t("a").click((function(){var e=t(this),a=void 0!==e.attr("href")?e.attr("href"):"",n=a.match(document.domain.split(".").reverse()[1]+"."+document.domain.split(".").reverse()[0]);return"social"==e.attr("rel")?ajsTrackSocialShare(a,e):a.match(/^javascript:/i)||""==a||"#"==a?ajsInteractiveLink(a,e):!(!a.match(/^https?\:/i)||!n)||ajsTrackLinkClick(a,e)}))),console.log("CDP Analytics: Link Tracking Completed"),"1"==cdp_analytics.accordian_enable&&(t(cdp_analytics.accordian_selector).click((function(){t(this);var e=t(this).text();if(""==e)for(var a=t(this).children,n=0;n<a.length&&""==(e=a[n].text());n++);return analytics.track(cdp_analytics.accordian_event,{}),!0})),console.log("CDP Analytics: Accordian Tracking"))})),"undefined"!=typeof analytics&&"1"==cdp_analytics.taxonomy_context&&analytics.addSourceMiddleware((function(t){var e=t.payload,a=t.next;t.integrations,"page"==e.obj.type&&(""!=cdp_analytics.categories&&(e.obj.context.page.categories=cdp_analytics.categories),""!=cdp_analytics.tags&&(e.obj.context.page.tags=cdp_analytics.tags)),a(e)}));