<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
global $wpdb;
$dateflag=FALSE;
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories ORDER BY name" );
$priorities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_priority" );
$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];
$advancedSettings=get_option( 'wpsp_advanced_settings' );

 /*
  * WooCommerce Custom Field
  */
 $woo_products=array();
 if (class_exists( 'WooCommerce' ) && class_exists( 'WPSupportPlusWoocommerce' )) {
     $args     = array( 'post_type' => 'product', 'orderby'=>'title', 'order'=>'ASC' );
     $products = get_posts( $args );
     foreach($products as $prod){
         $woo_products[$prod->ID]=$prod->post_title;
     }
}
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

global $current_user;
$current_user=wp_get_current_user();

$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$total_menu=$wpdb->num_rows;
$generalSettings=get_option('wpsp_general_settings');
/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 20 - suppress owner notification for tickets created by agent
 */
$wpsp_et_create_new_ticket = get_option( 'wpsp_et_create_new_ticket' );
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
 */
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];
?>
<h3><?php _e($advancedSettings['ticket_label_alice'][3],'wp-support-plus-responsive-ticket-system');?></h3>
<?php
 do_action('wpsp_before_user_ticket_form');
?>
<form id="frmCreateNewTicket" name="frmCreateNewTicket">
	<?php 
	$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
	foreach($advancedSettingsFieldOrder['fields_order'] as $field_id)
	{
		if(in_array($field_id,$advancedSettingsFieldOrder['display_fields']))
		{
			if(is_numeric($field_id))
			{
				$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields WHERE id='".$field_id."'" );
				foreach($customFields as $field)
				{?>
					<div id="wpsp_custom_<?php echo $field->id;?>" class="<?php echo ($field->field_categories=='0')?'':'wpsp_conditional_fields';?>" style="<?php echo ($field->field_categories=='0')?'display:block':'display:none';?>">
                                        <?php
					if($field->required)
					{
						switch($field->field_type){
							case '1': ?>
								<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br>	
								<input id="cust<?php echo $field->id;?>" class="wpsp_required" type="text" name="cust<?php echo $field->id;?>" style="width: 95%; margin-top: 10px;"/><br><br>
							<?php
							break;
							case '2': ?>
								<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br>
								<select id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>" class="wpsp_required">
								<option value=""></option>
                                                                <?php 
								if($field->field_options==NULL)
								{
									$field_options=array();
								}
								else
								{
									$field_options=unserialize($field->field_options);
								}
								foreach ($field_options as $field_option_key=>$field_option_value){
									echo '<option value="'.$field_option_value.'">'.$field_option_value.'</option>';
								}
								?>
								</select><br/><br/>
							<?php
							break;
							case '3': ?>
								<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/>
								<?php 
								if($field->field_options==NULL)
								{
									$field_options=array();
								}
								else
								{
									$field_options=unserialize($field->field_options);
								}
								foreach ($field_options as $field_option_key=>$field_option_value){
									echo '<input type="checkbox" name="cust'.$field->id.'[]" class="form-control wpsp_required" value="'.$field_option_value.'"> '.$field_option_value.'<br/>';
								}
								?><br/>
							<?php
							break;
							case '4': ?>
								<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/>
								<?php 
								if($field->field_options==NULL)
								{
									$field_options=array();
								}
								else
								{
									$field_options=unserialize($field->field_options);
								}
								foreach ($field_options as $field_option_key=>$field_option_value){
									echo '<input type="radio" class="form-control" name="cust'.$field->id.'" value="'.$field_option_value.'" required> '.$field_option_value.'<br/>';
								}
								?><br/>
							<?php
							break;
							case '5': ?>
								<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/>	
								<textarea class="wpsp_required" id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>"></textarea><br/><br/>		
							<?php
							break;
                                                        case '6': 
                                                                $dateflag=TRUE;
                                                                ?>
                                                                <span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br> 
                                                                <input id="cust<?php echo $field->id;?>" class="wpsp_required wpsp_datepicker" type="text"  name="cust<?php echo $field->id;?>" style="width: 95%; margin-top: 10px;" placeholder="<?php _e('Click here to select date','wp-support-plus-responsive-ticket-system');?>"/><br><br>
                                                        <?php
                                                        break;
                                                        case '7': if(class_exists( 'WooCommerce' ) && class_exists( 'WPSupportPlusWoocommerce' ) && $woo_products){?>
 								<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/>
                                                                 <select id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>" class="wpsp_required">
                                                                     <option value=""></option>
                                                                     <?php foreach ($woo_products as $key => $value) {?>
                                                                             <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                                                     <?php }?>
                                                                 </select>
 							<?php
                                                                 }
							break;
                                                        default : do_action('wpsp_extra_custom_fields_create_ticket_login_required',$field);
						}
					}
					else
					{
						switch($field->field_type){
							case '1': ?>
								<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br>
								<input id="cust<?php echo $field->id;?>" type="text" name="cust<?php echo $field->id;?>"  style="width: 95%; margin-top: 10px;"/><br><br>
							<?php 
							break;
							case '2':?>
                                                                <span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/>
								<select id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>">
								<option value=""></option>
                                                                <?php 
								if($field->field_options==NULL)
								{
									$field_options=array();
								}
								else
								{
									$field_options=unserialize($field->field_options);
								}
								foreach ($field_options as $field_option_key=>$field_option_value){
									echo '<option value="'.$field_option_value.'">'.$field_option_value.'</option>';
								}
								?>
								</select><br/><br/>
							<?php 
							break;
							case '3': ?>
								<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/>
								<?php 
								if($field->field_options==NULL)
								{
									$field_options=array();
								}
								else
								{
									$field_options=unserialize($field->field_options);
								}
								foreach ($field_options as $field_option_key=>$field_option_value){
									echo '<input type="checkbox" name="cust'.$field->id.'[]" class="form-control" value="'.$field_option_value.'"> '.$field_option_value.'<br/>';
								}
								?><br/>
							<?php
							break;
							case '4': ?>
								<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/>
								<?php 
								if($field->field_options==NULL)
								{
									$field_options=array();
								}
								else
								{
									$field_options=unserialize($field->field_options);
								}
								foreach ($field_options as $field_option_key=>$field_option_value){
									echo '<input type="radio" class="form-control" name="cust'.$field->id.'" value="'.$field_option_value.'"> '.$field_option_value.'<br/>';
								}
								?><br/>
							<?php
							break;
							case '5': ?>
								<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/>
								<textarea id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>"></textarea><br/><br/>
							<?php
							break;
                                                        case '6': 
                                                                $dateflag=TRUE;
                                                                ?>
                                                                <span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br> 
                                                                <input id="cust<?php echo $field->id;?>" class="wpsp_datepicker" type="text"  name="cust<?php echo $field->id;?>" style="width: 95%; margin-top: 10px;" placeholder="<?php _e('Click here to select the date','wp-support-plus-responsive-ticket-system');?>"/><br><br>
                                                        <?php
                                                        break;
                                                        case '7': if(class_exists( 'WooCommerce' ) && class_exists( 'WPSupportPlusWoocommerce' ) && $woo_products){?>
 								<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/>
                                                                 <select id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>" >
                                                                     <option value=""></option>
                                                                     <?php foreach ($woo_products as $key => $value) {?>
                                                                             <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                                                     <?php }?>
                                                                 </select>
 							<?php
                                                                 }
							break;
                                                        default : do_action('wpsp_extra_custom_fields_create_ticket_login_not_required',$field);
						}
					}
                                        ?>
                                        </div>
                                        <?php
				}
			}
			else
			{
				switch($field_id)
				{
					case 'dn':
						if ($_POST['backend']){
                                                    
                                                    do_action('wpsp_create_ticket_as_guestonly_backend');
                                                    
                                                    ?>
                                                        <div id="wpsp_backend_userselect">
							<span class="label label-info wpsp_title_label"><?php _e($advancedSettings['ticket_label_alice'][5],'wp-support-plus-responsive-ticket-system');?></span><br><br>
							<select id="create_ticket_user_type" name="create_ticket_user_type" class="hide_fields_support_plus" onchange="change_user_type();">
								<option value="user"><?php _e('Registered','wp-support-plus-responsive-ticket-system');?></option>
								<option value="guest"><?php _e('Guest','wp-support-plus-responsive-ticket-system');?></option>
							</select>
							<br>
                                                        </div>
							<div id="user_type_user">
                                                            <input style="margin-top: 5px;" type="text" id="create_ticket_as_user" name="create_ticket_as_user" disabled="disabled" value="<?php echo $current_user->display_name;?>" />
                                                            <button type="button" class="btn btn-primary" id="searchUserModal" onclick="getSearchUserForm();"><?php _e('Change User','wp-support-plus-responsive-ticket-system');?></button>
                                                            <br>
							</div>
							<br>
							<div id="user_type_guest" style="display:none">
							<span class="label label-info wpsp_title_label"><?php _e($default_labels['dn'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
							<input type="text" id="create_ticket_guest_user_name" name="create_ticket_guest_user_name" value=""  style="width: 95%; margin-top: 10px;"/><br><br>
							<span class="label label-info wpsp_title_label"><?php _e($default_labels['de'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
							<input type="text" id="create_ticket_guest_user_email" name="create_ticket_guest_user_email"  style="width: 95%; margin-top: 10px;"/><br><br>
							</div>
							<input type="hidden" name='backend' value='1' >
						<?php }
					break;
					case 'de':
					break;
					case 'ds': ?>
						<span class="label label-info wpsp_title_label"><?php _e($default_labels['ds'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
						<input class="wpsp_required" type="text" id="create_ticket_subject" name="create_ticket_subject" value="" /><br><br>
					<?php
					break;
					case 'dd': ?>
                                                <?php if(!$_POST['backend']){?>
                                                    <span class="label label-info wpsp_title_label"><?php _e($default_labels['dd'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
                                                <?php } else { ?>
                                                    <table>
                                                        <tr>
                                                            <td style="padding:10px 10px 10px 0px;">
                                                                <span class="label label-info wpsp_title_label"><?php _e($default_labels['dd'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
                                                            </td>
                                                            <td style="padding:10px 10px 10px 0px;">
                                                                <button type="button" class="btn btn-primary"  id="psmwpsp_canned"  onclick="cannedrep();"><?php _e('Canned Reply','wp-support-plus-responsive-ticket-system');?></button>
                                                            </td>
                                                        <tr>
                                                    </table>
                                                <?php }?>
						<textarea id="create_ticket_body" name="create_ticket_body"></textarea><br><br>
					<?php
					break;
					case 'dc': ?>
						<div>
							<span class="label label-info wpsp_title_label hide_fields_support_plus"><?php _e($default_labels['dc'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
							    <select id="create_ticket_category" name="create_ticket_category" class="hide_fields_support_plus" onchange="cat_wise_custom_field(this)">
								<option value=""></option><?php 
								foreach ($categories as $category){
	                                                            echo '<option value="'.$category->id.'">'.__(stripcslashes($category->name),'wp-support-plus-responsive-ticket-system').'</option>';
								}
								?>
							    </select><br><br>
                                                </div>
					<?php
					break;
					case 'dp': ?>
						<div>
							<span class="label label-info wpsp_title_label hide_fields_support_plus"><?php _e($default_labels['dp'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
							    <select id="create_ticket_priority" name="create_ticket_priority" class="hide_fields_support_plus">
							         <option value="" class="blankpri"></option>	
                                                             <?php 
								foreach ($priorities as $priority){
							            echo '<option value="'.strtolower($priority->name).'" >'.__($priority->name,'wp-support-plus-responsive-ticket-system').'</option>';
								}
								?>
							    </select>
						</div><br>
					<?php
					break;
					case 'da': ?>
						<div>
                                                    <span class="label label-info wpsp_title_label"><?php _e($default_labels['da'],'wp-support-plus-responsive-ticket-system');?></span><br>
                                                    <div class="wpsp_frm_attachment_container">
                                                        <input type="file" id="wpsp_frm_attachment_input_create" class="wpsp_frm_attachment_input">
                                                        <div id="wpsp_frm_attachment_copy_create" class="wpsp_frm_attachment" style="display: none;">
                                                            <span class="wpsp_frm_attachment_name"></span><br>
                                                            <span class="wpsp_frm_attachment_percentage">[0%]</span>
                                                            <span class="wpsp_frm_attachment_remove"></span>
                                                        </div>
                                                        <div id="wpsp_frm_attachment_list_create" class="wpsp_frm_attachment_list"></div>
                                                        <div id="wpsp_frm_attachment_ids_container_create" class="wpsp_frm_attachment_ids_container"></div>
                                                    </div>
						</div><br>
					<?php
					break;
				}
			}
		}
	}
        $showhidepublicoption=apply_filters('wpsp_showhide_ticket_publicprivate_option',true);
        
	if($generalSettings['enable_user_selection_public_private']==1 || $_POST['backend'] && $showhidepublicoption)
	{ ?>
	<input <?php echo ($generalSettings['default_ticket_type']==1)?'checked="checked"':'';?> type="checkbox" id="create_ticket_type" name="create_ticket_type" />	
	<span class="label label-info" style="font-size: 13px;"><?php _e($advancedSettings['ticket_label_alice'][6],'wp-support-plus-responsive-ticket-system');?></span><br><br/>
<?php 	}else
	{?>
		<input type="hidden" id="create_ticket_type" name="create_ticket_type" value="<?php echo $generalSettings['default_ticket_type'];?>">
<?php	}?>
	<?php
	/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
	 * Update 20 - suppress owner notification for tickets created by agent
	 */
        $showhidenotifyowner=apply_filters('wpsp_showhide_ticket_notifyowner_option',true);
	if ( ($wpsp_et_create_new_ticket['enable_success'] && $_POST['backend'] && ( $current_user->has_cap( 'manage_support_plus_ticket' ) || $current_user->has_cap( 'manage_support_plus_agent' ) )) && $showhidenotifyowner ) {
		?>
                <input type="checkbox" id="agent_silent_create" name="agent_silent_create" value="1" />	
		<span class="label label-info" style="font-size: 13px;"><?php _e('Don\'t Notify Owner','wp-support-plus-responsive-ticket-system');?></span><br><br/>	
		<?php
	}
	/* END CLOUGH I.T. SOLUTIONS MODIFICATION
	 */
	?>
	<input type="hidden" name="action" value="createNewTicket">
	<input type="hidden" id="create_ticket_as_user_id" name="user_id" value="<?php echo $current_user->ID;?>">
	<input type="hidden" id="type_user_default" name="type" value="user">
	<input type="hidden" id="guest_name" name="guest_name" value="">
	<input type="hidden" id="guest_email" name="guest_email" value="">
	<input type="submit" id="wpsp_submit" class="btn btn-success" value="<?php _e($advancedSettings['ticket_label_alice'][7],'wp-support-plus-responsive-ticket-system');?>">
	<input type="button" id="wpsp_reset" class="btn btn-success" value="<?php _e('Reset Form','wp-support-plus-responsive-ticket-system');?>" onclick="resetForm();" />
</form>

<?php if ($_POST['backend']){?>
<div  id="wsp_change_user_modal" style="display:none">
  <div id="modal-dialog">
    <div id="modal-content">
      <div id="modal-header">
          <button type="button" class="close" onclick="closep();">&times;</button>
        <h4 class="title" id="myModalLabel"><?php _e('Select User','wp-support-plus-responsive-ticket-system');?></h4>
      </div>
      <div id="body">
        <?php include( WCE_PLUGIN_DIR.'includes/admin/selectRegisteredUser.php' );?>
      </div>
      <div id="footer">
          <button type="button" class="btn btn-default" onclick="closep();"><?php _e('Close','wp-support-plus-responsive-ticket-system');?></button>
      </div>
    </div>
  </div>
</div>

<div id="psmwpsp" style="display: none">
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
                    </tr>
                    <?php 
                    $wpsp_canned_id=0;
                    foreach($canned as $can){ ?>
                        <tr id="mytr" onclick="replyonclick(<?php echo $can->id;?>)">
                            <td style="width: 50px;"><?php echo ++$wpsp_canned_id;?></td>
                            <td><?php echo stripcslashes($can->title);?></td>
                            <td style="display:none;" id="reply<?php echo $can->id; ?>"><?php echo stripcslashes($can->reply);?></td>
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

<script>
var currentIndex=10;
jQuery(document).ready(function(){
    jQuery("#psmwpsp_canned").click(function(){
        jQuery("#psmwpsp").show();
    });
    jQuery("#searchUserModal").click(function(){
        jQuery("#wsp_change_user_modal").show();
    });
});

function replyonclick(cid){
    var value = CKEDITOR.instances['create_ticket_body'].getData();
    var x=document.getElementById("reply"+cid);
    CKEDITOR.instances["create_ticket_body"].setData(value+x.innerHTML); 
    jQuery("#psmwpsp").hide();
}

function closepopup(){
    jQuery('#psmwpsp').hide();
}

function closep(){
    jQuery('#wsp_change_user_modal').hide();
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
    } else {
        jQuery('#wpsp_canned_More').prop('disabled', false); 
    }
    if(currentIndex<=10){
        jQuery('#wpsp_canned_Less').prop('disabled', true); 
    } else {
        jQuery('#wpsp_canned_Less').prop('disabled', false); 
    }
}
</script>
<?php 
}

if($dateflag){
?>
<script type="text/javascript">
jQuery('.wpsp_datepicker').datepicker({
    dateFormat : '<?php echo $advancedSettings['datecustfield'];?>',
    changeMonth: true,
    changeYear: true,
    yearRange: '1950:2050',                      
    defaultDate:'+0',                      
    onSelect: function (selected) {
        var dt1 = new Date(selected);
        dt1.setDate(dt1.getDate());
        jQuery(this).datepicker(dt1);
    }
});
</script>
<?php }?>

<script>
    var wpsp_attachment_counter=0;
    var wpsp_attachment_share_lock=false;
</script>
<?php if(in_array('da',$advancedSettingsFieldOrder['display_fields'])){?>
<script>
    jQuery('#wpsp_frm_attachment_input_create').change(function() {
        wpspUploadAttachment(this.files,'create');
    });
</script>
<?php }?>

<script>
    function wpsp_create_ticket_frm_extra_validation(){
        var flag=true;
        <?php do_action('wpsp_create_ticket_frm_extra_validation');?>
        return flag;
    }
</script>

<script>
 function resetForm(){
     if(confirm(display_ticket_data.reset_form)){
         jQuery('#frmCreateNewTicket')[0].reset();
         for (instance in CKEDITOR.instances){
                 CKEDITOR.instances[instance].setData(" ");
         }
     }
 }
</script>