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
    private $_library = array();

	public function __construct($aid = null, $uid = null) {
        $this->_settings = get_option( 'segment_keys' );
        $this->_anonId = $aid;
        $this->_userId = $uid;

        $this->_library = array(
            "name" => "analytics-php",
            "consumer" => "LibCurl",
            "source" => "WordPress CDP Analytics",
            "version" => _get_plugin_version()
        );
   
        Segment::init($this->_settings['segment_write_key'], 
            array(
                "host" => $this->getRegion(),
                "debug" => true,
                "ssl" => true,
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

    public function group($groupId, $traits = array(), $context = array()) {
        if (is_null($this->_anonId) && is_null($this->_userId)) {
            $this->_anonId = generateAnonId();
        }

        $context["library"] = $this->_library;

        Segment::group(array(
            "userId" => $this->_userId,
            "anonymousId" => $this->_anonId,
            "groupId" => $groupId,
            "traits" => $traits,
            "context" => $context,
            )
        );
        Segment::flush();
    }

    public function page($name, $category = null, $properties = array(), $context = array()) {
        if (is_null($this->_anonId) && is_null($this->_userId)) {
            $this->_anonId = generateAnonId();
        }

        $context["library"] = $this->_library;

        Segment::page(array(
            "userId" => $this->_userId,
            "anonymousId" => $this->_anonId,
            "category" => $category,
            "name" => $name,
            "properties" => $properties,
            "context" => $context,
            )
        );
        Segment::flush();
    }

    public function track($event, $properties = array(), $context = array(), $timestamp = null) {
        if (is_null($this->_anonId) && is_null($this->_userId)) {
            $this->_anonId = generateAnonId();
        }

        $context["library"] = $this->_library;
        
        Segment::track(array(
            "userId" => $this->_userId,
            "anonymousId" => $this->_anonId,
            "event" => $event,
            "properties" => $properties,
            "context" => $context,
            )
        );
        Segment::flush();
            
    }

    public function identify($traits = array(), $context = array(), $timestamp = null) {
        if (is_null($this->_anonId) && is_null($this->_userId)) {
            $this->_anonId = generateAnonId();
        }
        
        $context["library"] = $this->_library;

        Segment::identify(array(
            "userId" => $this->_userId,
            "anonymousId" => $this->_anonId,
            "traits" => $traits,
            "context" => $context,
            )
        );
        Segment::flush();
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