<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$faqcategories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_faq_catagories" );
$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];
?>
<div id="faqCatDisplayTableContainer" class="table-responsive">
	<table class="table table-striped">
		<tr>
			<th><?php _e($default_labels['dc'].' Name','wp-support-plus-responsive-ticket-system');?></th>
			<th><?php _e('Action','wp-support-plus-responsive-ticket-system');?></th>
		</tr>
		<?php foreach ($faqcategories as $faqcategory){?>
			<tr>
				<td><?php _e($faqcategory->name,'wp-support-plus-responsive-ticket-system');?></td>
				<td>
					<img alt="Edit" onclick="editFaqCategory(<?php echo $faqcategory->id;?>,'<?php echo $faqcategory->name;?>');" class="catEdit" title="Edit" src="<?php echo WCE_PLUGIN_URL.'asset/images/edit.png';?>" />
					<?php if($faqcategory->id!=1){?>
                                        <img alt="Delete" onclick="deleteFaqCategory(<?php echo $faqcategory->id;?>,'<?php echo $faqcategory->name;?>');" class="catDelete" title="Delete" src="<?php echo WCE_PLUGIN_URL.'asset/images/delete.png';?>" />
					<?php }?>
				</td>
			</tr>
		<?php }?>
	</table>
</div>
<div id="createFaqCategoryContainer">
<input id="newFaqCatName" class="form-control" type="text" placeholder="<?php _e('Enter '.$default_labels['dc'].' Name','wp-support-plus-responsive-ticket-system');?>" >
	<button class="btn btn-success" onclick="createNewFaqCategory();"><?php _e('Create New '.$default_labels['dc'],'wp-support-plus-responsive-ticket-system');?></button>
</div>
<div id="editFaqCategoryContainer">
	<input type="hidden" id="editFaqCatID" value="">
	<input id="editFaqCatName" class="form-control" type="text" >
	<button onclick="updateFaqCategory();" class="btn btn-success"><?php _e('Update '.$default_labels['dc'],'wp-support-plus-responsive-ticket-system');?></button>
</div>
