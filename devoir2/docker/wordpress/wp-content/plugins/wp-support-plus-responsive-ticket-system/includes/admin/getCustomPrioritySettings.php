<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$advancedSettingsPriorityOrder=get_option( 'wpsp_advanced_settings_priority_order' );
$sql="select * from {$wpdb->prefix}wpsp_custom_priority";
$priorities=$wpdb->get_results($sql);
$total_priorities=$wpdb->num_rows;

$default_status_priority=get_option( 'wpsp_default_status_priority_names' );
$priority_names_array=$default_status_priority['priority_names'];
$priority_names=array_values($priority_names_array);

$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];

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

?>
<div id="priorityDisplayTableContainer" class="table-responsive">
	<table id="custom_priority_order_table" class="table table-striped">
	<thead>
	  <tr>
	    <th><?php _e('Name','wp-support-plus-responsive-ticket-system');?></th>
	    <th><?php _e('Action','wp-support-plus-responsive-ticket-system');?></th>
	    <th><?php _e('Color','wp-support-plus-responsive-ticket-system');?></th>
	    <th><?php _e('Save','wp-support-plus-responsive-ticket-system');?></th>
	  </tr>
	</thead>
	<tbody>
	  <?php foreach ($priorities as $priority){?>
	  	<tr>
	  		<td><?php echo $priority->name;?></td>
			<td style="display:none;"><?php echo $priority->id;?></td>
	  		<td>
				<?php
					if(in_array($priority->name,$priority_names)){
						$is_default=1;
					}
					else
					{
						$is_default=0;
					}
				?>
				<img alt="Edit" onclick="editPriority(<?php echo $priority->id;?>,'<?php echo $priority->name;?>','<?php echo $is_default;?>');" class="catEdit" title="Edit" src="<?php echo WCE_PLUGIN_URL.'asset/images/edit.png';?>" />
				<?php
				if(!in_array($priority->name,$priority_names)){
				?>
				<img onclick="delete_custom_priority(<?php echo $priority->id;?>);" style="cursor: pointer;" title="<?php _e('Delete','wp-support-plus-responsive-ticket-system');?>" src="<?php echo WCE_PLUGIN_URL;?>asset/images/delete.png" >
				<?php 
				}
				?>
			</td>
	  		<td><input type="text" id="custom_priority_color_<?php echo $priority->id; ?>" value="<?php echo $priority->color;?>" class="wp-support-plus-color-picker" ></td>
	  		<td><img onclick="save_custom_priority_color(<?php echo $priority->id;?>);" style="cursor: pointer;" title="<?php _e('Update','wp-support-plus-responsive-ticket-system');?>" src="<?php echo WCE_PLUGIN_URL;?>asset/images/save.png" > <span class="custom-priority-color-saved" id="custom-priority-color-saved-<?php echo $priority->id;?>">Saved!</span></td>
	  	</tr>
	  <?php }?>
	</tbody>
	<script type="text/javascript">
		jQuery('tbody').sortable();
	</script>
	</table>
</div>
<input type="button" onclick="setCustomPriorityOrder();" class="btn btn-success" value="<?php _e('Save '.$default_labels['dp'].' Order','wp-support-plus-responsive-ticket-system');?>">
<?php if(!$total_priorities){?>
	<div style="width: 100%;text-align: center;"><?php _e('No Custom '.$default_labels['dp'].' Found','wp-support-plus-responsive-ticket-system');?></div>
	<hr>
<?php }?>
<div id="add_custom_priority_container">
	<h4><?php _e('Add New Priority','wp-support-plus-responsive-ticket-system');?></h4>
	<table>
		<tr>
			<td><?php _e($default_labels['dp'].' Name','wp-support-plus-responsive-ticket-system');?>&nbsp;:&nbsp;</td>
			<td><input type="text" id="custom_priority_text" value="" ></td>
		</tr>
		<tr>
			<td><?php _e($default_labels['dp'].' Color','wp-support-plus-responsive-ticket-system');?>&nbsp;:&nbsp;</td>
			<td><input type="text" id="custom_priority_color" value="" class="wp-support-plus-color-picker" ></td>
		</tr>
	</table><br>
	<button class="btn btn-success" onclick="create_custom_priority();" ><?php _e('Create New '.$default_labels['dp'],'wp-support-plus-responsive-ticket-system');?></button>
</div>

<div id="editCustomPriority">
	<input type="hidden" id="editCustomPriorityID" value="">
	<input type="hidden" id="editCustomPriorityDefault" value="">
	<input id="editCustomPriorityName" class="form-control" type="text" ><br/><br/>
	<button onclick="updateCustomPriority();" class="btn btn-success"><?php _e('Update','wp-support-plus-responsive-ticket-system');?></button>
</div>
