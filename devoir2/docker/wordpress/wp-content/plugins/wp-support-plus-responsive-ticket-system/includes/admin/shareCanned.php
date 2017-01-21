<?php 
    global $wpdb;
    $cid=$_POST['cid'];
    if($_POST['cuid']){
        $cuid=implode(',', $_POST['cuid']);
    }
    else {
        $cuid='';
    }
    $wpdb->update( 
        $wpdb->prefix.'wpsp_canned_reply', 
        array(
            'sid' => $cuid
        ), 
        array( 'id' => $cid )
    );
?>
