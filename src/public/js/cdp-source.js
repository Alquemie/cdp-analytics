document.addEventListener("DOMContentLoaded", () => {	
	if ( (typeof analytics !== 'undefined') && (cdp_analytics.campaign_normalize == "1" ) ) {
	
		const CLEANUTMSRC = function({ payload, next, integrations }) {
			if (payload.obj.type == "page") {
                 //If utm_source matches cdp_source_map
                let origSrc = "";
                if (payload.obj.context.hasOwnProperty('campaign')) {
                    origSrc = (payload.obj.context.campaign.hasOwnProperty('source')) ? payload.obj.context.campaign.source.toLowerCase() : "";
                }

                if (origSrc != "" && cdp_utm_map.hasOwnProperty(origSrc)) {
                    let queryParams = new URLSearchParams(window.location.search);
                    queryParams.set("utm_source", cdp_utm_map[origSrc].source);
                    
                    payload.obj.context.campaign.source = cdp_utm_map[origSrc].source;
                    if (payload.obj.properties.hasOwnProperty('last_touch')) {
                        payload.obj.properties.last_touch.source = cdp_utm_map[origSrc].source; 
                    }
                    if (cdp_utm_map[origSrc].hasOwnProperty('medium')) {
                        if (cdp_utm_map[origSrc].medium != "") {
                            payload.obj.context.campaign.medium = cdp_utm_map[origSrc].medium;
                            queryParams.set("utm_medium", cdp_utm_map[origSrc].medium);
                            if (payload.obj.properties.hasOwnProperty('last_touch')) {
                                payload.obj.properties.last_touch.medium = cdp_utm_map[origSrc].medium;
                            }
                        }
                    }

                    payload.obj.context.page.search = "?"+queryParams.toString();
                    payload.obj.context.page.url = window.location.protocol + '//' + window.location.hostname  
                        + window.location.pathname + "?"+queryParams.toString();
                    payload.obj.properties.search = "?"+queryParams.toString();
                    payload.obj.properties.url = window.location.protocol + '//' + window.location.hostname 
                        + window.location.pathname + "?"+queryParams.toString();
                    
                        // Replace current querystring with the new one.
                    history.replaceState(null, null, "?"+queryParams.toString());
                    console.log("CDP - Campaign Info Fixed");
                }

			}
			next(payload);
		};
		analytics.addSourceMiddleware(CLEANUTMSRC);
		console.log("CDP Analytics: Source Cleaner Enabled");
	}
	
});


