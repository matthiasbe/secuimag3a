<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$category = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}wpsp_catagories where id=".$_POST['cat_id'] );
echo stripcslashes($category->name);
?>