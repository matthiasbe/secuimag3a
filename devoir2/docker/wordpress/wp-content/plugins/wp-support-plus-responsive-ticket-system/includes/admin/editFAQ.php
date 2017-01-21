<h3>Edit FAQ</h3><br>
<?php 
global $wpdb;

if(isset($_REQUEST['action'])){
	//code to insert into db
	$values=array(
			'question'=>$_REQUEST['question'],
			'answer'=>$_REQUEST['wpsp_answer'],
			'category_id'=>$_REQUEST['category']
	);
	$wpdb->update($wpdb->prefix.'wpsp_faq',$values,array('id'=>$_REQUEST['id']));
	wp_redirect(admin_url('admin.php?page=wp-support-plus-faq')); 
}

$faq_cat_sql="select * from {$wpdb->prefix}wpsp_faq_catagories";
$faq_categories = $wpdb->get_results( $faq_cat_sql );

$faq=$wpdb->get_row("select * from {$wpdb->prefix}wpsp_faq where id=".$_REQUEST['id']);
?>
<form id="wpsp_add_faq" method="post" action="<?php echo admin_url('admin.php?page=wp-support-plus-faq&type=edit&action=set&noheader=true&id='.$_REQUEST['id']);?>">
	<b>Select Category:</b>
	<select name="category">
		<?php foreach ($faq_categories as $faq_category){?>
				<option value="<?php echo $faq_category->id;?>" <?php echo ($faq->category_id==$faq_category->id)?'selected="selected"':'';?>><?php echo $faq_category->name;?></option>
		<?php }?>
	</select><br><br>
	<b>Question:</b><br>
	<input type="text" id="wpsp_faq_question" name="question" value="<?php echo stripcslashes($faq->question);?>" style="width: 100%;"><br><br>
	<b>Answer:</b><br>
	<?php 
	$settings = array( 'media_buttons' => true, 'wpautop'=>false );
	wp_editor( stripcslashes($faq->answer), 'wpsp_answer', $settings );
	?>
	<br><br/>
	<button type="submit" class="btn btn-success">Submit</button>
</form>
<script type="text/javascript">
jQuery('#wpsp_add_faq').submit(function(e){
	if(jQuery('#wpsp_faq_question').val().trim()==''){
		alert('Please enter question');
		e.preventDefault();
	}
});
</script>
