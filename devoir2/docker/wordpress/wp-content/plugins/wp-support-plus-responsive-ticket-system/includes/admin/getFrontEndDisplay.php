<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
$FrontEndDisplaySettings = get_option('wpsp_front_end_display_settings');
?>
<br>
<span class="label label-info wpsp_title_label"><?php _e('FAQ', 'wp-support-plus-responsive-ticket-system'); ?></span><br><br>
<input type="radio" name="wpsp_faq_display_setting" value="1" <?php echo ($FrontEndDisplaySettings['wpsp_faq_display_setting'] == 1) ? 'checked="checked"' : ''; ?>> <?php _e('Enable', 'wp-support-plus-responsive-ticket-system'); ?>
<br>
<input type="radio" name="wpsp_faq_display_setting" value="0" <?php echo ($FrontEndDisplaySettings['wpsp_faq_display_setting'] == 0) ? 'checked="checked"' : ''; ?>> <?php _e('Disable', 'wp-support-plus-responsive-ticket-system'); ?>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Open Ticket Action Buttons', 'wp-support-plus-responsive-ticket-system'); ?></span><?php _e('(Applicable Only Frontend)', 'wp-support-plus-responsive-ticket-system'); ?><br><br>
<table id="front_end_display_table">
    <tr>
        <th><?php _e('Element','wp-support-plus-responsive-ticket-system');?></th>
        <th><?php _e('Visibility','wp-support-plus-responsive-ticket-system');?></th>
        <th><?php _e('Label','wp-support-plus-responsive-ticket-system');?></th>
        <th><?php _e('Font-Color','wp-support-plus-responsive-ticket-system');?></th>
        <th><?php _e('Background-Color','wp-support-plus-responsive-ticket-system');?></th>
    </tr>
    <tr>
        <td><?php _e('Back To Ticket', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideBackToTicket'] == 1) ? 'checked="checked"' : ''; ?> type="checkbox" id="hideBackToTicket" ></td>
        <td></td>
        <td><input type="text" id="wpsp_btt_fc" value="<?php _e($FrontEndDisplaySettings['wpsp_btt_fc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
        <td><input type="text" id="wpsp_btt_bc" value="<?php _e($FrontEndDisplaySettings['wpsp_btt_bc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
    </tr>
    <tr>
        <td><?php _e('Change Status', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideChangeStatus'] == 1) ? 'checked="checked"' : ''; ?> type="checkbox" id="hideChangeStatus" ></td>
        <td><input type="text" name="wpspFrontEndDisplayAlice" value="<?php _e($FrontEndDisplaySettings['front_end_display_alice'][1],'wp-support-plus-responsive-ticket-system');?>"></td>
        <td><input type="text" id="wpsp_cs_fc" value="<?php _e($FrontEndDisplaySettings['wpsp_cs_fc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
        <td><input type="text" id="wpsp_cs_bc" value="<?php _e($FrontEndDisplaySettings['wpsp_cs_bc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
    </tr>
    <tr>
        <td><?php _e('Close Ticket', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input disabled="disabled" <?php echo ($FrontEndDisplaySettings['wpsp_hideCloseTicket'] == 1) ? 'checked="checked"' : ''; ?> type="checkbox" id="hideCloseTicket" ></td>
        <td></td>
        <td><input type="text" id="wpsp_ct_fc" value="<?php _e($FrontEndDisplaySettings['wpsp_ct_fc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
        <td><input type="text" id="wpsp_ct_bc" value="<?php _e($FrontEndDisplaySettings['wpsp_ct_bc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
    </tr>
    <tr>
        <td><?php _e('Canned Reply (shown to agents only)', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideCannedReply'] == 1) ? 'checked="checked"' : ''; ?> type="checkbox" id="hideCannedReply" ></td>
        <td><input type="text" name="wpspFrontEndDisplayAlice" value="<?php _e($FrontEndDisplaySettings['front_end_display_alice'][2],'wp-support-plus-responsive-ticket-system');?>"></td>
        <td><input type="text" id="wpsp_cr_fc" value="<?php _e($FrontEndDisplaySettings['wpsp_cr_fc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
        <td><input type="text" id="wpsp_cr_bc" value="<?php _e($FrontEndDisplaySettings['wpsp_cr_bc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
    </tr>
    <tr>
        <td><?php _e('+More Action', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input disabled="disabled" <?php echo ($FrontEndDisplaySettings['wpsp_hideMoreAction'] == 1) ? 'checked="checked"' : ''; ?> type="checkbox" id="hideMoreAction" ></td>
        <td></td>
        <td><input type="text" id="wpsp_ma_fc" value="<?php _e($FrontEndDisplaySettings['wpsp_ma_fc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
        <td><input type="text" id="wpsp_ma_bc" value="<?php _e($FrontEndDisplaySettings['wpsp_ma_bc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
    </tr>
    <tr>
        <td><?php _e('Assign Agent (shown to agents only)', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideAssignAgent'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideAssignAgent" ></td>
        <td><input type="text" name="wpspFrontEndDisplayAlice" value="<?php _e($FrontEndDisplaySettings['front_end_display_alice'][3],'wp-support-plus-responsive-ticket-system');?>"></td>
       <td><input type="text" id="wpsp_aa_fc" value="<?php _e($FrontEndDisplaySettings['wpsp_aa_fc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
        <td><input type="text" id="wpsp_aa_bc" value="<?php _e($FrontEndDisplaySettings['wpsp_aa_bc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
    </tr>
    <tr>
        <td><?php _e('Delete Ticket (shown to agents only)', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideDeleteTicket'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideDeleteTicket" ></td>
        <td><input type="text" name="wpspFrontEndDisplayAlice" value="<?php _e($FrontEndDisplaySettings['front_end_display_alice'][4],'wp-support-plus-responsive-ticket-system');?>"></td>
        <td><input type="text" id="wpsp_dt_fc" value="<?php _e($FrontEndDisplaySettings['wpsp_dt_fc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
        <td><input type="text" id="wpsp_dt_bc" value="<?php _e($FrontEndDisplaySettings['wpsp_dt_bc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
    </tr>
</table>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Reply Form Fields', 'wp-support-plus-responsive-ticket-system'); ?></span><?php _e('(Applicable Frontend and Backend Both)', 'wp-support-plus-responsive-ticket-system'); ?><br><br>
<table id="front_end_display_table">
    <tr>
        <th><?php _e('Element','wp-support-plus-responsive-ticket-system');?></th>
        <th><?php _e('Visibility','wp-support-plus-responsive-ticket-system');?></th>
        <th><?php _e('Label','wp-support-plus-responsive-ticket-system');?></th>
        <th><?php _e('Font-Color','wp-support-plus-responsive-ticket-system');?></th>
        <th><?php _e('Background-Color','wp-support-plus-responsive-ticket-system');?></th>
    </tr>
    <tr>
        <td><?php _e('CC', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideCC'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideCC" ></td>
        <td colspan="3"><input type="text" name="wpspFrontEndDisplayAlice" value="<?php _e($FrontEndDisplaySettings['front_end_display_alice'][5],'wp-support-plus-responsive-ticket-system');?>"></td>
       
    </tr>
    <tr>
        <td><?php _e('BCC', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideBCC'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideBCC" ></td>
        <td td colspan="3"><input type="text" name="wpspFrontEndDisplayAlice" value="<?php _e($FrontEndDisplaySettings['front_end_display_alice'][6],'wp-support-plus-responsive-ticket-system');?>"></td>
        
    </tr>
    <tr>
        <td><?php _e('Status', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideStatus'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideStatus" ></td>
        <td td colspan="3"><input type="text" name="wpspFrontEndDisplayAlice" value="<?php _e($FrontEndDisplaySettings['front_end_display_alice'][7],'wp-support-plus-responsive-ticket-system');?>"></td>
        
    </tr>
    <tr>
        <td><?php _e('Category', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideCategory'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideCategory" ></td>
        <td td colspan="3"><input type="text" name="wpspFrontEndDisplayAlice" value="<?php _e($FrontEndDisplaySettings['front_end_display_alice'][8],'wp-support-plus-responsive-ticket-system');?>"></td>
       
    </tr>
    <tr>
        <td><?php _e('Priority', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hidePriority'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hidePriority" ></td>
        <td td colspan="3"><input type="text" name="wpspFrontEndDisplayAlice" value="<?php _e($FrontEndDisplaySettings['front_end_display_alice'][9],'wp-support-plus-responsive-ticket-system');?>"></td>
        
    </tr>
    <tr>
        <td><?php _e('Attachments', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideAttachments'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideAttachments" ></td>
        <td td colspan="3"><input type="text" name="wpspFrontEndDisplayAlice" value="<?php _e($FrontEndDisplaySettings['front_end_display_alice'][10],'wp-support-plus-responsive-ticket-system');?>"></td>
       
    </tr>
    <tr>
        <td><?php _e('Add Notes (shown to agents only)', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideAddNotes'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideAddNotes" ></td>
        <td><input type="text" name="wpspFrontEndDisplayAlice" value="<?php _e($FrontEndDisplaySettings['front_end_display_alice'][11],'wp-support-plus-responsive-ticket-system');?>"></td>
        <td><input type="text" id="wpsp_an_fc" value="<?php _e($FrontEndDisplaySettings['wpsp_an_fc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
        <td><input type="text" id="wpsp_an_bc" value="<?php _e($FrontEndDisplaySettings['wpsp_an_bc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
    </tr>
    <tr>
        <td><?php _e('Submit Reply', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input disabled="disabled" <?php echo ($FrontEndDisplaySettings['wpsp_hideSubmitReply'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideSubmitReply" ></td>
        <td><input type="text" name="wpspFrontEndDisplayAlice" value="<?php _e($FrontEndDisplaySettings['front_end_display_alice'][12],'wp-support-plus-responsive-ticket-system');?>"></td>
        <td><input type="text" id="wpsp_sr_fc" value="<?php _e($FrontEndDisplaySettings['wpsp_sr_fc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
        <td><input type="text" id="wpsp_sr_bc" value="<?php _e($FrontEndDisplaySettings['wpsp_sr_bc'],'wp-support-plus-responsive-ticket-system');?>" class="wp-support-plus-color-picker" ></td>
    </tr>
    <tr>
        <td><?php _e('Change Raised By (shown to agents only)', 'wp-support-plus-responsive'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_ChangeRaisedBy'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideChangeRaisedBy" ></td>
        <td><input type="text" name="wpspFrontEndDisplayAlice" value="<?php _e($FrontEndDisplaySettings['front_end_display_alice'][13],'wp-support-plus-responsive');?>"></td>
        <td><input type="text" id="wpsp_cb_fc" value="<?php _e($FrontEndDisplaySettings['wpsp_cb_fc'],'wp-support-plus-responsive');?>" class="wp-support-plus-color-picker" ></td>
        <td><input type="text" id="wpsp_cb_bc" value="<?php _e($FrontEndDisplaySettings['wpsp_cb_bc'],'wp-support-plus-responsive');?>" class="wp-support-plus-color-picker" ></td>
    </tr>
</table>
<hr>

<span class="label label-info wpsp_title_label"><?php _e('Ticket Threads', 'wp-support-plus-responsive-ticket-system'); ?></span><?php _e('( Applicable Only Frontend )', 'wp-support-plus-responsive-ticket-system'); ?><br><br>
<table id="front_end_display_table">
    <tr>
        <th><?php _e('Element','wp-support-plus-responsive-ticket-system');?></th>
        <th><?php _e('Visibility','wp-support-plus-responsive-ticket-system');?></th>        
    </tr>
    <tr>
        <td><?php _e('Email', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideEmail'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideEmail" ></td>       
    </tr>
    <tr>
        <td><?php _e('Days/Months/Year Ago', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideDaysMonthsYearAgo'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideDaysMonthsYearAgo" ></td>        
    </tr>
    <tr>
        <td><?php _e('Exact Date', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideExactDate'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideExactDate" ></td>   
    </tr>
    <tr>
        <td><?php _e('Exact Time', 'wp-support-plus-responsive-ticket-system'); ?></td>
        <td><input <?php echo ($FrontEndDisplaySettings['wpsp_hideExactTime'] == 1) ? 'checked="checked"' : ''; ?>type="checkbox" id="hideExactTime" ></td>   
    </tr>
</table>
<hr>

<button class="btn btn-success" id="setFrontEndDisplay" onclick="setFrontEndDisplay();"><?php _e('Save Settings', 'wp-support-plus-responsive-ticket-system'); ?></button>