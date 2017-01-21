<?php 
global $wpdb;
$cu=wp_get_current_user();
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories ORDER BY name" );
$roleManage=get_option( 'wpsp_role_management' );
$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];
$agents=array();
$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_agent')));
$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_supervisor')));
$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'administrator')));
foreach($roleManage['agents'] as $agentRole)
{
	$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>$agentRole)));
}
foreach($roleManage['supervisors'] as $supervisorRole)
{
	$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>$supervisorRole)));
}

$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];

$customFieldsDropDown = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields where field_type=2 OR field_type=4" );
$advancedSettingsCustomFilterFront=get_option( 'wpsp_advanced_settings_custom_filter_front' );

/*******************************************************************************************/
$flters_key=array(
	'filter_by_status_front',
	'filter_by_category_front',
	'filter_by_no_of_ticket_front',
	'filter_by_selection_front',
	'filter_by_search_front'
);

$customFieldsDropDown = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields where field_type=2" );
foreach ($customFieldsDropDown as $field){
	$flters_key[]='cust'.$field->id;
}

$flters_key=apply_filters('wpsp_register_new_filters_key_front',$flters_key);

$user_filter = get_user_meta( $cu->ID, 'wpspFrontEndFilter', true );
if($user_filter){
	foreach($flters_key as $key){
		if(!isset($user_filter[$key])) $user_filter[$key]='all';
	}
	update_user_meta( $cu->ID, 'wpspFrontEndFilter', $user_filter );
}
else {
	$user_filter=array();
	foreach($flters_key as $key){
		if($key != 'filter_by_selection_front' && $key != 'filter_by_no_of_ticket_front' && $key != 'filter_by_search_front') $user_filter[$key]='all';
		$user_filter['filter_by_selection_front']='text';
		$user_filter['filter_by_no_of_ticket_front']='10';
		$user_filter['filter_by_search_front']='';
	}
	update_user_meta( $cu->ID, 'wpspFrontEndFilter', $user_filter );
}

/*$user_filter_ordering = get_user_meta( $cu->ID, 'wpspFrontEndFilterOrder', true );
if($user_filter_ordering){
	if(!isset($user_filter_ordering['sortby'])) $user_filter_ordering['sortby']='';
	if(!isset($user_filter_ordering['order'])) $user_filter_ordering['order']='';
	update_user_meta( $cu->ID, 'wpspFrontEndFilterOrder', $user_filter_ordering );
}
else {
	$user_filter_ordering=array();
	$user_filter_ordering['sortby']='';
	$user_filter_ordering['order']='';
	update_user_meta( $cu->ID, 'wpspFrontEndFilterOrder', $user_filter_ordering );
}*/

/***************** reset filter code **********************/
$filterReset=array();
foreach($flters_key as $key){
	if($key != 'filter_by_selection_front' && $key != 'filter_by_no_of_ticket_front' && $key != 'filter_by_search_front') $filterReset[$key]='all';
	$filterReset['filter_by_selection_front']='text';
	$filterReset['filter_by_no_of_ticket_front']='10';
	$filterReset['filter_by_search_front']='';
}

$filterReset=apply_filters('wpsp_add_filters_reset_key_front',$filterReset);

?>
<script type="text/javascript">
	function wpsp_reset_filter(){
		<?php 
		foreach ($filterReset as $key => $val){
			echo 'jQuery(\'[name="'.$key.'"]\').val("'.$val.'");';
		}
		?>
		page_no=0;
		getTickets('','');
		jQuery('.wpspActionFrontBody').slideUp();
	}
</script>
<?php 
/***************** reset filter code end ******************/

/******************************************************************/
?>
<form id="wpspFrontendTicketFilter">
<?php

do_action('wpsp_add_new_filter_start_front',$user_filter);

if(($cu->has_cap('manage_support_plus_agent') && $cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['supervisor_logged_in']) && in_array('st',$advancedSettingsCustomFilterFront['supervisor_logged_in'])) || (!$cu->has_cap('manage_support_plus_agent') && $cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['agent_logged_in']) && in_array('st',$advancedSettingsCustomFilterFront['agent_logged_in'])) || (!$cu->has_cap('manage_support_plus_agent') && !$cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['logged_in']) && in_array('st',$advancedSettingsCustomFilterFront['logged_in']))){
?>

<div class="filter_item" id="filter_status_front">
	<table>
		<tr>
			<td><?php _e('Status:','wp-support-plus-responsive-ticket-system');?></td>
			<td>
				<select id="filter_by_status_front" name="filter_by_status_front">
					<option value="all" <?php echo ($user_filter['filter_by_status_front']=='all')?'selected="selected"':'';?>><?php _e('All','wp-support-plus-responsive-ticket-system');?></option>
					<?php
					$sql_status="select * from {$wpdb->prefix}wpsp_custom_status";
					$custom_statusses=$wpdb->get_results($sql_status);
					$total_statusses=$wpdb->num_rows;
					$advancedSettingsStatusOrder=get_option( 'wpsp_advanced_settings_status_order' );
					if(isset($advancedSettingsStatusOrder['status_order'])){
						if(is_array($advancedSettingsStatusOrder['status_order']))
						{
							$custom_statusses=array();
							foreach($advancedSettingsStatusOrder['status_order'] as $status_id)
							{
								$sql="select * from {$wpdb->prefix}wpsp_custom_status WHERE id=".$status_id." ";
								$status_data=$wpdb->get_results($sql);
								foreach($status_data as $status)
								{
									$custom_statusses=array_merge($custom_statusses,array($status));
								}
							}
						}
					}
					if($total_statusses)
					{
						foreach($custom_statusses as $custom_status){?>
                                                    <?php if($custom_status->name!=$advancedSettings['hide_selected_status_ticket']){?>
							<option value="<?php echo strtolower($custom_status->name)?>" <?php echo ($user_filter['filter_by_status_front']==strtolower($custom_status->name))?'selected="selected"':'';?>><?php _e(ucfirst($custom_status->name),'wp-support-plus-responsive-ticket-system');?></option>
                                                    <?php }?>    
						<?php
						}
					}
					?>
				</select>
			</td>
		</tr>
	</table>
</div>

<?php
}
if(($cu->has_cap('manage_support_plus_agent') && $cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['supervisor_logged_in']) && in_array('ct',$advancedSettingsCustomFilterFront['supervisor_logged_in'])) || (!$cu->has_cap('manage_support_plus_agent') && $cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['agent_logged_in']) && in_array('ct',$advancedSettingsCustomFilterFront['agent_logged_in'])) || (!$cu->has_cap('manage_support_plus_agent') && !$cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['logged_in']) && in_array('ct',$advancedSettingsCustomFilterFront['logged_in']))){?>
<div class="filter_item" id="filter_category_front">
	<table>
		<tr>
			<td><?php _e($default_labels['dc'].':','wp-support-plus-responsive-ticket-system');?></td>
			<td>
				<select id="filter_by_category_front" name="filter_by_category_front">
					<option value="all" <?php echo ($user_filter['filter_by_category_front']=='all')?'selected="selected"':'';?>><?php _e('All','wp-support-plus-responsive-ticket-system');?></option>
					<?php 
					foreach ($categories as $category){
						$selected=($user_filter['filter_by_category_front']==$category->id)?'selected="selected"':'';
						echo '<option value="'.$category->id.'" '.$selected.'>'.$category->name.'</option>';
					}
					?>
				</select>
			</td>
		</tr>
	</table>
</div>
<?php 
}

if(($cu->has_cap('manage_support_plus_agent') && $cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['supervisor_logged_in']) && in_array('not',$advancedSettingsCustomFilterFront['supervisor_logged_in'])) || (!$cu->has_cap('manage_support_plus_agent') && $cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['agent_logged_in']) && in_array('not',$advancedSettingsCustomFilterFront['agent_logged_in'])) || (!$cu->has_cap('manage_support_plus_agent') && !$cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['logged_in']) && in_array('not',$advancedSettingsCustomFilterFront['logged_in']))){?>
<div class="filter_item" id="filter_ticketNo_front">
	<table>
		<tr>
			<td><?php _e($advancedSettings['ticket_label_alice'][12],'wp-support-plus-responsive-ticket-system');?>:</td>
			<td>
				<select id="filter_by_no_of_ticket_front" name="filter_by_no_of_ticket_front">
					<option value="10" <?php echo ($user_filter['filter_by_no_of_ticket_front']=='10')?'selected="selected"':'';?>><?php _e('10','wp-support-plus-responsive-ticket-system');?></option>
					<option value="20" <?php echo ($user_filter['filter_by_no_of_ticket_front']=='20')?'selected="selected"':'';?>><?php _e('20','wp-support-plus-responsive-ticket-system');?></option>
					<option value="30" <?php echo ($user_filter['filter_by_no_of_ticket_front']=='30')?'selected="selected"':'';?>><?php _e('30','wp-support-plus-responsive-ticket-system');?></option>
					<option value="40" <?php echo ($user_filter['filter_by_no_of_ticket_front']=='40')?'selected="selected"':'';?>><?php _e('40','wp-support-plus-responsive-ticket-system');?></option>
					<option value="50" <?php echo ($user_filter['filter_by_no_of_ticket_front']=='50')?'selected="selected"':'';?>><?php _e('50','wp-support-plus-responsive-ticket-system');?></option>
				</select>
			</td>
		</tr>
	</table>
</div>

<?php 
}
foreach ($customFieldsDropDown as $field){
	if(($cu->has_cap('manage_support_plus_agent') && $cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['supervisor_logged_in']) && in_array($field->id,$advancedSettingsCustomFilterFront['supervisor_logged_in'])) || (!$cu->has_cap('manage_support_plus_agent') && $cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['agent_logged_in']) && in_array($field->id,$advancedSettingsCustomFilterFront['agent_logged_in'])) || (!$cu->has_cap('manage_support_plus_agent') && !$cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['logged_in']) && in_array($field->id,$advancedSettingsCustomFilterFront['logged_in']))){?>
	<div class="filter_item">
		<table>
			<tr>
				<td><?php echo $field->label;?>:</td>
				<td>
					<select id="custd<?php echo $field->id;?>_front" name="cust<?php echo $field->id;?>">
						<option value="all" <?php echo ($user_filter['cust'.$field->id]=='all')?'selected="selected"':'';?>><?php _e('All','wp-support-plus-responsive-ticket-system');?></option>
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
							$selected=($user_filter['cust'.$field->id]==$field_option_key)?'selected="selected"':'';
							echo '<option value="'.$field_option_key.'" '.$selected.'>'.$field_option_value.'</option>';
						}
						?>
					</select>
				</td>
			</tr>
		</table>
	</div>
<?php }
}

do_action('wpsp_add_new_filter_end_front',$user_filter);

if(($cu->has_cap('manage_support_plus_agent') && $cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['supervisor_logged_in']) && in_array('tt',$advancedSettingsCustomFilterFront['supervisor_logged_in'])) || (!$cu->has_cap('manage_support_plus_agent') && $cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['agent_logged_in']) && in_array('tt',$advancedSettingsCustomFilterFront['agent_logged_in'])) || (!$cu->has_cap('manage_support_plus_agent') && !$cu->has_cap('manage_support_plus_ticket') && is_array($advancedSettingsCustomFilterFront['logged_in']) && in_array('tt',$advancedSettingsCustomFilterFront['logged_in']))){?>
<div class="filter_item" id="filter_searchby_front">
	<table>
		<tr>
			<td><?php _e('By:','wp-support-plus-responsive-ticket-system');?></td>
			<td>
				<select id="filter_by_selection_front" name="filter_by_selection_front">
					<option value="text" <?php echo ($user_filter['filter_by_selection_front']=='text')?'selected="selected"':'';?>><?php _e('Text','wp-support-plus-responsive-ticket-system');?></option>
					<option value="id" <?php echo ($user_filter['filter_by_selection_front']=='id')?'selected="selected"':'';?>><?php _e('ID','wp-support-plus-responsive-ticket-system');?></option>
                                        <option value="created_by" <?php echo ($user_filter['filter_by_selection_front']=='created_by')?'selected="selected"':'';?>><?php _e('Created_by','wp-support-plus-responsive');?></option>
				</select>
			</td>
			<td><input type="text" id="filter_by_search_front" name="filter_by_search_front" size="10" placeholder="<?php _e('Search...','wp-support-plus-responsive-ticket-system');?>" /></td>
		</tr>
	</table>
</div>
<?php
}?>
<div class="filter_center">
	<button type="submit" class='btn btn-success'><?php _e('Apply & Remember','wp-support-plus-responsive-ticket-system');?></button>
	<button type="button" class='btn btn-success' onclick="wpspHideFilterFrontBody();"><?php _e('Cancel','wp-support-plus-responsive-ticket-system');?></button>
</div>

</form>

<script>
var wpsp_loaded=true;
</script>

