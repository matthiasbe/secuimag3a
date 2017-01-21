<?php 
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();

$advancedSettings=get_option( 'wpsp_advanced_settings' );
$advancedSettingsTicketList=get_option( 'wpsp_advanced_settings_ticket_list_order' );
$subCharLength=get_option( 'wpsp_ticket_list_subject_char_length' );

$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];

$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$customFieldSql='';
foreach ($customFields as $field){
	$customFieldSql.='t.cust'.$field->id.',';
}

$dateFormat = get_option( 'wpsp_ticket_list_date_format' );

$user_filter=array();
foreach ($_POST as $key=>$val){
	if($key!='page_no' && $key!='action')
	$user_filter[$key]=$val;
}
update_user_meta( $current_user->ID, 'wpspFrontEndFilter', $user_filter );

$strFilterNotActive=__('Apply Filter','wp-support-plus-responsive-ticket-system');
$strFilterActive=__('Show Active Filter','wp-support-plus-responsive-ticket-system');
$isWpspFilterAcive=false;
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

$filterReset=array();
foreach($flters_key as $key){
	if($key != 'filter_by_selection_front' && $key != 'filter_by_no_of_ticket_front' && $key != 'filter_by_search_front') $filterReset[$key]='all';
	$filterReset['filter_by_selection_front']='text';
	$filterReset['filter_by_no_of_ticket_front']='10';
	$filterReset['filter_by_search_front']='';
}

$filterReset=apply_filters('wpsp_add_filters_reset_key_front',$filterReset);

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
		jQuery('#wpspBtnApplyFrontTicketFilter').text('<?php echo $strFilterActive;?>');
		jQuery('#wpspBtnResetFrontTicketFilter').show();
	<?php }
	else {?>
		jQuery('#wpspBtnApplyFrontTicketFilter').text('<?php echo $strFilterNotActive;?>');
		jQuery('#wpspBtnResetFrontTicketFilter').hide();
	<?php }?>
</script>
<?php

/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 1 - Change Custom Status Color
 * Join custom status color database entry
 */
$sql="select t.id,t.type,t.subject,t.status,c.name as category,c.id as cat_id,t.assigned_to,t.priority,t.created_by,t.guest_email,t.guest_name,cs.color,cp.color as pcolor,".$customFieldSql."  
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
                
$sql=apply_filters('wpsp_get_ticket_list_frontend_sql',$sql,$customFieldSql);

$where="WHERE ";

$flagUseWhere=true;
if($current_user->has_cap('manage_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket'))
{
	$where.="(t.ticket_type=1 OR t.ticket_type=0) ";
}
else if(!$current_user->has_cap('manage_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket'))
{
	$where.="(t.assigned_to LIKE '%".$current_user->ID."%' OR t.assigned_to='0' OR t.created_by='".$current_user->ID."' OR t.ticket_type=1) ";
}
else
{
	$where.="(t.created_by=".$current_user->ID." OR t.ticket_type=1 OR t.guest_email='".$current_user->user_email."') ";
}

if(isset($_POST['filter_by_status_front']) && $_POST['filter_by_status_front'] != 'all' ) {
	$where .= "AND t.status='" . $_POST['filter_by_status_front'] . "' ";
}else if($advancedSettings['hide_selected_status_ticket']!='none'){
        $where .= "AND t.status <> '" . $advancedSettings['hide_selected_status_ticket'] . "' ";
}

if(isset($_POST['filter_by_category_front']) && $_POST['filter_by_category_front']!='all'){
	$where.="AND c.id='".$_POST['filter_by_category_front']."' ";
}

$customFieldsDropDown = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields where field_type=2 OR field_type=4" );
foreach ($customFieldsDropDown as $field){
	if(isset($_POST['cust'.$field->id]) && $_POST['cust'.$field->id]!='all'){
		$where.="AND t.cust".$field->id."='".$_POST['cust'.$field->id]."' ";
	}
}

if(isset($_POST['filter_by_selection_front'])){
	switch($_POST['filter_by_selection_front'])
	{
		case 'id':
			if($_POST['filter_by_search_front']!=''){
				//custome fields
				$custCondition='';
				$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
				$total_cust_field=$wpdb->num_rows;
				if($total_cust_field){
					foreach ($customFields as $field){
						$custCondition.="OR t.cust".$field->id." LIKE '%".$_POST['filter_by_search_front']."%' ";
					}
				}
	
				$where.="AND t.id IN (SELECT DISTINCT t.id from {$wpdb->prefix}wpsp_ticket t INNER JOIN {$wpdb->prefix}wpsp_ticket_thread th ON t.id=th.ticket_id WHERE t.id=".$_POST['filter_by_search_front']." OR t.id LIKE '%".$_POST['filter_by_search_front']."%') ";
			}
			break;
		case 'text':
			if($_POST['filter_by_search_front']!=''){
				//custome fields
				$custCondition='';
				$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
				$total_cust_field=$wpdb->num_rows;
				if($total_cust_field){
					foreach ($customFields as $field){
						$custCondition.="OR t.cust".$field->id." LIKE '%".$_POST['filter_by_search_front']."%' ";
					}
				}
	
				$where.="AND t.id IN (SELECT DISTINCT t.id from {$wpdb->prefix}wpsp_ticket t INNER JOIN {$wpdb->prefix}wpsp_ticket_thread th ON t.id=th.ticket_id WHERE t.subject LIKE '%".$_POST['filter_by_search_front']."%' OR th.body LIKE '%".$_POST['filter_by_search_front']."%' ".$custCondition.") ";
			}
			break;
                case 'created_by':
 		    if($_POST['filter_by_search_front']!=''){
 			$term=esc_attr( $_POST['filter_by_search_front'] );
 			$sql1 = "SELECT * FROM " . $wpdb->base_prefix . "users WHERE 1=1 AND (user_login LIKE '%" . $term . "%' OR user_email LIKE '%" . $term . "%' OR display_name LIKE '%" . $term . "%')";
 			$users = $wpdb->get_results( $sql1 );
 			if(is_array($users) && count($users)>0)
 			{
 				$user_ids=array();
 				foreach($users as $user)
 				{
 					$user_ids=array_merge($user_ids,array($user->ID));
 				}
 				$where.="AND ( t.id IN (SELECT DISTINCT t.id from {$wpdb->prefix}wpsp_ticket t INNER JOIN {$wpdb->prefix}wpsp_ticket_thread th ON t.id=th.ticket_id WHERE t.created_by IN (".implode(",",$user_ids).")) OR ";
 				$where.="t.id IN (SELECT DISTINCT t.id from {$wpdb->prefix}wpsp_ticket t INNER JOIN {$wpdb->prefix}wpsp_ticket_thread th ON t.id=th.ticket_id WHERE t.guest_name LIKE '%".$_POST['filter_by_search_front']."%' OR t.guest_email LIKE'%".$_POST['filter_by_search_front']."%')) ";
 			}
 			else
 			{
 				$where.="AND t.id IN (SELECT DISTINCT t.id from {$wpdb->prefix}wpsp_ticket t INNER JOIN {$wpdb->prefix}wpsp_ticket_thread th ON t.id=th.ticket_id WHERE t.guest_name LIKE '%".$_POST['filter_by_search_front']."%' OR t.guest_email LIKE'%".$_POST['filter_by_search_front']."%') ";
 			}
 		    }
		    break;
	}
}

$where=apply_filters('wpsp_get_ticket_list_where_frontend',$where,$customFieldsDropDown,$current_user);

$order_by='ORDER BY t.update_time DESC ';

$order_by=apply_filters('wpsp_get_ticket_list_orderby_frontend',$order_by);

if(!isset($_POST['filter_by_no_of_ticket_front']))
{
	$_POST['filter_by_no_of_ticket_front']=10;
}

/*
* Get total page count
*/
$findTotalRowsSQL="select count(*) "
       . "FROM {$wpdb->prefix}wpsp_ticket t 
              INNER JOIN {$wpdb->prefix}wpsp_catagories c ON t.cat_id=c.id 
              LEFT JOIN {$wpdb->prefix}wpsp_custom_status cs ON t.status=cs.name 
              LEFT JOIN {$wpdb->prefix}wpsp_custom_priority cp ON t.priority=cp.name ";

$findTotalRowsSQL=apply_filters('wpsp_get_ticket_list_count_frontend_sql',$findTotalRowsSQL);

$totalrows = $wpdb->get_var( $findTotalRowsSQL.$where );
$current_page=$_POST['page_no']+1;
$total_pages=ceil($totalrows/$_POST['filter_by_no_of_ticket_front']);

$limit_start=$_POST['page_no']*$_POST['filter_by_no_of_ticket_front'];
$limit="LIMIT ".$limit_start.",".$_POST['filter_by_no_of_ticket_front']." ";

$sql.=$where;
$sql.=$order_by;

$sql.=$limit;
$tickets = $wpdb->get_results( $sql );
?>
<div class="table-responsive">
	<table id="tblFontEndTickets" class="table table-striped table-hover">
	  <tr>
	<?php 
		foreach($advancedSettingsTicketList['frontend_ticket_list'] as $frontend_ticket_field_key => $frontend_ticket_field_value)
		{
			if($frontend_ticket_field_value)
			{
				if(is_numeric($frontend_ticket_field_key))
				{
					$field = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields WHERE id='".$frontend_ticket_field_key."'" );
					if($field->isVarFeild && $current_user->has_cap('manage_support_plus_ticket')){
                                             ?><th  id="wpsp_cust<?php echo $field->id;?>"><?php echo $field->label;?></th><?php
                                        } else if($field->isVarFeild==0){
                                             ?><th  id="wpsp_cust<?php echo $field->id;?>"><?php echo $field->label;?></th><?php
                                        }
				}
				else
				{
					switch($frontend_ticket_field_key){
						case 'id': ?><th  id="wpsp_front_id">#</th><?php
						break;
						case 'st': ?><th id="wpsp_front_status"><?php _e('Status','wp-support-plus-responsive-ticket-system');?></th><?php
						break;
						case 'sb': ?><th id="wpsp_front_subject"><?php _e($default_labels['ds'],'wp-support-plus-responsive-ticket-system');?></th><?php
						break;
                                                case 'rb': if($current_user->has_cap('manage_support_plus_ticket')){
                                                           ?><th id="wpsp_front_raised_by"><?php _e('Raised By','wp-support-plus-responsive');?></th><?php
                                                           }
                                                break;
						case 'ct': ?><th id="wpsp_front_category"><?php _e($default_labels['dc'],'wp-support-plus-responsive-ticket-system');?></th><?php
						break;
						case 'at': if($current_user->has_cap('manage_support_plus_ticket')){
							?><th id="wpsp_front_assign"><?php _e('Assigned to','wp-support-plus-responsive-ticket-system');?></th><?php
							}
						break;
						case 'pt': ?><th class="priority" id="wpsp_front_priority"><?php _e($default_labels['dp'],'wp-support-plus-responsive-ticket-system');?></th><?php
						break;
						case 'ut': ?><th class="updated" id="wpsp_front_updated"><?php _e('Updated','wp-support-plus-responsive-ticket-system');?></th><?php
						break;
						case 'cdt': ?><th id="wpsp_front_created"><?php _e('Date Created','wp-support-plus-responsive-ticket-system');?></th><?php
						break;
						case 'udt': ?><th id="wpsp_front_updated"><?php _e('Date Updated','wp-support-plus-responsive-ticket-system');?></th><?php
						break;
                                            default:                                                     
                                                do_action('wpsp_add_th_in_ticket_list_for_frontend');                                                    
                                                break;
					}
				}
			}
		}
		?>
	  </tr>
	  <?php 
	  foreach ($tickets as $ticket){
		
		if(apply_filters('wpsp_check_current_ticket_in_list',false,$ticket,$current_user)){
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
		
                $css='cursor:pointer';
                $css=apply_filters('wpsp_ticket_list_tr_style_frontend',$css,$ticket);
		echo "<tr style='".$css."' onclick='openTicket(".$ticket->id.");'>";

		foreach($advancedSettingsTicketList['frontend_ticket_list'] as $frontend_ticket_field_key => $frontend_ticket_field_value){
			if($frontend_ticket_field_value==1){
				if(is_numeric($frontend_ticket_field_key)){
					$field = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields WHERE id='".$frontend_ticket_field_key."'" );
                                        
					if($field->isVarFeild && $current_user->has_cap('manage_support_plus_ticket')){
                                             $value='cust'.$frontend_ticket_field_key;
                                             ?><td><?php echo $ticket->{$value};?></td><?php
                                        } else if($field->isVarFeild==0){
                                             $value='cust'.$frontend_ticket_field_key;
                                             ?><td><?php echo $ticket->{$value};?></td><?php
                                        }
				}
				else {
					switch($frontend_ticket_field_key){
						case 'id': echo "<td>".__($ticket->id,'wp-support-plus-responsive-ticket-system')."</td>";
									break;
						case 'st': echo "<td><span class='label label-".$status_color."' style='font-size: 13px;".$style."'>".__(ucfirst($ticket->status),'wp-support-plus-responsive-ticket-system')."<span></td>";
									break;
						case 'sb': $str_dots=""; 
							if(strlen(stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES))) > $subCharLength['frontend'])
							{
								$str_dots="...";
							}
							echo "<td title='".stripslashes(htmlspecialchars_decode($ticket->subject))."'>".substr(stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES)), 0,$subCharLength['frontend']).$str_dots."</td>";
									break;
						case 'ct': echo "<td class='category'>".__($ticket->category,'wp-support-plus-responsive-ticket-system')."</td>";
									break;
						case 'at': if($current_user->has_cap('manage_support_plus_ticket')){
										echo "<td>".__($agent_name,'wp-support-plus-responsive-ticket-system')."</td>";
									}
									break;
                                                case 'rb': if($current_user->has_cap('manage_support_plus_ticket')){
                                                                                echo "<td>".__($raised_by,'wp-support-plus-responsive-ticket-system')."</td>";
                                                                        }
									break;
						case 'pt': echo "<td class='priority'><span class='label label-".$priority_color."' style='font-size: 13px;background-color:".$priority_color."'>".__(ucfirst($ticket->priority),'wp-support-plus-responsive-ticket-system')."</span></td>";
						break;
						case 'ut': echo "<td>".__($modified,'wp-support-plus-responsive-ticket-system')."</td>";
						break;
						case 'cdt': 
							if($dateFormat['cdt_frontend']=="")
							{
								$cdt=date_i18n( get_option( 'date_format' ), strtotime( get_date_from_gmt( $ticket->create_date, 'Y-m-d H:i:s') ) ) . ' ' . get_date_from_gmt( $ticket->create_date, 'H:i:s');
							}
							else
							{
								$cdt=date_i18n( $dateFormat['cdt_frontend'], strtotime( get_date_from_gmt( $ticket->create_date, $dateFormat['cdt_frontend']) ) );
							}
							echo "<td>".__($cdt,'wp-support-plus-responsive-ticket-system')."</td>";
						break;
						case 'udt': 
							if($dateFormat['udt_frontend']=="")
							{
								$udt=date_i18n( get_option( 'date_format' ), strtotime( get_date_from_gmt( $ticket->update_date, 'Y-m-d H:i:s') ) ). ' ' . get_date_from_gmt( $ticket->update_date, 'H:i:s');
							}
							else
							{
								$udt=date_i18n( $dateFormat['udt_frontend'], strtotime( get_date_from_gmt( $ticket->create_date, $dateFormat['udt_frontend']) ) );
							}
							echo "<td>".__($udt,'wp-support-plus-responsive-ticket-system')."</td>";
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
	  <li class="previous <?php echo $prev_class;?>"><a href="javascript:load_prev_page(<?php echo $prev_page_no;?>);">&larr; <?php _e('Newer','wp-support-plus-responsive-ticket-system');?></a></li>
	  <li><?php echo $current_page;?> <?php _e('of','wp-support-plus-responsive-ticket-system');?> <?php echo $total_pages;?> <?php _e('Pages','wp-support-plus-responsive-ticket-system');?></li>
	  <li class="next <?php echo $next_class;?>"><a href="javascript:load_next_page(<?php echo $next_page_no;?>);"><?php _e('Older','wp-support-plus-responsive-ticket-system');?> &rarr;</a></li>
	</ul>
	<div style="text-align: center;<?php echo ($total_pages==0)? '':'display: none;';?>"><?php _e($advancedSettings['ticket_label_alice'][20],'wp-support-plus-responsive-ticket-system');?></div>
	<hr style="<?php echo ($total_pages==0)? '':'display: none;';?>">
</div>
<script>
    var currentScreen='ticket_list';
</script>