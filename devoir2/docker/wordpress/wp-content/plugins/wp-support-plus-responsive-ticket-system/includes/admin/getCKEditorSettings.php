<?php
$CKEditorSettings=get_option( 'wpsp_ckeditor_settings' );

?>
<br>
<span class="label label-info wpsp_title_label"><?php _e('Enable CKEditor for','wp-support-plus-responsive-ticket-system');?></span><br><br>
<table>
    <tr>
        <td><input <?php echo ($CKEditorSettings['guestUserFront']=='1')?'checked="checked"':'';?> type="checkbox" id="ckeditor_enable_guest" value="1"/></td>
        <td><?php _e('Guest User','wp-support-plus-responsive-ticket-system');?></td>
    </tr>
    <tr>
        <td><input <?php echo ($CKEditorSettings['loginUserFront']=='1')?'checked="checked"':'';?> type="checkbox" id="ckeditor_enable_login_user" value="1"/></td>
        <td><?php _e('Login User','wp-support-plus-responsive-ticket-system');?></td>
    </tr>
</table>

<hr>
<button class="btn btn-success" id="setCKeditorSettingsBtn" onclick="setCKEditorSettings();"><?php _e('Save Settings','wp-support-plus-responsive-ticket-system');?></button>