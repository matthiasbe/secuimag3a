<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$sql="select * from {$wpdb->prefix}wpsp_panel_custom_menu";
$menus=$wpdb->get_results($sql);
$total_menu=$wpdb->num_rows;

?>
<div id="menuDisplayTableContainer" class="table-responsive">
	<table class="table table-striped">
	  <tr>
	    <th><?php _e('Icon','wp-support-plus-responsive-ticket-system');?></th>
	    <th><?php _e('Menu','wp-support-plus-responsive-ticket-system');?></th>
	    <th><?php _e('Redirect URL','wp-support-plus-responsive-ticket-system');?></th>
	    <th><?php _e('Action','wp-support-plus-responsive-ticket-system');?></th>
	  </tr>
	  <?php foreach ($menus as $menu){?>
	  	<tr>
	  		<td><img class="cusom_slider_menu_icon" src="<?php echo $menu->menu_icon;?>" ></td>
	  		<td><?php echo $menu->menu_text;?></td>
	  		<td><a target="_blank" href="<?php echo $menu->redirect_url;?>"><?php echo $menu->redirect_url;?></a></td>
	  		<td><img onclick="delete_custom_menu(<?php echo $menu->id;?>);" style="cursor: pointer;" title="<?php _e('Delete','wp-support-plus-responsive-ticket-system');?>" src="<?php echo WCE_PLUGIN_URL;?>asset/images/delete.png" ></td>
	  	</tr>
	  <?php }?>
	</table>
</div>
<?php if(!$total_menu){?>
	<div style="width: 100%;text-align: center;"><?php _e('No Menus Found','wp-support-plus-responsive-ticket-system');?></div>
	<hr>
<?php }?>

<div id="add_custom_menu_container">
	<h4><?php _e('Add New Menu','wp-support-plus-responsive-ticket-system');?></h4>
	<input type="hidden" id="custom_menu_icon" value="<?php echo WCE_PLUGIN_URL;?>asset/images/custom_menu_default.jpg" >
	<table>
		<tr>
			<td><?php _e('Select Icon (40 x 40 pixel)','wp-support-plus-responsive-ticket-system');?></td>
			<td class="seperator_menu_items">:</td>
			<td><img id="cusom_slider_menu_icon" onclick="change_default_menu_icon();" src="<?php echo WCE_PLUGIN_URL;?>asset/images/custom_menu_default.jpg" ></td>
		</tr>
		<tr>
			<td><?php _e('Menu Text','wp-support-plus-responsive-ticket-system');?></td>
			<td class="seperator_menu_items">:</td>
			<td><input type="text" id="custom_menu_text" value="" ></td>
		</tr>
		<tr>
			<td><?php _e('Redirect URL','wp-support-plus-responsive-ticket-system');?></td>
			<td class="seperator_menu_items">:</td>
			<td><input type="text" id="custom_menu_url" value="" ></td>
		</tr>
	</table><br>
	<button class="btn btn-success" onclick="create_custom_panel_menu();" ><?php _e('Create New Menu','wp-support-plus-responsive-ticket-system');?></button>
</div>

