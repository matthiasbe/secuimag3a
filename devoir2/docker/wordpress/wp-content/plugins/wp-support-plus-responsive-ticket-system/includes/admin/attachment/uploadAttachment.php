<?php
global $wpdb;
$isError=false;
$errorMessege=__('error','wp-support-plus-responsive-ticket-system');
$attachment_id=0;

if(!$_FILES){
    $isError=true;
}

if( !$isError ){
    $tempExtension=  explode('.', $_FILES[0]['name']);
    $extension=$tempExtension[count($tempExtension)-1];
    switch ($extension){
        case 'exe':
        case 'php':
        case 'js':
            $isError=true;
            $errorMessege=__('Error: file format not supported!','wp-support-plus-responsive-ticket-system');
            break;
    }
}

if( !$isError && $_FILES[0]['tmp_name']==''){
    $isError=true;
    $errorMessege=__('Error: file size exceeded allowed limit!','wp-support-plus-responsive-ticket-system');
}

if( !$isError ){
    $upload_dir = wp_upload_dir();
    if (!file_exists($upload_dir['basedir'] . '/wpsp/')) {
        mkdir($upload_dir['basedir'] . '/wpsp/', 0755, true);
    }
    $save_directory = $upload_dir['basedir'] . '/wpsp/'.time().'_'.str_replace(' ','_',$_FILES[0]['name']);
    $save_url = $upload_dir['baseurl'] . '/wpsp/'.time().'_'.str_replace(' ','_',$_FILES[0]['name']);
    move_uploaded_file($_FILES[0]['tmp_name'], $save_directory);
    
    //download key to check unique
    $key=0;
    do{
        $key=uniqid().uniqid();
        $sql="select id from {$wpdb->prefix}wpsp_attachments where download_key='".$key."'";
        $result=$wpdb->get_var($sql);
    }while ($result);
    
    $values=array(
        'filename'=>$_FILES[0]['name'],
        'filepath'=>$save_directory,
        'fileurl'=>$save_url,
        'filetype'=>$_FILES[0]['type'],
        'download_key'=>$key,
        'active'=>0
    );
    $wpdb->insert($wpdb->prefix.'wpsp_attachments',$values);
    $attachment_id= $wpdb->insert_id;
    $errorMessege=__('done','wp-support-plus-responsive');
}

$isError=($isError)?'1':'0';

echo '{';
echo '"isError":"'.$isError.'",';
echo '"errorMessege":"'.$errorMessege.'",';
echo '"attachment_id":"'.$attachment_id.'"';
echo '}';
?>