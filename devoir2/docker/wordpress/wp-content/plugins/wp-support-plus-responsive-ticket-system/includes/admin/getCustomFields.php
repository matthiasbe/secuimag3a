<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$total_menu=$wpdb->num_rows;
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories" );
?>
<div id="catDisplayTableContainer" class="table-responsive">
	<table class="table table-striped">
		<tr>
			<th><?php _e('Label','wp-support-plus-responsive-ticket-system');?></th>
			<th><?php _e('Required','wp-support-plus-responsive-ticket-system');?></th>
                        <th><?php _e('Categories','wp-support-plus-responsive-ticket-system');?></th>
			<th><?php _e('Action','wp-support-plus-responsive-ticket-system');?></th>
		</tr>
		<?php foreach ($customFields as $field){
                        $field_options_string="";
			if($field->field_options==NULL)
			{
				$field_options=array();
			}
			else
			{
				$field_options=unserialize($field->field_options);
				$count=1;
				foreach($field_options as $field_option_key=>$field_option_value){
					$count++;
					if($count<=count($field_options))
					{
						$field_options_string.=$field_option_value."<br>";
					}
					else
					{
						$field_options_string.=$field_option_value;
					}
				}
			}
                        // getting categories Name For Custom Name
                        $categories_Name=array();
                        $cat_ids=  explode(',',$field->field_categories);
                        foreach($cat_ids as $cat_id){
                            $sql="select name from {$wpdb->prefix}wpsp_catagories where id=$cat_id";
                            $categories_Name[]=$wpdb->get_var($sql);                           
                        }                      
                        $categories_Name=  implode(',', $categories_Name);
                        if(empty($categories_Name)){
                            $categories_Name='None';
                        }
                        else{
                            $categories_Name=$categories_Name;
                        }
                        ?>
			<tr>
                            <td><?php echo $field->label;?></td>
                            <td><?php $field->required?_e('Required','wp-support-plus-responsive-ticket-system'):_e('Optional','wp-support-plus-responsive-ticket-system');?></td>
                            <td><?php echo $categories_Name;?> </td>
                            <td>
                                <img alt="Edit" onclick="editCustomField(<?php echo $field->id;?>,'<?php echo $field->label;?>','<?php echo $field->required;?>','<?php echo $field->field_type;?>','<?php echo $field_options_string;?>','<?php echo $field->field_categories; ?>','<?php echo $field->isVarFeild;?>');" class="catEdit" title="Edit" src="<?php echo WCE_PLUGIN_URL.'asset/images/edit.png';?>" />
                                <img alt="Edit" onclick="deleteCustomField(<?php echo $field->id;?>,'<?php echo $field->label;?>');" class="catDelete" title="Delete" src="<?php echo WCE_PLUGIN_URL.'asset/images/delete.png';?>" />
                            </td>
			</tr>
		<?php } ?>
	</table>
	<?php if(!$total_menu){?>
		<div style="width: 100%;text-align: center;"><?php _e('No Custom Fields Found','wp-support-plus-responsive-ticket-system');?></div>
		<hr>
	<?php } ?>
</div>
<div id="createCustFieldContainer">
	<input id="newCustFieldName" class="form-control" type="text" placeholder="<?php _e('Enter New Field Label','wp-support-plus-responsive-ticket-system');?>" >
	<input type="checkbox" class="form-control" id="newCustRequired"><label class="required_label"><?php _e('Required','wp-support-plus-responsive-ticket-system');?></label><br/>
        <input type="checkbox" class="form-control" id="newisVarFeild"><label class="required_label"><?php _e('Variable Feild','wp-support-plus-responsive');?></label>
        <small><i>(<?php _e('If you set custom field as variable field, it will not show on create ticket form, but can be edited by support team for their reference regarding the ticket.','wp-support-plus-responsive-ticket-system');?>)</i></small><br>
	<?php _e('Field Type','wp-support-plus-responsive-ticket-system');?>:
	<select id="newFieldType" onchange="select_field_type_options()">
		<option value="1"><?php _e('Text','wp-support-plus-responsive-ticket-system');?></option>
		<option value="2"><?php _e('Drop Down','wp-support-plus-responsive-ticket-system');?></option>
		<option value="3"><?php _e('Checkbox','wp-support-plus-responsive-ticket-system');?></option>
		<option value="4"><?php _e('Radio Button','wp-support-plus-responsive-ticket-system');?></option>
		<option value="5"><?php _e('Textarea','wp-support-plus-responsive-ticket-system');?></option>
                <option value="6"><?php _e('Date','wp-support-plus-responsive-ticket-system');?></option>
                <?php do_action('wpsp_extra_custom_field_create_form')?>
        <?php if(class_exists( 'WooCommerce' ) && class_exists( 'WPSupportPlusWoocommerce' )) {?>
                <option value="7"><?php _e('Woocommerce Product','wp-support-plus-responsive');?></option>                
        <?php }?>
	</select><br/><br/>
        <span><?php _e('Assign Categories','wp-support-plus-responsive-ticket-system');?></span>
        <select id="field_categories" name="field_categories" size="4" multiple="multiple">
            <?php 
		foreach ($categories as $category){
			echo '<option value="'.$category->id.'">'.$category->name.'</option>';
		}
		?>
	</select>
        <span><small><i>(<?php _e('This custom field will only visible when one of the selected category is choosen in create ticket form. If you want to show this all the time, please leave this empty.','wp-support-plus-responsive');?>)</i></small></span>
        <br/><br/>
	<div id="field_options" style="display:none">
	<?php _e('Field Options','wp-support-plus-responsive-ticket-system');?>:
	<textarea id="newFieldOptions" placeholder="value1
				value2"></textarea><br/>
	<small><code>*</code><?php _e('Please enter the one option per line, option key and value should be entered as shown in the field','wp-support-plus-responsive-ticket-system');?></small><br/>
	</div>
	<button class="btn btn-success" onclick="createNewCustomField();"><?php _e('Create New Field','wp-support-plus-responsive-ticket-system');?></button>
</div>
<div id="editCustFieldContainer">
	<input type="hidden" id="editCustFieldID" value="">
	<input id="editCustFieldName" class="form-control" type="text" >
	<input type="checkbox" class="form-control" id="editCustRequired"><label class="required_label"><?php _e('Required','wp-support-plus-responsive-ticket-system');?></label><br/>
        <input type="checkbox" class="form-control" id="editisVarFeild"><label class="required_label"><?php _e('Variable Feild','wp-support-plus-responsive');?></label><br/>
	<?php _e('Field Type','wp-support-plus-responsive-ticket-system');?>:
	<select id="editFieldType" onchange="select_field_type_options_edit()">
		<option value="1"><?php _e('Text','wp-support-plus-responsive-ticket-system');?></option>
		<option value="2"><?php _e('Drop Down','wp-support-plus-responsive-ticket-system');?></option>
		<option value="3"><?php _e('Checkbox','wp-support-plus-responsive-ticket-system');?></option>
		<option value="4"><?php _e('Radio Button','wp-support-plus-responsive-ticket-system');?></option>
		<option value="5"><?php _e('Textarea','wp-support-plus-responsive-ticket-system');?></option>
                <option value="6"><?php _e('Date','wp-support-plus-responsive-ticket-system');?></option>
                <?php do_action('wpsp_extra_custom_field_edit_form')?>
        <?php if(class_exists( 'WooCommerce' ) && class_exists( 'WPSupportPlusWoocommerce' )) {?>
                <option value="7"><?php _e('Woocommerce Product','wp-support-plus-responsive');?></option>                
        <?php }?>
	</select><br/><br/>
        <span><?php _e('Assign Categories','wp-support-plus-responsive-ticket-system');?></span>
        <select id="field_categories_update" size="4" multiple="multiple">
            <?php 
            foreach ($categories as $category){
                echo '<option value="'.$category->id.'"> '.$category->name.'</option>';
            }
            ?>
	</select><br/><br/>
	<div id="edit_field_options" style="display:none">
	<?php _e('Field Options','wp-support-plus-responsive-ticket-system');?>:
	<textarea id="editFieldOptions" placeholder="key=>value
		key2=>value2"></textarea><br/>
	<small><code>*</code><?php _e('Please enter the one option per line, option key and value should be entered as shown in the field','wp-support-plus-responsive-ticket-system');?></small><br/>
	</div>
	<button onclick="updateCustomField();" class="btn btn-success"><?php _e('Update','wp-support-plus-responsive-ticket-system');?></button>
</div>


<script>
    function updateCreateNewCustomField(data){
        <?php do_action('wpsp_update_create_customfield_data_js');?>
        return data;
    }
    
    function updateEditCustomField(data){
        <?php do_action('wpsp_update_editcustomfield_data_js');?>
        return data;
    }
    
    function updateCustomField(){
	if(jQuery('#editCustFieldName').val().trim()!=''){
		if((jQuery('#editFieldType').val()=='2' && jQuery('#editFieldOptions').val().trim()!='') || (jQuery('#editFieldType').val()=='3' && jQuery('#editFieldOptions').val().trim()!='') || (jQuery('#editFieldType').val()=='4' && jQuery('#editFieldOptions').val().trim()!='') || (jQuery('#editFieldType').val()=='1') || (jQuery('#editFieldType').val()=='5')  || (jQuery('#editFieldType').val()=='6') <?php do_action('wpsp_update_validate_extra_custom_fields',true)?> ) {
			jQuery('#settingsCustomFields .wait').show();
			jQuery('#settingsCustomFields .settingsCustomFieldsContainer').hide();
			var required=0;
                        var isVariableFeild=0;
			if(jQuery('#editCustRequired').attr('checked'))
			{
				required=1;
			}
                        if(jQuery('#editisVarFeild').attr('checked'))
			{
				isVariableFeild=1;
			}
			var data = {
				'action': 'updateCustomField',
				'field_id': jQuery('#editCustFieldID').val(),
				'label':jQuery('#editCustFieldName').val(),
				'required':required,
				'field_type':jQuery('#editFieldType').val(),
				'field_options':jQuery('#editFieldOptions').val(),
                                'field_categories_update':jQuery('#field_categories_update').val(),
                                'isVarFeild':isVariableFeild
			};
                        data=updateEditCustomField(data);
			jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
				getCustomFields();
			});
		}
		else
		{
			alert(display_ticket_data.insert_field_label);
			jQuery('#editFieldOptions').val('');
			jQuery('#editFieldOptions').focus();
		}
	}
	else{
		alert(display_ticket_data.insert_field_label);
		jQuery('#editCustFieldName').val('');
		jQuery('#editCustFieldName').focus();
	}
}
</script>
    
