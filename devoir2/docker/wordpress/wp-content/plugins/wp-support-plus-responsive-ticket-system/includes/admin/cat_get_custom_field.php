<?php
$cat_id=$_POST['cat_id'];
global $wpdb;
$cust_fields = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpsp_custom_fields");
$field_ids=array();
$field_ids_other=array();
foreach ($cust_fields as $field){
    $field_categories=  explode(',', $field->field_categories);
    if(array_search($cat_id, $field_categories)> -1){
        $field_ids[]=$field->id;
    } else {
        foreach ($field_categories as $category){
            if($category!=0){
                $field_ids_other[]=array($field->id,$field->field_type);
            }
        }
    }
}
echo json_encode(array($field_ids,$field_ids_other));
?>

