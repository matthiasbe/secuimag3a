<?php 
global $wpdb,$current_user;
$current_user=wp_get_current_user();

$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];

$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];

$sql="select * 
FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
$ticket = $wpdb->get_row( $sql );

$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories" );
$priorities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_priority" );
/*****************************************************************************************************/
$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$display_fields=$advancedSettingsFieldOrder['display_fields'];
$advancedSettingsPriorityOrder=get_option( 'wpsp_advanced_settings_priority_order' );
if(isset($advancedSettingsPriorityOrder['priority_order'])){
	if(is_array($advancedSettingsPriorityOrder['priority_order']))
	{
		$priorities=array();
		foreach($advancedSettingsPriorityOrder['priority_order'] as $priority_id)
		{
			$sql="select * from {$wpdb->prefix}wpsp_custom_priority WHERE id=".$priority_id." ";
			$priority_data=$wpdb->get_results($sql);
			foreach($priority_data as $priority)
			{
				$priorities=array_merge($priorities,array($priority));
			}
		}
	}
}
/******************************************************************************************************/
?>

<h3><?php echo '['.__($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive-ticket-system').' ';?><?php echo $advancedSettings['wpsp_ticket_id_prefix'].$_POST['ticket_id'].'] '.stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES));?></h3><br>

<table id="tblChangeStatusContainer">
  <tr>
    <td><?php _e('Status','wp-support-plus-responsive-ticket-system');?></td>
    <td>:</td>
    <td>
    	<select id="change_status_ticket_status">
			<?php
				$sql_status="select * from {$wpdb->prefix}wpsp_custom_status";
				$custom_statusses=$wpdb->get_results($sql_status);
				$total_statusses=$wpdb->num_rows;
				$advancedSettingsStatusOrder=get_option( 'wpsp_advanced_settings_status_order' );
				if(isset($advancedSettingsStatusOrder['status_order'])){
					if(is_array($advancedSettingsStatusOrder['status_order']))
					{
						$custom_statusses=array();
						foreach($advancedSettingsStatusOrder['status_order'] as $status_id)
						{
							$sql="select * from {$wpdb->prefix}wpsp_custom_status WHERE id=".$status_id." ";
							$status_data=$wpdb->get_results($sql);
							foreach($status_data as $status)
							{
								$custom_statusses=array_merge($custom_statusses,array($status));
							}
						}
					}
				}
				if($total_statusses)
				{
					foreach($custom_statusses as $custom_status){?>
						<option value="<?php echo $custom_status->name;?>" <?php echo (strtolower($ticket->status)==strtolower($custom_status->name))?'selected="selected"':'';?>><?php _e(ucfirst($custom_status->name),'wp-support-plus-responsive-ticket-system');?></option>
					<?php
					}
				}
			?>
		</select>
    </td>
  </tr>
<?php
if(in_array('dc',$display_fields)){
?>
  <tr>
    <td><?php _e($default_labels['dc'],'wp-support-plus-responsive-ticket-system');?></td>
    <td>:</td>
    <td>
    	<select id="change_status_category">
			<?php 
			foreach ($categories as $category){
				$selected=($category->id==$ticket->cat_id)?'selected="selected"':'';
				echo '<option value="'.$category->id.'" '.$selected.'>'.__($category->name,'wp-support-plus-responsive-ticket-system').'</option>';
			}
			?>
		</select>
    </td>
  </tr>
<?php
}
else
{
?><input type="hidden" name="change_status_category" id="change_status_category" value="<?php echo $ticket->cat_id;?>"><?php
}
if(in_array('dp',$display_fields)){
?>
  <tr>
    <td><?php _e($default_labels['dp'],'wp-support-plus-responsive-ticket-system');?></td>
    <td>:</td>
    <td>
    	<select id="change_status_priority">
			<?php 
			foreach ($priorities as $priority){
				?>
				<option value="<?php echo strtolower($priority->name);?>" <?php echo ($ticket->priority==strtolower($priority->name))?'selected="selected"':'';?>><?php _e($priority->name,'wp-support-plus-responsive-ticket-system');?></option>
			<?php
			}
			?>
		</select>
    </td>
  </tr>

<?php
}
else
{
?><input type="hidden" name="change_status_priority" id="change_status_priority" value="<?php echo $ticket->priority;?>"><?php
}
if($current_user->has_cap('manage_support_plus_agent'))
{
?>
  <tr>
    <td><?php _e($advancedSettings['ticket_label_alice'][11],'wp-support-plus-responsive-ticket-system');?></td>
    <td>:</td>
    <td>
    	<select id="change_status_type">
			<option value="0" <?php echo ($ticket->ticket_type=='0')?'selected="selected"':'';?>><?php _e('Private','wp-support-plus-responsive-ticket-system');?></option>
			<option value="1" <?php echo ($ticket->ticket_type=='1')?'selected="selected"':'';?>><?php _e('Public','wp-support-plus-responsive-ticket-system');?></option>
	</select>
    </td>
  </tr>
  <?php
/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 16 - silent status change
 */
?>
<tr>
	<td><?php _e('Notify','wp-support-plus-responsive-ticket-system');?></td>
	<td>:</td>
	<td>
		<select id="notify">
			<option value="1"><?php _e('Yes','wp-support-plus-responsive-ticket-system');?></option>
			<option value="0"><?php _e('No','wp-support-plus-responsive-ticket-system');?></option>
		</select>
	</td>
</tr>
<?php
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
 */
?>
<?php
}
else
{?>
	<input type="hidden" id="change_status_type" value="<?php echo $ticket->ticket_type;?>">
<?php
}?>

</table>

<button class="btn btn-success changeTicketSubBtn" onclick="openTicket(<?php echo $_POST['ticket_id']?>);"><?php _e('Cancel','wp-support-plus-responsive-ticket-system');?></button>
<button class="btn btn-success changeTicketSubBtn" onclick="setChangeTicketStatus(<?php echo $_POST['ticket_id'];?>);"><?php _e('Save Changes','wp-support-plus-responsive-ticket-system');?></button>

<script>
var currentScreen='change_status';
var currentTicketID=<?php echo $ticket->id?>;
</script>