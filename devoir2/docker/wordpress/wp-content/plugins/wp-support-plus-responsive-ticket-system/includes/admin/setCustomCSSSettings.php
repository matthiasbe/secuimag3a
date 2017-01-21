<?php
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) { 
	$customCSSSettings=$_POST['custom_css'];
	update_option('wpsp_customcss_settings',$customCSSSettings);
}
?>
