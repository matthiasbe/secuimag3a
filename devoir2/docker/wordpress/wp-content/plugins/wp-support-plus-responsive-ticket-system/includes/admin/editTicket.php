<?php
global $wpdb;
$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];
$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];
?>
<h3><?php _e($advancedSettings['ticket_label_alice'][8],'wp-support-plus-responsive-ticket-system')?></h3><br>
<?php 
global $wpdb;
$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$ticket=$wpdb->get_row("select * from {$wpdb->prefix}wpsp_ticket where id=".$_POST['ticket_id']);
?>
<form id="editcustfield" name="editcustfield" onsubmit="setEditCustomField(event,this,<?php echo $_POST['ticket_id']?>)">
	<span class="label label-info wpsp_title_label"><?php _e($default_labels['ds'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
	<input class="wpsp_required" type="text" id="subject" name="subject" value="<?php echo $ticket->subject;?>" maxlength="80" style="width: 95%; margin-top: 10px;"/><br><br>
	<?php $i=0;
	foreach ($customFields as $field){
                $custom="cust".$field->id;
                
                $ass_cat=  explode(',', $field->field_categories);
                if(!(array_search($ticket->cat_id, $ass_cat)>-1)  && $field->field_categories!='0'){
                    continue;
                }
		if($field->required)
		{
			switch($field->field_type){
				case '1': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br>
					<input class="wpsp_required" type="text" name="cust<?php echo $field->id;?>" value="<?php echo $ticket->$custom?>" maxlength="80" style="width: 95%; margin-top: 10px;"/><br><br>
				<?php
				break;
				case '2': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br>
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
						if($ticket->$custom==$field_option_value)
						{
							$selected='selected';
						}
						else
						{
							$selected='';
						}
						echo '<option value="'.$field_option_value.'" '.$selected.'>'.$field_option_value.'</option>';
					}
					?>
					</select><br/><br/>
				<?php
				break;
				case '3': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/><br>
					<?php 
					if($field->field_options==NULL)
					{
						$field_options=array();
					}
					else
					{
						$field_options=unserialize($field->field_options);
					}
					$check_values=explode(",",$ticket->$custom);
					foreach ($field_options as $field_option_key=>$field_option_value){
						if(in_array($field_option_value,$check_values))
						{
							$checked="checked";
						}
						else
						{
							$checked="";
						}
						echo '<input type="checkbox" name="cust'.$field->id.'[]" class="form-control wpsp_required" value="'.$field_option_value.'" '.$checked.'> '.$field_option_value.'<br/>';
					}
					?><br/>
				<?php
				break;
				case '4': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/><br>
					<div id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>">
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
						if($ticket->$custom==$field_option_value)
						{
							$checked='checked';
						}
						else
						{
							$checked='';
						}
						echo '<input type="radio" class="form-control" name="cust'.$field->id.'" value="'.$field_option_value.'" '.$checked.' required> '.$field_option_value.'<br/>';
					}
					?></div><br/>
				<?php
				break;
				case '5': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/><br/>
					<textarea class="wpsp_required" id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>"><?php echo $ticket->$custom?></textarea><br/><br/>
				<?php
				break;
                                case '6': ?>
                                        <span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br> 
                                        <input class="wpsp_required wpsp_datepicker" type="text"  name="cust<?php echo $field->id;?>" style="width: 95%; margin-top: 10px;" value="<?php echo $ticket->$custom?>"/><br><br>
                                <?php
                                break;
                                default :do_action('wpsp_extra_customfield_edit_indivisualticket_form_required',$field,$ticket);
			}
		}
		else
		{
			switch($field->field_type){
				case '1': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br>
					<input type="text" name="cust<?php echo $field->id;?>" value="<?php echo $ticket->$custom?>" maxlength="80" style="width: 95%; margin-top: 10px;"/><br><br>
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
						if($ticket->$custom==$field_option_value)
						{
							$selected='selected';
						}
						else
						{
							$selected='';
						}
						echo '<option value="'.$field_option_value.'" '.$selected.'>'.$field_option_value.'</option>';
					}
					?>
					</select><br/><br/>
				<?php 
				break;
				case '3': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/><br>
					<?php 
					if($field->field_options==NULL)
					{
						$field_options=array();
					}
					else
					{
						$field_options=unserialize($field->field_options);
					}
					$check_values=explode(",",$ticket->$custom);
					foreach ($field_options as $field_option_key=>$field_option_value){
						if(in_array($field_option_value,$check_values))
						{
							$checked="checked";
						}
						else
						{
							$checked="";
						}
						echo '<input type="checkbox" name="cust'.$field->id.'[]" class="form-control" value="'.$field_option_value.'" '.$checked.'> '.$field_option_value.'<br/>';
					}
					?><br/>
				<?php
				break;
				case '4': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/><br>
					<div id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>">
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
						if($ticket->$custom==$field_option_value)
						{
							$checked='checked';
						}
						else
						{
							$checked='';
						}
						echo '<input type="radio" class="form-control" name="cust'.$field->id.'" value="'.$field_option_value.'" '.$checked.'> '.$field_option_value.'<br/>';
					}
					?></div><br/>
				<?php
				break;
				case '5': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/><br/>
					<textarea id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>"><?php echo $ticket->$custom?></textarea><br/><br/>
				<?php
				break;
                                case '6': ?>
                                        <span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br> 
                                        <input class="wpsp_datepicker" type="text"  name="cust<?php echo $field->id;?>" style="width: 95%; margin-top: 10px;" value="<?php echo $ticket->$custom?>"/><br><br>
                                <?php
                                break;
                                default :do_action('wpsp_extra_customfield_edit_indivisualticket_form_notrequired',$field,$ticket);
			}
		}
	}
	?>
	<br>
        <input type="hidden" name="action" value="setEditCustomField">
        <input type="hidden" name="ticket_id" value="<?php echo $_POST['ticket_id'];?>">
        <input type="submit" id="wpsp_submit" class="btn btn-success" value="<?php _e($advancedSettings['ticket_label_alice'][7],'wp-support-plus-responsive');?>">
</form>
<script>
jQuery(document).ready(function() {
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
});
</script>
    
<script>
function exatraCustomFieldValidations(){
    var flag=true;
    <?php do_action('wpsp_extra_custom_fields_validations');?>
     return flag;
}
</script>