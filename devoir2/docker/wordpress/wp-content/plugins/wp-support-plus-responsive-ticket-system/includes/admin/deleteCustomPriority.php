<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

global $wpdb;
$result=$wpdb->get_results("Select name from ".$wpdb->prefix.'wpsp_custom_priority'." WHERE id=".$_POST['id']);
if(isset($result[0]->name))
{
	$priority_name=$result[0]->name;
}
$wpdb->delete($wpdb->prefix.'wpsp_custom_priority',array('id'=>$_POST['id']));
$default_status_priority=get_option( 'wpsp_default_status_priority_names' );
if(isset($default_status_priority['priority_names']['normal'])){
	$values=array('priority'=>$default_status_priority['priority_names']['normal']);
}
elseif(isset($default_status_priority['priority_names']['Normal'])){
	$values=array('priority'=>$default_status_priority['priority_names']['Normal']);
}
else
{
	$values=array('priority'=>'normal');
}
if(isset($priority_name))
{
	$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('priority'=>$priority_name));
}

$advancedSettingsPriorityOrder=get_option( 'wpsp_advanced_settings_priority_order' );
if(isset($advancedSettingsPriorityOrder['priority_order']) && $advancedSettingsFieldOrder['priority_order']){
	array_splice($advancedSettingsPriorityOrder['priority_order'], array_search($_POST['id'], $advancedSettingsPriorityOrder['priority_order']), 1);
	update_option('wpsp_advanced_settings_priority_order',$advancedSettingsPriorityOrder);
}
?>
