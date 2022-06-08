<?php
/**
 * Track and Identify Forms.
 *
 * @package     Twilio\Segment
 * @since       1.0.0
 * @author      Chris Carrel
 * @link        https://segment.com
 * @license     GNU-2.0+
 */

namespace Twilio\Segment;

class forms {

	public function __construct() {

        if ( class_exists( 'GFForms' ) ) {
            add_action( 'wp_footer', array($this, 'trackGF'));
            add_action( 'gform_after_submission', array($this, 'after_submission'), 10, 2 );
            add_filter( 'gform_validation_message', array($this, 'track_error'), 10, 2 );
        }
		
	}

    function track_error( $message, $form ) {
        if ( gf_upgrade()->get_submissions_block() ) {
            return $message;
        }
        
        $error = PHP_EOL;
        $error .= '<script type="text/javascript">';
        $error .= 'analytics.ready(function() {';
        $error .= 'analytics.track("Form Submission Failed", { ';
        $error .= '  form: "' . $form['title'] . '", ';
        $error .= '  error: "Validation Failed", ';
        $error .= '  fields: { ';
        foreach ( $form['fields'] as $field ) {
            if ( $field->failed_validation ) {
                $error .= '  ' . strtolower(\GFCommon::get_label( $field )) . ': "' . $field->validation_message . '", ';
            }
        }
        $error .= '  }  });';
        $error .= '});</script>';
        $error .= PHP_EOL;

        return $message . $error;
    }

    function after_submission( $entry, $form ) {
        // add_action('wp_footer', array($this, 'sendIdentify'));
        $formId = $form["id"];
        add_action( 'wp_footer', function() use ( $formId ) {
            ?>
            <script type="text/javascript">
                analytics.ready(function() {
                    gfIdentify( <?php echo $formId; ?> );
                });
            </script>
            <?php
        });
    }

    function trackGF() {
        $file = _is_in_development_mode()
            ? '/assets/dist/gravity.min.js'
            : '/assets/scripts/gravity.js';
    
        wp_enqueue_script(
            'twilio-segment-gravityforms',
            _get_plugin_url() . $file,
            [ 'jquery' ],
            _get_asset_version( $file ),
            true
        );
    }
}

new forms;
