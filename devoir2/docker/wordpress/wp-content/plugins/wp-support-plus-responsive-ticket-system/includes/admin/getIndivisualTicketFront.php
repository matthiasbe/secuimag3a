<?php 
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();
$generalSettings=get_option( 'wpsp_general_settings' );
$advancedSettings=get_option( 'wpsp_advanced_settings' );
$FrontEndDisplaySettings = get_option('wpsp_front_end_display_settings');
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];
$flag_backend_frontend=0;

$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];
if(!is_numeric($_POST['ticket_id'])) die(); //sql injection protection

$sql="select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
$ticket = $wpdb->get_row( $sql );

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
} else {
    if($ticket->created_by==$current_user->ID || $ticket->ticket_type==1 || $ticket->guest_email==$current_user->user_email || apply_filters('wpsp_hack_flag_front_for_otherthan_staff_user',false,$ticket,$current_user)){
        $hackFlag=FALSE;
    } else {
        $hackFlag=TRUE;
    }
}

$hackFlag=apply_filters('wpsp_allow_agent_to_view_other_agent_ticket_frontend',$hackFlag,$ticket,$current_user);

if($hackFlag){
    die(__('Sorry! You do not have permission to view this ticket.','wp-support-plus-responsive-ticket-system'));
}

if(apply_filters('wpsp_check_current_ticket_in_list',false,$ticket,$current_user)){
     die(__('Sorry! You do not have permission to view this ticket.','wp-support-plus-responsive'));
}

$sql="select *,
		TIMESTAMPDIFF(MONTH,create_time,UTC_TIMESTAMP()) as date_modified_month,
		TIMESTAMPDIFF(DAY,create_time,UTC_TIMESTAMP()) as date_modified_day,
		TIMESTAMPDIFF(HOUR,create_time,UTC_TIMESTAMP()) as date_modified_hour,
 		TIMESTAMPDIFF(MINUTE,create_time,UTC_TIMESTAMP()) as date_modified_min,
 		TIMESTAMPDIFF(SECOND,create_time,UTC_TIMESTAMP()) as date_modified_sec,
		is_note as note 
		FROM {$wpdb->prefix}wpsp_ticket_thread WHERE ticket_id=".$_POST['ticket_id'].' ORDER BY create_time ' ;
if($advancedSettings['wpsp_reply_form_position']==0){
    $sql.='ASC';
} else {
     $sql.='DESC';
}

$threads= $wpdb->get_results( $sql );
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories" );
$priorities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_priority" );

$advancedSettingsPriorityOrder=get_option( 'wpsp_advanced_settings_priority_order' );
if(isset($advancedSettingsPriorityOrder['priority_order'])){
	if(is_array($advancedSettingsPriorityOrder['priority_order']))
	{
		$priorities=array();
		foreach($advancedSettingsPriorityOrder['priority_order'] as $priority_id)
		{
			$sql="select * from {$wpdb->prefix}wpsp_custom_priority WHERE id=".$priority_id." ";
			$priority_data=$wpdb->get_results($sql);
			foreach($priority_data as $priority)
			{
				$priorities=array_merge($priorities,array($priority));
			}
		}
	}
}

$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );

$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$total_cust_field=$wpdb->num_rows;

$isVisible=apply_filters('wpsp_showhide_indivisual_ticket_buttons_front',true,$ticket,$current_user);

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
$arrayVisible=apply_filters('wpsp_indivisual_ticket_frontend_visible_btns',$arrayVisible,$ticket,$current_user);

?>
<?php if($arrayVisible['back'] && $FrontEndDisplaySettings['wpsp_hideBackToTicket']){
    $btnStyle="color:".$FrontEndDisplaySettings['wpsp_btt_fc']."; background-color:".$FrontEndDisplaySettings['wpsp_btt_bc']."; border-color:".$FrontEndDisplaySettings['wpsp_btt_bc'];
    ?>
   <button style="<?php echo $btnStyle;?>" class="btn btn-primary changeTicketSubBtn" onclick="backToTicketFromIndisual();"><?php _e($advancedSettings['ticket_label_alice'][17],'wp-support-plus-responsive-ticket-system');?></button>
<?php }?>
    
<?php if($arrayVisible['ticketstatus'] && $isVisible && $FrontEndDisplaySettings['wpsp_hideChangeStatus']){
    $btnStyle="color:".$FrontEndDisplaySettings['wpsp_ct_fc']."; background-color:".$FrontEndDisplaySettings['wpsp_ct_bc']."; border-color:".$FrontEndDisplaySettings['wpsp_ct_bc'];
    ?>
    <button style="<?php echo $btnStyle;?>" class="btn btn-primary changeTicketSubBtn" id="btnChangeTicketStatus" onclick="getChangeTicketStatus(<?php echo $_POST['ticket_id'];?>);"><?php printf(__('%s','wp-support-plus-responsive-ticket-system'),$FrontEndDisplaySettings['front_end_display_alice'][1]);?></button>
<?php }?>
    
<?php if($arrayVisible['closeticket'] && $isVisible && !$generalSettings['close_ticket_btn_status_val']=='' && $generalSettings['close_ticket_btn_status_val']!=$ticket->status && $FrontEndDisplaySettings['wpsp_hideCloseTicket']){
    $btnStyle="color:".$FrontEndDisplaySettings['wpsp_cs_fc']."; background-color:".$FrontEndDisplaySettings['wpsp_cs_bc']."; border-color:".$FrontEndDisplaySettings['wpsp_cs_bc'];
    ?>    
    <button  style="<?php echo $btnStyle;?>" id="wpsp_close_btn_action" class="btn btn-primary wpsp_ticket_nav_btn" onclick="wpsp_closeTicketStatus(<?php echo $_POST['ticket_id'];?>,'<?php echo $generalSettings['close_ticket_btn_status_val'];?>');" > <?php echo $generalSettings['close_btn_alice'];?></button>
<?php }?>
<?php if($arrayVisible['canned'] && $isVisible && $current_user->has_cap('manage_support_plus_ticket') && $FrontEndDisplaySettings['wpsp_hideCannedReply']){
    $btnStyle="color:".$FrontEndDisplaySettings['wpsp_cr_fc']."; background-color:".$FrontEndDisplaySettings['wpsp_cr_bc']."; border-color:".$FrontEndDisplaySettings['wpsp_cr_bc'];
    ?>
    <button style="<?php echo $btnStyle;?>" class="btn btn-primary wpsp_ticket_nav_btn" id="psmwpsp_canned"  type="submit" data-target="#psmwpsp_loader" onclick="cannedrep();"><?php printf(__('%s','wp-support-plus-responsive-ticket-system'),$FrontEndDisplaySettings['front_end_display_alice'][2]);?></button>
<?php }?>
 
 <?php if($arrayVisible['assignagent'] && $isVisible && ($current_user->has_cap('manage_support_plus_agent') || ($current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_assign_tickets']==1) ) && $FrontEndDisplaySettings['wpsp_hideAssignAgent']){
         $btnStyle="color:".$FrontEndDisplaySettings['wpsp_aa_fc']."; background-color:".$FrontEndDisplaySettings['wpsp_aa_bc']."; border-color:".$FrontEndDisplaySettings['wpsp_aa_bc'];
         ?>
         <button style="<?php echo $btnStyle;?>" class="btn btn-primary wpsp_ticket_nav_btn" onclick="assignAgent(<?php echo $_POST['ticket_id'];?>);"><?php printf(__('%s','wp-support-plus-responsive-ticket-system'),$FrontEndDisplaySettings['front_end_display_alice'][3]);?></button>
<?php }?>

<?php do_action('wpsp_ticket_action_before_more_btn_frontend',$ticket);?>

<?php if( $arrayVisible['moreaction'] && (($current_user->has_cap('manage_support_plus_agent') || ($current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_assign_tickets']==1) ) && $FrontEndDisplaySettings['wpsp_hideAssignAgent']) || (($current_user->has_cap('manage_support_plus_agent') || ($current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_delete_tickets']==1)) && $FrontEndDisplaySettings['wpsp_hideDeleteTicket'])||current_user_can( 'manage_options' )&& $FrontEndDisplaySettings['wpsp_ChangeRaisedBy']){
    $btnStyle="color:".$FrontEndDisplaySettings['wpsp_ma_fc']."; background-color:".$FrontEndDisplaySettings['wpsp_ma_bc']."; border-color:".$FrontEndDisplaySettings['wpsp_ma_bc'];
    ?>
    <button style="<?php echo $btnStyle;?>" id="wpsp_slide_demo" class="btn btn-primary wpsp_ticket_nav_btn"><?php _e('+More Actions','wp-support-plus-responsive-ticket-system');?></button>
    <?php }
else{
    foreach($advancedSettings['modify_raised_by'] as $modifyRaisedBy){
        if($arrayVisible['moreaction'] && (($modifyRaisedBy == 'wp_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket') && $FrontEndDisplaySettings['wpsp_ChangeRaisedBy']) ||(($modifyRaisedBy == 'wp_support_plus_supervisor') && $current_user->has_cap('manage_support_plus_agent') && $FrontEndDisplaySettings['wpsp_ChangeRaisedBy'])){
        $btnStyle="color:".$FrontEndDisplaySettings['wpsp_ma_fc']."; background-color:".$FrontEndDisplaySettings['wpsp_ma_bc']."; border-color:".$FrontEndDisplaySettings['wpsp_ma_bc'];
        ?>
    <button style="<?php echo $btnStyle;?>" id="wpsp_slide_demo" class="btn btn-primary wpsp_ticket_nav_btn"><?php _e('+More Actions','wp-support-plus-responsive-ticket-system');?></button>
    <?php }
    }
}?>

<div id="wpsp_show_more">
    
     <?php if($arrayVisible['raisedby'] && current_user_can( 'manage_options' )&& $FrontEndDisplaySettings['wpsp_ChangeRaisedBy']){
        $btnStyle="color:".$FrontEndDisplaySettings['wpsp_cb_fc']."; background-color:".$FrontEndDisplaySettings['wpsp_cb_bc']."; border-color:".$FrontEndDisplaySettings['wpsp_cb_bc'];
        ?>
        <button style="<?php echo $btnStyle;?>" class="btn btn-primary wpsp_ticket_nav_btn" onclick="getRaisedByTicketUser(<?php echo $_POST['ticket_id'];?>);"><?php printf(__('%s','wp-support-plus-responsive'),$FrontEndDisplaySettings['front_end_display_alice'][13]);?></button><?php
        }
    else{
        foreach($advancedSettings['modify_raised_by'] as $modifyRaisedBy){
            if($arrayVisible['raisedby'] && (( $current_user->has_cap('manage_support_plus_ticket') && $modifyRaisedBy == 'wp_support_plus_agent')) && $FrontEndDisplaySettings['wpsp_ChangeRaisedBy'] || (($modifyRaisedBy == 'wp_support_plus_supervisor') && $current_user->has_cap('manage_support_plus_agent')) && $FrontEndDisplaySettings['wpsp_ChangeRaisedBy']){
            $btnStyle="color:".$FrontEndDisplaySettings['wpsp_cb_fc']."; background-color:".$FrontEndDisplaySettings['wpsp_cb_bc']."; border-color:".$FrontEndDisplaySettings['wpsp_cb_bc'];
        ?>
        <button style="<?php echo $btnStyle;?>" class="btn btn-primary wpsp_ticket_nav_btn" onclick="getRaisedByTicketUser(<?php echo $_POST['ticket_id'];?>);"><?php printf(__('%s','wp-support-plus-responsive'),$FrontEndDisplaySettings['front_end_display_alice'][13]);?></button>
    <?php   }
        }   
    }?>
    <?php if($arrayVisible['deleteticket'] && ($current_user->has_cap('manage_support_plus_agent') || ($current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_delete_tickets']==1)) && $FrontEndDisplaySettings['wpsp_hideDeleteTicket']){
        $btnStyle="color:".$FrontEndDisplaySettings['wpsp_dt_fc']."; background-color:".$FrontEndDisplaySettings['wpsp_dt_bc']."; border-color:".$FrontEndDisplaySettings['wpsp_dt_bc'];
        ?>
        <button style="<?php echo $btnStyle;?>" class="btn btn-danger wpsp_ticket_nav_btn" onclick="deleteTicket(<?php echo $_POST['ticket_id'];?>);"><?php printf(__('%s','wp-support-plus-responsive-ticket-system'),$FrontEndDisplaySettings['front_end_display_alice'][4]);?></button>
    <?php } ?>
</div>
<br/>
<?php 
     do_action('wpsp_open_ticket_frontend_before_subject_header',$ticket);
     
    $editCustomField=apply_filters('wpsp_enable_disable_edit_custom_fields',true,$ticket,$current_user);
?>
<h3>
    <?php echo '['.__($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive-ticket-system')?><?php echo $advancedSettings['wpsp_ticket_id_prefix'].$_POST['ticket_id'].'] '.stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES));?>
    <?php if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }?>
</h3>
<!-- Custom Field -->
<?php if($total_cust_field){?>
	<div class="threadContainer">
		<?php 
		foreach ($customFields as $field){
			if(in_array($field->id,$advancedSettingsFieldOrder['display_fields']))
			{
                            $fieldValue=$wpdb->get_var("select cust".$field->id." from {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id']);
                            if($fieldValue){
				switch($field->field_type)
				{
					case '1':
                                            echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES)); 
                                            if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }
                                            echo "<br>";
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
                                                if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }
                                                echo "<br>";
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
                                                if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }
                                                echo "<br>";
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
                                                        if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }
                                                        echo "<br>";
						}
					break;
					case '5':
						echo "<b>".$field->label.":</b> <br>".nl2br($fieldValue);
                                                if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }
                                                echo "<br>";
					break;
                                        case '6':
                                                echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES));
                                                if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }
                                                echo "<br>";
                                        break;
                                        case '7': if(class_exists( 'WooCommerce' ) && class_exists( 'WPSupportPlusWoocommerce' )) {
                                                $pf = new WC_Product_Factory();
                                                $product = $pf->get_product($fieldValue);            
                                                $prod_url = get_permalink( $product->id );
                                                $prod_title=$product->post->post_title;
                                                echo "<b>".$field->label.":</b> ".'<a href="'.$prod_url.'" target="__blank">'.$prod_title.'</a>';
                                                if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }
                                                echo "<br>";
                                            }
                                        break;
                                        default: do_action('wpsp_display_extra_custom_fields_frontend',$ticket,$field,$fieldValue);
				}
                            }
			}
                        else if($field->isVarFeild && $current_user->has_cap('manage_support_plus_ticket')){
                            $fieldValue=$wpdb->get_var("select cust".$field->id." from {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id']);
                            if($fieldValue){
                                switch($field->field_type)
                                {
                                        case '1':
                                                echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES));
                                                if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }
                                                echo "<br>";
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
                                                        if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }
                                                        echo "<br>";
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
                                                        if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }
                                                        echo "<br>";
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
                                                        if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }
                                                        echo "<br>";
                                                }
                                        break;
                                        case '5':
                                            echo "<b>".$field->label.":</b> <br>".nl2br($fieldValue);
                                            if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }
                                            echo "<br>";
                                        break;
                                        case '6':
                                            echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES));
                                            if($current_user->has_cap('manage_support_plus_ticket')){ echo $editCustomField? '<img alt="Edit" class="wpsp_edit_fields" title="Edit" onclick="getEditCustomField('. $_POST['ticket_id'] .')" src="'.WCE_PLUGIN_URL.'asset/images/edit.png'.'" />':''; }
                                            echo "<br>";
                                        break;
                                        default: do_action('wpsp_display_extra_custom_fields_frontend_variablefields',$ticket,$field,$fieldValue);
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
if($ticket->extension_meta){
    ?>
    <div class="threadContainer">
    <?php
    $extension_meta=  explode(',', $ticket->extension_meta);
    if($extension_meta[0]==1){ //woocommerce
        if(class_exists( 'WooCommerce' ) && class_exists( 'WPSupportPlusWoocommerce' ) && $extension_meta[1]==1){ //woocommerce
            $pf = new WC_Product_Factory();
            $product = $pf->get_product($extension_meta[2]);            
            $prod_url = get_permalink( $product->id );
            $prod_title=$product->post->post_title;
            echo "<b>".__('Product','wp-support-plus-responsive-ticket-system').":</b> ".'<a href="'.$prod_url.'" target="__blank">'.$prod_title.'</a>'."<br>";
        } else if($extension_meta[1]==2){ //order
            $order = new WC_Order($extension_meta[2]);
            $order_title=__('Order','wp-support-plus-responsive-ticket-system').' #'.$extension_meta[2];
            echo "<b>".__('Order','wp-support-plus-responsive-ticket-system').":</b> ".$order_title."<br>";
            ?>
            <table class="wpsp_order_display_open_ticket">
                <tr>
                    <th class="wpsp_order_tbl_col1"><?php echo _e('Product','wp-support-plus-responsive-ticket-system');?></th>
                    <th class="wpsp_order_tbl_col2"><?php echo _e('Total','wp-support-plus-responsive-ticket-system');?></th>
                </tr>
            <?php
            foreach ($order->get_items() as $key => $lineItem) {
                $pf = new WC_Product_Factory();
                $product = $pf->get_product($lineItem['product_id']);            
                $prod_url = get_permalink( $product->id );
                $prod_title=$product->post->post_title;
                ?>
                <tr>
                    <td class="wpsp_order_tbl_col1"><a href="<?php echo $prod_url;?>" target="__blank"><?php echo $prod_title;?></a> x <?php echo $lineItem['qty'];?></td>
                    <td class="wpsp_order_tbl_col2"><?php echo get_woocommerce_currency_symbol($order->order_currency).$lineItem['line_subtotal'];?></td>
                </tr>
                <?php
            }
            ?>
                <tr>
                    <td class="wpsp_order_tbl_col1"><b><?php _e('Subtotal','wp-support-plus-responsive-ticket-system');?></b></td>
                    <td class="wpsp_order_tbl_col2"><b><?php echo get_woocommerce_currency_symbol($order->order_currency).$order->get_subtotal();?></b></td>
                </tr>
                <tr>
                    <td class="wpsp_order_tbl_col1"><b><?php _e('Discount','wp-support-plus-responsive-ticket-system');?></b></td>
                    <td class="wpsp_order_tbl_col2"><b>-<?php echo $order->get_discount_to_display();?></b></td>
                </tr>
                <tr>
                    <td class="wpsp_order_tbl_col1"><b><?php _e('Payment Method','wp-support-plus-responsive-ticket-system');?></b></td>
                    <td class="wpsp_order_tbl_col2"><b><?php echo $order->payment_method;?></b></td>
                </tr>
                <tr>
                    <td class="wpsp_order_tbl_col1"><b><?php _e('Total','wp-support-plus-responsive-ticket-system');?></b></td>
                    <td class="wpsp_order_tbl_col2"><b><?php echo get_woocommerce_currency_symbol($order->order_currency).$order->order_total;?></b></td>
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

do_action('wpsp_after_custom_field_open_ticket_frontend',$ticket);
?>
<!-- Extension integration end -->
<?php 
if($advancedSettings['wpsp_reply_form_position']==1 && apply_filters('wpsp_show_reply_form',true,$ticket)){
    include( WCE_PLUGIN_DIR.'includes/admin/replyFormPosition.php' );
}
?>
<?php
/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 18 - Thread accordion
 * jQuery accordion for threads
 */ 
if($advancedSettings['enable_accordion']){
?>
<div id="threadAccordion" class="wpSupportPlus">
<?php
}
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
 */
?>
<?php foreach ($threads as $thread){
    
    if(apply_filters('wpsp_thread_filter_frontend',false,$ticket,$thread,$current_user)){
         continue;
    }
    
    if($thread->note==0 || ($thread->note==1 && $current_user->has_cap('manage_support_plus_ticket')) || ($thread->note==2 && $current_user->has_cap('manage_support_plus_ticket'))|| ($thread->note==3 && $current_user->has_cap('manage_support_plus_ticket'))|| ($thread->note==4 && $current_user->has_cap('manage_support_plus_ticket'))|| ($thread->note==5 && $current_user->has_cap('manage_support_plus_ticket'))|| ($thread->note==6 && $current_user->has_cap('manage_support_plus_ticket'))){
        $user_name='';
        $user_email='';
        $signature='';
        if($thread->created_by){
            $user=get_userdata( $thread->created_by );
            $user_name=$user->display_name;
            $user_email=$user->user_email;
            $userSignature = $wpdb->get_row( "select signature FROM {$wpdb->prefix}wpsp_agent_settings WHERE agent_id=".$thread->created_by );
            if($wpdb->num_rows){
                $signature='<br>---<br>'.stripcslashes(htmlspecialchars_decode($userSignature->signature,ENT_QUOTES));
            }
        }
        else{
            $user_name=$thread->guest_name;
            $user_email=$thread->guest_email;
        }
        $modified='';
        if ($thread->date_modified_month) $modified=$thread->date_modified_month.' '.__('months ago','wp-support-plus-responsive-ticket-system');
        else if ($thread->date_modified_day) $modified=$thread->date_modified_day.' '.__('days ago','wp-support-plus-responsive-ticket-system');
        else if ($thread->date_modified_hour) $modified=$thread->date_modified_hour.' '.__('hours ago','wp-support-plus-responsive-ticket-system');
        else if ($thread->date_modified_min) $modified=$thread->date_modified_min.' '.__('minutes ago','wp-support-plus-responsive-ticket-system');
        else $modified=$thread->date_modified_sec.' '.__('seconds ago','wp-support-plus-responsive-ticket-system');
        $modified_exact_date=get_date_from_gmt( $thread->create_time, 'Y-m-d');
        $modified_exact_time=get_date_from_gmt( $thread->create_time, 'H:i:s');
        $attachments=array();
        if($thread->attachment_ids){
            $attachments=explode(',', $thread->attachment_ids);
        }
        $body=stripcslashes(htmlspecialchars_decode($thread->body,ENT_QUOTES));
        if($thread->note==0){
            $body.=$signature;
        }
        if(!$FrontEndDisplaySettings['wpsp_hideEmail']) $user_email='';
        if(!$FrontEndDisplaySettings['wpsp_hideDaysMonthsYearAgo']) $modified='';
        if(!$FrontEndDisplaySettings['wpsp_hideExactDate']) $modified_exact_date='';
        if(!$FrontEndDisplaySettings['wpsp_hideExactTime']) $modified_exact_time='';
        if($modified_exact_date || $modified_exact_time){    
            $log_modifier='('.$modified_exact_date.' '.$modified_exact_time.')';
        }else{
            $log_modifier='';
        }
        ?>
            <?php
        if($thread->note==2 && $current_user->has_cap('manage_support_plus_ticket')){
            $assigned_to=$body;
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
        <div class="threadContainer unclickableAccBody"><?php echo __('Assign to','wp-support-plus-responsive').' '.$assign_to_str;?></div>
         <?php 
        } 
            else if ($thread->note == 3 && $current_user->has_cap('manage_support_plus_ticket')) {
                $thread->body;
                $user=get_userdata( $thread->created_by );
                $user_name=$user->display_name;
                ?>
                <h3 class="unclickableAcc"><strong><?php echo $user_name ?> <?php echo __('changed status to','wp-support-plus-responsive').' '. $thread->body.' '.$log_modifier; ?></strong></h3>
                <div class="threadContainer unclickableAccBody"><?php echo $user_name?><?php echo __('Change status to','wp-support-plus-responsive').' '. $thread->body; ?></div>
                <?php }  
            else if ($thread->note == 4 && $current_user->has_cap('manage_support_plus_ticket')) {
                $cat_id=$thread->body;
                $user=get_userdata( $thread->created_by );
                $user_name=$user->display_name;
                $cat_name = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wpsp_catagories where id=".$cat_id);
                ?>
                <h3 class="unclickableAcc"><strong><?php echo $user_name ?> <?php echo __('changed category to','wp-support-plus-responsive').' '. $cat_name->name.' '.$log_modifier; ?></strong></h3>
                <div class="threadContainer unclickableAccBody"><?php echo $user_name?><?php echo __('change category to','wp-support-plus-responsive').' '. $cat_name->name; ?></div>
                <?php }  
            else if ($thread->note == 5 && $current_user->has_cap('manage_support_plus_ticket')) {
                $priority_name=$thread->body;
                $user=get_userdata( $thread->created_by );
                $user_name=$user->display_name;
                 ?>
        <h3 class="unclickableAcc"><strong><?php echo $user_name ?> <?php echo __('changed priority to','wp-support-plus-responsive').' '. $priority_name.' '.$log_modifier; ?></strong></h3>
        <div class="threadContainer unclickableAccBody"><?php echo $user_name?><?php echo __('change priority to','wp-support-plus-responsive').' '. $priority_name; ?></div>
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
        else{ ?>
        <h3><strong><?php echo $user_name;?></strong> <em><?php echo $user_email;?></em> <?php echo $modified_exact_date;?> <?php echo $modified_exact_time;?> <?php echo ($modified)?"($modified)":'';?>
        <?php if($thread->attachment_ids){?>
            <img alt="attachment" title="attachment" class="wpsp_paperclip_image" src="<?php echo WCE_PLUGIN_URL.'asset/images/paperclip_2.png';?>" />
        <?php }?></h3>
        <div class="threadContainer">
            <div class="threadHeader">
                <div class="gravtar_container">
                    <?php echo get_avatar($user_email,60);?>
                </div>
                <div class="threadInfo">
                    <span class="threadUserName"><?php echo $user_name;?></span><br>
                    <small class="threadUserType"><?php echo $user_email;?></small><br>
                    <small class="threadCreateDate"><?php echo $modified_exact_date;?> <?php echo $modified_exact_time;?> <?php echo ($modified)?"($modified)":'';?></small>                   
                </div>
                
                <?php 
                if($current_user->has_cap('manage_support_plus_ticket')){
                    do_action('wpsp_threadinfo_thread',$thread,$user_email);
                }
                ?>
                
            </div>
            <?php
            if($thread->note==1 && $current_user->has_cap('manage_support_plus_ticket')){ ?>
                <div class='note' style='size:18px;color:red;'><?php _e('Private Note : Not Visible to Customers','wp-support-plus-responsive-ticket-system');?></div>
            <?php }?>

            <div class="threadBody"><?php do_action('wpsp_before_thread_body_frontend',$thread)?><?php echo $body;?></div>
            <?php if(count($attachments)){?>
                <div class="threadAttachment">
                        <span id="wpsp_reply_attach_label"><?php _e('Attachments','wp-support-plus-responsive');?>:</span><br>
                        <?php 
                        $attachCount=0;
                        foreach ($attachments as $attachment){
                                $attach=$wpdb->get_row( "select * from {$wpdb->prefix}wpsp_attachments where id=".$attachment );
                                $attachCount++;
                                if($advancedSettings['wpsp_attachment_download_url']==1){
                                    $attachment_url=home_url().'?ticket_attachment='.$attach->download_key;
                                }else{
                                    $attachment_url=$attach->fileurl;
                                }
                        ?>
                        <div class="wpsp_attachment_row">
                            <?php echo $attachCount;?>.
                            <a class="attachment_link" title="Download" target="_blank" href="<?php echo $attachment_url;?>" ><?php echo $attach->filename;?></a>
                        </div>
                        <?php }?>
                </div>
            <?php }?>
            
            <?php do_action('wpsp_after_main_thread_frontend',$ticket,$thread,$current_user);?>
            
        </div>
        <?php
        }?>
<?php }
      do_action('wpsp_indivisual_ticket_create_new_accordian_thread_frontend',$thread,$current_user);
}

if($advancedSettings['enable_accordion']){
?>
</div>
<?php
}

if($advancedSettings['wpsp_reply_form_position']==0 && apply_filters('wpsp_show_reply_form',true,$ticket)){
    include( WCE_PLUGIN_DIR.'includes/admin/replyFormPosition.php' );
}

do_action('wpsp_action_after_open_individual_ticket_front',$ticket);
?>

<div id="psmwpsp_loader" style="display: none">
    <div  id="myModal">
     <h4 id="myModalLabel"><?php _e('Canned Reply','wp-support-plus-responsive-ticket-system');?></h4>
    </div>
        <div id="popup">
                <?php 
                  global $wpdb;
                  $sql="select * from {$wpdb->prefix}wpsp_canned_reply where uID=".$current_user->ID." OR sid LIKE '%".$current_user->ID."%'";
                  $canned = $wpdb->get_results( $sql );
                ?>
                <table class="table table-striped table-hover" id="wpspCannedTBL">
                    <tr>
                      <th style="width: 50px;">#</th>
                      <th><?php _e('Title','wp-support-plus-responsive-ticket-system');?></th>
                      <th style="display:none;">Body</th>
                      <?php do_action('wpsp_add_th_in_wpspCannedTBL_frontend');?>
                    </tr>
                    <?php 
                    $wpsp_canned_id=0;
                    foreach($canned as $can){ ?>
                        <tr id="mytr" onclick="replyonclick(<?php echo $can->id;?>)">
                            <td style="width: 50px;"><?php echo ++$wpsp_canned_id;?></td>
                            <td><?php echo stripcslashes($can->title);?></td>
                            <td style="display:none;" id="reply<?php echo $can->id; ?>"><?php echo stripcslashes($can->reply);?></td>
                            <?php do_action('wpsp_add_td_in_wpspCannedTBL_frontend',$can)?>
                        </tr>
                    <?php }?>
                </table>
                <?php 
                if(!$canned){?>
                        <div style="text-align: center;"><?php _e("No Reply Found",'wp-support-plus-responsive-ticket-system');?></div>
                        <hr>
                <?php }?>
                        <button type="button" class="btn-default"id="wpsp_canned_Less"onclick="wpsp_canned_previous();"><?php _e('Previous','wp-support-plus-responsive-ticket-system');?></button>
                        <button type="button" class="btn-default" id="wpsp_canned_More" style="alignment:right" onclick="wpsp_canned_next();"><?php _e('Next','wp-support-plus-responsive-ticket-system');?></button>
                <div>
                    <button type="button" class="btn btn-default" id="closebtn" onclick="closepopup();" style="float:right"><?php _e('Close','wp-support-plus-responsive-ticket-system');?></button>
                </div>
        </div>
</div>

<script type="text/javascript">
    var currentIndex=10;
    var currentScreen='open_ticket';
    var currentTicketID=<?php echo $ticket->id?>;
    var wpsp_show_ck_editor_for_this_ticket=true;
    jQuery(document).ready(function(){
        jQuery("#psmwpsp_canned").click(function(){
            jQuery("#psmwpsp_loader").show();
        });        
        jQuery("#wpsp_popup_ticket").click(function(){
            jQuery('#wpsp_popup_ticket_div').show();       
        });        
        jQuery('#wpsp_show_more').hide();
        jQuery("#wpsp_slide_demo").click(function(){
            jQuery('#wpsp_show_more').slideToggle();
        });
        jQuery('.unclickableAcc').click(function (){
              $this.preventDefault();
        });
    });
    function replyonclick(cid){
        if( display_ticket_data.ckeditor_enable_for_loggedin=='1'){
            var value = CKEDITOR.instances['replyBody'].getData();
            var x=document.getElementById("reply"+cid);
            CKEDITOR.instances["replyBody"].setData(value+x.innerHTML);
        } else if( display_ticket_data.ckeditor_enable_for_loggedin=='0'){
            var value = jQuery("#replyBody").val();
            var x=document.getElementById("reply"+cid);
            jQuery("#replyBody").val(value+x.innerHTML);
        }
        <?php do_action('wpsp_add_value_of_wpspCannedTBL_on_replybody_through_js_frontend');?>
        jQuery('#psmwpsp_loader').hide();
    }
    function closepopup(){
        jQuery('#psmwpsp_loader').hide();
    }
     function cannedrep(){
        jQuery("#wpspCannedTBL tr").hide();
        jQuery("#wpspCannedTBL tr").slice(0, 10).show();
        checkButton();
    }
    function wpsp_canned_next(){
        jQuery("#wpspCannedTBL tr").hide(); 
        jQuery("#wpspCannedTBL tr").slice(currentIndex, currentIndex +10).show();
        currentIndex+=10;
        checkButton();
    } 
    function wpsp_canned_previous(){
       currentIndex-=10;
       jQuery("#wpspCannedTBL tr").hide(); 
       jQuery("#wpspCannedTBL tr").slice(currentIndex-10, currentIndex).show();          
       checkButton();
    }
    
    function checkButton(){ 
       var currentLength;
       currentLength =jQuery("#wpspCannedTBL tr").length;
       if(currentLength<currentIndex){
       jQuery('#wpsp_canned_More').prop('disabled', true);
       }else{
       jQuery('#wpsp_canned_More').prop('disabled', false); 
        }
       if(currentIndex<=10){
       jQuery('#wpsp_canned_Less').prop('disabled', true); 
       }else{
       jQuery('#wpsp_canned_Less').prop('disabled', false); 
       }
    }
    
    function wpsp_reply_form_extra_validation(){
         var flag=true;
         <?php do_action('wpsp_js_reply_form_extra_validation');?>
         return flag;
    }
    
    <?php do_action('wpsp_getindividualticketfront_js_function_end',$ticket);?>
    
</script>
