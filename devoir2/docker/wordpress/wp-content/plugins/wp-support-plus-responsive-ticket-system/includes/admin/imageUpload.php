<?php
$UploadImageSettings=get_option( 'wpsp_upload_image_settings' );

$upload_dir = wp_upload_dir();
$ext;
if(($_FILES['leftSupportBtn']['name'])){
    $count=1;
    if(isset($UploadImageSettings['filecountLeft'])){
        $count=$UploadImageSettings['filecountLeft'];
    } else {
        $UploadImageSettings['filecountLeft']=$count;
    }
    
    $info = pathinfo($_FILES['leftSupportBtn']['name']);
    $ext = $info['extension']; // get the extension of the file
    $oldname = "leftSupportBtn".$count.".".$ext;
    $oldTargetPath = $upload_dir['basedir'].'/'.$oldname;
    if(file_exists($oldTargetPath)){
        unlink($oldTargetPath);
    }
    
    $newname = "leftSupportBtn".($count+1).".".$ext;
    $targetPath = $upload_dir['basedir'].'/'.$newname;
    $targetURL = $upload_dir['baseurl'].'/'.$newname;
    move_uploaded_file( $_FILES['leftSupportBtn']['tmp_name'], $targetPath);
    $UploadImageSettings['leftSupportButton']=$targetURL;
    
    $UploadImageSettings['filecountLeft']=$count+1;
}

if(($_FILES['rightSupportBtn']['name'])){
    $count=1;
    if(isset($UploadImageSettings['filecountRight'])){
        $count=$UploadImageSettings['filecountRight'];
    } else {
        $UploadImageSettings['filecountRight']=$count;
    }
    
    $info = pathinfo($_FILES['rightSupportBtn']['name']);
    $ext = $info['extension']; // get the extension of the file
    
    $oldname = "rightSupportBtn".$count.".".$ext;
    $oldTargetPath = $upload_dir['basedir'].'/'.$oldname;
    if(file_exists($oldTargetPath)){
        unlink($oldTargetPath);
    }
    
    $newname = "rightSupportBtn".($count+1).".".$ext;
    $targetPath = $upload_dir['basedir'].'/'.$newname;
    $targetURL = $upload_dir['baseurl'].'/'.$newname;
    move_uploaded_file( $_FILES['rightSupportBtn']['tmp_name'], $targetPath);
    $UploadImageSettings['rightSupportButton']=$targetURL;
    
    $UploadImageSettings['filecountRight']=$count+1;
}

if(($_FILES['support_panel_icon']['name'])){
    $count=1;
    if(isset($UploadImageSettings['filecountPanelIcon'])){
        $count=$UploadImageSettings['filecountPanelIcon'];
    } else {
        $UploadImageSettings['filecountPanelIcon']=$count;
    }
    
    $info = pathinfo($_FILES['support_panel_icon']['name']);
    $ext = $info['extension']; // get the extension of the file
    
    $oldname = "support_panel_icon".$count.".".$ext;
    $oldTargetPath = $upload_dir['basedir'].'/'.$oldname;
    if(file_exists($oldTargetPath)){
        unlink($oldTargetPath);
    }
    
    $newname = "support_panel_icon".($count+1).".".$ext;
    $targetPath = $upload_dir['basedir'].'/'.$newname;
    $targetURL = $upload_dir['baseurl'].'/'.$newname;
    move_uploaded_file( $_FILES['support_panel_icon']['tmp_name'], $targetPath);
    $UploadImageSettings['panel_image']=$targetURL;   
    
    $UploadImageSettings['filecountPanelIcon']=$count+1;
}

update_option('wpsp_upload_image_settings',$UploadImageSettings);
?>
