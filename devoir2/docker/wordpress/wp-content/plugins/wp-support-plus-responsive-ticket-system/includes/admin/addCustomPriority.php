<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

$values=array(
		'name'=>$_POST['custom_priority_text'],
		'color'=>$_POST['custom_priority_color'] // added to handle custom_status_color
);

$wpdb->insert($wpdb->prefix.'wpsp_custom_priority',$values);
$last_id=$wpdb->insert_id;
$advancedSettingsPriorityOrder=get_option( 'wpsp_advanced_settings_priority_order' );
$priority_order=$advancedSettingsPriorityOrder['priority_order'];
if(isset($advancedSettingsPriorityOrder['priority_order']) && is_array($advancedSettingsPriorityOrder['priority_order'])){
	$advancedSettingsPriorityOrder['priority_order']=array_merge($advancedSettingsPriorityOrder['priority_order'],array($last_id));
	update_option('wpsp_advanced_settings_priority_order',$advancedSettingsPriorityOrder);
}
?>
