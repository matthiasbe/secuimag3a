//jQuery(document).ready(function(){
//	
//});
//
//function checkSkypeOnlineAgentForChat(){
//	jQuery('#support_skype_chat_body #supportChatContainer').hide();
//	jQuery('#support_skype_chat_body .wait').show();
//	
//	var data = {
//		'action': 'getChatOnlineAgents'
//	};
//
//	jQuery.post(display_button_data.wpsp_ajax_url, data, function(response) {
//		jQuery('#support_skype_chat_body .wait').hide();
//		jQuery('#support_skype_chat_body #supportChatContainer').html(response);
//		jQuery('#support_skype_chat_body #supportChatContainer').show();
//	});
//}
//
//function checkSkypeOnlineAgentForCall(){
//	jQuery('#support_skype_call_body #supportCallContainer').hide();
//	jQuery('#support_skype_call_body .wait').show();
//	
//	var data = {
//		'action': 'getCallOnlineAgents'
//	};
//
//	jQuery.post(display_button_data.wpsp_ajax_url, data, function(response) {
//		jQuery('#support_skype_call_body .wait').hide();
//		jQuery('#support_skype_call_body #supportCallContainer').html(response);
//		jQuery('#support_skype_call_body #supportCallContainer').show();
//	});
//}