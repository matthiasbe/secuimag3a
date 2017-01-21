<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;
global $current_user;
$current_user=wp_get_current_user();

$sql="select id,signature,skype_id,skype_chat_availability,skype_call_availability FROM {$wpdb->prefix}wpsp_agent_settings WHERE  agent_id=".$current_user->ID;
$currentAgent = $wpdb->get_row( $sql );

if(!$wpdb->num_rows){
	$values=array(
			'agent_id'=>$current_user->ID,
			'signature'=>'',
			'skype_id'=>'',
			'skype_chat_availability'=>0,
			'skype_call_availability'=>0
	);
	$wpdb->insert($wpdb->prefix.'wpsp_agent_settings',$values);
	$sql="select id,signature,skype_id,skype_chat_availability,skype_call_availability FROM {$wpdb->prefix}wpsp_agent_settings WHERE  agent_id=".$current_user->ID;
	$currentAgent = $wpdb->get_row( $sql );
}
?>
<br>
<span class="label label-info wpsp_title_label"><?php _e('Signature','wp-support-plus-responsive-ticket-system');?></span><br>
<textarea id="agentSignature" ><?php echo stripcslashes(htmlspecialchars_decode($currentAgent->signature,ENT_QUOTES));?></textarea><br>
<hr>
<span class="label label-info wpsp_title_label"><?php _e('Skype Settings','wp-support-plus-responsive-ticket-system');?></span><br><br>
<small><code>*</code><?php _e(' You\'ll need to update your Skype setting to allow incoming calls or messages from people who are not on your contact list.','wp-support-plus-responsive-ticket-system');?></small>
<table id="tblChangeStatusContainer">
  <tr>
    <td><?php _e('Skype ID','wp-support-plus-responsive-ticket-system');?></td>
    <td>:</td>
    <td><input type="text" id="txtAgentSkypeId" value="<?php echo $currentAgent->skype_id;?>"></td>
  </tr>
  <tr>
    <td><?php _e('Available to Chat?','wp-support-plus-responsive-ticket-system');?></td>
    <td>:</td>
    <td>
    	<input type="radio" name="rdbAvailableChat" value="1" <?php echo ($currentAgent->skype_chat_availability==1)?'checked="checked"':''; ?>> <?php _e('Yes','wp-support-plus-responsive-ticket-system');?> 
    	<input type="radio" class="secondRadioButton" name="rdbAvailableChat" value="0" <?php echo ($currentAgent->skype_chat_availability==0)?'checked="checked"':''; ?>> <?php _e('No','wp-support-plus-responsive-ticket-system');?>
    </td>
  </tr>
  <tr>
    <td><?php _e('Available to Call?','wp-support-plus-responsive-ticket-system');?></td>
    <td>:</td>
    <td>
    	<input type="radio" name="rdbAvailableCall" value="1" <?php echo ($currentAgent->skype_call_availability==1)?'checked="checked"':''; ?>> <?php _e('Yes','wp-support-plus-responsive-ticket-system');?> 
    	<input type="radio" class="secondRadioButton" name="rdbAvailableCall" value="0" <?php echo ($currentAgent->skype_call_availability==0)?'checked="checked"':''; ?>> <?php _e('No','wp-support-plus-responsive-ticket-system');?>
    </td>
  </tr>
</table>
<hr>
<button id="saveAgentSettingsBtn" class="btn btn-success" onclick="setSignature(<?php echo $currentAgent->id;?>);"><?php _e('Save Settings','wp-support-plus-responsive-ticket-system');?></button>
