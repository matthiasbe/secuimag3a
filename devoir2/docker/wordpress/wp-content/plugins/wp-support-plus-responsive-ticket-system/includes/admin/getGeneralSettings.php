<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$generalSettings=get_option( 'wpsp_general_settings' );
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories" );
$pages=get_pages( array('post_type' => 'page','post_status' => 'publish') );
$posts=get_posts( array('post_type' => 'post','post_status' => 'publish') );
?>
<br>
<span class="label label-info wpsp_title_label"><?php _e('Support Page/Post','wp-support-plus-responsive-ticket-system');?></span><br>
<select id="setSupportPage">
	<option value="0" <?php echo ($generalSettings['post_id']==0)?'selected="selected"':'';?>><?php _e('Select Page/Post','wp-support-plus-responsive-ticket-system');?></option>
	<optgroup label="Page">
		<?php 
		foreach ($pages as $page){
			$selected=($generalSettings['post_id']==$page->ID)?'selected="selected"':'';
			echo '<option '.$selected.' value="'.$page->ID.'">'.$page->post_title.'</option>';
		}
		?>
	</optgroup>
	<optgroup label="Post">
		<?php 
		foreach ($posts as $post){
			$selected=($generalSettings['post_id']==$post->ID)?'selected="selected"':'';
			echo '<option '.$selected.' value="'.$post->ID.'">'.$post->post_title.'</option>';
		}
		?>
	</optgroup>
</select><br>
<small><code>*</code><?php _e('Use shortcode','wp-support-plus-responsive-ticket-system');?> <code>[wp_support_plus]</code> <?php _e('in selected page/post above.','wp-support-plus-responsive-ticket-system');?></small>
<hr>

<!---------------------------Code to add default category--------------------------------->
<span class="label label-info wpsp_title_label"><?php _e('Default Category','wp-support-plus-responsive-ticket-system');?></span><br/><br/>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst">
	<select id="default_ticket_category" name="default_ticket_category">
	<?php 
	foreach ($categories as $category){
		if($generalSettings['default_ticket_category']=="$category->id") $selected="selected";
		else $selected="";
		echo '<option value="'.$category->id.'" '.$selected.'>'.__($category->name,'wp-support-plus-responsive-ticket-system').'</option>';
	}
	?>
	</select>
    </td>
  </tr>
</table>
<small><code>*</code><?php _e('Selected Category will be default category for new tickets.','wp-support-plus-responsive-ticket-system');?></small><br>
<hr/>
<!----------------------------Code End------------------------------------------------------>

<!---------------------------Code to add default status--------------------------------->
<span class="label label-info wpsp_title_label"><?php _e('Default Status','wp-support-plus-responsive-ticket-system');?></span><br/><br/>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst">
	<select id="default_ticket_status" name="default_ticket_status">
	<?php 
	$sql_status="select * from {$wpdb->prefix}wpsp_custom_status";
	$custom_statusses=$wpdb->get_results($sql_status);
	$total_statusses=$wpdb->num_rows;
	if($total_statusses)
	{
		foreach($custom_statusses as $custom_status){?>
			<option value="<?php echo $custom_status->id?>" <?php echo ($generalSettings['default_new_ticket_status']==$custom_status->id)?'selected="selected"':'';?>><?php echo $custom_status->name;?></option>
		<?php
		}
	}
	?>
	</select>
    </td>
  </tr>
</table>
<small><code>*</code><?php _e('Selected Status will be assigned to new tickets.','wp-support-plus-responsive-ticket-system');?></small><br>
<hr/>
<!----------------------------Code End------------------------------------------------------>

<!---------------------------Code to add default ticket status after cust reply--------------------------------->
<span class="label label-info wpsp_title_label"><?php _e('Ticket Status After Customer Reply','wp-support-plus-responsive-ticket-system');?></span><br/><br/>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst">
	<select id="default_ticket_status_after_cust_reply" name="default_ticket_status_after_cust_reply">
            <option value="default">Default</option>
	<?php 
	$sql_status="select * from {$wpdb->prefix}wpsp_custom_status";
	$custom_statusses=$wpdb->get_results($sql_status);
	$total_statusses=$wpdb->num_rows;
	if($total_statusses)
	{
		foreach($custom_statusses as $custom_status){?>
			<option value="<?php echo $custom_status->name?>" <?php echo ($generalSettings['ticket_status_after_cust_reply']==$custom_status->name)?'selected="selected"':'';?>><?php echo $custom_status->name;?></option>
		<?php
		}
	}
	?>
	</select>
    </td>
  </tr>
</table>
<small><code>*</code><?php _e('Ticket status will be changed to selected status (other than default) after ticket creator reply back.','wp-support-plus-responsive-ticket-system');?></small><br>
<hr/>
<!----------------------------Code End------------------------------------------------------>
<!---------------------------Code for ticket type settings--------------------------------->

<span class="label label-info wpsp_title_label"><?php _e('Close Ticket','wp-support-plus-responsive-ticket-system');?></span><br><br>
<?php _e('Label','wp-support-plus-responsive-ticket-system');?>: <input type="text" id="wpsp_close_btn_alice" value="<?php echo $generalSettings['close_btn_alice'];?>"><br><br>
<?php _e('Select Status','wp-support-plus-responsive-ticket-system');?>:
<select id="close_btn_ticket_status" name="reply_ticket_status">
        <?php
        $sql_status="select * from {$wpdb->prefix}wpsp_custom_status";
        $custom_statusses=$wpdb->get_results($sql_status);
        $advancedSettingsStatusOrder=get_option( 'wpsp_advanced_settings_status_order' );
        if(isset($advancedSettingsStatusOrder['status_order'])){
            if(is_array($advancedSettingsStatusOrder['status_order'])){
                $custom_statusses=array();
                foreach($advancedSettingsStatusOrder['status_order'] as $status_id){
                    $sql="select * from {$wpdb->prefix}wpsp_custom_status WHERE id=".$status_id." "; 
                    $status_data=$wpdb->get_results($sql);
                    foreach($status_data as $status){
                        $custom_statusses=array_merge($custom_statusses,array($status));
                    }
                }
            }
        }
        ?>
        <option value="" <?php echo ($generalSettings['close_ticket_btn_status_val']=='')?'selected="selected"':'';?>></option>
        <?php
        foreach($custom_statusses as $custom_status){?>
            <option value="<?php echo strtolower($custom_status->name);?>" <?php echo ($generalSettings['close_ticket_btn_status_val']==strtolower($custom_status->name))?'selected="selected"':'';?>><?php _e(ucfirst($custom_status->name),'wp-support-plus-responsive-ticket-system');?></option>
        <?php
    }
        ?>
</select>
<hr/>

<span class="label label-info wpsp_title_label"><?php _e('Ticket Type Settings','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e('If enabled, users will be able to make a ticket public/private from front end.','wp-support-plus-responsive-ticket-system');?></small><br>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst"><input <?php echo ($generalSettings['enable_user_selection_public_private']==1)?'checked="checked"':'';?> type="checkbox" id="enableUserSelectionPublicPrivate" /></td>
    <td class="tblGeneralStingsTdSecond"><?php _e('Allow users to make a ticket public/private from front end.','wp-support-plus-responsive-ticket-system');?></td>
  </tr>
</table><br>
<small><code>*</code><?php _e('If users are allowed to make a ticket public/private this will be the selected type for ticket. If user is not allowed to select ticket type from front end then this will be the type of the ticket created from front end.','wp-support-plus-responsive-ticket-system');?></small><br>
<?php _e('Default Ticket Type','wp-support-plus-responsive-ticket-system');?>:
<select id="setDefaultTicketType">
	<option value="0" <?php echo ($generalSettings['default_ticket_type']=='0')?'selected="selected"':'';?>><?php _e('Private','wp-support-plus-responsive-ticket-system');?></option>
	<option value="1" <?php echo ($generalSettings['default_ticket_type']=='1')?'selected="selected"':'';?>><?php _e('Public','wp-support-plus-responsive-ticket-system');?></option>
</select><br>
<hr>
<!----------------------------Code End------------------------------------------------------>
<span class="label label-info wpsp_title_label"><?php _e('Support Button','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e('If enabled, button will be shown on all pages of front-end which redirect to support page/post selected above on click.','wp-support-plus-responsive-ticket-system');?></small><br>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst"><input <?php echo ($generalSettings['enable_support_button']==1)?'checked="checked"':'';?> type="checkbox" id="setEnableSupportBtn" /></td>
    <td class="tblGeneralStingsTdSecond"><?php _e('Enable Support Button','wp-support-plus-responsive-ticket-system');?></td>
  </tr>
</table><br>
<?php _e('Button Position','wp-support-plus-responsive-ticket-system');?>:<br>
<select id="setBtnPosition">
	<option value="top_left" <?php echo ($generalSettings['support_button_position']=='top_left')?'selected="selected"':'';?>><?php _e('Top Left','wp-support-plus-responsive-ticket-system');?></option>
	<option value="top_right" <?php echo ($generalSettings['support_button_position']=='top_right')?'selected="selected"':'';?>><?php _e('Top Right','wp-support-plus-responsive-ticket-system');?></option>
	<option value="bottom_left" <?php echo ($generalSettings['support_button_position']=='bottom_left')?'selected="selected"':'';?>><?php _e('Bottom Left','wp-support-plus-responsive-ticket-system');?></option>
	<option value="bottom_right" <?php echo ($generalSettings['support_button_position']=='bottom_right')?'selected="selected"':'';?>><?php _e('Bottom Right','wp-support-plus-responsive-ticket-system');?></option>
</select><br>
<hr>

<?php
/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 10 - disable front end new ticket submission
 * Prevents the submission of new tickets from the front end. This may be useful if you want tickets to only be raised via email piping
 */
?>
<span class="label label-info wpsp_title_label"><?php _e('Front End Ticket Submission','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e('If enabled, prevents the submission of new tickets from the front end by logged in users. This may be useful if you want tickets to only be raised via email piping','wp-support-plus-responsive-ticket-system');?></small><br>
<span class="label label-danger" style="font-size: 13px;">Warning!</span> This does not override the <strong>Guest Ticket</strong> settings below<br/>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst"><input <?php echo ($generalSettings['front_end_submission']==1)?'checked="checked"':'';?> type="checkbox" id="setFrontEndSubmission" /></td>
    <td class="tblGeneralStingsTdSecond"><?php _e('Prevent Front End Ticket Submission','wp-support-plus-responsive-ticket-system');?></td>
  </tr>
</table>
<hr />
<?php
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
 */
?>

<span class="label label-info wpsp_title_label"><?php _e('Guest Ticket','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e('If enabled, non logged-in user will able to raise ticket','wp-support-plus-responsive-ticket-system');?></small><br>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst"><input <?php echo ($generalSettings['enable_guest_ticket']==1)?'checked="checked"':'';?> type="checkbox" id="setEnableGuestTicket" /></td>
    <td class="tblGeneralStingsTdSecond"><?php _e('Enable Guest Tickets','wp-support-plus-responsive-ticket-system');?></td>
  </tr>
</table><br>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst"><input <?php echo ($generalSettings['enable_register_guest_user']==1)?'checked="checked"':'';?> type="checkbox" id="setEnableregisterguestuser" /></td>
    <td class="tblGeneralStingsTdSecond"><?php _e('Register Guest as User','wp-support-plus-responsive-ticket-system');?></td>
  </tr>
</table>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst"><?php _e('Role for new user','wp-support-plus-responsive-ticket-system');?>:</td>
    <td class="tblGeneralStingsTdSecond">
        <?php
        global $wp_roles;
        $userRoles=$wp_roles->roles;
        ?>
        <select id="setUserRole">
            <?php foreach ($userRoles as $roleSlug=>$role){?>
                        <option value="<?php echo $roleSlug;?>" <?php echo ($generalSettings['guest_user_role']==$roleSlug)?'selected="selected"':'';?>><?php echo $role['name'];?></option>
            <?php }?>
        </select>
    </td>
  </tr>
</table>
<hr>
<small><code>*</code><?php _e('If enabled, Guest user will be able to add attachment to a ticket. Note. This setting is applicable for front end tickets only','wp-support-plus-responsive-ticket-system');?></small><br>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst"><input <?php echo ($generalSettings['allow_attachment_for_guest_ticket']==1)?'checked="checked"':'';?> type="checkbox" id="setAllowAttachmentGuestTicket" /></td>
    <td class="tblGeneralStingsTdSecond"><?php _e('Allow attachments for guest tickets','wp-support-plus-responsive-ticket-system');?></td>
  </tr>
</table><br>
<!-- Code end -->
<small><code>*</code><?php _e('If not set bellow API key and secret, Google No CAPTCHA reCAPTCHA will not be loaded. This is applicable to guest ticket form only!','wp-support-plus-responsive-ticket-system');?><br><a href="https://www.google.com/recaptcha/admin"><?php _e('Click here','wp-support-plus-responsive-ticket-system');?></a> <?php _e('to get API key for your site.','wp-support-plus-responsive-ticket-system');?></small><br>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst"><?php _e('Google reCAPTCHA Site Key','wp-support-plus-responsive-ticket-system');?>:</td>
    <td class="tblGeneralStingsTdSecond"><input type="text" id="setGoogleCaptchaAPIKey" value="<?php echo $generalSettings['google_nocaptcha_key'];?>" /></td>
  </tr>
  <tr>
    <td class="tblGeneralStingsTdFirst"><?php _e('Google reCAPTCHA Secret key','wp-support-plus-responsive-ticket-system');?>:</td>
    <td class="tblGeneralStingsTdSecond"><input type="text" id="setGoogleCaptchaSecKey" value="<?php echo $generalSettings['google_nocaptcha_secret'];?>" /></td>
  </tr>
</table><br>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Default Login','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e('If enabled, it will display default login form link and default login form will be enabled','wp-support-plus-responsive-ticket-system');?></small><br>
<table>
    <tr>
      <td class="tblGeneralStingsTdFirst"><input <?php echo ($generalSettings['enable_default_login']==1)?'checked="checked"':'';?> type="checkbox" id="setEnableDefaultLogin" /></td>
      <td class="tblGeneralStingsTdSecond"><?php _e('Enable Default Login','wp-support-plus-responsive-ticket-system');?></td>
    </tr>
    <tr>
       <td class="tblGeneralStingsTdFirst">
          <input type="radio" name="wpsp_default_login_module" value="1"<?php echo ($generalSettings['default_login_module']==1)?'checked="checked"':''; ?>> 
       </td>
       <td class="tblGeneralStingsTdSecond">
           <?php _e('Default Login Module','wp-support-plus-responsive-ticket-system');?>
       </td>
    </tr>
    <tr>
       <td class="tblGeneralStingsTdFirst">
          <input type="radio" name="wpsp_default_login_module" value="0" <?php echo ($generalSettings['default_login_module']==0)?'checked="checked"':''; ?>>
       </td>
       <td class="tblGeneralStingsTdSecond">
           <?php _e('Wp Login Link','wp-support-plus-responsive-ticket-system');?>
       </td>
    </tr>
</table><br>
<hr>

<!---------------------------------Code for agent settings----------------------------------------->
<span class="label label-info wpsp_title_label"><?php _e('Agent Settings','wp-support-plus-responsive-ticket-system');?></span><br><br>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst"><input <?php echo ($generalSettings['allow_agents_to_assign_tickets']==1)?'checked="checked"':'';?> type="checkbox" id="setAgentTicketAssign" /></td>
    <td class="tblGeneralStingsTdSecond"><?php _e('Allow Agents to assign tickets','wp-support-plus-responsive-ticket-system');?></td>
  </tr>
</table><br>
<table>
  <tr>
    <td class="tblGeneralStingsTdFirst"><input <?php echo ($generalSettings['allow_agents_to_delete_tickets']==1)?'checked="checked"':'';?> type="checkbox" id="setAgentTicketDelete" /></td>
    <td class="tblGeneralStingsTdSecond"><?php _e('Allow Agents to delete tickets','wp-support-plus-responsive-ticket-system');?></td>
  </tr>
</table><br>

<hr>
<!-------------------------------Code for agent settings end---------------------------------------->

<span class="label label-info wpsp_title_label"><?php _e('Facebook App Details','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><a data-toggle="modal" data-target="#wpl_facebook_modal"><?php _e('Click Here','wp-support-plus-responsive-ticket-system');?></a> <?php _e('for help to create App','wp-support-plus-responsive-ticket-system');?></small><br>
<table>
  <tr>
    <td class="facebookAppTdFirst"><?php _e('App ID:','wp-support-plus-responsive-ticket-system');?></td>
    <td class="facebookAppTdFirst">
    	<input type="text" id="fbAppID" value="<?php echo $generalSettings['fbAppID'];?>">
    </td>
  </tr>
  <tr>
    <td class="facebookAppTdFirst"><?php _e('App Secret:','wp-support-plus-responsive-ticket-system');?></td>
    <td class="facebookAppTdFirst">
    	<input type="text" id="fbAppSecret" value="<?php echo $generalSettings['fbAppSecret'];?>">
    </td>
  </tr>
</table><br>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Front End Support Panel','wp-support-plus-responsive-ticket-system');?></span><br><br>
<table>
  <tr>
    <td class="facebookAppTdFirst"><?php _e('Enable:','wp-support-plus-responsive-ticket-system');?></td>
    <td class="facebookAppTdFirst">
    	<input type="radio" name="rdbEnableSliderMenu" value="1" <?php echo ($generalSettings['enable_slider_menu']==1)?'checked="checked"':''; ?>> <?php _e('Yes','wp-support-plus-responsive-ticket-system');?> 
    	<input type="radio" name="rdbEnableSliderMenu" class="wpspRdbSecond" value="0" <?php echo ($generalSettings['enable_slider_menu']==0)?'checked="checked"':''; ?>> <?php _e('No','wp-support-plus-responsive-ticket-system');?>
    </td>
  </tr>
  <tr>
    <td class="facebookAppTdFirst"><?php _e('Support Title:','wp-support-plus-responsive-ticket-system');?></td>
    <td class="facebookAppTdFirst">
    	<input type="text" id="txtSupportTitle" value="<?php echo $generalSettings['support_title'];?>">
    </td>
  </tr>
  <tr>
    <td class="facebookAppTdFirst"><?php _e('Phone Number:','wp-support-plus-responsive-ticket-system');?></td>
    <td class="facebookAppTdFirst">
    	<input type="text" id="txtPhoneNumber" value="<?php echo $generalSettings['support_phone_number'];?>">
    </td>
  </tr>
  <tr>
    <td class="facebookAppTdFirst"><?php _e('Display Skype Chat?:','wp-support-plus-responsive-ticket-system');?></td>
    <td class="facebookAppTdFirst">
    	<input type="radio" name="rdbAvailableChat" value="1" <?php echo ($generalSettings['display_skype_chat']==1)?'checked="checked"':''; ?>> <?php _e('Yes','wp-support-plus-responsive-ticket-system');?> 
    	<input type="radio" name="rdbAvailableChat" class="wpspRdbSecond" value="0" <?php echo ($generalSettings['display_skype_chat']==0)?'checked="checked"':''; ?>> <?php _e('No','wp-support-plus-responsive-ticket-system');?>
    </td>
  </tr>
  <tr>
    <td class="facebookAppTdFirst"><?php _e('Display Skype Call?:','wp-support-plus-responsive-ticket-system');?></td>
    <td class="facebookAppTdFirst">
    	<input type="radio" name="rdbAvailableCall" value="1" <?php echo ($generalSettings['display_skype_call']==1)?'checked="checked"':''; ?>> <?php _e('Yes','wp-support-plus-responsive-ticket-system');?> 
    	<input type="radio" name="rdbAvailableCall" class="wpspRdbSecond" value="0" <?php echo ($generalSettings['display_skype_call']==0)?'checked="checked"':''; ?>> <?php _e('No','wp-support-plus-responsive-ticket-system');?>
    </td>
  </tr>
</table><br>
<hr>

<button class="btn btn-success" id="setGeneralSubBtn" onclick="setGeneralSettings();"><?php _e('Save Settings','wp-support-plus-responsive-ticket-system');?></button>

<div class="modal fade" id="wpl_facebook_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php _e('Facebook App Help','wp-support-plus-responsive-ticket-system');?></h4>
      </div>
      <div class="modal-body">
        <ol>
			<li><a href="https://developers.facebook.com/apps" target="_blank"><?php _e('Create your application','wp-support-plus-responsive-ticket-system');?></a></li>
			<li><?php _e('Look for Site URL field in the Website tab and enter','wp-support-plus-responsive-ticket-system');?> <b><?php echo get_site_url();?></b></li>
			<li><?php _e('After this, go to the','wp-support-plus-responsive-ticket-system');?> <a href="https://developers.facebook.com/apps" target="_blank"><?php _e('Facebook Application List','wp-support-plus-responsive-ticket-system');?></a> <?php _e('page and select your newly created application','wp-support-plus-responsive-ticket-system');?></li>
			<li><?php _e('Go to','wp-support-plus-responsive-ticket-system');?> <b><?php _e('Settings','wp-support-plus-responsive-ticket-system');?></b> <?php _e('and enter','wp-support-plus-responsive-ticket-system');?> <b><?php _e('Contact Email','wp-support-plus-responsive-ticket-system');?></b></li>
			<li><?php _e('Go to','wp-support-plus-responsive-ticket-system');?> <b><?php _e('Settings','wp-support-plus-responsive-ticket-system');?></b> --> <b><?php _e('+Add Platform','wp-support-plus-responsive-ticket-system');?></b> <?php _e('select','wp-support-plus-responsive-ticket-system');?> <b><?php _e('Website','wp-support-plus-responsive-ticket-system');?></b> <?php _e('and enter','wp-support-plus-responsive-ticket-system');?> <b><?php echo get_site_url();?></b></li>
			<li><?php _e('Go to','wp-support-plus-responsive-ticket-system');?> <b><?php _e('Status and Review','wp-support-plus-responsive-ticket-system');?></b><?php _e(' and','wp-support-plus-responsive-ticket-system');?> <b><?php _e('ON','wp-support-plus-responsive-ticket-system');?></b> <?php _e('available to the general public','wp-support-plus-responsive-ticket-system');?></li>
			<li><?php _e('Go to','wp-support-plus-responsive-ticket-system');?> <b><?php _e('Dashboard','wp-support-plus-responsive-ticket-system');?></b><?php _e(' and Copy the values from these fields:','wp-support-plus-responsive-ticket-system');?> <b><?php _e('App ID/API key','wp-support-plus-responsive-ticket-system');?></b><?php _e(' and','wp-support-plus-responsive-ticket-system');?> <b><?php _e('Application Secret','wp-support-plus-responsive-ticket-system');?></b><?php _e(', and enter in <b>Facebook App Settings','wp-support-plus-responsive-ticket-system');?></b></li>
		</ol>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close','wp-support-plus-responsive-ticket-system');?></button>
      </div>
    </div>
  </div>
</div>
