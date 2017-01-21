<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$advancedSettingsCustomFilterFront=get_option( 'wpsp_advanced_settings_custom_filter_front' );

$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];

$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];

$filter_fields=array('st','ct','not','tt');
$Fields = $wpdb->get_results( "SELECT id FROM {$wpdb->prefix}wpsp_custom_fields WHERE field_type=2 OR field_type=4" );
foreach($Fields as $field)
{
	$filter_fields=array_merge($filter_fields,array($field->id));
}
?>
<div id="catDisplayTableContainer" class="table-responsive">
	<table id="custom_filter_front_end" class="table table-striped">
		<thead>
		<tr>
			<th><?php _e('Field No.','wp-support-plus-responsive-ticket-system');?></th>
			<th><?php _e('Label','wp-support-plus-responsive-ticket-system');?></th>
			<th><?php _e('Logged In Users','wp-support-plus-responsive-ticket-system');?></th>
			<th><?php _e('Agents','wp-support-plus-responsive-ticket-system');?></th>
			<th><?php _e('Supervisors','wp-support-plus-responsive-ticket-system');?></th>
		</tr>
		</thead>
		<tbody><?php
			$field_num=1;
			foreach($filter_fields as $field_id)
			{	if(is_numeric($field_id))
				{
					$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields WHERE id='".$field_id."'" );
					foreach($customFields as $customField)
					{
						$field_options_string="";
						if($customField->field_options==NULL)
						{
							$field_options=array();
						}
						else
						{
							$field_options=unserialize($customField->field_options);
							$count=1;
							foreach($field_options as $field_option_key=>$field_option_value){
								$count++;
								if($count<=count($field_options))
								{
									$field_options_string.=$field_option_value."<br>";
								}
								else
								{
									$field_options_string.=$field_option_value;
								}
							}
						}
					}
					?>
					<tr id="<?php echo $field_num;?>">
						<td><?php echo $field_num++;?></td>
						<td><?php echo $customField->label;?></td>
						<td style="display:none;"><?php echo $customField->id;?></td>
						<td>
							<?php if(in_array($field_id,$advancedSettingsCustomFilterFront['logged_in'])){ 
								$checked="checked='checked'";
							}else {
								$checked="";
							}?>
							<input type="checkbox" id="logged_in_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
						</td>
						<td>
							<?php if(in_array($field_id,$advancedSettingsCustomFilterFront['agent_logged_in'])){ 
								$checked="checked='checked'";
							}else {
								$checked="";
							}?>
							<input type="checkbox" id="agents_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
						</td>
						<td>
							<?php if(in_array($field_id,$advancedSettingsCustomFilterFront['supervisor_logged_in'])){ 
								$checked="checked='checked'";
							}else {
								$checked="";
							}?>
							<input type="checkbox" id="supervisors_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
						</td>
					</tr>
				<?php
				}
				else
				{
					switch($field_id){
						case 'st':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Status','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if(is_array($advancedSettingsCustomFilterFront['logged_in']) && in_array($field_id,$advancedSettingsCustomFilterFront['logged_in'])){ 
										$checked="checked='checked'";
									}else {
										$checked="";
									}?>
									<input type="checkbox" id="logged_in_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								</td>
								<td>
									<?php if(is_array($advancedSettingsCustomFilterFront['agent_logged_in']) && in_array($field_id,$advancedSettingsCustomFilterFront['agent_logged_in'])){ 
										$checked="checked='checked'";
									}else {
										$checked="";
									}?>
									<input type="checkbox" id="agents_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								</td>
								<td>
									<?php if(is_array($advancedSettingsCustomFilterFront['supervisor_logged_in']) && in_array($field_id,$advancedSettingsCustomFilterFront['supervisor_logged_in'])){ 
										$checked="checked='checked'";
									}else {
										$checked="";
									}?>
									<input type="checkbox" id="supervisors_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								</td>
							</tr>
							<?php
							break;
						case 'ct':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e($default_labels['dc'],'wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if(is_array($advancedSettingsCustomFilterFront['logged_in']) && in_array($field_id,$advancedSettingsCustomFilterFront['logged_in'])){ 
										$checked="checked";
									}else {
										$checked="";
									}?>
									<input type="checkbox" id="logged_in_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								</td>
								<td>
									<?php if(is_array($advancedSettingsCustomFilterFront['agent_logged_in']) && in_array($field_id,$advancedSettingsCustomFilterFront['agent_logged_in'])){ 
										$checked="checked='checked'";
									}else {
										$checked="";
									}?>
									<input type="checkbox" id="agents_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								</td>
								<td>
									<?php if(is_array($advancedSettingsCustomFilterFront['supervisor_logged_in']) && in_array($field_id,$advancedSettingsCustomFilterFront['supervisor_logged_in'])){ 
										$checked="checked='checked'";
									}else {
										$checked="";
									}?>
									<input type="checkbox" id="supervisors_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								</td>
							</tr>
							<?php
							break;
						case 'not':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e($advancedSettings['ticket_label_alice'][12],'wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if(is_array($advancedSettingsCustomFilterFront['logged_in']) && in_array($field_id,$advancedSettingsCustomFilterFront['logged_in'])){ 
										$checked="checked='checked'";
									}else {
										$checked="";
									}?>
									<input type="checkbox" id="logged_in_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								</td>
								<td>
									<?php if(is_array($advancedSettingsCustomFilterFront['agent_logged_in']) && in_array($field_id,$advancedSettingsCustomFilterFront['agent_logged_in'])){ 
										$checked="checked='checked'";
									}else {
										$checked="";
									}?>
									<input type="checkbox" id="agents_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								</td>
								<td>
									<?php if(is_array($advancedSettingsCustomFilterFront['supervisor_logged_in']) && in_array($field_id,$advancedSettingsCustomFilterFront['supervisor_logged_in'])){ 
										$checked="checked='checked'";
									}else {
										$checked="";
									}?>
									<input type="checkbox" id="supervisors_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								</td>
							</tr>
							<?php
							break;
						case 'tt':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Text','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if(is_array($advancedSettingsCustomFilterFront['logged_in']) && in_array($field_id,$advancedSettingsCustomFilterFront['logged_in'])){ 
										$checked="checked='checked'";
									}else {
										$checked="";
									}?>
									<input type="checkbox" id="logged_in_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								</td>
								<td>
									<?php if(is_array($advancedSettingsCustomFilterFront['agent_logged_in']) && in_array($field_id,$advancedSettingsCustomFilterFront['agent_logged_in'])){ 
										$checked="checked='checked'";
									}else {
										$checked="";
									}?>
									<input type="checkbox" id="agents_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								</td>
								<td>
									<?php if(is_array($advancedSettingsCustomFilterFront['supervisor_logged_in']) && in_array($field_id,$advancedSettingsCustomFilterFront['supervisor_logged_in'])){ 
										$checked="checked='checked'";
									}else {
										$checked="";
									}?>
									<input type="checkbox" id="supervisors_<?php echo $field_id;?>" name="filter_field_<?php echo $field_id;?>" value="1" <?php echo $checked;?>><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								</td>
							</tr>
							<?php
							break;
					}
				}
			}
			?>
		</tbody>
	</table>
</div>
<input type="button" onclick="setCustomFilterFrontEnd();" class="btn btn-success" value="<?php _e('Save Settings','wp-support-plus-responsive-ticket-system');?>">
