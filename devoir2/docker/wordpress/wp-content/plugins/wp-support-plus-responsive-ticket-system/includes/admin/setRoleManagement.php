<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$cu = wp_get_current_user();
if (!$cu->has_cap('manage_options')) die();

$roleManage=get_option( 'wpsp_role_management' );

if($_POST['agent_role']==NULL){
	$roleManage['agents']=array();
}
else $roleManage['agents']=$_POST['agent_role'];

if($_POST['supervisor_role']==NULL){
	$roleManage['supervisors']=array();
}
else $roleManage['supervisors']=$_POST['supervisor_role'];

/*********Added for front end ticket restriction******************/
if($_POST['front_ticket_all']==1){
    $roleManage['front_ticket_all']=true;
} else {
    $roleManage['front_ticket_all']=false;
}
if(isset($_POST['front_ticket'])){
    $roleManage['front_ticket']=array_merge(array('administrator','wp_support_plus_agent','wp_support_plus_supervisor'),$_POST['front_ticket']);
} else {
    $roleManage['front_ticket']=array('administrator','wp_support_plus_agent','wp_support_plus_supervisor');
}
/*********End*****************************************************/

update_option('wpsp_role_management',$roleManage);

$roleManage=get_option( 'wpsp_role_management' );

global $wp_roles;
$userRoles=$wp_roles->roles;
foreach ($userRoles as $roleSlug=>$role){
	if($roleSlug=='administrator' || $roleSlug=='wp_support_plus_agent' || $roleSlug=='wp_support_plus_supervisor') continue;
	$role = get_role( $roleSlug );
	$role->remove_cap( 'manage_support_plus_ticket' );
	$role->remove_cap( 'manage_support_plus_agent' );
}
$userRoles=$wp_roles->roles;
foreach ($userRoles as $roleSlug=>$role){
	if($roleSlug=='administrator' || $roleSlug=='wp_support_plus_agent' || $roleSlug=='wp_support_plus_supervisor') continue;
	if(is_numeric(array_search($roleSlug,$roleManage['agents']))){
		$role = get_role( $roleSlug );
		$role->add_cap( 'manage_support_plus_ticket' );
	}
	if(is_numeric(array_search($roleSlug,$roleManage['supervisors']))){
		$role = get_role( $roleSlug );
		$role->add_cap( 'manage_support_plus_ticket' );
		$role->add_cap( 'manage_support_plus_agent' );
	}
}
?>
