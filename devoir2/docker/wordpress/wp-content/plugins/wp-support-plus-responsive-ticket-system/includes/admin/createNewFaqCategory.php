<?php 
global $wpdb;
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
	$values=array('name'=>$_POST['faq_cat_name']);
	$wpdb->insert($wpdb->prefix.'wpsp_faq_catagories',$values);
}
?>
