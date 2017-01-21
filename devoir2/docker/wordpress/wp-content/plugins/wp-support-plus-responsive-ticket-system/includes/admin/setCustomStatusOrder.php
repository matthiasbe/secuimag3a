<?php
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) { 
	$advancedSettingsStatusOrder=array(
			'status_order'=>$_POST['status_order']
	);
	update_option('wpsp_advanced_settings_status_order',$advancedSettingsStatusOrder);
}
?>
