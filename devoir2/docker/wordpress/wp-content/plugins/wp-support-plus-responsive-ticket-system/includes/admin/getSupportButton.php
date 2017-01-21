<?php 
header("Cache-Control: no-cache, must-revalidate");
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb,$wp_roles;
$userRoles=$wp_roles->roles;
$supportImageOption=get_option( 'wpsp_upload_image_settings' );
?><br>
<span class="label label-info wpsp_title_label"><?php _e('Support Button','wp-support-plus-responsive-ticket-system');?></span><br><br>
<form id="wpsp_upload_icons">
    <table id="wpsp_support_image">
        <tr>        
            <td><img  src="<?php echo $supportImageOption['leftSupportButton'];?>" style="width: 47px; height: 168px;" id="wpsp_left_img"></td>
            <td><input type="file" name="leftSupportBtn" id="wpsp_fileToUpload_first"/><i><?php _e('( Please select image of width:47px and height: 168px )','wp-support-plus-responsive-ticket-system');?></i></td>
        </tr>

        <tr>
            <td><img src="<?php echo $supportImageOption['rightSupportButton'];?>" style="width: 47px; height: 168px;"  id="wpsp_right_img"></td>
            <td><input type="file" name="rightSupportBtn" id="wpsp_fileToUpload_second"/><i><?php _e('( Please select image of width:47px and height: 168px )','wp-support-plus-responsive-ticket-system');?></i></td>
        </tr> 
   </table><br>
    <span class="label label-info wpsp_title_label"><?php _e('Support Panel Icon','wp-support-plus-responsive-ticket-system');?></span><br><br>
    <table id="wpsp_support_panel">
        <tr>
            <td><img src="<?php echo $supportImageOption['panel_image'];?>" style="width: 40px; height: 40px;"  id="wpsp_panel_img"></td>
            <td><input type="file" name="support_panel_icon" id="wpsp_fileToUpload_thried"/><i><?php _e('( Please select image of width:40px and height: 40px )','wp-support-plus-responsive-ticket-system');?></i></td>
        </tr> 
    </table>
    <button type="button" id="wpsp_upload_images" class="btn btn-success" onclick="wpsp_image_upload();"><?php _e('Upload Images','wp-support-plus-responsive-ticket-system');?></button>
</form>

