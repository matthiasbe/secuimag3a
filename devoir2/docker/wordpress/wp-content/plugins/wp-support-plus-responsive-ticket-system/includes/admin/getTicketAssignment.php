<?php 
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();
$generalSettings=get_option( 'wpsp_general_settings' );
$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];

if(($current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_assign_tickets']==0 && !$current_user->has_cap('manage_support_plus_agent')) || (!$current_user->has_cap('manage_support_plus_ticket') && !$current_user->has_cap('manage_support_plus_agent'))){
	echo "Sorry You don't have permission to access this!!!";
	die();
}

$sql="select * 
FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
$ticket = $wpdb->get_row( $sql );

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

<h3><?php echo '['.__($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive-ticket-system')?> <?php echo $advancedSettings['wpsp_ticket_id_prefix'].$_POST['ticket_id'].'] '.stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES));?></h3><br>

<span class="label label-info wpsp_title_label"><?php _e('Assign to','wp-support-plus-responsive-ticket-system');?></span><br><br>

<select id="assignTicketAgentId" multiple="multiple">
	<?php 
	$assigned_agents=explode(',',$ticket->assigned_to);
	foreach ($agents as $agent){
		?>
		<option <?php echo (is_numeric(array_search($agent->ID,$assigned_agents)))?'selected="selected"':'';?> value="<?php echo $agent->ID;?>"><?php echo $agent->display_name;?></option>
		<?php 
	}
	?>
</select><br><br>
<button class="btn btn-success changeTicketSubBtn" onclick="openTicket(<?php echo $_POST['ticket_id']?>);"><?php _e('Cancel','wp-support-plus-responsive-ticket-system');?></button>
<button class="btn btn-success changeTicketSubBtn" onclick="setTicketAssignment(<?php echo $_POST['ticket_id'];?>);"><?php _e('Save Changes','wp-support-plus-responsive-ticket-system');?></button>
<script>
var currentScreen='assign_agent';
var currentTicketID=<?php echo $ticket->id?>;
</script>
