<?php
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')){ 
	$default_field_label=array(
			'dn'=>$_POST['name_label'],
			'de'=>$_POST['email_label'],
			'ds'=>$_POST['subject_label'],
			'dd'=>$_POST['description_label'],
			'dc'=>$_POST['category_label'],
			'dp'=>$_POST['priority_label'],
			'da'=>$_POST['attachment_label']
	);
	$advancedSettingsFieldOrder=array(
			'fields_order'=>$_POST['data'],
			'display_fields'=>$_POST['display_data'],
			'default_fields_label'=>$default_field_label
	);
	$advancedSettingsFieldOrder['wpsp_default_value_of_subject']=$_POST['wpsp_default_value_of_subject'];
        update_option('wpsp_advanced_settings_field_order',$advancedSettingsFieldOrder);
}
?>
