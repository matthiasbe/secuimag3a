<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$advancedSettingsTicketList=get_option( 'wpsp_advanced_settings_ticket_list_order' );
$subCharLength=get_option( 'wpsp_ticket_list_subject_char_length' );
$dateFormat = get_option( 'wpsp_ticket_list_date_format' );

$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];
if(isset($advancedSettingsTicketList))
{
	if(is_array($advancedSettingsTicketList)){
		$backend_ticket_list=$advancedSettingsTicketList['backend_ticket_list'];
		$frontend_ticket_list=$advancedSettingsTicketList['frontend_ticket_list'];
	}
}
if(isset($backend_ticket_list) && is_array($backend_ticket_list)){
?>
<div id="catDisplayTableContainer" class="table-responsive">
	<h2><?php _e($advancedSettings['ticket_label_alice'][18],'wp-support-plus-responsive-ticket-system');?></h2>
	<table id="backend_field_list_table" class="table table-striped">
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
			foreach($backend_ticket_list as $field_id=>$status)
			{	
                                if(is_numeric($field_id))
				{
					$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields WHERE id='".$field_id."'" );
					foreach($customFields as $customField)
					{?>
						<tr>
							<td><?php echo $field_num++;?></td>
							<td><?php echo $customField->label;?></td>
							<td style="display:none;"><?php echo $customField->id;?></td>
							<td>
								<?php if($status==1){ ?>
								<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
								<?php }else {?>
								<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
								<?php }?>
							</td>
							<td></td>
						</tr>
					<?php
					}
				}
				else
				{
					switch($field_id){
						case 'id':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('ID','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'st':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Status','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'sb':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Subject','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td>
									<?php _e('Char. Length','wp-support-plus-responsive-ticket-system');?>:&nbsp;
									<input id="wpsp_backend_sub_char_length" type="text" value="<?php echo $subCharLength['backend'];?>">&nbsp;
									<button type="button" onclick="setSubCharLength();"><?php _e('Save','wp-support-plus-responsive-ticket-system');?></button>
									<span class="custom-priority-color-saved setSubCharLengthCSS"><?php _e('Saved!','wp-support-plus-responsive-ticket-system');?></span>
								</td>
							</tr>
							<?php
							break;
						case 'rb':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Raised By','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'ty':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Type','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'ct':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Category','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'at':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Assigned To','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'pt':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Priority','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'ut':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Updated Time','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'cdt':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Date Created','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td>
									<?php _e('Date Format','wp-support-plus-responsive-ticket-system');?>:&nbsp;
									<input id="wpsp_backend_cdt_format" type="text" value="<?php echo $dateFormat['cdt_backend'];?>">&nbsp;
									<button type="button" onclick="setDateFormat();"><?php _e('Save','wp-support-plus-responsive-ticket-system');?></button>
									<span class="custom-priority-color-saved setDateFormatCSS"><?php _e('Saved!','wp-support-plus-responsive-ticket-system');?></span><br>
									<div class="wpspDateFormatDiv">
										<b><a href="http://php.net/manual/en/function.date.php" target="_blank"><?php _e('Click here','wp-support-plus-responsive-ticket-system');?></a></b> <?php _e('to see available date formats. E.g.','wp-support-plus-responsive-ticket-system');?> <b>d-M-Y</b> <?php _e('will display date as','wp-support-plus-responsive-ticket-system');?> <b>10-Sep-2015</b> 
									</div>
								</td>
							</tr>
							<?php
							break;
						case 'udt':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Date Updated','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td>
									<?php _e('Date Format','wp-support-plus-responsive-ticket-system');?>:&nbsp;
									<input id="wpsp_backend_udt_format" type="text" value="<?php echo $dateFormat['udt_backend'];?>">&nbsp;
									<button type="button" onclick="setDateFormat();"><?php _e('Save','wp-support-plus-responsive-ticket-system');?></button>
									<span class="custom-priority-color-saved setDateFormatCSS"><?php _e('Saved!','wp-support-plus-responsive-ticket-system');?></span><br>
									<div class="wpspDateFormatDiv">
										<b><a href="http://php.net/manual/en/function.date.php" target="_blank"><?php _e('Click here','wp-support-plus-responsive-ticket-system');?></a></b> <?php _e('to see available date formats. E.g.','wp-support-plus-responsive-ticket-system');?> <b>d-M-Y</b> <?php _e('will display date as','wp-support-plus-responsive-ticket-system');?> <b>10-Sep-2015</b> 
									</div>
								</td>
							</tr>
							<?php
							break;
                                                        case 'acd':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Agent Created','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="backend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
                                                        default:                                                         
                                                                do_action('wpsp_add_backend_ticket_list_field_in_advanced_settings',$field_id,$field_num,$status);                                                         
                                                                break;
					}
				}
			}
			?>
		</tbody>
	</table>
	<script type="text/javascript">
		jQuery('tbody').sortable();
	</script>
	<h2><?php _e($advancedSettings['ticket_label_alice'][19],'wp-support-plus-responsive-ticket-system');?></h2>
	<table id="frontend_field_list_table" class="table table-striped">
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
			foreach($frontend_ticket_list as $field_id=>$status)
			{	if(is_numeric($field_id))
				{
					$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields WHERE id='".$field_id."'" );
					foreach($customFields as $customField)
					{?>
						<tr id="<?php echo $field_num;?>">
							<td><?php echo $field_num++;?></td>
							<td><?php echo $customField->label;?></td>
							<td style="display:none;"><?php echo $customField->id;?></td>
							<td>
								<?php if($status==1){ ?>
								<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
								<?php }else {?>
								<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
								<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
								<?php }?>
							</td>
							<td></td>
						</tr>
					<?php
					}
				}
				else
				{
					switch($field_id){
						case 'id':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('ID','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'st':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Status','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'sb':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Subject','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td>
									<?php _e('Char. Length','wp-support-plus-responsive-ticket-system');?>:&nbsp;
									<input id="wpsp_frontend_sub_char_length" type="text" value="<?php echo $subCharLength['frontend'];?>">&nbsp;
									<button type="button" onclick="setSubCharLength();"><?php _e('Save','wp-support-plus-responsive-ticket-system');?></button>
									<span class="custom-priority-color-saved setSubCharLengthCSS"><?php _e('Saved!','wp-support-plus-responsive-ticket-system');?></span>
								</td>
							</tr>
							<?php
							break;
						case 'rb':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Raised By','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'ty':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Type','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'ct':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Category','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'at':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Assigned To','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'pt':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Priority','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;
						case 'ut':?>
							<tr id="<?php echo $field_num;?>">
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Updated Time','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td></td>
							</tr>
							<?php
							break;

						case 'cdt':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Date Created','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td>
									<?php _e('Date Format','wp-support-plus-responsive-ticket-system');?>:&nbsp;
									<input id="wpsp_frontend_cdt_format" type="text" value="<?php echo $dateFormat['cdt_frontend'];?>">&nbsp;
									<button type="button" onclick="setDateFormat();"><?php _e('Save','wp-support-plus-responsive-ticket-system');?></button>
									<span class="custom-priority-color-saved setDateFormatCSS"><?php _e('Saved!','wp-support-plus-responsive-ticket-system');?></span><br>
									<div class="wpspDateFormatDiv">
										<b><a href="http://php.net/manual/en/function.date.php" target="_blank"><?php _e('Click here','wp-support-plus-responsive-ticket-system');?></a></b> <?php _e('to see available date formats. E.g.','wp-support-plus-responsive-ticket-system');?> <b>d-M-Y</b> <?php _e('will display date as','wp-support-plus-responsive-ticket-system');?> <b>10-Sep-2015</b> 
									</div>
								</td>
							</tr>
							<?php
							break;
						case 'udt':?>
							<tr>
								<td><?php echo $field_num++;?></td>
								<td><?php _e('Date Updated','wp-support-plus-responsive-ticket-system');?></td>
								<td style="display:none;"><?php echo $field_id;?></td>
								<td>
									<?php if($status==1){ ?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1" checked><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0"><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }else {?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="1"><?php _e('Show','wp-support-plus-responsive-ticket-system');?>
									<input type="radio" name="frontend_field_<?php echo $field_id;?>" value="0" checked><?php _e('Hide','wp-support-plus-responsive-ticket-system');?>
									<?php }?>
								</td>
								<td>
									<?php _e('Date Format','wp-support-plus-responsive-ticket-system');?>:&nbsp;
									<input id="wpsp_frontend_udt_format" type="text" value="<?php echo $dateFormat['cdt_frontend'];?>">&nbsp;
									<button type="button" onclick="setDateFormat();"><?php _e('Save','wp-support-plus-responsive-ticket-system');?></button>
									<span class="custom-priority-color-saved setDateFormatCSS"><?php _e('Saved!','wp-support-plus-responsive-ticket-system');?></span><br>
									<div class="wpspDateFormatDiv">
										<b><a href="http://php.net/manual/en/function.date.php" target="_blank"><?php _e('Click here','wp-support-plus-responsive-ticket-system');?></a></b> <?php _e('to see available date formats. E.g.','wp-support-plus-responsive-ticket-system');?> <b>d-M-Y</b> <?php _e('will display date as','wp-support-plus-responsive-ticket-system');?> <b>10-Sep-2015</b> 
									</div>
								</td>
							</tr>
							<?php
							break;
                                                default :                                                         
                                                        do_action('wpsp_add_frontend_ticket_list_field_in_advanced_settings',$field_id,$field_num,$status);                                                         
                                                        break;
					}
				}
			}
			?>
		</tbody>
	</table>
	<script type="text/javascript">
		jQuery('tbody').sortable();
	</script>
</div>
<input type="button" onclick="setTicketListFieldSettings();" class="btn btn-success" value="<?php _e('Save Settings','wp-support-plus-responsive-ticket-system');?>">
<?php
}
?>
