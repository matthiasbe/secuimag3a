<?php 
global $wpdb;
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories" );
$priorities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_priority" );
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
?>
<table id="tblChangeStatusContainer">
  <tr>
    <td><?php _e('Status','wp-support-plus-responsive-ticket-system');?></td>
    <td>:</td>
    <td>
    	<select id="change_status_ticket_status">
			<option value="select"><?php _e('Select','wp-support-plus-responsive-ticket-system');?></option>
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
						<option value="<?php echo $custom_status->name?>"><?php _e(ucfirst($custom_status->name),'wp-support-plus-responsive-ticket-system');?></option>
					<?php
					}
				}
			?>
		</select>
    </td>
  </tr>
  <tr>
    <td><?php _e('Category','wp-support-plus-responsive-ticket-system');?></td>
    <td>:</td>
    <td>
    	<select id="change_status_category">
			<option value="select"><?php _e('Select','wp-support-plus-responsive-ticket-system');?></option>
			<?php 
			foreach ($categories as $category){
				echo '<option value="'.$category->id.'" >'.$category->name.'</option>';
			}
			?>
		</select>
    </td>
  </tr>
  <tr>
    <td><?php _e('Priority','wp-support-plus-responsive-ticket-system');?></td>
    <td>:</td>
    <td>
    	<select id="change_status_priority">
    		<option value="select"><?php _e('Select','wp-support-plus-responsive-ticket-system');?></option>
			<?php 
			foreach ($priorities as $priority){
				echo '<option value="'.strtolower($priority->name).'" >'.__($priority->name,'wp-support-plus-responsive-ticket-system').'</option>';
			}
			?>
		</select>
    </td>
  </tr>
</table>

<button class="btn btn-success" onclick="wpspBulkChangeStatusSubmitChanges();"><?php _e('Save Changes','wp-support-plus-responsive-ticket-system');?></button>
<button type="button" class='btn btn-success' onclick="wpspHideFilterDashboardBody();"><?php _e('Cancel','wp-support-plus-responsive-ticket-system');?></button>
