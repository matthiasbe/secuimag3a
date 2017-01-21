<?php 
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();
$emailSettings=get_option( 'wpsp_email_notification_settings' );
$generalSettings=get_option( 'wpsp_general_settings' );
$wpsp_et_change_ticket_assign_agent=get_option( 'wpsp_et_change_ticket_assign_agent' );

$advancedSettings=get_option( 'wpsp_advanced_settings' );

/*****************************************************/
$sql="select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
$ticket = $wpdb->get_row( $sql );
/*****************************************************/
$ticket_created_by = $_POST['user_id'];
$wpspGuestName='';
$wpspGuestEmail='';
if($ticket_created_by==0){
	$ticket_type="guest";
        $wpspGuestName=$_POST['guest_name'];
        $wpspGuestEmail=$_POST['guest_email'];     
}
else{
	$ticket_type="user";
        $ticket_created_by=$_POST['reg_user_id'];
}
$values=array(
		'created_by'=>$ticket_created_by,
		'update_time'=>current_time('mysql', 1),
		'updated_by'=>$current_user->ID,
		'type'=>$ticket_type,
                'guest_name'=>$wpspGuestName,
                'guest_email'=>$wpspGuestEmail
);
$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('id'=>$_POST['ticket_id']));
/* Thread for change raised by*/ 
$thread_creater=$current_user->ID; if($ticket_created_by!=$ticket->created_by){     
    $threads=array(                     
        'ticket_id'=>$_POST['ticket_id'],                     
        'body'=>$ticket_created_by,                     
        'attachment_ids'=>'',                     
        'create_time'=>current_time('mysql', 1),                     
        'created_by'=>$thread_creater,                     
        'guest_name'=>$wpspGuestName,                     
        'guest_email'=>$wpspGuestEmail,                     
        'is_note'=>6     
    );     
    $wpdb->insert($wpdb->prefix.'wpsp_ticket_thread',$threads); 
}
die();
?>