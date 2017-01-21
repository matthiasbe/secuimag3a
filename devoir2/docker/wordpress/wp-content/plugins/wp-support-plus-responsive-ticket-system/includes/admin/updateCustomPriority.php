<?php 
global $wpdb;
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
	$values=array('name'=>$_POST['name']);
	if($_POST['is_default']){
		$default_status_priority=get_option( 'wpsp_default_status_priority_names' );
		$sql="select * from {$wpdb->prefix}wpsp_custom_priority WHERE id=".$_POST['priority_id']." ";
		$priority_data=$wpdb->get_results($sql);
		foreach($priority_data as $priority)
		{
			foreach($default_status_priority['priority_names'] as $key=>$value)
			{
				if($value==$priority->name){
					$default_status_priority['priority_names'][$key]=$_POST['name'];
					$wpdb->update($wpdb->prefix.'wpsp_ticket',array('priority'=>$_POST['name']),array('priority'=>$priority->name));
				}
			}
		}
		update_option('wpsp_default_status_priority_names',$default_status_priority);
		
	}
	$wpdb->update($wpdb->prefix.'wpsp_custom_priority',$values,array('id'=>$_POST['priority_id']));
	
}
?>
