<?php
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
	/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
	 * Update 10 - disable front end new ticket submission
	 * add front_end_submission
	 */ 
	$generalSettings=array(
			'post_id'=>$_POST['post_id'],
			'enable_support_button'=>$_POST['enable_support_button'],
			'support_button_position'=>$_POST['support_button_position'],
			'enable_guest_ticket'=>$_POST['enable_guest_ticket'],
			'fbAppID'=>$_POST['fbAppID'],
			'fbAppSecret'=>$_POST['fbAppSecret'],
			'enable_slider_menu'=>$_POST['enable_slider_menu'],
			'support_title'=>$_POST['support_title'],
			'support_phone_number'=>$_POST['support_phone_number'],
			'display_skype_chat'=>$_POST['display_skype_chat'],
			'display_skype_call'=>$_POST['display_skype_call'],
			'default_ticket_category'=>$_POST['default_ticket_category'],
			'enable_default_login'=>$_POST['enable_default_login'],
			'enable_user_selection_public_private'=>$_POST['enable_user_selection_public_private'],
			'default_ticket_type'=>$_POST['default_ticket_type'],
			'allow_agents_to_assign_tickets'=>$_POST['allow_agents_to_assign_tickets'],
			'allow_agents_to_delete_tickets'=>$_POST['allow_agents_to_delete_tickets'],
			'allow_attachment_for_guest_ticket'=>$_POST['allow_attachment_guest_ticket'],
			'ticket_status_after_cust_reply'=>$_POST['ticket_status_after_cust_reply'],
			'google_nocaptcha_key'=>$_POST['google_nocaptcha_key'],
			'google_nocaptcha_secret'=>$_POST['google_nocaptcha_secret'],
			'front_end_submission' => $_POST['front_end_submission'],
			'default_new_ticket_status' => $_POST['default_new_ticket_status'],
                        'default_login_module'=>$_POST['default_login_module'],
                        'close_ticket_btn_status_val'=>$_POST['close_ticket_btn_status'],
                        'close_btn_alice'=>$_POST['close_btn_alice'],
                        'enable_register_guest_user'=>$_POST['enable_register_guest_user'],
                        'guest_user_role'=>$_POST['guest_user_role']
	);
	/* END CLOUGH I.T. SOLUTIONS MODIFICATION
	 */
	update_option('wpsp_general_settings',$generalSettings);
}
?>
