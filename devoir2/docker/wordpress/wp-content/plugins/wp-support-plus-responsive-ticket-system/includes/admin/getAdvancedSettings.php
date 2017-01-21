<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb,$wp_roles;
$userRoles=$wp_roles->roles;
$advancedSettings=get_option( 'wpsp_advanced_settings' );
$ticketReplyLinkPageID=get_option( 'wpsp_ticket_open_page_shortcode' );
$pages=get_pages( array('post_type' => 'page','post_status' => 'publish') );
$posts=get_posts( array('post_type' => 'post','post_status' => 'publish') );

if(!$advancedSettings['hide_selected_status_ticket_backend']) $advancedSettings['hide_selected_status_ticket_backend']=array();
if(!$advancedSettings['modify_raised_by']) $advancedSettings['modify_raised_by']=array();
?>
<br>
<span class="label label-info wpsp_title_label"><?php _e('Guest Ticket','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e('This message will be dispayed when a new ticket is submitted from guest','wp-support-plus-responsive-ticket-system');?></small><br><br>
<textarea id="guest_ticket_submission_message" name="guest_ticket_submission_message"><?php echo stripslashes($advancedSettings['guest_ticket_submission_message']);?></textarea>
<br>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst"><input <?php echo ( $advancedSettings['guest_ticket_redirect']==1)?'checked="checked"':'';?> type="checkbox" id="guest_ticket_redirect" name='guest_ticket_redirect'/></td>
    <td class="tblGeneralStingsTdSecond"><?php _e('Redirect to below link after submit(above message wont display)','wp-support-plus-responsive-ticket-system');?></td>
  </tr><br>
  <tr>
      <td></td>
      <td><input type="text" name='guest_ticket_redirect_url' id='guest_ticket_redirect_url' value="<?php echo ( $advancedSettings['guest_ticket_redirect_url'])?>"></td>
  </tr>
</table><br>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Ticket ID Prefix','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e('If you set SRN, [Ticket #123456] will become [Ticket SRN123456] whereever applicable. Please don\'t leave blank.','wp-support-plus-responsive-ticket-system');?></small><br><br>
<input type="text" id="wpsp_ticket_id_prefix" value="<?php echo $advancedSettings['wpsp_ticket_id_prefix'];?>">
<br>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Dashboard Menu Label','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e('This is applicable for dashboard menu title only. Please don\'t leave blank.','wp-support-plus-responsive-ticket-system');?></small><br><br>
<input type="text" id="dashboardMenuLabel" value="<?php echo $advancedSettings['wpsp_dashboard_menu_label'];?>">
<br>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Ticket Status','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e('This is applicable for all pending tickets only. Please leave blank to disable this feature.','wp-support-plus-responsive-ticket-system');?></small><br><br>
<?php _e("All pending tickets will automatically get closed after",'wp-support-plus-responsive-ticket-system');?> <input type="text" id="pendingTicketClose" size="4" value="<?php echo $advancedSettings['pending_ticket_close'];?>"> <?php _e("days",'wp-support-plus-responsive-ticket-system');?>.
<br>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Reply Form Position','wp-support-plus-responsive-ticket-system');?></span><br><br>
<input type="radio" name="wpsp_reply_form_position" value="1"<?php echo ($advancedSettings['wpsp_reply_form_position']==1)?'checked="checked"':''; ?>> <?php _e('Above Threads','wp-support-plus-responsive-ticket-system');?>
<br>
<input type="radio" name="wpsp_reply_form_position" value="0"<?php echo ($advancedSettings['wpsp_reply_form_position']==0)?'checked="checked"':''; ?>> <?php _e('Below Threads','wp-support-plus-responsive-ticket-system');?>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Shortcode Used In','wp-support-plus-responsive-ticket-system');?></span><br><br>
<input type="radio" name="wpsp_shortcode_used_in" value="1"<?php echo ($advancedSettings['wpsp_shortcode_used_in']==1)?'checked="checked"':''; ?>> <?php _e('Default Wordpress Editor','wp-support-plus-responsive-ticket-system');?>
<br>
<input type="radio" name="wpsp_shortcode_used_in" value="0"<?php echo ($advancedSettings['wpsp_shortcode_used_in']==0)?'checked="checked"':''; ?>> <?php _e('Page Builder','wp-support-plus-responsive-ticket-system');?>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Accordion View','wp-support-plus-responsive-ticket-system');?></span><br><br>
<input type="radio" name="enable_accordion" value="1" <?php echo ($advancedSettings['enable_accordion']==1)?'checked="checked"':'';?>><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
<br>
<input type="radio" name="enable_accordion" value="0" <?php echo ($advancedSettings['enable_accordion']==0)?'checked="checked"':'';?>><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Ticket Id','wp-support-plus-responsive-ticket-system');?></span><br><br>
<input type="radio" name="ticketId" value="1" <?php echo ($advancedSettings['ticketId']==1)?'checked="checked"':'';?>><?php _e('Sequential','wp-support-plus-responsive-ticket-system');?>
<br>
<input type="radio" name="ticketId" value="0" <?php echo ($advancedSettings['ticketId']==0)?'checked="checked"':'';?>><?php _e('Random','wp-support-plus-responsive-ticket-system');?>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Frontend Logout (above support plus functionality)','wp-support-plus-responsive-ticket-system');?></span><br><br>
<input type="radio" name="logout_Settings" value="1" <?php echo ($advancedSettings['logout_Settings']==1)?'checked="checked"':'';?>><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
<br>
<input type="radio" name="logout_Settings" value="0" <?php echo ($advancedSettings['logout_Settings']==0)?'checked="checked"':'';?>><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Ticket Link Page/Post','wp-support-plus-responsive-ticket-system');?></span><br>
<select id="setTicketLinkPage">
	<option value="0" <?php echo ($ticketReplyLinkPageID==0)?'selected="selected"':'';?>><?php _e('Select Page/Post','wp-support-plus-responsive-ticket-system');?></option>
	<optgroup label="Page">
		<?php 
		foreach ($pages as $page){
			$selected=($ticketReplyLinkPageID==$page->ID)?'selected="selected"':'';
			echo '<option '.$selected.' value="'.$page->ID.'">'.$page->post_title.'</option>';
		}
		?>
	</optgroup>
	<optgroup label="Post">
		<?php 
		foreach ($posts as $post){
			$selected=($ticketReplyLinkPageID==$post->ID)?'selected="selected"':'';
			echo '<option '.$selected.' value="'.$post->ID.'">'.$post->post_title.'</option>';
		}
		?>
	</optgroup>
</select><br>
<small><code>*</code><?php _e('Use shortcode','wp-support-plus-responsive-ticket-system');?> <code>[wpsp_open_ticket]</code> <?php _e('in selected page/post above.','wp-support-plus-responsive-ticket-system');?></small>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Hide Selected Status Tickets on Front-end','wp-support-plus-responsive-ticket-system');?></span><br><br>
<form id="wpspBackendTicketFilter1">
<div class="filter_item">
	<table>
		<tr>
			<td><?php _e('Status:','wp-support-plus-responsive-ticket-system');?></td>
			<td>
				<select id="hide_selected_status_ticket" name="hide_selected_status_ticket">
					<option value="none" <?php echo ($advancedSettings['hide_selected_status_ticket']=='none')?'selected="selected"':'';?>><?php _e('None','wp-support-plus-responsive-ticket-system');?></option>
					<?php
					$sql_status="select * from {$wpdb->prefix}wpsp_custom_status";
					$custom_statusses=$wpdb->get_results($sql_status);
					$total_statusses=$wpdb->num_rows;
					$advancedSettingsStatusOrder=get_option( 'wpsp_advanced_settings_status_order' );
					if(isset($advancedSettingsStatusOrder['status_order'])){
						if(is_array($advancedSettingsStatusOrder['status_order'])){
							$custom_statusses=array();
							foreach($advancedSettingsStatusOrder['status_order'] as $status_id)
		                                        {   $sql="select * from {$wpdb->prefix}wpsp_custom_status WHERE id=".$status_id."";
							    $status_data=$wpdb->get_results($sql);
							    foreach($status_data as $status){
							    $custom_statusses=array_merge($custom_statusses,array($status));
						            }
                                                        }   
						}
					}		
                                   
					if($total_statusses)
					{
						foreach($custom_statusses as $custom_status){?>
                                        <option value="<?php echo $custom_status->name;?>" <?php echo ($advancedSettings['hide_selected_status_ticket']==$custom_status->name)?'selected="selected"':'';?>><?php _e($custom_status->name,'wp-support-plus-responsive-ticket-system');?></option>
						<?php
						}
					}
					?>
				</select>
			</td>
		</tr>
	</table>
</div>
</form>
<br>
<table>
     <tr>
         <td class=""><?php _e('Message for Ticket URL screen for above selected status:','wp-support-plus-responsive-ticket-system');?></td>  
     </tr>
     <tr>
         <td>
            <textarea id="wpsp_ticket_url_message"><?php echo stripslashes(htmlspecialchars($advancedSettings['message_for_ticket_url']));?></textarea>
         </td>
     </tr>
 </table>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Date Custom Field1','wp-support-plus-responsive-ticket-system');?></span><br/><br/>
<small><code>*</code><?php _e('Select Date Format:','wp-support-plus-responsive-ticket-system');?></small><br><br>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst">
        <select id="datecustfield" name="datecustfield">
                <option value="dd-mm-yy" <?php echo ($advancedSettings['datecustfield']=='dd-mm-yy')?'selected="selected"':'';?>><?php _e('dd-mm-yy','wp-support-plus-responsive-ticket-system');?></option>
                <option value="mm-dd-yy" <?php echo ($advancedSettings['datecustfield']=='mm-dd-yy')?'selected="selected"':'';?>><?php _e('mm-dd-yy','wp-support-plus-responsive-ticket-system');?></option>
                <option value="yy-mm-dd" <?php echo ($advancedSettings['datecustfield']=='yy-mm-dd')?'selected="selected"':'';?>><?php _e('yy-mm-dd','wp-support-plus-responsive-ticket-system');?></option>
                <option value="yy-dd-mm" <?php echo ($advancedSettings['datecustfield']=='yy-dd-mm')?'selected="selected"':'';?>><?php _e('yy-dd-mm','wp-support-plus-responsive-ticket-system');?></option>
                <option value="mm-yy-dd" <?php echo ($advancedSettings['datecustfield']=='mm-yy-dd')?'selected="selected"':'';?>><?php _e('mm-yy-dd','wp-support-plus-responsive-ticket-system');?></option>
                <option value="dd-yy-mm" <?php echo ($advancedSettings['datecustfield']=='dd-yy-mm')?'selected="selected"':'';?>><?php _e('dd-yy-mm','wp-support-plus-responsive-ticket-system');?></option> 
       </select><br>  
    </td>
  </tr>
</table>
<hr>

<!-- Hide selected status- -->
<br>
<span class="label label-info wpsp_title_label"><?php _e('Hide Selected Status Tickets on Back-end','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e('Select Statuses','wp-support-plus-responsive-ticket-system');?> <?php _e('for hiding tickets from back-end','wp-support-plus-responsive-ticket-system');?></small><br>
<table><?php 
$sql_status="select * from {$wpdb->prefix}wpsp_custom_status";
$custom_statusses=$wpdb->get_results($sql_status);
foreach ($custom_statusses as $custom_status){
    ?><tr>
        <td><input <?php echo (is_numeric(array_search($custom_status->id,$advancedSettings['hide_selected_status_ticket_backend'])))?'checked="checked"':'';?> type="checkbox" name="hideSelectedStatusBackend[]" value="<?php echo $custom_status->id;?>"/></td>
        <td><?php _e($custom_status->name,'wp-support-plus-responsive-ticket-system');?></td>
    </tr><?php
}
?></table>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Admin Bar Link','wp-support-plus-responsive-ticket-system');?></span><br><br>
<input type="radio" name="admin_bar_Setting" value="1" <?php echo ($advancedSettings['admin_bar_Setting']==1)?'checked="checked"':'';?>><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
<br>
<input type="radio" name="admin_bar_Setting" value="0" <?php echo ($advancedSettings['admin_bar_Setting']==0)?'checked="checked"':'';?>><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Default Tab','wp-support-plus-responsive-ticket-system');?></span><br><br>
<form id="wpspBackendTicketFilter1">
<div class="filter_item">
    <table>
        <tr>
            <td><?php _e('Select Tab:','wp-support-plus-responsive-ticket-system');?></td>
            <td>
                <select id="active_tab" name="active_tab">
                    <option value="1"<?php echo ($advancedSettings['active_tab']==1)?'selected="selected"':'';?>><?php _e('Tickets','wp-support-plus-responsive-ticket-system');?></option>
                    <option value="2"<?php echo ($advancedSettings['active_tab']==2)?'selected="selected"':'';?>><?php _e('Create New Ticket','wp-support-plus-responsive-ticket-system');?></option>
                    <option value="3"<?php echo ($advancedSettings['active_tab']==3)?'selected="selected"':'';?>><?php _e('FAQs','wp-support-plus-responsive-ticket-system');?></option>                                        
                </select>                            
            </td>                        
        </tr>   
    </table>
</div>
</form>
<hr>

<br>
<span class="label label-info wpsp_title_label"><?php _e('Change Raised By','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e('This will enable','wp-support-plus-responsive-ticket-system');?> <b><?php _e('ability to modify raised by for tickets','wp-support-plus-responsive-ticket-system');?></b></small><br>
<table>
<?php 
foreach ($userRoles as $roleSlug=>$role){
    if($roleSlug =='wp_support_plus_agent' || $roleSlug =='wp_support_plus_supervisor'){
    ?>
    <tr>
        <td><input <?php echo (is_numeric(array_search($roleSlug,$advancedSettings['modify_raised_by'])))?'checked="checked"':'';?> type="checkbox" name="modifyRaisedBy[]" value="<?php echo $roleSlug;?>"/></td>
        <td><?php _e($role['name'],'wp-support-plus-responsive-ticket-system');?></td>
    </tr>
    <?php }
}
?>
</table>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Email Reply Above','wp-support-plus-responsive-ticket-system');?></span><br><br>
<input type="radio" name="reply_above" value="1" <?php echo ($advancedSettings['reply_above']==1)?'checked="checked"':'';?>><?php _e('Enable','wp-support-plus-responsive-ticket-system');?>
<br>
<input type="radio" name="reply_above" value="0" <?php echo ($advancedSettings['reply_above']==0)?'checked="checked"':'';?>><?php _e('Disable','wp-support-plus-responsive-ticket-system');?>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Redirect after ticket update','wp-support-plus-responsive-ticket-system');?></span><br><br>
  <small><code>*</code><?php _e('Applicable when you open ticket and perform any action to it','wp-support-plus-responsive-ticket-system');?></small><br>
  <input type="radio" name="wpsp_ticket_list" value="1" <?php echo ($advancedSettings['wpsp_redirect_after_ticket_update']==1)?'checked="checked"':'';?>><?php _e('Show Ticket List','wp-support-plus-responsive-ticket-system');?>
  <br>
  <input type="radio" name="wpsp_ticket_list" value="0" <?php echo ($advancedSettings['wpsp_redirect_after_ticket_update']==0)?'checked="checked"':'';?>><?php _e('Stay in ticket open window','wp-support-plus-responsive-ticket-system');?><br><br>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Attachment Settings','wp-support-plus-responsive-ticket-system');?></span><br><br>
<?php _e('Attachment maximum file size','wp-support-plus-responsive');?> <input type="text" id="wpspAttachMaxFileSize" value="<?php echo $advancedSettings['wpspAttachMaxFileSize'];?>"><?php _e('MB','wp-support-plus-responsive-ticket-system');?>
<br>
<input type="radio" name="wpsp_download_url" value="1" <?php echo ($advancedSettings['wpsp_attachment_download_url']==1)?'checked="checked"':'';?>><?php _e('Download Attachment','wp-support-plus-responsive');?>
<br>
<input type="radio" name="wpsp_download_url" value="0" <?php echo ($advancedSettings['wpsp_attachment_download_url']==0)?'checked="checked"':'';?>><?php _e('Try opening in browser with direct attachment url','wp-support-plus-responsive');?>
<hr>
<span class="label label-info wpsp_title_label"><?php _e('Attachment Color Setting','wp-support-plus-responsive');?></span><br><br> 
<table>     
    <tr>         
        <td>             
            <?php _e('Attachment Background Color : ','wp-support-plus-responsive');?>         
        </td>         
        <td>             
            <input type="text" id="wpspAttachment_bc" value="<?php _e($advancedSettings['wpspAttachment_bc'],'wp-support-plus-responsive');?>" class="wp-support-plus-color-picker" >         
        </td>     
    </tr>     
    <tr>        
        <td>         
            <?php _e('Attachment Progress Bar Color :  ','wp-support-plus-responsive');?>         
        </td>         
        <td>             
            <input type="text" id="wpspAttachment_pc" value="<?php _e($advancedSettings['wpspAttachment_pc'],'wp-support-plus-responsive');?>" class="wp-support-plus-color-picker" >         
        </td>     
    </tr> 
</table> 
<hr>
<br>
<span class="label label-info wpsp_title_label"><?php _e('Bootstrap Files','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e('Uncheck if there is Bootstrap files conflict on frontend','wp-support-plus-responsive-ticket-system');?></small><br>
<table>
<tr>
<td><input <?php echo $advancedSettings['wpspBootstrapCSSSetting']?'checked="checked"':'';?>type="checkbox" name="bootcss" value="<?php echo 'bootcss';?>"/></td>
<td><?php _e('Bootstrap CSS File','wp-support-plus-responsive-ticket-system');?></td>
</tr>
<tr>
    <td><input <?php echo $advancedSettings['wpspBootstrapJSSetting']?'checked="checked"':'';?>type="checkbox" name="bootjs" value="<?php echo 'bootjs';?>"/></td>
<td><?php _e('Bootstrap JS File','wp-support-plus-responsive-ticket-system');?></td>
</tr>    
</table>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Main Ticket Label','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e('In order to change Ticket Label, must translate below phrases','wp-support-plus-responsive-ticket-system');?></small><br><br>
<table id='wpspTBLTicketLblTranslate'>
    <tr>
        <th><?php _e('Phrase','wp-support-plus-responsive-ticket-system');?></th>
        <th><?php _e('Translation','wp-support-plus-responsive-ticket-system');?></th>
    </tr>
    <tr>
        <td>Ticket</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Tickets</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][2],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Create New Ticket</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][3],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Ticket List Settings</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][4],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Create Ticket As</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][5],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Make Ticket Public</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][6],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Submit Ticket</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][7],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Edit Ticket</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][8],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Reply Ticket</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][9],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Delete Ticket</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][10],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Ticket Type</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][11],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>No. of Tickets</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][12],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Ticket Creator</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][13],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Create Ticket Success Email</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][14],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Delete Ticket Notification Email</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][15],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>New Ticket From Thread</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][16],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Back to Tickets</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][17],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Backend Ticket List Fields</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][18],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>Frontend Ticket List Fields</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][19],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
    <tr>
        <td>No Tickets Found</td>
        <td><input type="text" name="wpspTicketAlice" value="<?php _e($advancedSettings['ticket_label_alice'][20],'wp-support-plus-responsive-ticket-system');?>"></td>
    </tr>
</table>
<hr>

<button class="btn btn-success" id="setAdvancedSubBtn" onclick="setAdvancedSettings();"><?php _e('Save Settings','wp-support-plus-responsive-ticket-system');?></button>
