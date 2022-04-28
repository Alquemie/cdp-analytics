<?php
/**
 * REQUIRED: Segment analytics.js
 *
 * @package     Twilio\Segment
 * @since       1.0.0
 * @author      Chris Carrel
 * @link        https://segment.com
 * @license     GNU-2.0+
 */

namespace Twilio\Segment;

class analytics {

	public function __construct() {
        add_action('wp_head', array($this, 'addSegment'));
	}

    function addSegment() {
        $segment = get_option( 'segment_keys' );
        if ( isset( $segment['segment_write_key'] ) ) {
            $writeKey = $segment['segment_write_key'];
            $subdomain = (isset($segment['segment_custom_domain']) && ($segment['segment_custom_domain'] !== "")) ? $segment['segment_custom_domain'] : "cdn.segment.com";
            echo "<script>";
            echo '!function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://' . $subdomain . '/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="' . $writeKey . '";;analytics.SNIPPET_VERSION="4.15.3";';
            echo 'analytics.load("' . $writeKey . '");';
            echo "analytics.page();";
            echo "}}();";
            echo "</script>" . PHP_EOL;
        }

        if ( isset( $segment['segment_google_measurement_id'] ) ) {
            $measurementId = strtoupper($segment['segment_google_measurement_id']);
            if ( substr($measurementId,0,2) == "G-" ) {
                echo "<!-- Global site tag (gtag.js) - Google Analytics -->";
                echo "<script async src=\"https://www.googletagmanager.com/gtag/js?id=" . $measurementId . "\"></script>";
                echo "<script>";
                echo "  window.dataLayer = window.dataLayer || [];";
                echo "  function gtag(){dataLayer.push(arguments);}";
                echo "  gtag('js', new Date());";

                echo "  gtag('config', '" . $measurementId . "');";
                echo "</script>" . PHP_EOL;
            }
        }
    }

}

new analytics;
