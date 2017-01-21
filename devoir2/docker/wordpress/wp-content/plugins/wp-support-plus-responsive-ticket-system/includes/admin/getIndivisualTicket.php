<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpdb;
global $current_user;
$current_user = wp_get_current_user();
$generalSettings = get_option('wpsp_general_settings');
$advancedSettings = get_option('wpsp_advanced_settings');

$advancedSettingsFieldOrder = get_option('wpsp_advanced_settings_field_order');
$default_labels = $advancedSettingsFieldOrder['default_fields_label'];
$flag_backend_frontend=1;

if (!is_numeric($_POST['ticket_id']))
    die(); //sql injection

$sql = "select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=" . $_POST['ticket_id'];
$ticket = $wpdb->get_row($sql);

/*
 * Exit if someone try to hack
 */
$hackFlag=FALSE;
if($current_user->has_cap('manage_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket')){
    $hackFlag=FALSE;
}
else if(!$current_user->has_cap('manage_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket')){
    $assigned_to=array();
    if($ticket->assigned_to){
        $assigned_to=  explode(',', $ticket->assigned_to);
    }
    if(array_search($current_user->ID, $assigned_to) > -1){
        $hackFlag=FALSE;
    } else if($ticket->created_by==$current_user->ID){
        $hackFlag=FALSE;
    } else if(!$ticket->assigned_to){
        $hackFlag=FALSE;
    } else if($ticket->ticket_type==1){        
        $hackFlag=FALSE;
    } else {
        $hackFlag=true;
    }
}
$hackFlag=apply_filters('wpsp_allow_agent_to_view_other_agent_ticket_backend',$hackFlag,$ticket,$current_user);
if($hackFlag){
    die(__('Sorry! You do not have permission to view this ticket.','wp-support-plus-responsive-ticket-system'));
}

if(apply_filters('wpsp_check_current_ticket_in_list',false,$ticket,$current_user)){
     die(__('Sorry! You do not have permission to view this ticket.','wp-support-plus-responsive'));
}

$sql = "select *,
		TIMESTAMPDIFF(MONTH,create_time,UTC_TIMESTAMP()) as date_modified_month,
		TIMESTAMPDIFF(DAY,create_time,UTC_TIMESTAMP()) as date_modified_day,
		TIMESTAMPDIFF(HOUR,create_time,UTC_TIMESTAMP()) as date_modified_hour,
 		TIMESTAMPDIFF(MINUTE,create_time,UTC_TIMESTAMP()) as date_modified_min,
 		TIMESTAMPDIFF(SECOND,create_time,UTC_TIMESTAMP()) as date_modified_sec,
		is_note as note 
		FROM {$wpdb->prefix}wpsp_ticket_thread WHERE ticket_id=" . $_POST['ticket_id'] . ' ORDER BY create_time ';
if ($advancedSettings['wpsp_reply_form_position'] == 0) {
    $sql.='ASC';
} else {
    $sql.='DESC';
}
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
 */
$threads = $wpdb->get_results($sql);
$categories = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpsp_catagories");
$priorities = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpsp_custom_priority");
$advancedSettingsPriorityOrder = get_option('wpsp_advanced_settings_priority_order');
if (isset($advancedSettingsPriorityOrder['priority_order'])) {
    if (is_array($advancedSettingsPriorityOrder['priority_order'])) {
        $priorities = array();
        foreach ($advancedSettingsPriorityOrder['priority_order'] as $priority_id) {
            $sql = "select * from {$wpdb->prefix}wpsp_custom_priority WHERE id=" . $priority_id . " ";
            $priority_data = $wpdb->get_results($sql);
            foreach ($priority_data as $priority) {
                $priorities = array_merge($priorities, array($priority));
            }
        }
    }
}

$advancedSettingsFieldOrder = get_option('wpsp_advanced_settings_field_order');

$customFields = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpsp_custom_fields");
$total_cust_field = $wpdb->num_rows;
?>

<?php
$arrayVisible=array(
    'back'=>1,
    'ticketstatus'=>1,
    'canned'=>1,
    'assignagent'=>1,
    'closeticket'=>1,
    'moreaction'=>1,
    'clone'=>1,
    'raisedby'=>1,
    'deleteticket'=>1
);

 $isVisible=apply_filters('wpsp_showhide_indivisual_ticket_buttons',true,$ticket,$current_user);
 $arrayVisible=apply_filters('wpsp_indivisual_ticket_backend_visible_btns',$arrayVisible,$ticket,$current_user);
?>
<?php
if($arrayVisible['back']){?>
<button class="btn btn-primary wpsp_ticket_nav_btn" onclick="backToTicketFromIndisual();"><?php _e($advancedSettings['ticket_label_alice'][17], 'wp-support-plus-responsive-ticket-system'); ?></button>
<?php
}?>
<?php if($isVisible && $arrayVisible['ticketstatus']){
?>
<button class="btn btn-primary wpsp_ticket_nav_btn" id="btnChangeTicketStatus" onclick="getChangeTicketStatus(<?php echo $_POST['ticket_id']; ?>);"><?php _e('Change Status', 'wp-support-plus-responsive-ticket-system'); ?></button>
<?php
}?>
<?php if($isVisible && $arrayVisible['canned']){
?>
<button class="btn btn-primary wpsp_ticket_nav_btn" id="psmwpsp_canned" onclick="cannedrep();"><?php _e('Canned Reply', 'wp-support-plus-responsive-ticket-system'); ?></button>
<?php
}?>
<?php
     if ($current_user->has_cap('manage_support_plus_agent') && $arrayVisible['assignagent']) { 
     ?>
         <button class="btn btn-primary wpsp_ticket_nav_btn"onclick="assignAgent(<?php echo $_POST['ticket_id']; ?>);"><?php _e('Assign Agent', 'wp-support-plus-responsive-ticket-system'); ?></button>
     <?php
     }
     else if(!$current_user->has_cap('manage_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_assign_tickets'] == 1 && $arrayVisible['assignagent']){
     ?>
        <button class="btn btn-primary wpsp_ticket_nav_btn"onclick="assignAgent(<?php echo $_POST['ticket_id']; ?>);"><?php _e('Assign Agent', 'wp-support-plus-responsive-ticket-system'); ?></button>
     <?php    
     }
?>

<?php if ($isVisible && $arrayVisible['closeticket'] && !$generalSettings['close_ticket_btn_status_val'] == '' && $generalSettings['close_ticket_btn_status_val'] != $ticket->status) { ?>
    <button id="wpsp_close_btn_action" class="btn btn-primary wpsp_ticket_nav_btn" onclick="wpsp_closeTicketStatus(<?php echo $_POST['ticket_id']; ?>, '<?php echo $generalSettings['close_ticket_btn_status_val']; ?>');" > <?php echo $generalSettings['close_btn_alice']; ?></button>
<?php } ?>
<?php   do_action('wpsp_ticket_action_before_more_btn_dashboard',$ticket);?>
<?php if($isVisible && $arrayVisible['moreaction']){ ?>
<button id="wpsp_slide_demo" class="btn btn-primary wpsp_ticket_nav_btn"><?php _e('+More Actions', 'wp-support-plus-responsive-ticket-system'); ?></button>
<?php 
}?>
<div id="wpsp_show_more">
    <?php if($isVisible && $arrayVisible['clone']){
    ?>
    <button class="btn btn-primary wpsp_ticket_nav_btn" id="clone" onclick="cloneTicket(<?php echo $_POST['ticket_id']; ?>);"><?php _e('Clone Ticket', 'wp-support-plus-responsive-ticket-system'); ?></button>
    <?php
    }?> 
<?php
if (current_user_can('manage_options') && $arrayVisible['raisedby']) {
    ?><button class="btn btn-primary wpsp_ticket_nav_btn" onclick="getRaisedByTicketUser(<?php echo $_POST['ticket_id']; ?>);"><?php _e('Change Raised By', 'wp-support-plus-responsive-ticket-system'); ?></button>&nbsp;<?php
    } else {
        foreach ($advancedSettings['modify_raised_by'] as $modifyRaisedBy) {
            if ((($modifyRaisedBy == 'wp_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket')) || (($modifyRaisedBy == 'wp_support_plus_supervisor') && $current_user->has_cap('manage_support_plus_agent')) && $arrayVisible['raisedby']) {
                ?><button class="btn btn-primary wpsp_ticket_nav_btn" onclick="getRaisedByTicketUser(<?php echo $_POST['ticket_id']; ?>);"><?php _e('Change Raised By', 'wp-support-plus-responsive-ticket-system'); ?></button>&nbsp;<?php
            }
        }
    }
    ?>                            
    <?php if ($current_user->has_cap('manage_support_plus_agent') && $arrayVisible['deleteticket']) { ?>
        <button class="btn btn-danger wpsp_ticket_nav_btn" onclick="deleteTicket(<?php echo $_POST['ticket_id']; ?>);"><?php _e($advancedSettings['ticket_label_alice'][10], 'wp-support-plus-responsive-ticket-system'); ?></button>
    <?php }
    if (!$current_user->has_cap('manage_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_delete_tickets'] == 1 && $arrayVisible['deleteticket']) {
        ?>
        <button class="btn btn-danger wpsp_ticket_nav_btn" onclick="deleteTicket(<?php echo $_POST['ticket_id']; ?>);"><?php _e($advancedSettings['ticket_label_alice'][10], 'wp-support-plus-responsive-ticket-system'); ?></button>
    <?php }
    ?>        
</div>
<br>
<?php 
 do_action('wpsp_open_ticket_backend_before_subject_header',$ticket);
 
 $editCustomField=apply_filters('wpsp_enable_disable_edit_custom_fields',true,$ticket,$current_user);
?>
<h3>
    <?php echo '[' . __($advancedSettings['ticket_label_alice'][1], 'wp-support-plus-responsive-ticket-system') ?> <?php echo $advancedSettings['wpsp_ticket_id_prefix'] . $_POST['ticket_id'] . '] ' . stripcslashes(htmlspecialchars_decode($ticket->subject, ENT_QUOTES)); 
          echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
    ?>
</h3>
<!-- Custom Field -->
<?php if ($total_cust_field) { ?>
    <div class="threadContainer">
    <?php
    foreach ($customFields as $field) {
        if (in_array($field->id, $advancedSettingsFieldOrder['display_fields'])) {
            $fieldValue = $wpdb->get_var("select cust" . $field->id . " from {$wpdb->prefix}wpsp_ticket WHERE id=" . $_POST['ticket_id']);
            if ($fieldValue) {
                switch ($field->field_type) {
                    case '1':
                        echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($fieldValue, ENT_QUOTES));
                        echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
                        break;
                    case '2':
                        if ($field->field_options != NULL) {
                            $field_options = unserialize($field->field_options);
                            if (isset($field_options[$fieldValue])) {
                                echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($field_options[$fieldValue], ENT_QUOTES));
                            } else {
                                echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($fieldValue, ENT_QUOTES));
                            }
                            echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
                        }
                        break;
                    case '3':
                        if ($field->field_options != NULL) {
                            $field_options = unserialize($field->field_options);
                            if (isset($field_options[$fieldValue])) {
                                echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($field_options[$fieldValue], ENT_QUOTES));
                            } else {
                                echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($fieldValue, ENT_QUOTES));
                            }
                            echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
                        }
                        break;
                    case '4':
                        if ($field->field_options != NULL) {
                            $field_options = unserialize($field->field_options);
                            if (isset($field_options[$fieldValue])) {
                                echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($field_options[$fieldValue], ENT_QUOTES));
                            } else {
                                echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($fieldValue, ENT_QUOTES));
                            }
                            echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
                        }
                        break;
                    case '5':
                        echo "<b>" . $field->label . ":</b> <br>" . nl2br($fieldValue);
                        echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
                        break;
                    case '6':
                        echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($fieldValue, ENT_QUOTES));
                        echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
                        break;
                    case '7': if(class_exists( 'WooCommerce' ) && class_exists( 'WPSupportPlusWoocommerce' )) {
                            $pf = new WC_Product_Factory();
                            $product = $pf->get_product($fieldValue);            
                            $prod_url = get_permalink( $product->id );
                            $prod_title=$product->post->post_title;
                            echo "<b>".$field->label.":</b> ".'<a href="'.$prod_url.'" target="__blank">'.$prod_title.'</a>';
                            echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
                        }
                       break;
                    default:
                        do_action('wpsp_display_extra_custom_fields_backend',$ticket,$field,$fieldValue);
                }
            }
        }
        else if($field->isVarFeild){
            $fieldValue=$wpdb->get_var("select cust".$field->id." from {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id']);
            if($fieldValue){
                switch($field->field_type)
                {
                        case '1':
                                echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES));
                                echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
                        break;
                        case '2':
                                if($field->field_options!=NULL)
                                {
                                        $field_options=unserialize($field->field_options);
                                        if(isset($field_options[$fieldValue]))
                                        {
                                                echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($field_options[$fieldValue],ENT_QUOTES));
                                        }
                                        else
                                        {
                                                echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES));
                                        }
                                        echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
                                }
                        break;
                        case '3':
                                if($field->field_options!=NULL)
                                {
                                        $field_options=unserialize($field->field_options);
                                        if(isset($field_options[$fieldValue]))
                                        {
                                                echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($field_options[$fieldValue],ENT_QUOTES));
                                        }
                                        else
                                        {
                                                echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES));
                                        }
                                        echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
                                }
                        break;
                        case '4':
                                if($field->field_options!=NULL)
                                {
                                        $field_options=unserialize($field->field_options);
                                        if(isset($field_options[$fieldValue]))
                                        {
                                                echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($field_options[$fieldValue],ENT_QUOTES));
                                        }
                                        else
                                        {
                                                echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES));
                                        }
                                        echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
                                }
                        break;
                        case '5':
                            echo "<b>".$field->label.":</b> <br>".nl2br($fieldValue);
                            echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
                        break;
                        case '6':
                            echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES));
                            echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" /><br>':'<br>';
                        break;
                        default: do_action('wpsp_display_extra_custom_fields_backend_variablefields',$ticket,$field,$fieldValue);
                }
            }
        }
    }
    ?>
    </div>
    <?php } ?>
<!-- End Of Custom Field -->
<!-- Extension integration start -->
    <?php
    if ($ticket->extension_meta) {
        ?>
    <div class="threadContainer">
        <?php
        $extension_meta = explode(',', $ticket->extension_meta);
        if ($extension_meta[0] == 1) { //woocommerce
            if (class_exists( 'WooCommerce' ) && class_exists( 'WPSupportPlusWoocommerce' ) && $extension_meta[1] == 1) { //woocommerce
                $pf = new WC_Product_Factory();
                $product = $pf->get_product($extension_meta[2]);
                $prod_url = get_permalink($product->id);
                $prod_title = $product->post->post_title;
                echo "<b>" . __('Product', 'wp-support-plus-responsive-ticket-system') . ":</b> " . '<a href="' . $prod_url . '" target="__blank">' . $prod_title . '</a>' . "<br>";
            } else if ($extension_meta[1] == 2) { //order
                $order = new WC_Order($extension_meta[2]);
                $order_title = __('Order', 'wp-support-plus-responsive-ticket-system') . ' #' . $extension_meta[2];
                echo "<b>" . __('Order', 'wp-support-plus-responsive-ticket-system') . ":</b> " . '<a href="' . admin_url('post.php') . '?post=' . $extension_meta[2] . '&action=edit" target="__blank">' . $order_title . "</a><br>";
                ?>
                <table class="wpsp_order_display_open_ticket">
                    <tr>
                        <th class="wpsp_order_tbl_col1"><?php echo _e('Product', 'wp-support-plus-responsive-ticket-system'); ?></th>
                        <th class="wpsp_order_tbl_col2"><?php echo _e('Total', 'wp-support-plus-responsive-ticket-system'); ?></th>
                    </tr>
                <?php
                foreach ($order->get_items() as $key => $lineItem) {
                    $pf = new WC_Product_Factory();
                    $product = $pf->get_product($lineItem['product_id']);
                    $prod_url = get_permalink($product->id);
                    $prod_title = $product->post->post_title;
                    ?>
                        <tr>
                            <td class="wpsp_order_tbl_col1"><a href="<?php echo $prod_url; ?>" target="__blank"><?php echo $prod_title; ?></a> x <?php echo $lineItem['qty']; ?></td>
                            <td class="wpsp_order_tbl_col2"><?php echo get_woocommerce_currency_symbol($order->order_currency) . $lineItem['line_subtotal']; ?></td>
                        </tr>
                <?php
            }
            ?>
                    <tr>
                        <td class="wpsp_order_tbl_col1"><b><?php _e('Subtotal', 'wp-support-plus-responsive-ticket-system'); ?></b></td>
                        <td class="wpsp_order_tbl_col2"><b><?php echo get_woocommerce_currency_symbol($order->order_currency) . $order->get_subtotal(); ?></b></td>
                    </tr>
                    <tr>
                        <td class="wpsp_order_tbl_col1"><b><?php _e('Discount', 'wp-support-plus-responsive-ticket-system'); ?></b></td>
                        <td class="wpsp_order_tbl_col2"><b>-<?php echo $order->get_discount_to_display(); ?></b></td>
                    </tr>
                    <tr>
                        <td class="wpsp_order_tbl_col1"><b><?php _e('Payment Method', 'wp-support-plus-responsive-ticket-system'); ?></b></td>
                        <td class="wpsp_order_tbl_col2"><b><?php echo $order->payment_method; ?></b></td>
                    </tr>
                    <tr>
                        <td class="wpsp_order_tbl_col1"><b><?php _e('Total', 'wp-support-plus-responsive-ticket-system'); ?></b></td>
                        <td class="wpsp_order_tbl_col2"><b><?php echo get_woocommerce_currency_symbol($order->order_currency) . $order->order_total; ?></b></td>
                    </tr>
                </table>
            <?php
        }
    }
    
    do_action('wpsp_ext_meta_display',$ticket);
    
    ?>
    </div>
    <?php
}

do_action('wpsp_after_custom_field_open_ticket_backend',$ticket);
?>
<!-- Extension integration end -->
<?php
if ($advancedSettings['wpsp_reply_form_position'] == 1 && apply_filters('wpsp_show_reply_form',true,$ticket)) {
    include( WCE_PLUGIN_DIR . 'includes/admin/replyFormPosition.php' );
}
?>

    <?php
    if ($advancedSettings['enable_accordion']) {
        ?>
    <div id="threadAccordion" class="wpSupportPlus">
        <?php
    }
    ?>
<?php foreach ($threads as $thread) { ?>
    <?php
    /* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
     * Update 18 - Thread accordion
     * jQuery accordion for threads
     */
    /* <div class="threadContainer"> */
    /* END CLOUGH I.T. SOLUTIONS MODIFICATION
     */
    if(apply_filters('wpsp_thread_filter_backend',false,$ticket,$thread,$current_user)){
         continue;
    }
    
    if ($thread->note == 0 || ($thread->note == 1 && $current_user->has_cap('manage_support_plus_ticket')) || ($thread->note == 2 && $current_user->has_cap('manage_support_plus_ticket')) ||($thread->note == 3 && $current_user->has_cap('manage_support_plus_ticket'))||($thread->note == 4 && $current_user->has_cap('manage_support_plus_ticket'))||($thread->note == 5 && $current_user->has_cap('manage_support_plus_ticket'))|| ($thread->note==6 && $current_user->has_cap('manage_support_plus_ticket'))) {
        ?>
            <?php
            $user_name = '';
            $user_email = '';
            $signature = '';
            if ($thread->created_by) {
                $user = get_userdata($thread->created_by);
                $user_name = $user->display_name;
                $user_email = $user->user_email;

                $userSignature = $wpdb->get_row("select signature FROM {$wpdb->prefix}wpsp_agent_settings WHERE agent_id=" . $thread->created_by);
                if ($wpdb->num_rows) {
                    $signature = '<br>---<br>' . stripcslashes(htmlspecialchars_decode($userSignature->signature, ENT_QUOTES));
                }
            } else {
                $user_name = $thread->guest_name;
                $user_email = $thread->guest_email;
            }
            $modified = '';
            if ($thread->date_modified_month)
                $modified = $thread->date_modified_month . ' ' . __('months ago', 'wp-support-plus-responsive');
            else if ($thread->date_modified_day)
                $modified = $thread->date_modified_day . ' ' . __('days ago', 'wp-support-plus-responsive');
            else if ($thread->date_modified_hour)
                $modified = $thread->date_modified_hour . ' ' . __('hours ago', 'wp-support-plus-responsive');
            else if ($thread->date_modified_min)
                $modified = $thread->date_modified_min . ' ' . __('minutes ago', 'wp-support-plus-responsive');
            else
                $modified = $thread->date_modified_sec . ' ' . __('seconds ago', 'wp-support-plus-responsive');
            /* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
             * Update 4 - Display thread date time
             * convert create_time to local time from gmt and add to $modified
             */
            $modified .= ' (' . date_i18n( get_option( 'date_format' ), strtotime( get_date_from_gmt( $thread->create_time, 'Y-m-d H:i:s') ) ) . ' ' . get_date_from_gmt( $thread->create_time, 'H:i:s') . ')';
            $log_modifier=' (' . date_i18n( get_option( 'date_format' ), strtotime( get_date_from_gmt( $thread->create_time, 'Y-m-d H:i:s') ) ) . ' ' . get_date_from_gmt( $thread->create_time, 'H:i:s') . ')';
            /* END CLOUGH I.T. SOLUTIONS MODIFICATION
             */
            $attachments = array();
            if ($thread->attachment_ids) {
                $attachments = explode(',', $thread->attachment_ids);
            }

            $body = stripcslashes(htmlspecialchars_decode($thread->body, ENT_QUOTES));
            if ($thread->note == 0) {
                $body.=$signature;
            }
            ?>
            <?php
            /* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
             * Update 18 - Thread accordion
             * jQuery accordion for threads
             */
            ?>
            <?php
            if ($thread->note == 2 && $current_user->has_cap('manage_support_plus_ticket')) {
                $assigned_to = $body;
                $assign_to_str='None';
                if($body){
                    $assign_to_users=  explode(',', $body);
                    $assign_to_str=array();
                    foreach ($assign_to_users as $assign_user_id){
                        $user = get_userdata($assign_user_id);
                        $assign_to_str[]=$user->display_name;
                    }
                    $assign_to_str=  implode(',', $assign_to_str);
                }
                ?>
                <h3 class="unclickableAcc"><strong><?php echo $user_name.' '.__('assigned to','wp-support-plus-responsive').' '. $assign_to_str.' '.$log_modifier;?></strong></h3>
                <div class="threadContainer unclickableAccBody"><?php echo __('assigned to','wp-support-plus-responsive').' '.$assign_to_str;?></div>
                <?php } 
                
            else if ($thread->note == 3 && $current_user->has_cap('manage_support_plus_ticket')) {
                $thread->body;
                $user = get_userdata($thread->created_by);
                $user_name = $user->display_name;
                ?>
                <h3 class="unclickableAcc"><strong><?php echo $user_name ?> <?php echo __('changed status to','wp-support-plus-responsive').' '. $thread->body.' '.$log_modifier; ?></strong></h3>
                <div class="threadContainer unclickableAccBody"><?php echo $user_name?> <?php echo __('changed status to','wp-support-plus-responsive').' '. $thread->body; ?></div>
                <?php }  
            else if ($thread->note == 4 && $current_user->has_cap('manage_support_plus_ticket')) {
                $cat_id=$thread->body;
                $cat_name = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wpsp_catagories where id=".$cat_id);
                $user = get_userdata($thread->created_by);
                $user_name = $user->display_name;
                ?>
                <h3 class="unclickableAcc"><strong><?php echo $user_name ?> <?php echo __('changed category to','wp-support-plus-responsive').' '. $cat_name->name.' '.$log_modifier; ?></strong></h3>
                <div class="threadContainer unclickableAccBody"><?php echo $user_name ?> <?php echo __('changed category to','wp-support-plus-responsive').' '. $cat_name->name; ?></div>
                <?php }  
            else if ($thread->note == 5 && $current_user->has_cap('manage_support_plus_ticket')) {
                $priority_name=$thread->body;
                $user = get_userdata($thread->created_by);
                $user_name = $user->display_name;
                ?>
               <h3 class="unclickableAcc"><strong><?php echo $user_name ?> <?php echo __('changed priority to','wp-support-plus-responsive').' '. $priority_name.' '.$log_modifier; ?></strong></h3>
               <div class="threadContainer unclickableAccBody"><?php echo $user_name?> <?php echo __('changed priority to','wp-support-plus-responsive').' '. $priority_name; ?></div>
                <?php } 
            else if ($thread->note == 6 && $current_user->has_cap('manage_support_plus_ticket')) {             
                $raised_by=$thread->body;             
                if($raised_by==0){                 
                    $raised_by=$thread->guest_name;             
                    
                }else{                 
                    $raised_by=get_userdata( $raised_by );                 
                    $raised_by=$raised_by->display_name;            
                    }             
                    $user=get_userdata( $thread->created_by );             
                    $user_name=$user->display_name;             
                    ?>
               <h3 class="unclickableAcc"><strong><?php echo $user_name ?> <?php echo __('changed raised by to','wp-support-plus-responsive').' '. $raised_by.' '.$log_modifier; ?></strong></h3>             
               <div class="threadContainer unclickableAccBody"><?php echo $user_name?> <?php echo __('changed raised by to','wp-support-plus-responsive').' '. $raised_by; ?></div>            
                <?php }  
            else {
                ?>
                <h3><strong><?php echo $user_name; ?></strong> <em><?php echo $user_email; ?></em> <?php echo $modified; ?>
                    <?php if($thread->attachment_ids){?>
                    <img alt="attachment" title="attachment" class="wpsp_paperclip_image" src="<?php echo WCE_PLUGIN_URL.'asset/images/paperclip_2.png';?>" />
                    <?php }?></h3>
                <div class="threadContainer">
                    <div class="threadHeader">
                <?php
                /* END CLOUGH I.T. SOLUTIONS MODIFICATION
                 */
                ?>
               
                        <div class="gravtar_container">
            <?php echo get_avatar($user_email, 60); ?>
                        </div>
                        <div class="threadInfo">
                            <span class="threadUserName"><?php echo $user_name; ?></span><br>
                            <small class="threadUserType"><?php echo $user_email; ?></small><br>
                            <small class="threadCreateTime"><?php echo $modified; ?></small>
                        </div>
                        
                        <?php do_action('wpsp_threadinfo_thread',$thread,$user_email);?>
                        
                        <?php if ($current_user->has_cap('manage_support_plus_agent')) { ?>
                            <div class="wpsp_edit_ticket_threads">
                                <a href="<?php echo admin_url('admin.php?page=wp-support-plus-faq&type=editThread&id=' . $thread->id); ?>">
                                    <img alt="Edit" title="Edit" src="<?php echo WCE_PLUGIN_URL . 'asset/images/edit.png'; ?>" />
                                </a>
                            </div>
            <?php
            }
            /* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
             * Update 14 - create new ticket from thread
             */
            ?>
                        <div class="wpsp_add_new_ticket_threads">
                            <img alt="<?php echo __($advancedSettings['ticket_label_alice'][16], 'wp-support-plus-responsive'); ?>" title="<?php echo __($advancedSettings['ticket_label_alice'][16], 'wp-support-plus-responsive'); ?>" src="<?php echo WCE_PLUGIN_URL . 'asset/images/new.png'; ?>" style="cursor:pointer;" onclick="ticketFromThread(<?php echo $thread->id; ?>)" />
                        </div>
                        <?php if ($current_user->has_cap('manage_options')) { ?>
                        <div class="wpsp_add_new_ticket_threads">
                            <img alt="Delete" title="Remove Thread" src="<?php echo WCE_PLUGIN_URL . 'asset/images/trash.png'; ?>" style="cursor:pointer;" onclick="deleteThread(<?php echo $thread->id; ?>,<?php echo $ticket->id; ?>)" />
                        </div>
                        <?php } ?>
                    </div>
            <?php       
            /* END CLOUGH I.T. SOLUTIONS MODIFICATION
             */
            ?>
                        <?php if ($thread->note == 1 && $current_user->has_cap('manage_support_plus_ticket')) { ?>
                        <div class='note' style='size:18px;color:red;'><?php _e('Private Note : Not Visible to Customers', 'wp-support-plus-responsive'); ?></div>
                            <?php }
                        ?>
                    <div class="threadBody"><?php do_action('wpsp_before_thread_body_backend',$thread)?><?php echo $body;?></div>

                    <?php if (count($attachments)) { ?>
                    <div class="threadAttachment">
                        <span id="wpsp_reply_attach_label"><?php _e('Attachments','wp-support-plus-responsive');?>:</span><br>
                        <?php 
                        $attachCount=0;
                        foreach ($attachments as $attachment){
                            $attach=$wpdb->get_row( "select * from {$wpdb->prefix}wpsp_attachments where id=".$attachment );
                            $attachCount++;
                            if($advancedSettings['wpsp_attachment_download_url']==1){
                                $attachment_url=home_url().'?ticket_attachment='.$attach->download_key;
                            }
                            else{
                                $attachment_url=$attach->fileurl;
                            }
                        ?>
                        <div class="wpsp_attachment_row">
                            <?php echo $attachCount;?>.
                            <a class="attachment_link" title="Download" target="_blank" href="<?php echo $attachment_url;?>" ><?php echo $attach->filename;?></a>
                        </div>
                        <?php }?>
                    </div>
                    <?php } ?>
                    <?php do_action('wpsp_after_main_thread_backend',$ticket,$thread,$current_user);?>
                </div>    
                    <?php } ?>

                <?php }
                      do_action('wpsp_indivisual_ticket_create_new_accordian_thread',$thread,$current_user);
            }
            ?>
            <?php
            /* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
             * Update 18 - Thread accordion
             * jQuery accordion for threads
             */
            if ($advancedSettings['enable_accordion']) {
                ?>
    </div>
        <?php
    }
    /* END CLOUGH I.T. SOLUTIONS MODIFICATION
     */
    if ($advancedSettings['wpsp_reply_form_position'] == 0 && apply_filters('wpsp_show_reply_form',true,$ticket)){
        include( WCE_PLUGIN_DIR . 'includes/admin/replyFormPosition.php' );
    }
    do_action('wpsp_action_after_open_individual_ticket',$ticket);
    ?>
<div id="psmwpsp" style="display: none">
    <div  id="myModal">
        <h4 id="myModalLabel"><?php _e('Canned Reply', 'wp-support-plus-responsive-ticket-system'); ?></h4>
    </div>
    <div id="popup">
<?php
global $wpdb;
$sql = "select * from {$wpdb->prefix}wpsp_canned_reply where uID=" . $current_user->ID . " OR sid LIKE '%" . $current_user->ID . "%'";
$canned = $wpdb->get_results($sql);
?>
        <table class="table table-striped table-hover" id="wpspCannedTBL">
            <tr>
                <th style="width: 50px;">#</th>
                <th><?php _e('Title', 'wp-support-plus-responsive-ticket-system'); ?></th>
                <th style="display:none;">Body</th>
                <?php do_action('wpsp_add_th_in_wpspCannedTBL');?>
            </tr>
        <?php
        $wpsp_canned_id = 0;
        foreach ($canned as $can) {
            ?>
                <tr id="mytr" onclick="replyonclick(<?php echo $can->id; ?>)">
                    <td style="width: 50px;"><?php echo ++$wpsp_canned_id; ?></td>
                    <td><?php echo stripcslashes($can->title); ?></td>
                    <td style="display:none;" id="reply<?php echo $can->id; ?>"><?php echo stripcslashes($can->reply); ?></td>
                    <?php do_action('wpsp_add_td_in_wpspCannedTBL',$can)?>
                </tr>
<?php } ?>
        </table>
            <?php if (!$canned) { ?>
            <div style="text-align: center;"><?php _e("No Reply Found", 'wp-support-plus-responsive-ticket-system'); ?></div>
            <hr>
<?php } ?>
        <button type="button" class="btn-default" id="wpsp_canned_Less" onclick="wpsp_canned_previous();"><?php _e('Previous', 'wp-support-plus-responsive-ticket-system'); ?></button>
        <button type="button" class="btn-default" id="wpsp_canned_More" style="alignment:right" onclick="wpsp_canned_next();"><?php _e('Next', 'wp-support-plus-responsive-ticket-system'); ?></button>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" onclick="closepopup();"><?php _e('Close', 'wp-support-plus-responsive-ticket-system'); ?></button>
        </div>
    </div>
</div>
</div>
</div>
<script>
    var currentIndex = 10;
    var currentScreen='open_ticket';
    var currentTicketID=<?php echo $ticket->id?>;
    jQuery(document).ready(function () {
        jQuery("#psmwpsp_canned").click(function () {
            jQuery("#psmwpsp").show();
        });
        jQuery("#wpsp_popup_ticket").click(function () {
            jQuery('#wpsp_popup_ticket_div').show();
        });
        jQuery('#wpsp_show_more').hide();
        jQuery("#wpsp_slide_demo").click(function () {
            jQuery('#wpsp_show_more').slideToggle();
        });
        jQuery('.unclickableAcc').click(function (){
            $this.preventDefault();
        });
    });
    function replyonclick(cid) {
        var value = CKEDITOR.instances['replyBody'].getData();
        var x = document.getElementById("reply" + cid);
        CKEDITOR.instances["replyBody"].setData(value + x.innerHTML);
        jQuery('#psmwpsp').hide();
        <?php do_action('wpsp_add_value_of_wpspCannedTBL_on_replybody_through_js');?>
    }
    function closepopup() {
        jQuery('#psmwpsp').hide();
    }
    function cannedrep() {
        jQuery("#wpspCannedTBL tr").hide();
        jQuery("#wpspCannedTBL tr").slice(0, 10).show();
        checkButton();
    }
    function wpsp_canned_next() {
        jQuery("#wpspCannedTBL tr").hide();
        jQuery("#wpspCannedTBL tr").slice(currentIndex, currentIndex + 10).show();
        currentIndex += 10;
        checkButton();
    }
    function wpsp_canned_previous() {
        currentIndex -= 10;
        jQuery("#wpspCannedTBL tr").hide();
        jQuery("#wpspCannedTBL tr").slice(currentIndex - 10, currentIndex).show();
        checkButton();
    }
    function checkButton() {
        var currentLength;
        currentLength = jQuery("#wpspCannedTBL tr").length;
        if (currentLength < currentIndex) {
            jQuery('#wpsp_canned_More').prop('disabled', true);
        } else {
            jQuery('#wpsp_canned_More').prop('disabled', false);
        }
        if (currentIndex <= 10) {
            jQuery('#wpsp_canned_Less').prop('disabled', true);
        } else {
            jQuery('#wpsp_canned_Less').prop('disabled', false);
        }
    }
    function wpsp_reply_form_extra_validation(){
         var flag=true;
         <?php do_action('wpsp_js_reply_form_extra_validation');?>
         return flag;
    }
</script>
<style type="text/css">
    #wpspCannedTBL td,#wpspCannedTBL th{
        color: #000000;
    }
</style>
