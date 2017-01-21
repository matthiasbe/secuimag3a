<?php 
global $wpdb;
add_thickbox();
$sql="select * from {$wpdb->prefix}wpsp_panel_custom_menu";
$menus=$wpdb->get_results($sql);
?>

<div id="support_panel_title">
	<img class="support_panel_icon" src="<?php echo $UploadImageSettings['panel_image'];?>" >
	<div id="support_panel_title_text"><?php echo $generalSettings['support_title'];?></div>
	<img id="support_panel_close" src="<?php echo WCE_PLUGIN_URL.'asset/images/close.gif';?>" >
</div>
<?php if($generalSettings['support_phone_number']){?>
<div id="support_call_phone_number" class="front_support_menu">
	<img class="support_panel_icon" src="<?php echo WCE_PLUGIN_URL.'asset/images/call.png';?>" >
	<div class="support_panel_menu_text"><?php echo __($generalSettings['support_phone_number'],'wp-support-plus-responsive-ticket-system');?></div>
</div>
<?php }?>
<?php if($generalSettings['display_skype_chat']){?>
	<a href="#" onclick="getChatOnlineAgents()" <?php _e('Online Skype Chat Agents','wp-support-plus-responsive-ticket-system');?>>
                <div id="support_skype_chat" class="front_support_menu">
                           <img class="support_panel_icon" src="<?php echo WCE_PLUGIN_URL.'asset/images/Skype-icon.png';?>" >
                           <div class="support_panel_menu_text"><?php _e('Skype Chat','wp-support-plus-responsive-ticket-system');?></div>
                </div>
        </a>
	<div id="support_skype_chat_body" style="display:none;">
        
	        <div id="supportChatContainer"></div>
	        <div class="wait">
	        	<img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>">
	        </div>        
	</div>
<!--	<script type="text/javascript">
		checkSkypeOnlineAgentForChat();
	</script>-->
<?php }?>
<?php if($generalSettings['display_skype_call']){?>
	<a href="#" onclick="getCallOnlineAgents()"<?php _e('Online Skype Call Agents','wp-support-plus-responsive-ticket-system');?>>
		<div id="support_skype_call" class="front_support_menu">
				<img class="support_panel_icon" src="<?php echo WCE_PLUGIN_URL.'asset/images/skype_phone.png';?>" >
				<div class="support_panel_menu_text"><?php _e('Skype Call','wp-support-plus-responsive-ticket-system');?></div>
		</div>
	</a>
	<div id="wpsp_chat_popup_body" style="display:none;">		
	        <div id="supportCallContainer"></div>
	        <div class="wait">
	        	<img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>">
	        </div>
	</div>
<!--	<script type="text/javascript">
		checkSkypeOnlineAgentForCall();
	</script>-->
<?php }?>
<?php foreach ($menus as $menu){?>
	<a href="<?php echo $menu->redirect_url;?>" >
		<div class="front_support_menu">
			<img class="support_panel_icon" src="<?php echo $menu->menu_icon;?>" >
			<div class="support_panel_menu_text"><?php echo $menu->menu_text;?></div>
		</div>
	</a>
<?php }?>
<div id="support_page_redirect" class="front_support_menu">
	<img class="support_panel_icon" src="<?php echo WCE_PLUGIN_URL.'asset/images/support-icon.png';?>" >
	<div class="support_panel_menu_text"><?php _e('Support Ticket','wp-support-plus-responsive-ticket-system');?></div>
</div>
