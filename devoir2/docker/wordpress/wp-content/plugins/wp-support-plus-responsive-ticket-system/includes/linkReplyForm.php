<?php
$wpsp_open_ticket_page_url=get_permalink(get_option( 'wpsp_ticket_open_page_shortcode' ));
$wpsp_user_name='';
$wpsp_user_email='';
if(is_user_logged_in()){
    global $current_user;
    $current_user=wp_get_current_user();
    $wpsp_user_name=$current_user->display_name;
    $wpsp_user_email=$current_user->user_email;
}
?>
<?php 
 $sql="select status FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$ticket_id;
 $ticket = $wpdb->get_row( $sql );
if($advancedSettings['hide_selected_status_ticket']!=$ticket->status){?>
<br>
<div id="wpsp_link_form">
    <div id="wpsp_link_form_body">
        <h3><?php _e('Reply Form','wp-support-plus-responsive-ticket-system');?></h3>
        <?php _e('Name','wp-support-plus-responsive-ticket-system');?>:<br>
        <input type="text" id="wpsp_link_form_name" <?php echo ($wpsp_user_name)?'disabled="disabled"':'';?> value="<?php echo $wpsp_user_name;?>"><br>
        <?php _e('Email Address','wp-support-plus-responsive-ticket-system');?>:<br>
        <input type="text" id="wpsp_link_form_email" <?php echo ($wpsp_user_email)?'disabled="disabled"':'';?> value="<?php echo $wpsp_user_email;?>"><br>
        <?php _e('Description','wp-support-plus-responsive-ticket-system');?>:<br>
        <textarea id="wpsp_link_form_desc"></textarea><br>
        <button id="wpsp_link_form_submit_btn" onclick="submitLinkForm();"><?php _e('Submit Reply','wp-support-plus-responsive-ticket-system');?></button>
    </div>
    <div id="wsp_wait">
	<img alt="<?php echo __('Please Wait...', 'wp-support-plus-responsive-ticket-system')?>" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>" />
    </div>
</div>
<?php }
else{
      echo stripslashes($advancedSettings['message_for_ticket_url']);
}?>
<script type="text/javascript">
    function submitLinkForm(){
        if(jQuery('#wpsp_link_form_name').val().trim()==''){
            alert('<?php _e('Please insert your name!','wp-support-plus-responsive-ticket-system');?>');
            return;
        }
        if(jQuery('#wpsp_link_form_email').val().trim()==''){
            alert('<?php _e('Please insert your email!','wp-support-plus-responsive-ticket-system');?>');
            return;
        }
        if(jQuery('#wpsp_link_form_desc').val().trim()==''){
            alert('<?php _e('Please insert description!','wp-support-plus-responsive-ticket-system');?>');
            return;
        }
        if(!wpsp_validateEmail(jQuery('#wpsp_link_form_email').val().trim())){
            alert('<?php _e('Please insert valid email!','wp-support-plus-responsive-ticket-system');?>');
            return;
        }
        
        jQuery('#wpsp_link_form_body').hide();
	jQuery('#wsp_wait').show();
	var data = {
		'action': 'wpspSubmitLinkForm',
                'wpsp_ticket_id':<?php echo $ticket_id;?>,
                'wpsp_name':jQuery('#wpsp_link_form_name').val().trim(),
                'wpsp_email':jQuery('#wpsp_link_form_email').val().trim(),
                'wpsp_desc':jQuery('#wpsp_link_form_desc').val().trim()
	};
        jQuery.post("<?php echo admin_url( 'admin-ajax.php' );?>", data, function(response) {
            window.location.href="<?php echo $wpsp_open_ticket_page_url;?>?ticket_id=<?php echo $_REQUEST['ticket_id'];?>";
	});
    }
    function wpsp_validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
</script>