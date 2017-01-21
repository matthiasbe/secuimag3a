<?php
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) { 
	$advancedSettingsTicketList=array(
			'backend_ticket_list'=>array_combine($_POST['backend_data'],$_POST['backend_display_data']),
			'frontend_ticket_list'=>array_combine($_POST['frontend_data'],$_POST['frontend_display_data'])
	);
	update_option('wpsp_advanced_settings_ticket_list_order',$advancedSettingsTicketList);
}
?>
