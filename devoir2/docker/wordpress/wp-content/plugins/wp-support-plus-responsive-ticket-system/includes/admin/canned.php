<?php 
$page=(isset($_REQUEST['type']))?$_REQUEST['type']:'show';
switch ($page){
	case 'show': include( WCE_PLUGIN_DIR.'includes/admin/showcanned.php' );
					break;
	case 'add' : include( WCE_PLUGIN_DIR.'includes/admin/addcanned.php' );
					break;
	case 'edit' : include( WCE_PLUGIN_DIR.'includes/admin/editcanned.php' );
					break;
	case 'delete' : include( WCE_PLUGIN_DIR.'includes/admin/deletcanned.php' );
					break;
	
}
?>
