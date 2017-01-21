<?php 
global $wpdb;
$cu=wp_get_current_user();
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories ORDER BY name" );
$priorities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_priority" );

$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];

$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];

$roleManage=get_option( 'wpsp_role_management' );
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
//filter available
$flters_key=array(
	'filter_by_status',
	'filter_by_type',
	'filter_by_category',
	'filter_by_assigned_to',
	'filter_by_priority',
	'filter_by_no_of_ticket',
	'filter_by_selection',
	'filter_by_search'
);

$customFieldsDropDown = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields where field_type=2" );
foreach ($customFieldsDropDown as $field){
	$flters_key[]='cust'.$field->id;
}

$flters_key=apply_filters('wpsp_register_new_filters_key',$flters_key);

$user_filter = get_user_meta( $cu->ID, 'wpspBackEndFilter', true );
if($user_filter){
	foreach($flters_key as $key){
		if(!isset($user_filter[$key])) $user_filter[$key]='all';
	}
	update_user_meta( $cu->ID, 'wpspBackEndFilter', $user_filter );
}
else {
	$user_filter=array();
	foreach($flters_key as $key){
		if($key != 'filter_by_selection' && $key != 'filter_by_no_of_ticket' && $key != 'filter_by_search') $user_filter[$key]='all';
		$user_filter['filter_by_selection']='text';
		$user_filter['filter_by_no_of_ticket']='10';
		$user_filter['filter_by_search']='';
	}
	update_user_meta( $cu->ID, 'wpspBackEndFilter', $user_filter );
}

$user_filter_ordering = get_user_meta( $cu->ID, 'wpspBackEndFilterOrder', true );
if($user_filter_ordering){
	if(!isset($user_filter_ordering['sortby'])) $user_filter_ordering['sortby']='';
	if(!isset($user_filter_ordering['order'])) $user_filter_ordering['order']='';
	update_user_meta( $cu->ID, 'wpspBackEndFilterOrder', $user_filter_ordering );
}
else {
	$user_filter_ordering=array();
	$user_filter_ordering['sortby']='';
	$user_filter_ordering['order']='';
	update_user_meta( $cu->ID, 'wpspBackEndFilterOrder', $user_filter_ordering );
}

/***************** reset filter code **********************/
$filterReset=array();
foreach($flters_key as $key){
	if($key != 'filter_by_selection' && $key != 'filter_by_no_of_ticket' && $key != 'filter_by_search') $filterReset[$key]='all';
	$filterReset['filter_by_selection']='text';
	$filterReset['filter_by_no_of_ticket']='10';
	$filterReset['filter_by_search']='';
}

$filterReset=apply_filters('wpsp_add_filters_reset_key_backend',$filterReset);

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
		jQuery('.wpspActionDashboardBody').slideUp();
	}
</script>
<?php 
/***************** reset filter code end ******************/

?>
<form id="wpspBackendTicketFilter">

<?php do_action('wpsp_add_new_filter_start_backend',$user_filter);?>
    
<div class="filter_item" id="filter_status_backend">
	<table>
		<tr>
			<td><?php _e('Status:','wp-support-plus-responsive-ticket-system');?></td>
			<td>
				<select id="filter_by_status" name="filter_by_status">
					<option value="all" <?php echo ($user_filter['filter_by_status']=='all')?'selected="selected"':'';?>><?php _e('All','wp-support-plus-responsive-ticket-system');?></option>
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
							<option value="<?php echo strtolower($custom_status->name);?>" <?php echo ($user_filter['filter_by_status']==strtolower($custom_status->name))?'selected="selected"':'';?>><?php _e(ucfirst($custom_status->name),'wp-support-plus-responsive-ticket-system');?></option>
						<?php
						}
					}
					?>
				</select>
			</td>
		</tr>
	</table>
</div>

<div class="filter_item" id="filter_type_backend">
	<table>
		<tr>
			<td><?php _e('Type:','wp-support-plus-responsive-ticket-system');?></td>
			<td>
				<select id="filter_by_type" name="filter_by_type">
					<option value="all" <?php echo ($user_filter['filter_by_type']=='all')?'selected="selected"':'';?>><?php _e('All','wp-support-plus-responsive-ticket-system');?></option>
					<option value="user" <?php echo ($user_filter['filter_by_type']=='user')?'selected="selected"':'';?>><?php _e('User','wp-support-plus-responsive-ticket-system');?></option>
					<option value="guest" <?php echo ($user_filter['filter_by_type']=='guest')?'selected="selected"':'';?>><?php _e('Guest','wp-support-plus-responsive-ticket-system');?></option>
				</select>
			</td>
		</tr>
	</table>
</div>
<?php if(in_array('dc',$advancedSettingsFieldOrder['display_fields'])){?>
<div class="filter_item" id="filter_category_backend">
	<table>
		<tr>
			<td><?php _e($default_labels['dc'].':','wp-support-plus-responsive-ticket-system');?></td>
			<td>
				<select id="filter_by_category" name="filter_by_category">
					<option value="all" <?php echo ($user_filter['filter_by_category']=='all')?'selected="selected"':'';?>><?php _e('All','wp-support-plus-responsive-ticket-system');?></option>
					<?php 
					foreach ($categories as $category){
						$selected=($user_filter['filter_by_category']==$category->id)?'selected="selected"':'';
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
if($cu->has_cap('manage_support_plus_agent') && $cu->has_cap('manage_support_plus_ticket'))
{
?>
<div class="filter_item" id="filter_assignedto_backend">
	<table>
		<tr>
			<td><?php _e('Assigned to:','wp-support-plus-responsive-ticket-system');?></td>
			<td>
				<select id="filter_by_assigned_to" name="filter_by_assigned_to">
					<option value="all" <?php echo ($user_filter['filter_by_assigned_to']=='all')?'selected="selected"':'';?>><?php _e('All','wp-support-plus-responsive-ticket-system');?></option>
					<option value="0" <?php echo ($user_filter['filter_by_assigned_to']=='0')?'selected="selected"':'';?>><?php _e('None','wp-support-plus-responsive-ticket-system');?></option>
					<?php 
					foreach ($agents as $agent){
						?>
						<option value="<?php echo $agent->ID;?>" <?php echo ($user_filter['filter_by_assigned_to']==$agent->ID)?'selected="selected"':'';?>><?php echo $agent->display_name;?></option>
						<?php 
					}
					?>
				</select>
			</td>
		</tr>
	</table>
</div>
<?php
}
else
{?>
<input type="hidden" id="filter_by_assigned_to" name="filter_by_assigned_to" value="<?php echo $cu->ID;?>">
<?php
}
if(in_array('dp',$advancedSettingsFieldOrder['display_fields'])){?>
<div class="filter_item" id="filter_priority_backend">
	<table>
		<tr>
			<td><?php _e($default_labels['dp'].':','wp-support-plus-responsive-ticket-system');?></td>
			<td>
				<select id="filter_by_priority" name="filter_by_priority">
					<option value="all" <?php echo ($user_filter['filter_by_priority']=='all')?'selected="selected"':'';?>><?php _e('All','wp-support-plus-responsive-ticket-system');?></option>
					<?php
					$sql_priorities="select * from {$wpdb->prefix}wpsp_custom_priority";
					$custom_priorities=$wpdb->get_results($sql_priorities);
					$total_priorities=$wpdb->num_rows;
					$advancedSettingsPriorityOrder=get_option( 'wpsp_advanced_settings_priority_order' );
					if(isset($advancedSettingsPriorityOrder['priority_order'])){
						if(is_array($advancedSettingsPriorityOrder['priority_order']))
						{
							$custom_priorities=array();
							foreach($advancedSettingsPriorityOrder['priority_order'] as $priority_id)
							{
								$sql="select * from {$wpdb->prefix}wpsp_custom_priority WHERE id=".$priority_id." ";
								$priority_data=$wpdb->get_results($sql);
								foreach($priority_data as $priority)
								{
									$custom_priorities=array_merge($custom_priorities,array($priority));
								}
							}
						}
					}
					if($total_priorities)
					{
						foreach($custom_priorities as $custom_priority){?>
                                        <option value="<?php echo $custom_priority->name;?>" <?php echo (strtolower($user_filter['filter_by_priority'])==strtolower($custom_priority->name))?'selected="selected"':'';?>><?php _e($custom_priority->name,'wp-support-plus-responsive-ticket-system');?></option>
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
}?>
<div class="filter_item" id="filter_ticketNo_backend">
	<table>
		<tr>
			<td><?php _e($advancedSettings['ticket_label_alice'][12],'wp-support-plus-responsive-ticket-system');?>:</td>
			<td>
				<select id="filter_by_no_of_ticket" name="filter_by_no_of_ticket">
					<option value="10" <?php echo ($user_filter['filter_by_no_of_ticket']=='10')?'selected="selected"':'';?>><?php _e('10','wp-support-plus-responsive-ticket-system');?></option>
					<option value="20" <?php echo ($user_filter['filter_by_no_of_ticket']=='20')?'selected="selected"':'';?>><?php _e('20','wp-support-plus-responsive-ticket-system');?></option>
					<option value="30" <?php echo ($user_filter['filter_by_no_of_ticket']=='30')?'selected="selected"':'';?>><?php _e('30','wp-support-plus-responsive-ticket-system');?></option>
					<option value="40" <?php echo ($user_filter['filter_by_no_of_ticket']=='40')?'selected="selected"':'';?>><?php _e('40','wp-support-plus-responsive-ticket-system');?></option>
					<option value="50" <?php echo ($user_filter['filter_by_no_of_ticket']=='50')?'selected="selected"':'';?>><?php _e('50','wp-support-plus-responsive-ticket-system');?></option>
					<option value="100" <?php echo ($user_filter['filter_by_no_of_ticket']=='100')?'selected="selected"':'';?>><?php _e('100','wp-support-plus-responsive-ticket-system');?></option>
					<option value="500" <?php echo ($user_filter['filter_by_no_of_ticket']=='500')?'selected="selected"':'';?>><?php _e('500','wp-support-plus-responsive-ticket-system');?></option>
				</select>
			</td>
		</tr>
	</table>
</div>

<?php foreach ($customFieldsDropDown as $field){
	if(in_array($field->id,$advancedSettingsFieldOrder['display_fields'])){?>
	<div class="filter_item">
		<table>
			<tr>
				<td><?php echo $field->label;?>:</td>
				<td>
					<select id="custd<?php echo $field->id;?>" name="cust<?php echo $field->id;?>">
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
							$selected=($user_filter['cust'.$field->id]==$field_option_value)?'selected="selected"':'';
							echo '<option value="'.$field_option_value.'" '.$selected.'>'.$field_option_value.'</option>';
						}
						?>
					</select>
				</td>
			</tr>
		</table>
	</div>
<?php 	}
}?>

<?php do_action('wpsp_add_new_filter_end_backend',$user_filter);?>

<br><br>
<div class="filter_center" id="filter_searchby_backend">
	<?php _e('Search By:','wp-support-plus-responsive-ticket-system');?>&nbsp;&nbsp;&nbsp;
	<select id="filter_by_selection" name="filter_by_selection">
		<option value="text" <?php echo ($user_filter['filter_by_selection']=='text')?'selected="selected"':'';?>><?php _e('Text','wp-support-plus-responsive-ticket-system');?></option>
		<option value="id" <?php echo ($user_filter['filter_by_selection']=='id')?'selected="selected"':'';?>><?php _e('ID','wp-support-plus-responsive-ticket-system');?></option>		
		<option value="created_by" <?php echo ($user_filter['filter_by_selection']=='created_by')?'selected="selected"':'';?>><?php _e('Created By','wp-support-plus-responsive-ticket-system');?></option>
		
	</select>
</div>
<div class="filter_center">
	<input type="text" id="filter_by_search" name="filter_by_search" placeholder="<?php _e('Search...','wp-support-plus-responsive-ticket-system');?>" value="<?php echo $user_filter['filter_by_search'];?>" />
</div>
<div class="filter_center">
	<button type="submit" class='btn btn-success'><?php _e('Apply & Remember','wp-support-plus-responsive-ticket-system');?></button>
	<button type="button" class='btn btn-success' onclick="wpspHideFilterDashboardBody();"><?php _e('Cancel','wp-support-plus-responsive-ticket-system');?></button>
</div>

</form>
