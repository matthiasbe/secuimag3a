<?php 
global $wpdb;
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
	$values=array('name'=>$_POST['name']);
	if($_POST['is_default']){
		$default_status_priority=get_option( 'wpsp_default_status_priority_names' );
		$sql="select * from {$wpdb->prefix}wpsp_custom_status WHERE id=".$_POST['status_id']." ";
		$status_data=$wpdb->get_results($sql);
		foreach($status_data as $status)
		{
			foreach($default_status_priority['status_names'] as $key=>$value)
			{
				if($value==$status->name){
					$default_status_priority['status_names'][$key]=$_POST['name'];
					$wpdb->update($wpdb->prefix.'wpsp_ticket',array('status'=>$_POST['name']),array('status'=>$status->name));
				}
			}
		}
		update_option('wpsp_default_status_priority_names',$default_status_priority);
		
	}
	$wpdb->update($wpdb->prefix.'wpsp_custom_status',$values,array('id'=>$_POST['status_id']));
	
}
?>
