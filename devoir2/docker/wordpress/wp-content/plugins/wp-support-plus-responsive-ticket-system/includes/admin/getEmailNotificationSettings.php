<?php 
global $wpdb;
$generalSettings=get_option( 'wpsp_general_settings' );
$emailSettings=get_option( 'wpsp_email_notification_settings' );

?>
<br>
<span class="label label-info wpsp_title_label"><?php _e('Mail Settings','wp-support-plus-responsive-ticket-system');?></span><br><br>
<table id="tblEmailFrom">
  <tr>
    <td><?php _e('From Email:','wp-support-plus-responsive-ticket-system');?></td>
    <td><input type="text" id="txtFromEmail" value="<?php echo $emailSettings['default_from_email'];?>" /></td>
  </tr>
  <tr>
    <td><?php _e('From Name:','wp-support-plus-responsive-ticket-system');?></td>
    <td><input type="text" id="txtFromName" value="<?php echo $emailSettings['default_from_name'];?>"/></td>
  </tr>
  <tr>
    <td><?php _e('Reply To:','wp-support-plus-responsive-ticket-system');?></td>
    <td><input type="text" id="wpsp_txtReplyTo" value="<?php echo $emailSettings['default_reply_to'];?>"/></td>
  </tr>
</table>

<hr>
<span class="label label-info wpsp_title_label"><?php _e('Administrator Notifications','wp-support-plus-responsive-ticket-system');?></span><br><br>
<table>
  <tr>
    <td><?php _e('Administrator Emails :','wp-support-plus-responsive-ticket-system');?></td>
    <td><textarea id="adminEmails" rows="3" cols="30"><?php echo $emailSettings['administrator_emails'];?></textarea></td>
  </tr>
</table>
<small><code>*</code><?php _e('Please add one email address per line. These email addresses will receive administrator email notifications','wp-support-plus-responsive-ticket-system');?></small><br><br>

<hr>
<span class="label label-info wpsp_title_label"><?php _e('Ignore Email Notifications','wp-support-plus-responsive-ticket-system');?></span><br><br>
<table>
    <small><code>*</code><?php _e('This will not send any email to given email addresses.','wp-support-plus-responsive-ticket-system');?></small><br><br>
    <tr>
      <td><?php _e('Ignore Emails :','wp-support-plus-responsive-ticket-system');?></td>
      <td><textarea id="ignoreEmails" rows="3" cols="30"><?php echo $emailSettings['ignore_emails'];?></textarea></td>
    </tr>
</table>
<small><code>*</code><?php _e('Please add one email address per line. These email addresses will not receive administrator email notifications','wp-support-plus-responsive-ticket-system');?></small><br><br>
<?php
do_action('wpsp_before_get_email_notification_settings_submit');
?>
<hr>
<button class="btn btn-success" onclick="setEmailSettings();"><?php _e('Save Settings','wp-support-plus-responsive-ticket-system');?></button>

