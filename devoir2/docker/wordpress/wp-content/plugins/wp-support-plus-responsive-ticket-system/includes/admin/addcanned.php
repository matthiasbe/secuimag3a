<h3>Add New Canned Reply</h3><br>
<?php 
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();

if(isset($_REQUEST['action'])){
	//code to insert into db
	$values=array(
			'title'=>$_REQUEST['title'],
			'reply'=>$_REQUEST['wpsp_canned_reply'],
			'uID'=> $current_user->ID
	);
        $values=apply_filters('wpsp_insert_field_in_canned_reply_table',$values);
	$wpdb->insert($wpdb->prefix.'wpsp_canned_reply',$values);
	wp_redirect(admin_url('admin.php?page=wp-support-plus-Canned-Reply')); 
}

?>
<form id="wpsp_add_canned" method="post" action="<?php echo admin_url('admin.php?page=wp-support-plus-Canned-Reply&type=add&action=set&noheader=true');?>">
	
	<b>Title:</b><br>
	<input type="text" id="wpsp_title" name="title" style="width: 100%;"><br><br>
        <?php do_action('wpsp_add_field_for_canned_reply_after_title');?>
	<b>Reply:</b><br>
	<?php 
	$settings = array( 'media_buttons' => true, 'wpautop'=>false );
	wp_editor( '', 'wpsp_canned_reply', $settings );
	?>
	<br><br/>
	<button type="submit" class="btn btn-success">Submit</button>
</form>
<script type="text/javascript">
jQuery('#wpsp_add_canned').submit(function(e){
	if(jQuery('#wpsp_title').val().trim()==''){
		alert('Please enter question');
		e.preventDefault();
	}
});
</script>
