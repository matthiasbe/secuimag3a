<?php 
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();
$generalSettings=get_option( 'wpsp_general_settings' );
$advancedSettings=get_option( 'wpsp_advanced_settings' );
$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];

$sql="select * 
FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
$ticket = $wpdb->get_row( $sql );

$roleManage=get_option( 'wpsp_role_management' );
$guestUser = new stdClass();
$guestUser->ID = 0;
$guestUser->display_name = "Guest";
$users=array_merge(array($guestUser),get_users(array('orderby'=>'display_name')));
$raised_by='';
    if($ticket->type=='user'){
            $user=get_userdata( $ticket->created_by );
            $raised_by=$user->display_name;
    }
    else{
            $raised_by=$ticket->guest_name;
    }
?>
<div id="wpsp_changeraisedby"> 
<h3><?php echo '['.__($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive')?> <?php echo $advancedSettings['wpsp_ticket_id_prefix'].$_POST['ticket_id'].'] '.stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES));?></h3><br>
<span class="label label-info wpsp_title_label"><?php _e('Raised By','wp-support-plus-responsive');?></span><br><br>

<select id="assignTicketRaisedById" onchange="wpsp_change_user();" name="assignTicketRaisedByIdFront">
       <option value="1"><?php _e('Registered','wp-support-plus-responsive');?></option>
       <option value="0"><?php _e('Guest','wp-support-plus-responsive');?></option>
</select><br><br>

<div id="user_type_user_front">
    <?php if($ticket->type=='user'){?>
    <input style="margin-top: 5px;" type="text" id="create_ticket_as_user" name="create_ticket_as_user" disabled="disabled" value="<?php echo $raised_by;?>" />
    <?php }else{?>
     <input style="margin-top: 5px;" type="text" id="create_ticket_as_user" name="create_ticket_as_user" disabled="disabled" value="<?php echo $current_user->display_name;?>" />
     <?php }?>
    <button type="button" class="btn btn-primary" id="wpsp_searchUser" onclick="getSearchUserForm();"><?php _e('Change User','wp-support-plus-responsive');?></button>
    <br>
</div>

<div id="user_type_guest_front" style="display:none">
    <span class="label label-info wpsp_title_label"><?php _e($default_labels['dn'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
    <input type="text" id="wpsp_guest_user_name" name="wpsp_guest_user_name" value="<?php echo $ticket->guest_name;?>"><br><br>
    <span class="label label-info wpsp_title_label"><?php _e($default_labels['de'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
    <input type="text" id="wpsp_guest_user_email" name="wpsp_guest_user_email" value="<?php echo $ticket->guest_email;?>"><br><br>
</div>
<div style="display: none">
    <input type="hidden" id="type_user_default" name="type" value="<?php echo $ticket->created_by;?>">
    <?php if($ticket->type=='user'){?>
    <input type="hidden" id="create_ticket_as_user_id" name="user_id" value="<?php echo $ticket->created_by;?>">
     <?php }else{?>
    <input type="hidden" id="create_ticket_as_user_id" name="user_id" value="<?php echo $current_user->ID;?>"><?php }?>
    <input type="hidden" id="guest_name" name="guest_name" value="">
    <input type="hidden" id="guest_email" name="guest_email" value="">
</div>
    <div id="wsp_change_user_modal" style="display:none">
        <h4 class="title" id="changeuserLabel"><?php _e('Select User','wp-support-plus-responsive');?></h4>
        <div id="wpspbody">
        <?php include( WCE_PLUGIN_DIR.'includes/admin/selectRegisteredUser.php' );?>
        </div>
    </div>
    <button class="btn btn-success changeTicketSubBtn" onclick="wpsp_close_popup(<?php echo $_POST['ticket_id']?>);"><?php _e('Cancel','wp-support-plus-responsive');?></button>
    <button class="btn btn-success changeTicketSubBtn" onclick="setRaisedByTicketUser(<?php echo $_POST['ticket_id'];?>);"><?php _e('Save Changes','wp-support-plus-responsive');?></button>

</div>

<script>
    var currentScreen='assign_agent';
    var currentTicketID=<?php echo $ticket->id?>;
    jQuery(document).ready(function(){
        if(jQuery("#type_user_default").val()=="0"){
                jQuery("select#assignTicketRaisedById").prop('selectedIndex', 1);
                jQuery('#user_type_user_front').hide();
 		jQuery('#user_type_guest_front').show();
                jQuery('#wpsp_guest_user_name').addClass('wpsp_required');
 		jQuery('#wpsp_guest_user_email').addClass('wpsp_required');
             }else{
                jQuery("select#assignTicketRaisedById").prop('selectedIndex', 0);
                jQuery('#user_type_user_front').show();
                jQuery('#user_type_guest_front').hide();
                jQuery('#wpsp_guest_user_name').removeClass('wpsp_required');
                jQuery('#wpsp_guest_user_email').removeClass('wpsp_required');
           }
        jQuery("#wpsp_searchUser").click(function(){
           jQuery("#wsp_change_user_modal").show();
    });
});
</script>