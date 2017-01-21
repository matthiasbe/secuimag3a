<div class="wpspFilterFront">
	<button id="wpspBtnApplyFrontTicketFilter" class="btn btn-primary" onclick="wpsp_open_apply_filter();"><?php _e('Apply Filter', 'wp-support-plus-responsive-ticket-system');?></button>
	<button id="wpspBtnResetFrontTicketFilter" class="btn btn-primary" onclick="wpsp_reset_filter();"><?php _e('Reset Filter', 'wp-support-plus-responsive-ticket-system');?></button>
</div>
<div id="wpspBodyFrontTicketFilter" class="wpspActionFrontBody">
	<?php include( WCE_PLUGIN_DIR.'includes/admin/ticket_filter_front.php' );?>
</div>