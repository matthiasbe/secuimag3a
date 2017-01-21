<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
/*
 * multiple duplicate custom fields bug fix start
 */
$fields_order=array_unique($advancedSettingsFieldOrder['fields_order']);
$advancedSettingsFieldOrder['fields_order']=$fields_order;
update_option('wpsp_advanced_settings_field_order',$advancedSettingsFieldOrder);
/*
 * multiple duplicate custom fields bug fix end
 */
?>
<div id="catDisplayTableContainer" class="table-responsive">
	<table id="field_order_table" class="table table-striped">
		<thead>
		<tr>
			<th><?php _e('Field No.','wp-support-plus-responsive-ticket-system');?></th>
			<th><?php _e('Label','wp-support-plus-responsive-ticket-system');?></th>
			<th><?php _e('Status','wp-support-plus-responsive-ticket-system');?></th>
			<th><?php _e('Extra','wp-support-plus-responsive-ticket-system');?></th>
		</tr>
		</thead>
		<tbody><?php
			$field_num=1;
			foreach($advancedSettingsFieldOrder['fields_order'] as $field_id)
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
							<?php if(in_array($field_id,$advancedSettingsFieldOrder['display_fields'])){ ?>
							<input type="radio" name="field_status_<?php echo $field_id;?>" value="1" checked><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
							<input type="radio" name="field_status_<?php echo $field_id;?>" value="0"><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
							<?php }else {?>
							<input type="radio" name="field_status_<?php echo $field_id;?>" value="1"><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
							<input type="radio" name="field_status_<?php echo $field_id;?>" value="0" checked><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
							<?php }?>
						</td>
						<td></td>
					</tr>
				<?php
				}
				else
				{
					switch($field_id){
						case 'dn':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Name','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<div style="display: none;">
									<?php if(in_array($field_id,$advancedSettingsFieldOrder['display_fields'])){ ?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1" checked><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0"><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1"><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0" checked><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
									</div>
								</td>
								<td>
									<?php _e('Label','wp-support-plus-responsive-ticket-system');?>:&nbsp;
									<input id="wpsp_default_name_label" type="text" value="<?php echo $advancedSettingsFieldOrder['default_fields_label']['dn'];?>">
								</td>
							</tr>
							<?php
							break;
						case 'de':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Email Address','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<div style="display: none;">
									<?php if(in_array($field_id,$advancedSettingsFieldOrder['display_fields'])){ ?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1" checked><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0"><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1"><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0" checked><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
									</div>
								</td>
								<td>
									<?php _e('Label','wp-support-plus-responsive-ticket-system');?>:&nbsp;
									<input id="wpsp_default_email_label" type="text" value="<?php echo $advancedSettingsFieldOrder['default_fields_label']['de'];?>">
								</td>
							</tr>
							<?php
							break;
						case 'ds':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Subject','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<!--<div style="display: none;">-->
									<?php if(in_array($field_id,$advancedSettingsFieldOrder['display_fields'])){ ?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1" checked><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0"><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1"><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0" checked><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
									</div>
								</td>
								<td>
									<?php _e('Label','wp-support-plus-responsive-ticket-system');?>:&nbsp;
                                                                        <input id="wpsp_default_subject_label" type="text" value="<?php echo $advancedSettingsFieldOrder['default_fields_label']['ds'];?>"><br>
                                                                        <?php _e('Default value if disabled','wp-support-plus-responsive-ticket-system');?>:&nbsp;
									<input id="wpsp_default_subject_value" type="text" value="<?php echo $advancedSettingsFieldOrder['wpsp_default_value_of_subject'];?>">
								</td>
							</tr>
							<?php
							break;
						case 'dd':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Description','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<div style="display: none;">
									<?php if(in_array($field_id,$advancedSettingsFieldOrder['display_fields'])){ ?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1" checked><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0"><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1"><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0" checked><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
									</div>
								</td>
								<td>
									<?php _e('Label','wp-support-plus-responsive-ticket-system');?>:&nbsp;
									<input id="wpsp_default_description_label" type="text" value="<?php echo $advancedSettingsFieldOrder['default_fields_label']['dd'];?>">
								</td>
							</tr>
							<?php
							break;
						case 'dc':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Category','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if(in_array($field_id,$advancedSettingsFieldOrder['display_fields'])){ ?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1" checked><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0"><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1"><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0" checked><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td>
									<?php _e('Label','wp-support-plus-responsive-ticket-system');?>:&nbsp;
									<input id="wpsp_default_category_label" type="text" value="<?php echo $advancedSettingsFieldOrder['default_fields_label']['dc'];?>">
								</td>
							</tr>
							<?php
							break;
						case 'dp':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Priority','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if(in_array($field_id,$advancedSettingsFieldOrder['display_fields'])){ ?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1" checked><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0"><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1"><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0" checked><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td>
									<?php _e('Label','wp-support-plus-responsive-ticket-system');?>:&nbsp;
									<input id="wpsp_default_priority_label" type="text" value="<?php echo $advancedSettingsFieldOrder['default_fields_label']['dp'];?>">
								</td>
							</tr>
							<?php
							break;
						case 'da':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Attach File(s)','wp-support-plus-responsive-ticket-system')?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if(in_array($field_id,$advancedSettingsFieldOrder['display_fields'])){ ?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1" checked><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0"><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="1"><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="field_status_<?php echo $field_id;?>" value="0" checked><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td>
									<?php _e('Label','wp-support-plus-responsive-ticket-system');?>:&nbsp;
									<input id="wpsp_default_attachment_label" type="text" value="<?php echo $advancedSettingsFieldOrder['default_fields_label']['da'];?>">
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
	<script>
		jQuery('tbody').sortable();
	</script>
</div>
<input type="button" onclick="setFieldReorderSettings();" class="btn btn-success" value="<?php _e('Save Settings','wp-support-plus-responsive-ticket-system');?>">
