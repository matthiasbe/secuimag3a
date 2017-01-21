<?php
$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];
?>
<ul class="nav nav-tabs">
    <li class="active"><a href="#settingsAdvanced" id="tab_advanced_container" data-toggle="tab"><?php _e('Advanced Settings','wp-support-plus-responsive-ticket-system');?></a></li>
    <li><a href="#settingsCustomStatus" id="tab_custom_status_container" data-toggle="tab"><?php _e('Custom Status','wp-support-plus-responsive-ticket-system');?></a></li>
    <li><a href="#settingsCustomPriority" id="tab_custom_priority_container" data-toggle="tab"><?php _e('Custom Priority','wp-support-plus-responsive-ticket-system');?></a></li>
    <li><a href="#settingsFieldsReorder" id="tab_fields_reorder_container" data-toggle="tab"><?php _e('Re-Order Fields','wp-support-plus-responsive-ticket-system');?></a></li>
    <li><a href="#settingsTicketListFields" id="tab_ticket_list_container" data-toggle="tab"><?php _e($advancedSettings['ticket_label_alice'][4],'wp-support-plus-responsive-ticket-system');?></a></li>
    <li><a href="#settingsFrontEndDisplay" id="tab_front_end_display_container" data-toggle="tab"><?php _e('Front-End Display','wp-support-plus-responsive-ticket-system');?></a></li>
    <li><a href="#settingsCustomFilterFrontEnd" id="tab_custom_filter_container" data-toggle="tab"><?php _e('Custom Filter FrontEnd','wp-support-plus-responsive-ticket-system');?></a></li>
    <li><a href="#settingsCKEditor" id="tab_ckeditor_settings" data-toggle="tab"><?php _e('CKEditor','wp-support-plus-responsive-ticket-system');?></a></li>
    <li><a href="#settingsSupportButton" id="tab_support_btn_container" data-toggle="tab"><?php _e('Change Icons','wp-support-plus-responsive-ticket-system');?></a></li>
    <?php
    if(class_exists('WPSupportPlusWoocommerce')){
        include( WPSP_WOO_PLUGIN_DIR.'includes/admin/advanced_setting_header.php' );
    }
    if(class_exists('WPSupportPlusExportTicket')){
        include( WPSP_EXP_PLUGIN_DIR.'includes/admin/advanced_setting_header.php' );
    }
    ?>
</ul>
<!-- Tab panes -->
<div class="tab-content">
	<!-- Advanced Settings Start Here -->
	<div class="tab-pane active" id="settingsAdvanced">
		<div class="settingsAdvancedContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- Advanced Settings End Here -->
	<!-- Custom Status Start Here -->
	<div class="tab-pane" id="settingsCustomStatus">
		<div class="settingsCustomStatusContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- Custom Status Body End Here -->
	<!-- Fields reorder Start Here -->
	<div class="tab-pane" id="settingsFieldsReorder">
		<div class="settingsFieldsReorderContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- Fields reorder Body End Here -->
	<!-- Fields List Start Here -->
	<div class="tab-pane" id="settingsTicketListFields">
		<div class="settingsTicketListFieldsContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>       
	<!-- Fields List Body End Here -->
	<!-- Fields List Start Here -->
	<div class="tab-pane" id="settingsCustomFilterFrontEnd">
		<div class="settingsCustomFilterFrontEndContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
        
        <div class="tab-pane" id="settingsFrontEndDisplay">
		<div class="settingsFrontEndDisplayContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- Fields List Body End Here -->
	<!-- Custom Priority Start Here -->
	<div class="tab-pane" id="settingsCustomPriority">
		<div class="settingsCustomPriorityContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- Custom Priority Body End Here -->
       
	<div class="tab-pane" id="settingsCKEditor">
		<div class="settingsCKEditorContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	
        <div class="tab-pane" id="settingsSupportButton">
		<div class="settingsSupportButtonContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
        <?php
        if(class_exists('WPSupportPlusWoocommerce')){
            include( WPSP_WOO_PLUGIN_DIR.'includes/admin/advanced_setting_body.php' );
        }
        if(class_exists('WPSupportPlusExportTicket')){
            include( WPSP_EXP_PLUGIN_DIR.'includes/admin/advanced_setting_body.php' );
        }
        ?>
</div>
