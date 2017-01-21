<?php
$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];
?>
<ul class="nav nav-tabs">
	<li class="active"><a href="#settingsAdvanced" id="tab_advanced_container" data-toggle="tab"><?php _e($advancedSettings['ticket_label_alice'][3],'wp-support-plus-responsive-ticket-system');?></a></li>
	<li><a href="#settingsCustomStatus" id="tab_custom_status_container" data-toggle="tab"><?php _e($advancedSettings['ticket_label_alice'][9],'wp-support-plus-responsive-ticket-system');_e($ticket_label,'wp-support-plus-responsive-ticket-system');?></a></li>
	<li><a href="#settingsCustomPriority" id="tab_custom_priority_container" data-toggle="tab"><?php _e('Change Status','wp-support-plus-responsive-ticket-system');?></a></li>
	<li><a href="#settingsFieldsReorder" id="tab_fields_reorder_container" data-toggle="tab"><?php _e('Assign Agent','wp-support-plus-responsive-ticket-system');?></a></li>
	<li><a href="#settingsTicketListFields" id="tab_ticket_list_container" data-toggle="tab"><?php _e($advancedSettings['ticket_label_alice'][10],'wp-support-plus-responsive-ticket-system');?></a></li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
	<!-- Create New Ticket Start Here -->
	<div class="tab-pane active" id="settingsAdvanced">
		<div class="settingsAdvancedContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- Create New Ticket End Here -->
	<!-- Reply Ticket Start Here -->
	<div class="tab-pane" id="settingsCustomStatus">
		<div class="settingsCustomStatusContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- Reply Ticket Body End Here -->
	<!-- Change Status Start Here -->
	<div class="tab-pane" id="settingsFieldsReorder">
		<div class="settingsFieldsReorderContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- Change Status Body End Here -->
	<!-- Assign Agent Start Here -->
	<div class="tab-pane" id="settingsTicketListFields">
		<div class="settingsTicketListFieldsContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- Assign Agent Body End Here -->
	<!-- Fields List Start Here -->
	<div class="tab-pane" id="settingsCustomPriority">
		<div class="settingsCustomFilterFrontEndContainer"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- Fields List Body End Here -->
</div>
