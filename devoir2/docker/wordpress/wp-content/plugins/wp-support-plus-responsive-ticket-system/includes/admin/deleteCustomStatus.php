<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

global $wpdb;
$result=$wpdb->get_results("Select name from ".$wpdb->prefix.'wpsp_custom_status'." WHERE id=".$_POST['id']);
if(isset($result[0]->name))
{
	$status_name=$result[0]->name;
}
$wpdb->delete($wpdb->prefix.'wpsp_custom_status',array('id'=>$_POST['id']));
$default_status_priority=get_option( 'wpsp_default_status_priority_names' );
$values=array('status'=>$default_status_priority['status_names']['pending']);
if(isset($status_name))
{
	$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('status'=>$status_name));
}

$advancedSettingsStatusOrder=get_option( 'wpsp_advanced_settings_status_order' );
if(isset($advancedSettingsStatusOrder['status_order']) && $advancedSettingsFieldOrder['status_order']){
	array_splice($advancedSettingsStatusOrder['status_order'], array_search($_POST['id'], $advancedSettingsStatusOrder['status_order']), 1);
	update_option('wpsp_advanced_settings_status_order',$advancedSettingsStatusOrder);
}
?>
