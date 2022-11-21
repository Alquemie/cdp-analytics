<?php
/**
 * REQUIRED: Segment analytics.js
 *
 * @package     Alquemie\CDP
 * @since       1.0.0
 * @author      Chris Carrel
 * @link        https://segment.com
 * @license     GNU-2.0+
 */

namespace Alquemie\CDP;
use \Segment\Segment;

if ( ! class_exists( 'analytics' ) ) :

class analytics {
    private $_settings;
    private $_anonId = null;
    private $_userId = null;

	public function __construct($aid = null, $uid = null) {
        $this->_settings = get_option( 'segment_keys' );
        $this->_anonId = $aid;
        $this->_userId = $uid;

        error_log("Initialize Analytics:");
   
        Segment::init($this->_settings['segment_write_key'], 
            array(
                "host" => $this->getRegion(),
                "debug" => true,
                "ssl" => false,
                "error_handler" => function ($code, $msg) { error_log("Segment Error: " . $code . " -> " . $msg); }
            )
        );
         
	}

    public function setUserId($uid) {
        $this->_userId = $uid;
    }

    public function setAnonymousId($aid) {
        $this->_anonId = $aid;
    }

    private function getRegion() {
        $return = "api.segment.io";

        if ( isset( $this->_settings['segment_region_setting'] ) ) {
            $region = $this->_settings['segment_region_setting'];
            switch ($region) {
                case "eu1":
                    $return = "events.eu1.segmentapis.com";
                    break;
                default:
                    $return = "api.segment.io";
                    break;
            }
        }

        return $return;
    }

    public function track($event, $properties = array(), $context = array(), $timestamp = null) {
        error_log("Analytics: Track - {$event}");

        if (is_null($this->_anonId) && is_null($this->_userId)) {
            $this->_anonId = generateAnonId();
        }
        $timestamp = $this->get_timestamp($timestamp);

        $context["library"] = array(
            "name" => "analytics-php",
            "consumer" => "LibCurl",
            "source" => "WordPress - CDP Analytics",
            "version" => _get_plugin_version()
        );
        
        Segment::track(array(
            "userId" => $this->_userId,
            "anonymousId" => $this->_anonId,
            "event" => $event,
            "properties" => $properties,
            "context" => $context,
            // "timestamp" => $timestamp
            )
        );
        Segment::flush();
            
    }

    public function identify($traits = array(), $context = array(), $timestamp = null) {
        error_log("Analytics: Identify");

        if (is_null($this->_anonId) && is_null($this->_userId)) {
            $this->_anonId = generateAnonId();
        }
        $timestamp = $this->get_timestamp($timestamp);
        $context["library"] = array(
            "name" => "analytics-php",
            "consumer" => "LibCurl",
            "source" => "WordPress - CDP Analytics",
            "version" => _get_plugin_version()
        );

        Segment::identify(array(
            "userId" => $this->_userId,
            "anonymousId" => $this->_anonId,
            "traits" => $traits,
            "context" => $context,
            // "timestamp" => $timestamp
            )
        );
        Segment::flush();
    }

    private function get_timestamp($dateVal = null) {
        $eventTime = (is_null($dateVal)) ? date("Y-m-d h:i:sa") : $dateVal;
        //$tz_from = wp_timezone();
        $tz_to = 'UTC';
        $format = 'Y-m-d\TH:i:s\Z';

        $dt = new \DateTime($eventTime, wp_timezone());
        $dt->setTimeZone(new \DateTimeZone($tz_to));
        
        return $dt->format($format);
    }

    private function generateAnonId()
    {
        // Windows
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        } elseif (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        } else {
            return false;
        }
    }
}

endif;