<?php
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
    $CKEditorSettings=array(
        'guestUserFront'=>$_POST['guestUserFront'],
        'loginUserFront'=>$_POST['loginUserFront']
    );
    update_option('wpsp_ckeditor_settings',$CKEditorSettings);
}
?>