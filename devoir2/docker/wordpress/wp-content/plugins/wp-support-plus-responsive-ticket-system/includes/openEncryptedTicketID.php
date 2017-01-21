<?php
global $wpdb;
$generalSettings=get_option( 'wpsp_general_settings' );
$advancedSettings=get_option( 'wpsp_advanced_settings' );
$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$FrontEndDisplaySettings = get_option('wpsp_front_end_display_settings');

if(isset($_REQUEST['ticket_id'])){
    $ticket_id=(int)Decrypt($_REQUEST['ticket_id']);
    if(is_numeric($ticket_id)){
        /*
         * get ticket values
         */
        $sql="select subject,type,status,cat_id,priority,created_by,guest_name,guest_email
                        FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$ticket_id;
        $ticket = $wpdb->get_row( $sql );
        /*
         * get thread values
         */
        $sql="select id,body,attachment_ids,created_by,guest_name,guest_email,create_time,
                        TIMESTAMPDIFF(MONTH,create_time,UTC_TIMESTAMP()) as date_modified_month,
                        TIMESTAMPDIFF(DAY,create_time,UTC_TIMESTAMP()) as date_modified_day,
                        TIMESTAMPDIFF(HOUR,create_time,UTC_TIMESTAMP()) as date_modified_hour,
                        TIMESTAMPDIFF(MINUTE,create_time,UTC_TIMESTAMP()) as date_modified_min,
                        TIMESTAMPDIFF(SECOND,create_time,UTC_TIMESTAMP()) as date_modified_sec,
                        is_note as note 
                        FROM {$wpdb->prefix}wpsp_ticket_thread WHERE ticket_id=".$ticket_id.' ORDER BY create_time ' ;
        if($advancedSettings['wpsp_reply_form_position']==0){
            $sql.='ASC';
        } else {
             $sql.='DESC';
        }
        $threads= $wpdb->get_results( $sql );
        /*
         * Start subject
         */
        ?>
        <h3><?php echo '['.__($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive-ticket-system').' #'.$_POST['ticket_id'].'] '.stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES));?></h3><br>
        <?php
        /*
         * Start printing custom fields
         */
        $customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
        $total_cust_field=$wpdb->num_rows;
        if($total_cust_field && $advancedSettings['hide_selected_status_ticket']!=$ticket->status){ 
            ?>
            <div class="threadContainer">
                <?php 
                foreach ($customFields as $field) {
                    if (in_array($field->id, $advancedSettingsFieldOrder['display_fields'])) {
                        $fieldValue = $wpdb->get_var("select cust" . $field->id . " from {$wpdb->prefix}wpsp_ticket WHERE id=" . $ticket_id);
                        if (!$fieldValue)
                            $fieldValue = __('', 'wp-support-plus-responsive-ticket-system');
                        switch ($field->field_type) {
                            case '1':
                                echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($fieldValue, ENT_QUOTES)) . "<br>";
                                break;
                            case '2':
                                if ($field->field_options != NULL) {
                                    $field_options = unserialize($field->field_options);
                                    if (isset($field_options[$fieldValue])) {
                                        echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($field_options[$fieldValue], ENT_QUOTES)) . "<br>";
                                    } else {
                                        echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($fieldValue, ENT_QUOTES)) . "<br>";
                                    }
                                }
                                break;
                            case '3':
                                if ($field->field_options != NULL) {
                                    $field_options = unserialize($field->field_options);
                                    if (isset($field_options[$fieldValue])) {
                                        echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($field_options[$fieldValue], ENT_QUOTES)) . "<br>";
                                    } else {
                                        echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($fieldValue, ENT_QUOTES)) . "<br>";
                                    }
                                }
                                break;
                            case '4':
                                if ($field->field_options != NULL) {
                                    $field_options = unserialize($field->field_options);
                                    if (isset($field_options[$fieldValue])) {
                                        echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($field_options[$fieldValue], ENT_QUOTES)) . "<br>";
                                    } else {
                                        echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($fieldValue, ENT_QUOTES)) . "<br>";
                                    }
                                }
                                break;
                            case '5':
                                echo "<b>" . $field->label . ":</b> <br>" . nl2br($fieldValue) . "<br>";
                                break;
                            case '6':
                                echo "<b>" . $field->label . ":</b> " . stripcslashes(htmlspecialchars_decode($fieldValue, ENT_QUOTES)) . "<br>";
                                break;
                        }
                    }
                }
                ?>
            </div>
            <?php
        }
        /*
         * show reply form for above form position
         */
        if($advancedSettings['wpsp_reply_form_position']==1){
            include( WCE_PLUGIN_DIR.'includes/linkReplyForm.php' );
        }
        /*
         * start printing threads
         */
        echo '<br><br>';
        if($advancedSettings['enable_accordion']){
        ?>
        <div id="threadAccordion" class="wpSupportPlus">
        <?php
        }
    if($advancedSettings['hide_selected_status_ticket']!=$ticket->status){
        foreach ($threads as $thread){?>
            <?php
            if($thread->note==0){
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
//                $modified .= ' (' . date_i18n( get_option( 'date_format' ), strtotime( get_date_from_gmt( $thread->create_time, 'Y-m-d H:i:s') ) ) . ' ' . get_date_from_gmt( $thread->create_time, 'H:i:s') . ')';
                $modified_exact_date=get_date_from_gmt( $thread->create_time, 'Y-m-d');
                $modified_exact_time=get_date_from_gmt( $thread->create_time, 'H:i:s');
                $attachments=array();
                if($thread->attachment_ids){
                    $attachments=explode(',', $thread->attachment_ids);
                }
                $body=stripcslashes(htmlspecialchars_decode($thread->body,ENT_QUOTES));
                $body.=$signature;
                if(!$FrontEndDisplaySettings['wpsp_hideEmail']) $user_email='';
                if(!$FrontEndDisplaySettings['wpsp_hideDaysMonthsYearAgo']) $modified='';
                if(!$FrontEndDisplaySettings['wpsp_hideExactDate']) $modified_exact_date='';
                if(!$FrontEndDisplaySettings['wpsp_hideExactTime']) $modified_exact_time='';
                ?>
                <h3><strong><?php echo $user_name;?></strong> <em><?php echo $user_email;?></em> <?php echo $modified_exact_date;?> <?php echo $modified_exact_time;?> <?php echo ($modified)?"($modified)":'';?></h3>
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
                    </div>
                    <?php if($thread->note==1 && $current_user->has_cap('manage_support_plus_ticket')){ ?>
                            <div class='note' style='size:18px;color:red;'><?php _e('Private Note : Not Visible to Customers','wp-support-plus-responsive-ticket-system');?></div>
                    <?php }?>

                    <div class="threadBody"><?php echo $body;?></div>
                    <?php if(count($attachments)){?>
                    <div class="threadAttachment">
                            <span id="wpsp_reply_attach_label"><?php _e('Attachment: ','wp-support-plus-responsive-ticket-system');?></span>
                            <?php 
                            $attachCount=0;
                            foreach ($attachments as $attachment){
                                    $attach=$wpdb->get_row( "select * from {$wpdb->prefix}wpsp_attachments where id=".$attachment );
                                    $attachCount++;
                            ?>
                            <a class="attachment_link" title="Download" target="_blank" href="<?php echo $attach->fileurl;?>" ><?php echo ($attachCount>1)?', ':'';echo $attach->filename;?></a>
                            <?php }?>
                    </div>
                    <?php }?>
                </div>
        <?php }
        }
    }   
        if($advancedSettings['enable_accordion']){?>
        </div>
        <?php }
        /*
         * show reply form for below form position
         */
        if($advancedSettings['wpsp_reply_form_position']==0){
            include( WCE_PLUGIN_DIR.'includes/linkReplyForm.php' );
        }
        echo '<br><br>';
    }
}

function Encrypt($data){
    return dechex(rand()).'gqlrsdvfjfhds'.decbin($data).'mtdkjsdlsjjhc'.dechex(rand());
}
function Decrypt($e){
    $h=substr($e, strpos($e, 'gqlrsdvfjfhds')+strlen('gqlrsdvfjfhds'),strpos($e,'mtdkjsdlsjjhc')-(strpos($e,'gqlrsdvfjfhds')+strlen('gqlrsdvfjfhds')));
    return bindec($h);
}
?>
<script type="text/javascript">
    var activeAcc=0;
    jQuery(document).ready(function(){
        if(<?php echo $advancedSettings['wpsp_reply_form_position'];?>==0){
            activeAcc=jQuery("#threadAccordion h3").length-1;
        }
        if(<?php echo $advancedSettings['enable_accordion'];?>==1){
            jQuery("#threadAccordion").accordion({
                heightStyle:'content',
                active:activeAcc
            });
        }
    });
</script>