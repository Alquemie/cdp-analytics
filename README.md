# Segment Connection for WordPress

[Twilio Segment](https://www.segment.com) is the leading CDP utilized by over 20,000+ businesses that use Segment's software and APIs to collect, clean, and control their customer data.  

## Installation

1. Install this plugin in your WordPress wp-content/plugins folder
2. Activate the plugin via the WordPress dashboard
3. Create a new Website Javascript source from your [Segment Dashboard](https://app.segment.com) 
4. Open the configuration page in WordPress under  *Settings -> Segment*
5. Copy the WriteKey from the Segment Dashboard into WordPress 

### Change Log

#### v1.0.3 - Settings Update
- Added Module list to settings page

#### v1.0.2 - Gravity Forms Support
- Added script and hooks to support Gravity Forms
- Calls Track() and Identify() calls on submission

#### v1.0.0 - Initial Release
- Places analytics.js code in `<head>` of all pages
