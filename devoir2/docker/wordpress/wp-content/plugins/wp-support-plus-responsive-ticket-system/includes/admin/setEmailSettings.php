<?php
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
    $emailSettings=array(
        'default_from_email'=>$_POST['default_from_email'],
        'default_from_name'=>$_POST['default_from_name'],
        'default_reply_to'=>$_POST['default_reply_to'],
        'administrator_emails'=>$_POST['administrator_emails'],
        'enable_email_pipe'=> $_POST['enable_email_pipe'],
        'piping_type'=> $_POST['piping_type'],
        'ignore_emails'=>$_POST['ignore_emails']
    );
    update_option('wpsp_email_notification_settings',$emailSettings);
}
?>