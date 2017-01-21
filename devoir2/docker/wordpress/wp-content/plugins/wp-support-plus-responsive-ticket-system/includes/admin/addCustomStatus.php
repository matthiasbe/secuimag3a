<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;
/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 1 - Change Custom Status Color
 * Added 'color' column to store custom status color
 */
$values=array(
		'name'=>$_POST['custom_status_text'],
		'color'=>$_POST['custom_status_color'] // added to handle custom_status_color
);
/*$values=array(
		'name'=>$_POST['custom_status_text']
);*/
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
*/
$wpdb->insert($wpdb->prefix.'wpsp_custom_status',$values);
$last_id=$wpdb->insert_id;
$advancedSettingsStatusOrder=get_option( 'wpsp_advanced_settings_status_order' );
if(isset($advancedSettingsStatusOrder['status_order']) && is_array($advancedSettingsStatusOrder['status_order'])){
	$advancedSettingsStatusOrder['status_order']=array_merge($advancedSettingsStatusOrder['status_order'],array($last_id));
	update_option('wpsp_advanced_settings_status_order',$advancedSettingsStatusOrder);
}
?>
