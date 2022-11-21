# Segment Analytics.js for WordPress

[Twilio Segment](https://www.segment.com) is the leading CDP utilized by over 20,000+ businesses that use Segment's software and APIs to collect, clean, and control their customer data.  

## Installation

1. Install this plugin in your WordPress wp-content/plugins folder
2. Activate the plugin via the WordPress dashboard
3. Create a new Website Javascript source from your [Segment Dashboard](https://app.segment.com) 
4. Open the configuration page in WordPress under  *Settings -> Segment*
5. Copy the WriteKey from the Segment Dashboard into WordPress 

## Plugin Features

1. Add analytics.js to the site
2. Adds analytics.page() call to all pages on the WP site
3. Ability to add 'Link Clicked' track call on external, download, mailto and tel links
4. Ability to add 'Content Shared' track call on social share buttons

### Change Log

#### v1.3.0 - Full Refactor
- Code Cleanup
- Added Link Clicked event (external, mailto, tel)
- Added Content Shared event (social share links) 
- Added Support for Addons

#### v1.0.0 - Initial Release
- Places analytics.js code in `<head>` of all pages
