<?php
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) { 
	$advancedSettingsPriorityOrder=array(
			'priority_order'=>$_POST['priority_order']
	);
	update_option('wpsp_advanced_settings_priority_order',$advancedSettingsPriorityOrder);
}
?>
