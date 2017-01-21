<?php 
global $wpdb;
$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_ticket WHERE id=$_POST[ticket_id]" );
$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$total_cust_field=$wpdb->num_rows;
foreach ($results as $result){
    $res=array(
        'subject'=>$result->subject,
        'created_by'=>$result->created_by,
        'updated_by'=>$result->updated_by,
        'assigned_to'=>$result->assigned_to,
        'guest_name'=>$result->guest_name,
        'guest_email'=>$result->guest_email,
        'type'=>$result->type,
        'status'=>$result->status,
        'cat_id'=>$result-> cat_id,
        'create_time'=>$result->create_time,
        'update_time'=>$result->update_time,
        'priority'=>$result->priority,
        'ticket_type'=>$result->ticket_type
    );
 }

if($total_cust_field){ 
    foreach($customFields as $field){
        $fieldValue=$wpdb->get_var("select cust".$field->id." from {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id']);
        $res['cust'.$field->id]=$fieldValue;
   }
}
$sql=$wpdb->insert($wpdb->prefix.'wpsp_ticket',$res);

$ticket_id = $wpdb->insert_id;
$threads = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_ticket_thread WHERE ticket_id=$_POST[ticket_id]" );
foreach($threads as $thread){
    $th=array(
        'ticket_id'=>$ticket_id,
        'body'=>$thread->body,
        'attachment_ids'=>$thread->attachment_ids,
        'create_time'=>$thread->create_time,
        'created_by'=>$thread->created_by,
        'guest_name'=>$thread->guest_name,
        'guest_email'=>$thread->guest_email,
        'is_note'=>$thread->is_note
    );
    $sql=$wpdb->insert($wpdb->prefix.'wpsp_ticket_thread',$th);
}
?>
{"ticket_id":"<?php echo $ticket_id;?>"}