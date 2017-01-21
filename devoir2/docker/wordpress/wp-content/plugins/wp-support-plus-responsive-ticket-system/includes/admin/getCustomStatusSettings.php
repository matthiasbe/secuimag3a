<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$advancedSettingsStatusOrder=get_option( 'wpsp_advanced_settings_status_order' );
$sql="select * from {$wpdb->prefix}wpsp_custom_status";
$statusses=$wpdb->get_results($sql);
$default_status_priority=get_option( 'wpsp_default_status_priority_names' );
$status_names_array=$default_status_priority['status_names'];
$status_names=array_values($status_names_array);

$total_statusses=$wpdb->num_rows;
if(isset($advancedSettingsStatusOrder['status_order'])){
	if(is_array($advancedSettingsStatusOrder['status_order']))
	{
		$statusses=array();
		foreach($advancedSettingsStatusOrder['status_order'] as $status_id)
		{
			$sql="select * from {$wpdb->prefix}wpsp_custom_status WHERE id=".$status_id." ";
			$status_data=$wpdb->get_results($sql);
			foreach($status_data as $status)
			{
				$statusses=array_merge($statusses,array($status));
			}
		}
	}
}
?>
<div id="statusDisplayTableContainer" class="table-responsive">
	<table id="custom_status_order_table" class="table table-striped">
		<thead>
	  <tr>
	    <th><?php _e('Name','wp-support-plus-responsive-ticket-system');?></th>
	    <th><?php _e('Action','wp-support-plus-responsive-ticket-system');?></th>
	    <?php
	    /* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
	     * Update 1 - Change Custom Status Color
	     * Add custom status color picker
	     */
	    ?>
	    <th><?php _e('Color','wp-support-plus-responsive-ticket-system');?></th>
	    <th><?php _e('Save','wp-support-plus-responsive-ticket-system');?></th>
	    <?php
	    /* END CLOUGH I.T. SOLUTIONS MODIFICATION
	     */
	    ?>
	  </tr></thead>
	<tbody>
	  <?php foreach ($statusses as $status){?>
	  	<tr>
	  		<td><?php echo $status->name;?></td>
			<td style="display:none;"><?php echo $status->id;?></td>
	  		<td>
				<?php
					if(in_array($status->name,$status_names)){
						$is_default=1;
					}
					else
					{
						$is_default=0;
					}
				?>
				<img alt="Edit" onclick="editStatus(<?php echo $status->id;?>,'<?php echo $status->name;?>','<?php echo $is_default;?>');" class="catEdit" title="Edit" src="<?php echo WCE_PLUGIN_URL.'asset/images/edit.png';?>" />
				<?php
				if(!in_array($status->name,$status_names)){
				?>
				<img onclick="delete_custom_status(<?php echo $status->id;?>);" style="cursor: pointer;" title="<?php _e('Delete','wp-support-plus-responsive-ticket-system');?>" src="<?php echo WCE_PLUGIN_URL;?>asset/images/delete.png" >
				<?php 
				}
				?>
			</td>
	  		<?php
	  		/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
			 * Update 1 - Change Custom Status Color
			 * Add custom status color picker
			 */
			?>
	  		<td><input type="text" id="custom_status_color_<?php echo $status->id; ?>" value="<?php echo $status->color;?>" class="wp-support-plus-color-picker" ></td>
	  		<td><img onclick="save_custom_status_color(<?php echo $status->id;?>);" style="cursor: pointer;" title="<?php _e('Update','wp-support-plus-responsive-ticket-system');?>" src="<?php echo WCE_PLUGIN_URL;?>asset/images/save.png" > <span class="custom-status-color-saved" id="custom-status-color-saved-<?php echo $status->id;?>">Saved!</span></td>
	  		<?php
	  		/* END CLOUGH I.T. SOLUTIONS MODIFICATION
			*/
			?>
	  	</tr>
	  <?php }?>
	</tbody>
	</table>
	<script type="text/javascript">
		jQuery('tbody').sortable();
	</script>
</div>
<input type="button" onclick="setCustomStatusOrder();" class="btn btn-success" value="<?php _e('Save Status Order','wp-support-plus-responsive-ticket-system');?>">
<?php if(!$total_statusses){?>
	<div style="width: 100%;text-align: center;"><?php _e('No Custom Status Found','wp-support-plus-responsive-ticket-system');?></div>
	<hr>
<?php }?>
<div id="add_custom_status_container">
	<h4><?php _e('Add New Status','wp-support-plus-responsive-ticket-system');?></h4>
	<table>
		<?php
		/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
		 * Update 1 - Change Custom Status Color
		 * Added 'custom_status_color' field to be used as a color picker
		 */
		?>
		<tr>
			<td><?php _e('Status Name','wp-support-plus-responsive-ticket-system');?>&nbsp;:&nbsp;</td>
			<td><input type="text" id="custom_status_text" value="" ></td>
		</tr>
		<tr>
			<td><?php _e('Status Color','wp-support-plus-responsive-ticket-system');?>&nbsp;:&nbsp;</td>
			<td><input type="text" id="custom_status_color" value="" class="wp-support-plus-color-picker" ></td>
		</tr>
		<?php
		/*<tr>
			<td><?php _e('Status Name','wp-support-plus-responsive-ticket-system');?>&nbsp;:&nbsp;</td>
			<td><input type="text" id="custom_status_text" value="" ></td>
		</tr>*/
		/* END CLOUGH I.T. SOLUTIONS MODIFICATION
		*/
		?>
	</table><br>
	<button class="btn btn-success" onclick="create_custom_status();" ><?php _e('Create New Status','wp-support-plus-responsive-ticket-system');?></button>
</div>

<div id="editCustomStatus">
	<input type="hidden" id="editCustomStatusID" value="">
	<input type="hidden" id="editCustomStatusDefault" value="">
	<input id="editCustomStatusName" class="form-control" type="text" ><br/><br/>
	<button onclick="updateCustomStatus();" class="btn btn-success"><?php _e('Update','wp-support-plus-responsive-ticket-system');?></button>
</div>
