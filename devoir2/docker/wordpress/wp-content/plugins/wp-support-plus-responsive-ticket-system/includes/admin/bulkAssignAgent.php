<?php 
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();
$generalSettings=get_option( 'wpsp_general_settings' );

$roleManage=get_option( 'wpsp_role_management' );
$agents=array();
$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_agent')));
$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_supervisor')));
$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'administrator')));
foreach($roleManage['agents'] as $agentRole)
{
	$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>$agentRole)));
}
foreach($roleManage['supervisors'] as $supervisorRole)
{
	$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>$supervisorRole)));
}?>

<span class="label label-info wpsp_title_label"><?php _e('Assign to','wp-support-plus-responsive-ticket-system');?></span><br><br>

<select id="assignTicketAgentIdMultiple" multiple="multiple">
	<?php 
	foreach ($agents as $agent){
		?>
		<option value="<?php echo $agent->ID;?>"><?php echo $agent->display_name;?></option>
		<?php 
	}
	?>
</select><br><br>
<button class="btn btn-success" onclick="wpspBulkAssignAgentSubmitChanges();"><?php _e('Save Changes','wp-support-plus-responsive-ticket-system');?></button>
<button type="button" class='btn btn-success' onclick="wpspHideFilterDashboardBody();"><?php _e('Cancel','wp-support-plus-responsive-ticket-system');?></button>