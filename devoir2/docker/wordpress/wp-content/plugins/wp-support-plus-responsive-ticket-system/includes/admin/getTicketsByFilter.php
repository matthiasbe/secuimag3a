<?php 
global $wpdb;
$cu=wp_get_current_user();
$sortby=$_POST['sortby'];
$order=$_POST['order'];
unset($_POST['sortby']);
unset($_POST['order']);

/********************************************************************/
$advancedSettings=get_option( 'wpsp_advanced_settings' );
$sql_status="select * from {$wpdb->prefix}wpsp_custom_status";
$custom_statusses=$wpdb->get_results($sql_status);
$hideStatus = array();
if(!$advancedSettings['hide_selected_status_ticket_backend']) $advancedSettings['hide_selected_status_ticket_backend']=array();
foreach($custom_statusses as $custom_status){
	if(is_numeric(array_search($custom_status->id,$advancedSettings['hide_selected_status_ticket_backend']))){
		$hideStatus = array_merge($hideStatus,array($custom_status->name));
	}
}
/********************************************************************/

$advancedSettingsTicketList=get_option( 'wpsp_advanced_settings_ticket_list_order' );
$subCharLength=get_option( 'wpsp_ticket_list_subject_char_length' );

$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];

$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];

$dateFormat = get_option( 'wpsp_ticket_list_date_format' );

$user_filter=array();
foreach ($_POST as $key=>$val){
	if($key!='page_no' && $key!='action')
	$user_filter[$key]=$val;
}
update_user_meta( $cu->ID, 'wpspBackEndFilter', $user_filter );

if($sortby!=''){
	$user_filter_ordering=array();
	$user_filter_ordering['sortby']=$sortby;
	$user_filter_ordering['order']=$order;
	update_user_meta( $cu->ID, 'wpspBackEndFilterOrder', $user_filter_ordering );
}
/****************************** check for active filter *****************************/
$strFilterNotActive=__('Apply Filter','wp-support-plus-responsive-ticket-system');
$strFilterActive=__('Show Active Filter','wp-support-plus-responsive-ticket-system');
$isWpspFilterAcive=false;
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

$filterReset=array();
foreach($flters_key as $key){
	if($key != 'filter_by_selection' && $key != 'filter_by_no_of_ticket' && $key != 'filter_by_search') $filterReset[$key]='all';
	$filterReset['filter_by_selection']='text';
	$filterReset['filter_by_no_of_ticket']='10';
	$filterReset['filter_by_search']='';
}

$filterReset=apply_filters('wpsp_add_filters_reset_key_backend',$filterReset);

foreach ($user_filter as $key => $val){
	if (isset($user_filter[$key]) && $user_filter[$key]!=$filterReset[$key]){
		$isWpspFilterAcive=true;
		break;
	}
}
?>
<script type="text/javascript">
	<?php 
	if ($isWpspFilterAcive){?>
		jQuery('#wpspBtnApplyTicketFilter').text('<?php echo $strFilterActive;?>');
		jQuery('#wpspBtnResetTicketFilter').show();
	<?php }
	else {?>
		jQuery('#wpspBtnApplyTicketFilter').text('<?php echo $strFilterNotActive;?>');
		jQuery('#wpspBtnResetTicketFilter').hide();
	<?php }?>
</script>
<?php
/****************************** check for active filter end *****************************/

$roleManage_new=get_option( 'wpsp_role_management' );
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories" );
$agents_new=array();
$agents_new=array_merge($agents_new,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_agent')));
$agents_new=array_merge($agents_new,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_supervisor')));
$agents_new=array_merge($agents_new,get_users(array('orderby'=>'display_name','role'=>'administrator')));
foreach($roleManage_new['agents'] as $agentRole)
{
	$agents_new=array_merge($agents_new,get_users(array('orderby'=>'display_name','role'=>$agentRole)));
}
foreach($roleManage_new['supervisors'] as $supervisorRole)
{
	$agents_new=array_merge($agents_new,get_users(array('orderby'=>'display_name','role'=>$supervisorRole)));
}

$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$customFieldSql='';
foreach ($customFields as $field){
	$customFieldSql.='t.cust'.$field->id.',';
}

/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 1 - Change Custom Status Color
 * Join custom status database entry
 */
$sql="select t.id,t.type,t.subject,t.status,c.name as category,c.id as cat_id,t.assigned_to,t.priority,t.created_by,t.guest_name,t.agent_created,cs.color,cp.color as pcolor,".$customFieldSql."
		TIMESTAMPDIFF(MONTH,t.update_time,UTC_TIMESTAMP()) as date_modified_month,
		TIMESTAMPDIFF(DAY,t.update_time,UTC_TIMESTAMP()) as date_modified_day,
		TIMESTAMPDIFF(HOUR,t.update_time,UTC_TIMESTAMP()) as date_modified_hour,
 		TIMESTAMPDIFF(MINUTE,t.update_time,UTC_TIMESTAMP()) as date_modified_min,
 		TIMESTAMPDIFF(SECOND,t.update_time,UTC_TIMESTAMP()) as date_modified_sec,
		t.create_time as create_date, t.update_time as update_date  		
		FROM {$wpdb->prefix}wpsp_ticket t 
		INNER JOIN {$wpdb->prefix}wpsp_catagories c ON t.cat_id=c.id  
		LEFT JOIN {$wpdb->prefix}wpsp_custom_status cs ON t.status=cs.name 
		LEFT JOIN {$wpdb->prefix}wpsp_custom_priority cp ON t.priority=cp.name ";

$sql=apply_filters('wpsp_get_ticket_list_backend_sql',$sql,$customFieldSql);
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
*/
                
$sql=apply_filters('wpsp_get_ticket_list_backend_sql',$sql,$customFieldSql);

$flagUseWhere=false;
$where="WHERE ";
if(isset($_POST['filter_by_type']) && $_POST['filter_by_type']!='all'){
	$flagUseWhere=true;
	$where.="t.type='".$_POST['filter_by_type']."' ";
}
/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 2 - Create 'All Active' filter
 * Modify filter where clause
 */
if(isset($_POST['filter_by_status']) && $_POST['filter_by_status'] != 'all' ) {
	$where .= ( $flagUseWhere ) ? 'AND ' : '';
	$flagUseWhere = true;
	$where .= "t.status='" . $_POST['filter_by_status'] . "' ";
} else if (isset($_POST['filter_by_status']) && $_POST['filter_by_status'] == 'all' ) {
	if(sizeof($hideStatus)>0){
		$where .= ( $flagUseWhere ) ? 'AND ' : '';
		$flagUseWhere = true;
	}
	for($i=0;$i<sizeof($hideStatus);$i++){
		if($i<(sizeof($hideStatus)-1)){
			$where .= "t.status!='"."$hideStatus[$i]"."' ";
			$where .= ( $flagUseWhere ) ? 'AND ' : '';
		}
		elseif($i == (sizeof($hideStatus)-1)){
			$where .= "t.status!='"."$hideStatus[$i]"."' ";
		}
	}
}
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
*/
if(isset($_POST['filter_by_category']) && $_POST['filter_by_category']!='all'){
	$where.=($flagUseWhere)?'AND ':'';
	$flagUseWhere=true;
	$where.="c.id='".$_POST['filter_by_category']."' ";
}

if($cu->has_cap('manage_support_plus_ticket') && !$cu->has_cap('manage_support_plus_agent'))
{
	$where.=($flagUseWhere)?'AND ':'';
	$flagUseWhere=true;
	$where.="( t.assigned_to LIKE '%".$cu->ID."%' OR t.assigned_to='0' OR t.created_by='".$cu->ID."' OR t.ticket_type=1) ";
}
elseif(isset($_POST['filter_by_assigned_to']))
{
	if($_POST['filter_by_assigned_to']!='all'){
		$where.=($flagUseWhere)?'AND ':'';
		$flagUseWhere=true;
		$where.="t.assigned_to LIKE '%".$_POST['filter_by_assigned_to']."%' ";
	}
}

foreach ($customFieldsDropDown as $field){
	if(isset($_POST['cust'.$field->id]) && $_POST['cust'.$field->id]!='all'){
		$where.=($flagUseWhere)?'AND ':'';
		$flagUseWhere=true;
		$where.="t.cust".$field->id."='".$_POST['cust'.$field->id]."' ";
	}
}

if(isset($_POST['filter_by_priority']) &&  $_POST['filter_by_priority']!='all'){
	$where.=($flagUseWhere)?'AND ':'';
	$flagUseWhere=true;
	$where.="t.priority='".$_POST['filter_by_priority']."' ";
}
switch($_POST['filter_by_selection'])
{
	case 'id':
		if($_POST['filter_by_search']!=''){
			$where.=($flagUseWhere)?'AND ':'';
			$flagUseWhere=true;
		
			//custome fields
			$custCondition='';
			$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
			$total_cust_field=$wpdb->num_rows;
			if($total_cust_field){
				foreach ($customFields as $field){
					$custCondition.="OR t.cust".$field->id." LIKE '%".$_POST['filter_by_search']."%' ";
				}
			}
	
			$where.="t.id IN (SELECT DISTINCT t.id from {$wpdb->prefix}wpsp_ticket t INNER JOIN {$wpdb->prefix}wpsp_ticket_thread th ON t.id=th.ticket_id WHERE t.id=".$_POST['filter_by_search']." OR t.id LIKE '%".$_POST['filter_by_search']."%') ";
		}
		break;
	case 'text':
		if($_POST['filter_by_search']!=''){
			$where.=($flagUseWhere)?'AND ':'';
			$flagUseWhere=true;
		
			//custome fields
			$custCondition='';
			$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
			$total_cust_field=$wpdb->num_rows;
			if($total_cust_field){
				foreach ($customFields as $field){
					$custCondition.="OR t.cust".$field->id." LIKE '%".$_POST['filter_by_search']."%' ";
				}
			}
	
			$where.="t.id IN (SELECT DISTINCT t.id from {$wpdb->prefix}wpsp_ticket t INNER JOIN {$wpdb->prefix}wpsp_ticket_thread th ON t.id=th.ticket_id WHERE t.subject LIKE '%".$_POST['filter_by_search']."%' OR th.body LIKE '%".$_POST['filter_by_search']."%' ".$custCondition.") ";
		}
		break;
	case 'created_by':
		if($_POST['filter_by_search']!=''){
			$where.=($flagUseWhere)?'AND ':'';
			$flagUseWhere=true;
		
			$term=esc_attr( $_POST['filter_by_search'] );
			$sql1 = "SELECT * FROM " . $wpdb->base_prefix . "users WHERE 1=1 AND (user_login LIKE '%" . $term . "%' OR user_email LIKE '%" . $term . "%' OR display_name LIKE '%" . $term . "%')";
			$users = $wpdb->get_results( $sql1 );
			if(is_array($users) && count($users)>0)
			{
				$user_ids=array();
				foreach($users as $user)
				{
					$user_ids=array_merge($user_ids,array($user->ID));
				}
				$where.="( t.id IN (SELECT DISTINCT t.id from {$wpdb->prefix}wpsp_ticket t INNER JOIN {$wpdb->prefix}wpsp_ticket_thread th ON t.id=th.ticket_id WHERE t.created_by IN (".implode(",",$user_ids).")) OR ";
				$where.="t.id IN (SELECT DISTINCT t.id from {$wpdb->prefix}wpsp_ticket t INNER JOIN {$wpdb->prefix}wpsp_ticket_thread th ON t.id=th.ticket_id WHERE t.guest_name LIKE '%".$_POST['filter_by_search']."%' OR t.guest_email LIKE'%".$_POST['filter_by_search']."%')) ";
			}
			else
			{
				$where.="t.id IN (SELECT DISTINCT t.id from {$wpdb->prefix}wpsp_ticket t INNER JOIN {$wpdb->prefix}wpsp_ticket_thread th ON t.id=th.ticket_id WHERE t.guest_name LIKE '%".$_POST['filter_by_search']."%' OR t.guest_email LIKE'%".$_POST['filter_by_search']."%') ";
			}
		}
		break;
}

$where=($flagUseWhere)?$where:''; 
$where=apply_filters('wpsp_get_ticket_list_where_backend',$where,$hideStatus,$customFieldsDropDown,$cu);

switch($sortby){
	case 'id':
		if($order=='down')
		{
			$order_by='ORDER BY t.id ASC ';
		}
		if($order=='up')
		{
			$order_by='ORDER BY t.id DESC ';
		}
	break;
	case 'st':
		if($order=='down')
		{
			$order_by='ORDER BY t.status ASC ';
		}
		if($order=='up')
		{
			$order_by='ORDER BY t.status DESC ';
		}
	break;
	case 'sb':
		if($order=='down')
		{
			$order_by='ORDER BY t.subject ASC ';
		}
		if($order=='up')
		{
			$order_by='ORDER BY t.subject DESC ';
		}
	break;
	case 'rb':
		if($order=='down')
		{
			$order_by='ORDER BY t.created_by ASC ';
		}
		if($order=='up')
		{
			$order_by='ORDER BY t.created_by DESC ';
		}
	break;
	case 'ty':
		if($order=='down')
		{
			$order_by='ORDER BY t.type ASC ';
		}
		if($order=='up')
		{
			$order_by='ORDER BY t.type DESC ';
		}
	break;
	case 'ct':
		if($order=='down')
		{
			$order_by='ORDER BY c.name ASC ';
		}
		if($order=='up')
		{
			$order_by='ORDER BY c.name DESC ';
		}
	break;
	case 'at':
		if($order=='down')
		{
			$order_by='ORDER BY t.assigned_to ASC ';
		}
		if($order=='up')
		{
			$order_by='ORDER BY t.assigned_to DESC ';
		}
	break;
	case 'pt':
		if($order=='down')
		{
			$order_by='ORDER BY t.priority ASC ';
		}
		if($order=='up')
		{
			$order_by='ORDER BY t.priority DESC ';
		}
	break;
	case 'ut':
		if($order=='down')
		{
			$order_by='ORDER BY t.update_time ASC ';
		}
		if($order=='up')
		{
			$order_by='ORDER BY t.update_time DESC ';
		}
	break;

	case 'cdt':
		if($order=='down')
		{
			$order_by='ORDER BY t.create_time ASC ';
		}
		if($order=='up')
		{
			$order_by='ORDER BY t.create_time DESC ';
		}
	break;
	case 'udt':
		if($order=='down')
		{
			$order_by='ORDER BY t.update_time ASC ';
		}
		if($order=='up')
		{
			$order_by='ORDER BY t.update_time DESC ';
		}
	break;
        case 'acd':
		if($order=='down')
		{
			$order_by='ORDER BY t.agent_created ASC ';
		}
		if($order=='up')
		{
			$order_by='ORDER BY t.agent_created DESC ';
		}
	break;
	default :
		if(is_numeric($sortby)){
			if($order=='down')
			{
				$order_by='ORDER BY t.cust'.$sortby.' ASC ';
			}
			if($order=='up')
			{
				$order_by='ORDER BY t.cust'.$sortby.' DESC ';
			}
		}
	break;
}

if($order=='')
{
	$order_by='ORDER BY t.update_time DESC ';
}

$order_by=apply_filters('wpsp_get_ticket_list_orderby_backend',$order_by,$order,$sortby);

$sql.=$where;

$sql.=$order_by;
/*  
 *  Get total page count  
 */ 
$findTotalRowsSQL="select count(*) "
         . "FROM {$wpdb->prefix}wpsp_ticket t 
 		INNER JOIN {$wpdb->prefix}wpsp_catagories c ON t.cat_id=c.id  
 		LEFT JOIN {$wpdb->prefix}wpsp_custom_status cs ON t.status=cs.name 
		LEFT JOIN {$wpdb->prefix}wpsp_custom_priority cp ON t.priority=cp.name ";  
        
$findTotalRowsSQL=apply_filters('wpsp_get_ticket_list_count_backend_sql',$findTotalRowsSQL);

$totalrows = $wpdb->get_var( $findTotalRowsSQL.$where );
$current_page=$_POST['page_no']+1;
$total_pages=ceil($totalrows/$_POST['filter_by_no_of_ticket']);
/*  
 *  Page count end  
 */

$limit_start=$_POST['page_no']*$_POST['filter_by_no_of_ticket'];
$limit="LIMIT ".$limit_start.",".$_POST['filter_by_no_of_ticket']." ";
$sql.=$limit;

$tickets = $wpdb->get_results( $sql );
?>
<div class="table-responsive">
	<table class="table table-striped table-hover">
	   
	  <tr>
		<th><input type="checkbox" id="all_selected" name="all_selected" onchange="get_all_checked();" /></th>
		<?php 
		foreach($advancedSettingsTicketList['backend_ticket_list'] as $backend_ticket_field_key => $backend_ticket_field_value)
		{
			if($backend_ticket_field_value)
			{
				if(is_numeric($backend_ticket_field_key))
				{
					$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields WHERE id='".$backend_ticket_field_key."'" );
					foreach($customFields as $field)
					{
                                                ?><th id="<?php echo $field->id?>" onclick="sort_tickets('<?php echo $field->id?>');"><?php echo $field->label;?><?php
							if($order=="" && $sortby==$field->id){
								?><span class="dashicons"></span><?php
							}
							if($order=="down" && $sortby==$field->id){
								?><span class="dashicons dashicons-arrow-down-alt2"></span><?php
							}
							if($order=="up" && $sortby==$field->id){
								?><span class="dashicons dashicons-arrow-up-alt2"></span><?php
							}?></th><?php
					}
				}
				else
				{
					switch($backend_ticket_field_key){
						case 'id': ?><th id="id" onclick="sort_tickets('id');">#<?php
							if($order=="" && $sortby=='id'){
								?><span class="dashicons"></span><?php
							}
							if($order=="down" && $sortby=='id'){
								?><span class="dashicons dashicons-arrow-down-alt2"></span><?php
							}
							if($order=="up" && $sortby=='id'){
								?><span class="dashicons dashicons-arrow-up-alt2"></span><?php
							}?></th><?php
						break;
						case 'st': ?><th id="st" onclick="sort_tickets('st');"><?php _e('Status','wp-support-plus-responsive-ticket-system');?><?php
							if($order=="" && $sortby=='st'){
								?><span class="dashicons"></span><?php
							}
							if($order=="down" && $sortby=='st'){
								?><span class="dashicons dashicons-arrow-down-alt2"></span><?php
							}
							if($order=="up" && $sortby=='st'){
								?><span class="dashicons dashicons-arrow-up-alt2"></span><?php
							}?></th><?php
						break;
						case 'sb': ?><th id="sb" onclick="sort_tickets('sb');"><?php _e($default_labels['ds'],'wp-support-plus-responsive-ticket-system');?><?php
							if($order=="" && $sortby=='sb'){
								?><span class="dashicons"></span><?php
							}
							if($order=="down" && $sortby=='sb'){
								?><span class="dashicons dashicons-arrow-down-alt2"></span><?php
							}
							if($order=="up" && $sortby=='sb'){
								?><span class="dashicons dashicons-arrow-up-alt2"></span><?php
							}?></th><?php
						break;
						case 'rb': ?><th id="rb" onclick="sort_tickets('rb');"><?php _e('Raised By','wp-support-plus-responsive-ticket-system');?><?php
							if($order=="" && $sortby=='rb'){
								?><span class="dashicons"></span><?php
							}
							if($order=="down" && $sortby=='rb'){
								?><span class="dashicons dashicons-arrow-down-alt2"></span><?php
							}
							if($order=="up" && $sortby=='rb'){
								?><span class="dashicons dashicons-arrow-up-alt2"></span><?php
							}?></th><?php
						break;
						case 'ty': ?><th id="ty" onclick="sort_tickets('ty');"><?php _e('Type','wp-support-plus-responsive-ticket-system');?><?php
							if($order=="" && $sortby=='ty'){
								?><span class="dashicons"></span><?php
							}
							if($order=="down" && $sortby=='ty'){
								?><span class="dashicons dashicons-arrow-down-alt2"></span><?php
							}
							if($order=="up" && $sortby=='ty'){
								?><span class="dashicons dashicons-arrow-up-alt2"></span><?php
							}?></th><?php
						break;
						case 'ct': ?><th id="ct" onclick="sort_tickets('ct');"><?php _e($default_labels['dc'],'wp-support-plus-responsive-ticket-system');?><?php
							if($order=="" && $sortby=='ct'){
								?><span class="dashicons"></span><?php
							}
							if($order=="down" && $sortby=='ct'){
								?><span class="dashicons dashicons-arrow-down-alt2"></span><?php
							}
							if($order=="up" && $sortby=='ct'){
								?><span class="dashicons dashicons-arrow-up-alt2"></span><?php
							}?></th><?php
						break;
						case 'at': ?><th id="at" onclick="sort_tickets('at');"><?php _e('Assigned to','wp-support-plus-responsive-ticket-system');?><?php
							if($order=="" && $sortby=='at'){
								?><span class="dashicons"></span><?php
							}
							if($order=="down" && $sortby=='at'){
								?><span class="dashicons dashicons-arrow-down-alt2"></span><?php
							}
							if($order=="up" && $sortby=='at'){
								?><span class="dashicons dashicons-arrow-up-alt2"></span><?php
							}?></th><?php
						break;
						case 'pt': ?><th id="pt" onclick="sort_tickets('pt');"><?php _e($default_labels['dp'],'wp-support-plus-responsive-ticket-system');?><?php
							if($order=="" && $sortby=='pt'){
								?><span class="dashicons"></span><?php
							}
							if($order=="down" && $sortby=='pt'){
								?><span class="dashicons dashicons-arrow-down-alt2"></span><?php
							}
							if($order=="up" && $sortby=='pt'){
								?><span class="dashicons dashicons-arrow-up-alt2"></span><?php
							}?></th><?php
						break;
						case 'ut': ?><th id="ut" onclick="sort_tickets('ut');"><?php _e('Updated','wp-support-plus-responsive-ticket-system');?><?php
							if($order=="" && $sortby=='ut'){
								?><span class="dashicons"></span><?php
							}
							if($order=="down" && $sortby=='ut'){
								?><span class="dashicons dashicons-arrow-down-alt2"></span><?php
							}
							if($order=="up" && $sortby=='ut'){
								?><span class="dashicons dashicons-arrow-up-alt2"></span><?php
							}?></th><?php
						break;
						case 'cdt': ?><th id="cdt" onclick="sort_tickets('cdt');"><?php _e('Date Created','wp-support-plus-responsive-ticket-system');?><?php
							if($order=="" && $sortby=='cdt'){
								?><span class="dashicons"></span><?php
							}
							if($order=="down" && $sortby=='cdt'){
								?><span class="dashicons dashicons-arrow-down-alt2"></span><?php
							}
							if($order=="up" && $sortby=='cdt'){
								?><span class="dashicons dashicons-arrow-up-alt2"></span><?php
							}?></th><?php
						break;
						case 'udt': ?><th id="udt" onclick="sort_tickets('udt');"><?php _e('Date Updated','wp-support-plus-responsive-ticket-system');?><?php
							if($order=="" && $sortby=='udt'){
								?><span class="dashicons"></span><?php
							}
							if($order=="down" && $sortby=='udt'){
								?><span class="dashicons dashicons-arrow-down-alt2"></span><?php
							}
							if($order=="up" && $sortby=='udt'){
								?><span class="dashicons dashicons-arrow-up-alt2"></span><?php
							}?></th><?php
						break;
                                                case 'acd': ?><th id="acd" onclick="sort_tickets('acd');"><?php _e('Agent Created','wp-support-plus-responsive-ticket-system');?><?php
							if($order=="" && $sortby=='acd'){
								?><span class="dashicons"></span><?php
							}
							if($order=="down" && $sortby=='acd'){
								?><span class="dashicons dashicons-arrow-down-alt2"></span><?php
							}
							if($order=="up" && $sortby=='acd'){
								?><span class="dashicons dashicons-arrow-up-alt2"></span><?php
							}?></th><?php
						break;
                                                default:                                                 
                                                        do_action('wpsp_add_th_in_ticket_list',$order,$sortby);                                                 
                                                        break;
					}
				}
			}
		}
		?>
	  </tr>
	  <?php 
	  foreach ($tickets as $ticket){
                $assign_to=array();
                $assign_to=explode(',',$ticket->assigned_to);
                if($cu->has_cap('manage_support_plus_agent')){
                    if($_POST['filter_by_assigned_to']!='all'&& !(array_search($_POST['filter_by_assigned_to'], $assign_to) > -1)){
                        continue;
                    }
                }
                if(apply_filters('wpsp_check_current_ticket_in_list',false,$ticket,$cu)){
                     continue;
                }
              
		$raised_by='';
		if($ticket->type=='user'){
			$user=get_userdata( $ticket->created_by );
			$raised_by=$user->display_name;
		}
		else{
			$raised_by=$ticket->guest_name;
		}
		
		$modified='';
		if ($ticket->date_modified_month) $modified=$ticket->date_modified_month.' '.__('months ago','wp-support-plus-responsive-ticket-system');
		else if ($ticket->date_modified_day) $modified=$ticket->date_modified_day.' '.__('days ago','wp-support-plus-responsive-ticket-system');
		else if ($ticket->date_modified_hour) $modified=$ticket->date_modified_hour.' '.__('hours ago','wp-support-plus-responsive-ticket-system');
		else if ($ticket->date_modified_min) $modified=$ticket->date_modified_min.' '.__('minutes ago','wp-support-plus-responsive-ticket-system');
		else $modified=$ticket->date_modified_sec.' '.__('seconds ago','wp-support-plus-responsive-ticket-system');
		
		/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
		 * Update 1 - Change Custom Status Color
		 * Create style for custom status
		 */
		$status_color='';
		$style = '';
		switch ($ticket->status){
			case 'open': 
				$style = ( $ticket->color != NULL && $ticket->color != '' ) ? ' background-color:' . $ticket->color . ' !important;' : '';
				$status_color='danger';
				break;
			case 'pending': 
				$style = ( $ticket->color != NULL && $ticket->color != '' ) ? ' background-color:' . $ticket->color . ' !important;' : '';
				$status_color='warning';
				break;
			case 'closed': 
				$style = ( $ticket->color != NULL && $ticket->color != '' ) ? ' background-color:' . $ticket->color . ' !important;' : '';
				$status_color='success';
				break;
			default :
				$style = ( $ticket->color != NULL && $ticket->color != '' ) ? ' background-color:' . $ticket->color . ' !important;' : '';
				$status_color='info';
				break;
		}
		/* END CLOUGH I.T. SOLUTIONS MODIFICATION
		*/
		$priority_color='';
		switch ($ticket->priority){
			case 'high': $priority_color=$ticket->pcolor;break;
			case 'medium': $priority_color=$ticket->pcolor;break;
			case 'normal': $priority_color=$ticket->pcolor;break;
			case 'low': $priority_color=$ticket->pcolor;break;
			default :
				$priority_color=$ticket->pcolor;
				break;
		}
		
		$agent_name='';
		if($ticket->assigned_to=='0'){
			$agent_name="None";
		}
		else {
			$assigned_users=explode(',', $ticket->assigned_to);
			$u_display_names=array();
			foreach ($assigned_users as $user){
				$userdata=get_userdata($user);
				$u_display_names[]=$userdata->display_name;
			}
			$agent_name=implode(',',$u_display_names);
		}
		$agent_created='';
		if($ticket->agent_created!='0'){
			$user=get_userdata( $ticket->agent_created);
			$agent_created=$user->display_name;
		}

                $css='cursor:pointer;';
                $css=apply_filters('wpsp_ticket_list_tr_style_backend',$css,$ticket);
		$disabled=apply_filters('wpsp_disable_ticket_list_checkbox_backend','',$ticket,$cu);
		echo "<tr style='".$css."'  onclick='if(link)openTicket(".$ticket->id.");'>";
		echo "<td onmouseover='link=false;' onmouseout='link=true;'><input id='".$ticket->id."' type='checkbox' class='bulk_action_checkbox' onchange='wpspCheckBulkActionVisibility();' name='selected[]' value='".$ticket->id."' ".$disabled."/></td>";
		
		foreach($advancedSettingsTicketList['backend_ticket_list'] as $backend_ticket_field_key => $backend_ticket_field_value){
				if($backend_ticket_field_value==1){
						if(is_numeric($backend_ticket_field_key)){
							$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields WHERE id='".$backend_ticket_field_key."'" );
							foreach($customFields as $field)
							{
                                                            $value='cust'.$backend_ticket_field_key;
							?><td><?php echo $ticket->{$value};?></td><?php
						}
					}
					else
					{
						switch($backend_ticket_field_key){
							case 'id': echo "<td>".__($ticket->id,'wp-support-plus-responsive-ticket-system')." </td>";
							break;
							case 'st': echo "<td><span class='label label-".$status_color."' style='font-size: 13px;".$style."'>".__(ucfirst($ticket->status),'wp-support-plus-responsive-ticket-system')."<span></td>";
							break;
							case 'sb':$str_dots=""; 
								if(strlen(stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES))) > $subCharLength['backend'])
								{
									$str_dots="...";
								}
								echo "<td title='".stripslashes(htmlspecialchars_decode($ticket->subject))."'>".substr(stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES)), 0,$subCharLength['backend']).$str_dots."</td>";
							break;
							case 'rb': echo "<td>".__($raised_by,'wp-support-plus-responsive-ticket-system')."</td>";
							break;
							case 'ty': echo "<td>".__(ucfirst($ticket->type),'wp-support-plus-responsive-ticket-system')."</td>";
							break;
							case 'ct': echo "<td>".__($ticket->category,'wp-support-plus-responsive-ticket-system')."</td>";
							break;
							case 'at': echo "<td>".__($agent_name,'wp-support-plus-responsive-ticket-system')."</td>";
							break;
							case 'pt': echo "<td><span class='label label-".$priority_color."' style='font-size: 13px;background-color:".$priority_color."'>".__(ucfirst($ticket->priority),'wp-support-plus-responsive-ticket-system')."</span></td>";
							break;
							case 'ut': echo "<td>".__($modified,'wp-support-plus-responsive-ticket-system')."</td>";
							break;
							case 'cdt': 
								if($dateFormat['cdt_backend']=="")
								{
									$cdt=date_i18n( get_option( 'date_format' ), strtotime( get_date_from_gmt( $ticket->create_date, 'Y-m-d H:i:s') ) ) . ' ' . get_date_from_gmt( $ticket->create_date, 'H:i:s');
								}
								else
								{
									$cdt=date_i18n( $dateFormat['cdt_backend'], strtotime( get_date_from_gmt( $ticket->create_date, $dateFormat['cdt_backend']) ) );
								}
								echo "<td>".__($cdt,'wp-support-plus-responsive-ticket-system')."</td>";
							break;
							case 'udt': 
								if($dateFormat['udt_backend']=="")
								{
									$udt=date_i18n( get_option( 'date_format' ), strtotime( get_date_from_gmt( $ticket->update_date, 'Y-m-d H:i:s') ) ) . ' ' . get_date_from_gmt( $ticket->update_date, 'H:i:s');
								}
								else
								{
									$udt=date_i18n( $dateFormat['udt_backend'], strtotime( get_date_from_gmt( $ticket->update_date, $dateFormat['udt_backend']) ) );
								}
								echo "<td>".__($udt,'wp-support-plus-responsive-ticket-system')."</td>";
							break;
                                                        case 'acd': echo "<td>".__($agent_created,'wp-support-plus-responsive-ticket-system')."</td>";
							break;
                                                        default:                                                         
                                                                do_action('wpsp_add_td_in_ticket_list',$ticket);                                                         
                                                                break;
						}
					}
				}
			}
		echo "</tr>";
	  	}
	  ?>
	</table>
	<?php 
	$prev_page_no=$current_page-1;
	$prev_class=(!$prev_page_no)?'disabled':'';
	$next_page_no=($total_pages==$current_page)?$current_page-1:$current_page;
	$next_class=($total_pages==$current_page)?'disabled':'';
	?>
	<ul class="pager" style="<?php echo ($total_pages==0)? 'display: none;':'';?>">
	  <li class="previous <?php echo $prev_class;?>"><a href="javascript:load_prev_page(<?php echo $prev_page_no;?>,'<?php echo $sortby;?>','<?php echo $order;?>');">&larr; <?php _e('Newer','wp-support-plus-responsive-ticket-system');?></a></li>
	  <li><?php echo $current_page;?> <?php _e('of','wp-support-plus-responsive-ticket-system');?> <?php echo $total_pages;?> <?php _e('Pages','wp-support-plus-responsive-ticket-system');?></li>
	  <li class="next <?php echo $next_class;?>"><a href="javascript:load_next_page(<?php echo $next_page_no;?>,'<?php echo $sortby;?>','<?php echo $order;?>');"><?php _e('Older','wp-support-plus-responsive-ticket-system');?> &rarr;</a></li>
	</ul>
	<div style="text-align: center;<?php echo ($total_pages==0)? '':'display: none;';?>"><?php _e($advancedSettings['ticket_label_alice'][20],'wp-support-plus-responsive-ticket-system');?></div>
	<hr style="<?php echo ($total_pages==0)? '':'display: none;';?>">
</div>
<script>
    var currentScreen='ticket_list';
</script>