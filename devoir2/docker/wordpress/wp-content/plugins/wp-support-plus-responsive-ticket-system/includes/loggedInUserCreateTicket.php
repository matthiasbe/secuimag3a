<?php 
global $current_user;
$current_user=wp_get_current_user();
$roleManage=get_option('wpsp_role_management');
$advancedSettings=get_option( 'wpsp_advanced_settings' );
$generalSettings=get_option( 'wpsp_general_settings' );
$allowed_roles=array_intersect($roleManage['front_ticket'],$current_user->roles);
if($roleManage['front_ticket_all'] || count($allowed_roles)>0){?>
    <div id="wpsp_user_welcome">
        <?php if($advancedSettings['logout_Settings']==1){
        echo(__("Welcome", 'wp-support-plus-responsive-ticket-system').' <b>' . $current_user->display_name .'</b>'.'.');
        ?>
        <?php wp_loginout($_SERVER['REQUEST_URI']);} ?>
    </div><br>
    <div class="tab-pane" id="create_ticket">
        <div id="create_ticket_container"></div>
        <div class="wait"><img alt="<?php echo __('Please Wait', 'wp-support-plus-responsive-ticket-system');?>" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
    </div>
    <script type="text/javascript">
        wpsp_getCreateTicketShortcode();
    </script>
<?php } ?>      