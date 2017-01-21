<?php 
global $wpdb;
$wpsp_et_create_new_ticket=get_option( 'wpsp_et_create_new_ticket' );
$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];

?>
<br>
<span class="label label-info wpsp_title_label">
<?php _e("Available Templates", 'wp-support-plus-responsive-ticket-system' );?>
</span><br><br>
<div class='template_display'>
<?php 
foreach ($wpsp_et_create_new_ticket['templates'] as $key=>$val){
	echo '{'.$key.'} - '.__($val,'wp-support-plus-responsive-ticket-system').'<br>';
}
?>
</div>
<hr>
<form id='frmETCreateNewTicket'>
	<span class="label label-info wpsp_title_label">
	<?php _e($advancedSettings['ticket_label_alice'][14], 'wp-support-plus-responsive-ticket-system' );?>
	</span><br><br>
	<table>
	  <tr>
	    <td><input type="radio" name='etEnableSuccessBtn' value="1" <?php echo ($wpsp_et_create_new_ticket['enable_success'])?'checked':'';?>></td>
	    <td class='wpsp_radio_btn_label'><?php _e("Enable", 'wp-support-plus-responsive-ticket-system' );?></td>
	    <td><input type="radio" name='etEnableSuccessBtn' value="0" <?php echo ($wpsp_et_create_new_ticket['enable_success'])?'':'checked';?>></td>
	    <td class='wpsp_radio_btn_label'><?php _e("Disable", 'wp-support-plus-responsive-ticket-system' );?></td>
	  </tr>
	</table><br>
	<b><?php _e("Subject", 'wp-support-plus-responsive-ticket-system' );?>:</b>
        [<?php echo __($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive-ticket-system');?> <?php echo $advancedSettings['wpsp_ticket_id_prefix'];?>] <input type="text" id='wpsp_et_success_email_subject' name='wpsp_et_success_email_subject' style="width: 50%;" value='<?php echo htmlspecialchars(stripcslashes($wpsp_et_create_new_ticket['success_subject']), ENT_QUOTES);?>'/><br>
	<b><?php _e("Body", 'wp-support-plus-responsive-ticket-system' );?>:</b><br>
	<textarea id='wpsp_et_success_email_body' name='wpsp_et_success_email_body'><?php echo stripcslashes($wpsp_et_create_new_ticket['success_body']);?></textarea>
	<hr>
	
	<span class="label label-info wpsp_title_label">
	<?php _e("Staff Email", 'wp-support-plus-responsive-ticket-system' );?>
	</span><br><br>
	<table class='wpsp_et_role_tbl'>
	  <tr>
	  	<th><?php _e("Staff Applicable", 'wp-support-plus-responsive-ticket-system' );?></th>
	  	<th><?php _e("Enable", 'wp-support-plus-responsive-ticket-system' );?></th>
	  	<th><?php _e("Disable", 'wp-support-plus-responsive-ticket-system' );?></th>
	  </tr>
	  <tr>
	  	<td>
	  		<?php _e("Administrator", 'wp-support-plus-responsive-ticket-system' );?><br>
	  		<small><i>(<?php _e("Administrator Emails set in Email Notification Settings", 'wp-support-plus-responsive-ticket-system' );?>)</i></small>
	  	</td>
	    <td><input type="radio" name='etEnableStaffAdmin' value="1" <?php echo ($wpsp_et_create_new_ticket['staff_to_notify']['administrator'])?'checked':'';?>></td>
	    <td><input type="radio" name='etEnableStaffAdmin' value="0" <?php echo ($wpsp_et_create_new_ticket['staff_to_notify']['administrator'])?'':'checked';?>></td>    
	  </tr>
	  <tr>
	  	<td><?php _e("Supervisor", 'wp-support-plus-responsive-ticket-system' );?></td>
	    <td><input type="radio" name='etEnableStaffSupervisor' value="1" <?php echo ($wpsp_et_create_new_ticket['staff_to_notify']['supervisor'])?'checked':'';?>></td>
	    <td><input type="radio" name='etEnableStaffSupervisor' value="0" <?php echo ($wpsp_et_create_new_ticket['staff_to_notify']['supervisor'])?'':'checked';?>></td>    
	  </tr>
	  <tr>
	  	<td>
	  		<?php _e("Assigned Agents", 'wp-support-plus-responsive-ticket-system' );?><br>
	  		<small><i>(<?php _e("Agents pre-assigned to category", 'wp-support-plus-responsive-ticket-system' );?>)</i></small>
	  	</td>
	    <td><input type="radio" name='etEnableStaffAssignedAgent' value="1" <?php echo ($wpsp_et_create_new_ticket['staff_to_notify']['assigned_agent'])?'checked':'';?>></td>
	    <td><input type="radio" name='etEnableStaffAssignedAgent' value="0" <?php echo ($wpsp_et_create_new_ticket['staff_to_notify']['assigned_agent'])?'':'checked';?>></td>    
	  </tr>
	  <tr>
	  	<td><?php _e("All Agents", 'wp-support-plus-responsive-ticket-system' );?></td>
	    <td><input type="radio" name='etEnableStaffAllAgent' value="1" <?php echo ($wpsp_et_create_new_ticket['staff_to_notify']['all_agents'])?'checked':'';?>></td>
	    <td><input type="radio" name='etEnableStaffAllAgent' value="0" <?php echo ($wpsp_et_create_new_ticket['staff_to_notify']['all_agents'])?'':'checked';?>></td>    
	  </tr>
	</table><br>
	<b><?php _e("Subject", 'wp-support-plus-responsive-ticket-system' );?>:</b>
        [<?php echo __($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive-ticket-system');?> <?php echo $advancedSettings['wpsp_ticket_id_prefix'];?>] <input type="text" id='wpsp_et_staff_email_subject' name='wpsp_et_staff_email_subject' style="width: 50%;" value='<?php echo $wpsp_et_create_new_ticket['staff_subject'];?>'/><br>
	<b><?php _e("Body", 'wp-support-plus-responsive-ticket-system' );?>:</b><br>
	<textarea id='wpsp_et_staff_email_body' name='wpsp_et_staff_email_body'><?php echo htmlspecialchars(stripcslashes($wpsp_et_create_new_ticket['staff_body']), ENT_QUOTES);?></textarea>
	<hr>
	<button id="wpsp_save_et_cteate_new_ticket" type="submit" class="btn btn-success"><?php _e("Save Settings", 'wp-support-plus-responsive-ticket-system' );?></button>
</form>
