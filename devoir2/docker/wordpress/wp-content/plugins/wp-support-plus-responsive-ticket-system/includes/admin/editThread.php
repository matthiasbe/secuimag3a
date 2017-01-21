<h3>Edit Thread</h3><br>
<?php 
global $wpdb;

if(isset($_REQUEST['action'])){
	//code to insert into db
	$values=array(
		'body'=>htmlspecialchars($_REQUEST['wpsp_thread'],ENT_QUOTES)
	);
	$wpdb->update($wpdb->prefix.'wpsp_ticket_thread',$values,array('id'=>$_REQUEST['id']));
	wp_redirect(admin_url('admin.php?page=wp-support-plus')); 
}

$thread=$wpdb->get_row("select * from {$wpdb->prefix}wpsp_ticket_thread where id=".$_REQUEST['id']);
?>
<form method="post" action="<?php echo admin_url('admin.php?page=wp-support-plus-faq&type=editThread&action=set&noheader=true&id='.$_REQUEST['id']);?>">
	<?php 
	$settings = array( 'media_buttons' => true, 'wpautop'=>false );
	wp_editor( stripcslashes(htmlspecialchars_decode($thread->body,ENT_QUOTES)), 'wpsp_thread', $settings );
	?>
	<br>
	<button type="submit" class="btn btn-success">Submit</button>
</form>
