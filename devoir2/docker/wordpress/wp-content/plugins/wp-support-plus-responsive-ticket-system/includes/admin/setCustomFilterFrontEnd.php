<?php
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) { 
	$advancedSettingsCustomFilterFront=array(
			'logged_in'		=>	$_POST['logged_in'],
			'agent_logged_in'	=>	$_POST['agents'],
			'supervisor_logged_in'	=>	$_POST['supervisors']
	);
	update_option('wpsp_advanced_settings_custom_filter_front',$advancedSettingsCustomFilterFront);
}
?>
