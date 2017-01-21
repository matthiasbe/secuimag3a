<?php
$emailSettings=get_option( 'wpsp_email_notification_settings' );
$arrayVisible=array(
    'ticketstatus'=>1,
    'assignagent'=>1,
    'deleteticket'=>1
);
$arrayVisible=apply_filters('wpsp_ticket_list_backend_visible_btns',$arrayVisible,$current_user);
?>
<div class="wpspFilterDashboard">
	<button id="wpspBtnApplyTicketFilter" class="btn btn-primary" onclick="wpsp_open_apply_filter();"><?php _e('Apply Filter', 'wp-support-plus-responsive-ticket-system');?></button>
	<button id="wpspBtnResetTicketFilter" class="btn btn-primary" onclick="wpsp_reset_filter();"><?php _e('Reset Filter', 'wp-support-plus-responsive-ticket-system');?></button>
	
        <?php if( $emailSettings['enable_email_pipe'] && $emailSettings['piping_type']=='imap' && $current_user->has_cap('manage_support_plus_agent') && class_exists('WPSupportPlusEmailPipe') ){ ?>
            <button id="wpspBtnSynkIMAP" class="btn btn-primary" onclick="wpsp_imap_loader();"><?php _e('Sync Emails', 'wp-support-plus-responsive-ticket-system');?></button>
        <?php }?>
        <?php if($arrayVisible['ticketstatus']){
        ?>
	<button id="wpspBtnChangeBulkStatus" class="btn btn-primary wpspBulkActionBtn" onclick="wpspOpenBulkChangeStatus();"><?php _e('Change Status', 'wp-support-plus-responsive-ticket-system');?></button>
        <?php
        }?>
        
	<?php if($current_user->has_cap('manage_support_plus_agent')){?>
        <?php
                if($arrayVisible['assignagent']){
                    ?>
                    <button id="wpspBtnAssignBulkTickets" class="btn btn-primary wpspBulkActionBtn" onclick="wpspOpenBulkAssignAgent();"><?php _e('Assign Agent', 'wp-support-plus-responsive-ticket-system');?></button>
                    <?php
                }
                if($arrayVisible['deleteticket']){
                    ?>
                    <button id="wpspBtnDeleteBulkTickets" class="btn btn-danger wpspBulkActionBtn" onclick="wpspBulkTicketDelete();"><?php _e('Delete', 'wp-support-plus-responsive-ticket-system');?></button>
                    <?php
                }
        ?>
	<?php }
	else if($arrayVisible['assignagent'] && !$current_user->has_cap('manage_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_assign_tickets']==1){?>
	<button id="wpspBtnAssignBulkTickets" class="btn btn-primary wpspBulkActionBtn" onclick="wpspOpenBulkAssignAgent();"><?php _e('Assign Agent', 'wp-support-plus-responsive-ticket-system');?></button>
	<?php
	}
	if($arrayVisible['deleteticket'] && !$current_user->has_cap('manage_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_delete_tickets']==1){?>
	<button id="wpspBtnDeleteBulkTickets" class="btn btn-danger wpspBulkActionBtn" onclick="wpspBulkTicketDelete();"><?php _e('Delete', 'wp-support-plus-responsive-ticket-system');?></button>
	<?php
	}?>
</div>
<div id="wpspBodyTicketFilter" class="wpspActionDashboardBody">
	<?php include( WCE_PLUGIN_DIR.'includes/admin/ticket_filter.php' );?>
</div>
<div id="wpspBodyChangeBulkStatus" class="wpspActionDashboardBody">
	<?php include( WCE_PLUGIN_DIR.'includes/admin/bulkChangeStatus.php' );?>
</div>
<?php if($current_user->has_cap('manage_support_plus_agent')){?>
<div id="wpspBodyAssignBulkTickets" class="wpspActionDashboardBody">
	<?php include( WCE_PLUGIN_DIR.'includes/admin/bulkAssignAgent.php' );?>
</div>
<?php }
else if(!$current_user->has_cap('manage_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_assign_tickets']==1){?>
<div id="wpspBodyAssignBulkTickets" class="wpspActionDashboardBody">
	<?php include( WCE_PLUGIN_DIR.'includes/admin/bulkAssignAgent.php' );?>
</div>
<?php
}?>