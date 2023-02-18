
jQuery(document).ready(function($) {
	
	if ( (typeof analytics !== 'undefined') && (cdp_analytics.taxonomy_context == "1" ) ) {
	
		const ADDWPTAX = function({ payload, next, integrations }) {
			if (payload.obj.type == "page") {
				if (cdp_analytics.categories != "") payload.obj.properties.categories = cdp_analytics.categories;
				if (cdp_analytics.tags != "") payload.obj.properties.tags = cdp_analytics.tags;
			}
			next(payload);
		};
		analytics.addSourceMiddleware(ADDWPTAX);
		console.log("CDP Analytics: Taxonomy Tracking Enabled");
	}
	
});


