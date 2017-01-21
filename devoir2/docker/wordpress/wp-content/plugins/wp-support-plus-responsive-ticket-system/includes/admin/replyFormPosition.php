<?php
$FrontEndDisplaySettings = get_option('wpsp_front_end_display_settings');
$arrayVisible=array(
    'replystatus'=>1,
    'replycategory'=>1,
    'replypriority'=>1,
    'replyattachment'=>1
);
$arrayVisible=apply_filters('wpsp_reply_form_reply_status',$arrayVisible,$_POST['ticket_id'],$flag_backend_frontend);
?>
<form id="frmThreadReply" onsubmit="wpsp_replyTicketConfirm(event);">
	<div id="theadReplyContainer">
		<textarea id="replyBody" name="replyBody"></textarea>
		<?php
		/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
	         * Update 13 - reply additional recipients
	         */
	        ?>
		<div class="replyFloatedContainer">
                    <?php if($FrontEndDisplaySettings['wpsp_hideCC']|| $flag_backend_frontend){?>
		    <div class="replyCC" id="wpsp_replycc">
		        <span class="label label-info wpsp_title_label"><?php printf(__('%s','wp-support-plus-responsive-ticket-system'),$FrontEndDisplaySettings['front_end_display_alice'][5]);?></span> (<?php _e('Comma separated list','wp-support-plus-responsive-ticket-system');?>)<br>
		        <input type="text" name="reply_cc" id="reply_cc" />
		    </div>
                    <?php }?>
                    <?php if($FrontEndDisplaySettings['wpsp_hideBCC']|| $flag_backend_frontend){?>
		    <div class="replyCC" id="wpsp_replybcc">
		        <span class="label label-info wpsp_title_label"><?php printf(__('%s','wp-support-plus-responsive-ticket-system'),$FrontEndDisplaySettings['front_end_display_alice'][6]);?></span> (<?php _e('Comma separated list','wp-support-plus-responsive-ticket-system');?>)<br>
                        <input type="text" name="reply_bcc" id="reply_bcc" />
		    </div>
                    <?php }?>
		</div>
		<?php
	        /* EMD CLOUGH I.T. SOLUTIONS MODIFICATION
	         */
	        ?>

		<div id="replyFloatedContainer">
		    <?php if($arrayVisible['replystatus'] && ($FrontEndDisplaySettings['wpsp_hideStatus']|| $flag_backend_frontend)){?>
                        <div class="replyFloatLeft wpsp_reply" id="wpsp_status_reply">
				<span class="label label-info wpsp_title_label"><?php printf(__('%s','wp-support-plus-responsive-ticket-system'),$FrontEndDisplaySettings['front_end_display_alice'][7]);?></span><br>
				<select id="reply_ticket_status" name="reply_ticket_status">
					<?php
					$sql_status="select * from {$wpdb->prefix}wpsp_custom_status";
					$custom_statusses=$wpdb->get_results($sql_status);
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
					foreach($custom_statusses as $custom_status){?>
						<option value="<?php echo $custom_status->name ?>" <?php echo (strtolower($ticket->status)==strtolower($custom_status->name))?'selected="selected"':'';?>><?php _e(ucfirst($custom_status->name),'wp-support-plus-responsive-ticket-system');?></option>
					<?php
					}
					?>
				</select>
			</div>
                    <?php } else {?>
                            <input type="hidden" name="reply_ticket_status" id="reply_ticket_status" value="<?php echo $ticket->status;?>">
                    <?php }?>
			<?php
			if($arrayVisible['replycategory'] && (in_array("dc",$advancedSettingsFieldOrder['display_fields']) && $FrontEndDisplaySettings['wpsp_hideCategory']|| $flag_backend_frontend))
			{
			?>
			<div class="replyFloatLeft wpsp_reply" id="wpsp_category_reply">
				<span class="label label-info wpsp_title_label"><?php printf(__('%s','wp-support-plus-responsive-ticket-system'),$FrontEndDisplaySettings['front_end_display_alice'][8]);?></span><br>
				<select id="reply_ticket_category" name="reply_ticket_category">
					<?php 
					foreach ($categories as $category){
						$selected=($category->id==$ticket->cat_id)?'selected="selected"':'';
						echo '<option value="'.$category->id.'" '.$selected.'>'.stripcslashes($category->name).'</option>';
					}
					?>
				</select>
			</div>
			<?php
			}
			else{
			?><input type="hidden" name="reply_ticket_category" id="reply_ticket_category" value="<?php echo $ticket->cat_id;?>"><?php
			}
			if($arrayVisible['replypriority'] && (in_array("dp",$advancedSettingsFieldOrder['display_fields']) && $FrontEndDisplaySettings['wpsp_hidePriority']|| $flag_backend_frontend))
			{
			?>
			<div class="replyFloatLeft wpsp_reply" id="wpsp_priority_reply">
				<span class="label label-info wpsp_title_label"><?php printf(__('%s','wp-support-plus-responsive-ticket-system'),$FrontEndDisplaySettings['front_end_display_alice'][9]);?></span><br>
				<select id="reply_ticket_priority" name="reply_ticket_priority">
					<?php 
					foreach ($priorities as $priority){
					?>
						<option value="<?php echo strtolower($priority->name);?>" <?php echo ($ticket->priority==strtolower($priority->name))?'selected="selected"':'';?>><?php _e($priority->name,'wp-support-plus-responsive-ticket-system');?></option>
					<?php
					}
					?>
				</select>
			</div>
			<?php
			}
			else{
			?><input type="hidden" name="reply_ticket_priority" id="reply_ticket_priority" value="<?php echo $ticket->priority;?>"><?php
			}
			if($arrayVisible['replyattachment'] && (in_array("da",$advancedSettingsFieldOrder['display_fields']) && $FrontEndDisplaySettings['wpsp_hideAttachments']|| $flag_backend_frontend))
			{
			?>
                            <div class="replyFloatLeftFullWidth wpsp_reply" id="wpsp_reply_attachment">
                                <span class="label label-info wpsp_title_label"><?php printf(__('%s','wp-support-plus-responsive'),$FrontEndDisplaySettings['front_end_display_alice'][10]);?></span><br>
                                <div class="wpsp_frm_attachment_container">
                                    <input type="file" id="wpsp_frm_attachment_input_reply" class="wpsp_frm_attachment_input">
                                    <div id="wpsp_frm_attachment_copy_reply" class="wpsp_frm_attachment" style="display: none;">
                                        <span class="wpsp_frm_attachment_name"></span><br>
                                        <span class="wpsp_frm_attachment_percentage">[0%]</span>
                                        <span class="wpsp_frm_attachment_remove"></span>
                                    </div>
                                    <div id="wpsp_frm_attachment_list_reply" class="wpsp_frm_attachment_list"></div>
                                    <div id="wpsp_frm_attachment_ids_container_reply" class="wpsp_frm_attachment_ids_container"></div>
                                </div>
                            </div>
			<?php
			}?>
			<input type="hidden" name="action" value="replyTicket">
			<input type="hidden" name="ticket_id" value="<?php echo $_POST['ticket_id'];?>">
			<input type="hidden" name="user_id" value="<?php echo $current_user->ID;?>">
			<input type="hidden" name="type" value="user">
			<input type="hidden" name="guest_name" value="">
			<input type="hidden" name="guest_email" value="">
			<?php
			if($current_user->has_cap('manage_support_plus_ticket') && $FrontEndDisplaySettings['wpsp_hideAddNotes']|| $flag_backend_frontend){
                            $btnStyle="color:".$FrontEndDisplaySettings['wpsp_an_fc']."; background-color:".$FrontEndDisplaySettings['wpsp_an_bc']."; border-color:".$FrontEndDisplaySettings['wpsp_an_bc'];
                            ?>
				<input type="hidden" name="notify" value="true">
                                <input style="<?php echo $btnStyle;?>" type="button" id="wpsp_add_note_btn" class="btn btn-success replyFloatRight" value="<?php printf(__('%s','wp-support-plus-responsive-ticket-system'),$FrontEndDisplaySettings['front_end_display_alice'][11]);?>" onClick="addNote()" />
			<?php
			}
                        
                        $btnStyle="color:".$FrontEndDisplaySettings['wpsp_sr_fc']."; background-color:".$FrontEndDisplaySettings['wpsp_sr_bc']."; border-color:".$FrontEndDisplaySettings['wpsp_sr_bc'];
			?>
                        <input style="<?php echo $btnStyle;?>" type="submit" id="wpsp_submit_reply_btn" class="btn btn-success replyFloatRight" value="<?php printf(__('%s','wp-support-plus-responsive-ticket-system'),$FrontEndDisplaySettings['front_end_display_alice'][12]);?>">
		</div>
                <div class="replyFloatedContainer">
                <?php
                    do_action('wpsp_after_reply_fields');
                ?>
                </div>
	</div>
</form>

<script>
    var wpsp_attachment_counter=0;
    var wpsp_attachment_share_lock=false;
</script>
<?php if(in_array("da",$advancedSettingsFieldOrder['display_fields']) && $FrontEndDisplaySettings['wpsp_hideAttachments'] || $flag_backend_frontend){?>
<script>
    jQuery('#wpsp_frm_attachment_input_reply').change(function() {
        wpspUploadAttachment(this.files,'reply');
    });
</script>
<?php } ?>

<?php
do_action('wpsp_after_reply_form');
?>