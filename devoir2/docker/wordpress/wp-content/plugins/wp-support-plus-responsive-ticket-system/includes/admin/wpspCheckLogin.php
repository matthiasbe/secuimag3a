<?php 
$creds = array();
	$creds['user_login'] = $_POST['username'];
	$creds['user_password'] = $_POST['password'];
	$creds['remember'] = true;
	$user = wp_signon( $creds, false );
	if ( is_wp_error($user) )
		_e('Incorrect Username or Password', 'wp-support-plus-responsive-ticket-system');
	else 'OK';
?>