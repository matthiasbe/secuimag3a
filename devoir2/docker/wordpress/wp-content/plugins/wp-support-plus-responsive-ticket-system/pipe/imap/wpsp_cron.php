<?php
class WPSPCron{
    function check_offer_and_update(){
        $siteDataWPSP = file_get_contents('http://pradeepmakone.com/wp_support_plus_update_and_offers.txt');
        $wpsp_update_notice=array(
            'text'=>$siteDataWPSP
        );
        update_option('wpsp_update_notice_txt_time',$wpsp_update_notice);
    }
    
    function attachment_garbage_collection(){
        global $wpdb;
        $sql="select * from ".$wpdb->prefix."wpsp_attachments WHERE active=0 AND upload_time < DATE_SUB(now(), INTERVAL 1 DAY)";
        $garbage_attachments=$wpdb->get_results($sql);
        foreach ($garbage_attachments as $garbage_attachment){
            if(file_exists($garbage_attachment->filepath)){
                unlink($garbage_attachment->filepath);
            }
            $wpdb->delete($wpdb->prefix.'wpsp_attachments',array('id'=>$garbage_attachment->id));
        }
    }
}
?>