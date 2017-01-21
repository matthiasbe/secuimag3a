<?php
global $wpdb;
$attachment_key=$_REQUEST['ticket_attachment'];
$attachment=$wpdb->get_row("select * from {$wpdb->prefix}wpsp_attachments where download_key='".$attachment_key."'");
if($attachment){
    $filepath=$attachment->filepath;
    $filename=$attachment->filename;
    $content_type=$attachment->filetype;
    if ($fd = fopen ($filepath, "r")) {
        $fsize = filesize($filepath);
        header("Content-type: $content_type");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Content-length: $fsize");
        header("Cache-control: private");
        while(!feof($fd)) {
            $buffer = fread($fd, $fsize);
            echo $buffer;
        }
    }
    fclose ($fd);
}
?>