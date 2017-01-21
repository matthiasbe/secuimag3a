
<a href="<?php echo admin_url('admin.php?page=wp-support-plus-faq&type=add');?>" class="btn btn-primary"><?php _e("+ Add New",'wp-support-plus-responsive-ticket-system');?></a>
<br><br>
<?php 
global $wpdb;

$sql="select * from {$wpdb->prefix}wpsp_faq";
$faqs = $wpdb->get_results( $sql );
?>
<table class="table table-striped table-hover">
	  <tr>
		  <th style="width: 50px;">#</th>
		  <th><?php _e('Question','wp-support-plus-responsive-ticket-system');?></th>
		  <th><?php _e('Action','wp-support-plus-responsive-ticket-system');?></th>
	  </tr>
	  <?php 
	  foreach ($faqs as $faq){ ?>
	  	
	  	<tr>
	  		<td style="width: 50px;"><?php echo $faq->id;?></td>
	  		<td><?php echo stripcslashes($faq->question);?></td>
	  		<td>
	  			<a href="<?php echo admin_url('admin.php?page=wp-support-plus-faq&type=edit&id='.$faq->id);?>" class="btn btn-info">Edit</a>
	  			<button class="btn btn-danger" onclick="deleteFAQ(<?php echo $faq->id;?>);">Delete</button>
	  		</td>
	  	</tr>
	  	
	  <?php }?>
</table>
<?php 
if(!$faqs){?>
	<div style="text-align: center;"><?php _e("No FAQ's Found",'wp-support-plus-responsive-ticket-system');?></div>
	<hr>
<?php }?>
<script type="text/javascript">
	function deleteFAQ(id){
		if(confirm("Are you sure?")){
			location.href="<?php echo admin_url('admin.php?page=wp-support-plus-faq&type=delete&noheader=true');?>&id="+id;
		}
	}
</script>
