<?php 
global $current_user;
$current_user=wp_get_current_user();
$roleManage=get_option('wpsp_role_management');
$advancedSettings=get_option( 'wpsp_advanced_settings' );
$FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];

/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 10 - disable front end new ticket submission
 * get general settings
 */
$generalSettings=get_option( 'wpsp_general_settings' );
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
 */
$allowed_roles=array_intersect($roleManage['front_ticket'],$current_user->roles);
if($roleManage['front_ticket_all'] || count($allowed_roles)>0)
{
    if($advancedSettings['logout_Settings']==1){?>
        <div id="wpsp_user_welcome">
            <?php
            echo(__("Welcome", 'wp-support-plus-responsive-ticket-system').' <b>' . $current_user->display_name .'</b>'.'.');
            ?>
            <?php wp_loginout($_SERVER['REQUEST_URI']); ?>
        </div><br>
    <?php }?>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#" onclick="wpsp_getAllTickets();" id="tab_ticket_container" data-toggle="tab"><?php echo __($advancedSettings['ticket_label_alice'][2], 'wp-support-plus-responsive-ticket-system');?></a></li>
        <?php
        /* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
         * Update 10 - disable front end new ticket submission
         * show tab if not set to disable
         */
        if ( $generalSettings['front_end_submission'] == 0 ) {
            ?>
            <li><a href="#" onclick="wpsp_getCreateTicket();" id="tab_create_ticket" data-toggle="tab"><?php echo __($advancedSettings['ticket_label_alice'][3], 'wp-support-plus-responsive-ticket-system');?></a></li>
            <?php
        }
        /* END CLOUGH I.T. SOLUTIONS MODIFICATION
         */
        ?>
        <?php
        
        if ( $FrontEndDisplaySettings['wpsp_faq_display_setting']==1 ) {
            ?>
            <li><a href="#" onclick="wpsp_getFAQ();" id="tab_faq" data-toggle="tab"><?php echo __("FAQs", 'wp-support-plus-responsive-ticket-system');?></a></li>
            <?php
        }
        ?>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Tickets Tab Body Start Here -->
        <div class="tab-pane active" id="ticketContainer">
            <div id="ticketActionFront">
                    <?php include( WCE_PLUGIN_DIR.'includes/ticketActionFront.php' );?>
            </div>
            <div class="ticket_list"></div>
            <div class="ticket_indivisual"></div>
            <div class="ticket_assignment"></div>
            <div class="wait"><img alt="<?php echo __('Please Wait', 'wp-support-plus-responsive-ticket-system');?>" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
        </div>
        <!-- Tickets Tab Body End Here -->
        <!-- Create New Ticket Tab Body Start Here -->
        <div class="tab-pane" id="create_ticket">
            <div id="create_ticket_container"></div>
            <div class="wait"><img alt="<?php echo __('Please Wait', 'wp-support-plus-responsive-ticket-system');?>" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
        </div>
        <!-- Create New Ticket Tab Body End Here -->
        <!-- FAQ Tab Body Start Here -->
        <div class="tab-pane" id="FAQ_TAB">
            <div class="faq_filter">
                    <?php include( WCE_PLUGIN_DIR.'includes/admin/faq_filter_front.php' );?>
            </div>
            <div id="faq_container"></div>
            <div class="wait"><img alt="<?php echo __('Please Wait', 'wp-support-plus-responsive-ticket-system');?>" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
        </div>
        <!-- FAQ Tab Body End Here -->
    </div>
<?php }?>
