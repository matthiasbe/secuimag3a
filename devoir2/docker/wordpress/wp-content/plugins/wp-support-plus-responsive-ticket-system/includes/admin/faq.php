<?php 
$page=(isset($_REQUEST['type']))?$_REQUEST['type']:'show';
switch ($page){
	case 'show': include( WCE_PLUGIN_DIR.'includes/admin/showFAQ.php' );
					break;
	case 'add' : include( WCE_PLUGIN_DIR.'includes/admin/addFAQ.php' );
					break;
	case 'edit' : include( WCE_PLUGIN_DIR.'includes/admin/editFAQ.php' );
					break;
	case 'delete' : include( WCE_PLUGIN_DIR.'includes/admin/deleteFAQ.php' );
					break;
	case 'editThread' : include( WCE_PLUGIN_DIR.'includes/admin/editThread.php' );
					break;
	case 'editTicket' : include( WCE_PLUGIN_DIR.'includes/admin/editTicket.php' );
					break;
}
?>
