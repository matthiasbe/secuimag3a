<?php 
 global $wpdb;
$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
 	//code to insert into db
$values=array(
        'subject'=>htmlspecialchars($_POST['subject'],ENT_QUOTES)
);
foreach ($customFields as $field){
    if(!apply_filters('wpsp_extra_custom_fields_db_editticket',false,$field) && isset($_POST['cust'.$field->id]) && is_array($_POST['cust'.$field->id])){
                $_POST['cust'.$field->id]=implode(",",$_POST['cust'.$field->id]);
    }
    //$values['cust'.$field->id]=(isset($_POST['cust'.$field->id]))?($_POST['cust'.$field->id]):'';
    $values['cust'.$field->id]=(isset($_POST['cust'.$field->id]))?htmlspecialchars($_POST['cust'.$field->id],ENT_QUOTES):'';
}
$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('id'=>$_REQUEST['ticket_id']));