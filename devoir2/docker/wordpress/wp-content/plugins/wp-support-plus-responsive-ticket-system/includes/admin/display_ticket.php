<?php
$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];
?>
<!-- Nav tabs -->
<ul class="nav nav-tabs">
	<li class="active"><a href="#ticketContainer" id="tab_ticket_container" data-toggle="tab"><?php _e($advancedSettings['ticket_label_alice'][2],'wp-support-plus-responsive-ticket-system');?></a></li>
	<li><a href="#create_ticket" id="tab_create_ticket" data-toggle="tab"><?php _e($advancedSettings['ticket_label_alice'][3],'wp-support-plus-responsive-ticket-system');?></a></li>
	<li><a href="#agent_settings" id="tab_agent_settings" data-toggle="tab"><?php _e('Agent Settings','wp-support-plus-responsive-ticket-system');?></a></li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
	<!-- Tickets Tab Body Start Here -->
	<div class="tab-pane active" id="ticketContainer">
		<div id="ticketActionDashboard">
			<?php include( WCE_PLUGIN_DIR.'includes/admin/ticketActionDashboard.php' );?>
		</div>
		<div class="ticket_list"></div>
		<div class="ticket_indivisual"></div>
		<div class="ticket_assignment"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- Tickets Tab Body End Here -->
	<!-- Create New Ticket Tab Body Start Here -->
	<div class="tab-pane" id="create_ticket">
		<div id="create_ticket_container"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- Create New Ticket Tab Body End Here -->
	<!-- Agent Settings Tab Body Start Here -->
	<div class="tab-pane" id="agent_settings">
		<div id="agent_settings_area"></div>
		<div class="wait"><img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- Agent Settings Tab Body End Here -->
</div>
