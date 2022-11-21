
if (typeof jQuery != 'undefined')  {

  function toggleLinkOptions() {
    var isLinkTracking = "N";
	  if (jQuery('input[name="segment_keys[segment_tracklinks_enabled]"]') != undefined) isLinkTracking = jQuery('input[name="segment_keys[segment_tracklinks_enabled]"]:checked').val();
    if (isLinkTracking == "Y") {
      jQuery("tr.ext_target_row").show();
      jQuery("tr.share_selector_row").show();
    } else {
      jQuery("tr.ext_target_row").hide();
      jQuery("tr.share_selector_row").hide();
    }
    console.log("Option Toggle")
  }

  jQuery(document).ready( function() { 
    toggleLinkOptions();
  });

  jQuery('input[name="segment_keys[segment_tracklinks_enabled]"]').change(function() {
    toggleLinkOptions();
  });
}