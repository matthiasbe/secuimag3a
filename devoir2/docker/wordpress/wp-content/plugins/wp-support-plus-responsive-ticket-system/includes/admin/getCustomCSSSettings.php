<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$customCSSSettings=get_option( 'wpsp_customcss_settings' );

?>
<br>
<span class="label label-info wpsp_title_label"><?php _e('Custom CSS For Front End','wp-support-plus-responsive-ticket-system');?></span><br><br>
<textarea style="width:95%" rows=15 id="wp_support_plus_custom_css"><?php echo stripslashes($customCSSSettings);?></textarea>
<br>
<hr>
<button class="btn btn-success" id="setCustomCSSSub" onclick="setCustomCSSSettings();"><?php _e('Save Settings','wp-support-plus-responsive-ticket-system');?></button>
