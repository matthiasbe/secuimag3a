<?php 
$dateflag=FALSE;
$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$priorities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_priority" );
$advancedSettingsPriorityOrder=get_option( 'wpsp_advanced_settings_priority_order' );

$advancedSettings=get_option( 'wpsp_advanced_settings' );
$FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );

$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];
$CKEditorSettings=get_option( 'wpsp_ckeditor_settings' );

 /*
  * WooCommerce Custom Field
  */
 $woo_products=array();
 if(class_exists( 'WooCommerce' ) && class_exists( 'WPSupportPlusWoocommerce' )) {
     $args     = array( 'post_type' => 'product', 'orderby'=>'title', 'order'=>'ASC' );
     $products = get_posts( $args );
     foreach($products as $prod){
         $woo_products[$prod->ID]=$prod->post_title;
     }
}
if(isset($advancedSettingsPriorityOrder['priority_order'])){
	if(is_array($advancedSettingsPriorityOrder['priority_order']))
	{
		$priorities=array();
		foreach($advancedSettingsPriorityOrder['priority_order'] as $priority_id)
		{
			$sql="select * from {$wpdb->prefix}wpsp_custom_priority WHERE id=".$priority_id." ";
			$priority_data=$wpdb->get_results($sql);
			foreach($priority_data as $priority)
			{
				$priorities=array_merge($priorities,array($priority));
			}
		}
	}
}
$total_menu=$wpdb->num_rows;
$generalSettings=get_option('wpsp_general_settings');
$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );

//for google noCaptcha
if($generalSettings['google_nocaptcha_key'] && $generalSettings['google_nocaptcha_secret']){
	wp_enqueue_script('wpsp_google_nocaptcha', WCE_PLUGIN_URL . 'asset/js/google_noCaptcha.js');
}
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];
?>
<!-- Nav tabs -->
<ul class="nav nav-tabs">
	<li class="active"><a href="#ticketContainer" onclick="wpsp_getGuestTicketForm();" id="tab_ticket_container" data-toggle="tab"><?php echo __($advancedSettings['ticket_label_alice'][3], 'wp-support-plus-responsive-ticket-system');?></a></li>
        <?php if ( $FrontEndDisplaySettings['wpsp_faq_display_setting']==1 ) {?>
            <li><a href="#FAQ_TAB" onclick="wpsp_getGuestFAQ();" id="tab_faq" data-toggle="tab"><?php echo __("FAQs", 'wp-support-plus-responsive-ticket-system');?></a></li>
        <?php }?>
</ul>
<div class="tab-content">
	<!-- FAQ Tab Body Start Here -->
	<div class="tab-pane" id="FAQ_TAB">
		<div class="faq_filter">
			<?php include( WCE_PLUGIN_DIR.'includes/admin/faq_filter_front.php' );?>
		</div>
		<div id="faq_container"></div>
		<div class="wait"><img alt="<?php _e('Please Wait', 'wp-support-plus-responsive-ticket-system');?>" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
	</div>
	<!-- FAQ Tab Body End Here -->
	<!-- Tickets Tab Body Start Here -->
	<div class="tab-pane active" id="ticketContainer">
		<br>
		<form id="frmCreateNewTicketGeuest">
			<?php
			foreach($advancedSettingsFieldOrder['fields_order'] as $field_id)
			{
				if(in_array($field_id,$advancedSettingsFieldOrder['display_fields'])){
				if(is_numeric($field_id))
				{
					$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields WHERE id='".$field_id."'" );
					foreach ($customFields as $field){
                                            ?>
                                            <div id="wpsp_custom_<?php echo $field->id;?>" class="<?php echo ($field->field_categories=='0')?'':'wpsp_conditional_fields';?>" style="<?php echo ($field->field_categories=='0')?'display:block':'display:none';?>">
                                            <?php
						if($field->required)
						{
							switch($field->field_type){
								case '1': ?>
									<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br>
									<input id="cust<?php echo $field->id;?>" class="wpsp_required" type="text" name="cust<?php echo $field->id;?>"  style="width: 95%; margin-top: 10px;"/><br><br>
								<?php
								break;
								case '2': ?>
									<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br>
                                                                        <select id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>" class="wpsp_required">
									<option value=""></option><?php 
									if($field->field_options==NULL)
									{
										$field_options=array();
									}
									else
									{
										$field_options=unserialize($field->field_options);
									}
									foreach ($field_options as $field_option_key=>$field_option_value){
										echo '<option value="'.$field_option_value.'">'.$field_option_value.'</option>';
									}
									?>
									</select><br/><br/>
								<?php
								break;
								case '3': ?>
									<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/>		
									<?php 
									if($field->field_options==NULL)
									{
										$field_options=array();
									}
									else
									{
										$field_options=unserialize($field->field_options);
									}
									foreach ($field_options as $field_option_key=>$field_option_value){
										echo '<input type="checkbox" name="cust'.$field->id.'[]" class="form-control wpsp_required" value="'.$field_option_value.'"> '.$field_option_value.'<br/>';
									}
									?><br/>
								<?php
								break;
								case '4': ?>
									<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/>		
									<?php 
									if($field->field_options==NULL)
									{
										$field_options=array();
									}
									else
									{
										$field_options=unserialize($field->field_options);
									}
									foreach ($field_options as $field_option_key=>$field_option_value){
										echo '<input type="radio" class="form-control" name="cust'.$field->id.'" value="'.$field_option_value.'" required> '.$field_option_value.'<br/>';
									}
									?><br/>
								<?php
								break;
								case '5': ?>
									<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/>	
									<textarea class="wpsp_required" id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>"></textarea><br/><br/>	
								<?php
								break;
                                                                case '6': 
                                                                        $dateflag=TRUE;
                                                                        ?>
                                                                        <span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br> 
                                                                        <input id="cust<?php echo $field->id;?>" class="wpsp_required wpsp_datepicker" type="text"  name="cust<?php echo $field->id;?>" style="width: 95%; margin-top: 10px;" placeholder="<?php _e('Click here to select date','wp-support-plus-responsive-ticket-system');?>"/><br><br>
                                                                <?php
                                                                break;
                                                                case '7': if(class_exists( 'WooCommerce' ) && class_exists( 'WPSupportPlusWoocommerce' ) && $woo_products){?>
                                                                        <span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/>
                                                                        <select id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>" class="wpsp_required">
                                                                            <option value=""></option>
                                                                            <?php foreach ($woo_products as $key => $value) {?>
                                                                                    <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                <?php
                                                                }
                                                                break;
                                                                default : do_action('wpsp_extra_custom_fields_create_ticket_guest_required',$field);
							}
						}		
						else
						{
							switch($field->field_type){
								case '1': ?>
									<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br>
									<input id="cust<?php echo $field->id;?>" type="text" name="cust<?php echo $field->id;?>" style="width: 95%; margin-top: 10px;"/><br><br>
								<?php
								break;
								case '2': ?>
									<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?><br>
									<select id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>">
									<option value=""></option><?php 
									if($field->field_options==NULL)
									{
										$field_options=array();
									}
									else
									{
										$field_options=unserialize($field->field_options);
									}
									foreach ($field_options as $field_option_key=>$field_option_value){
										echo '<option value="'.$field_option_value.'">'.$field_option_value.'</option>';
									}
									?>
									</select><br/><br/>
								<?php 
								break;
								case '3': ?>
									<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/><br>
									<?php 
									if($field->field_options==NULL)
									{
										$field_options=array();
									}
									else
									{
										$field_options=unserialize($field->field_options);
									}
									foreach ($field_options as $field_option_key=>$field_option_value){
										echo '<input type="checkbox" name="cust'.$field->id.'[]" class="form-control" value="'.$field_option_value.'"> '.$field_option_value.'<br/>';
									}
									?><br/>
								<?php
								break;
								case '4': ?>
									<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br>
									<?php 
									if($field->field_options==NULL)
									{
										$field_options=array();
									}
									else
									{
										$field_options=unserialize($field->field_options);
									}
									foreach ($field_options as $field_option_key=>$field_option_value){
										echo '<input type="radio" class="form-control" name="cust'.$field->id.'" value="'.$field_option_value.'"> '.$field_option_value.'<br/>';
									}
									?><br/>
								<?php
								break;
								case '5': ?>
									<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/><br/>
									<textarea id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>"></textarea><br/><br/>	
								<?php
								break;
                                                                case '6': 
                                                                        $dateflag=TRUE;
                                                                        ?>
                                                                        <span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br> 
                                                                        <input class="wpsp_datepicker" type="text"  name="cust<?php echo $field->id;?>" style="width: 95%; margin-top: 10px;" placeholder="<?php _e('Click here to select date','wp-support-plus-responsive-ticket-system');?>"/><br><br>
                                                                <?php
                                                                break;
                                                                case '7': if(class_exists( 'WooCommerce' ) && class_exists( 'WPSupportPlusWoocommerce' ) && $woo_products){?>
                                                                        <span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/>
                                                                        <select id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>" >
                                                                           <option value=""></option>
                                                                           <?php foreach ($woo_products as $key => $value) {?>
                                                                                   <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                                                           <?php }?>
                                                                        </select>
                                                                 <?php
                                                                    }
                                                                break;
                                                                default : do_action('wpsp_extra_custom_fields_create_ticket_guest_notrequired',$field);
							}
		 				}
                                                ?>
                                            </div>
                                            <?php
					}
				}
				else
				{
					switch($field_id)
					{
						case 'dn': ?>
							<span class="label label-info" style="font-size: 13px;"><?php _e($default_labels['dn'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
							<input class="wpsp_required" type="text" id="create_ticket_guest_name" name="guest_name"  style="width: 95%; margin-top: 10px;" /><br><br>
						<?php
						break;
						case 'de': ?>
							<span class="label label-info" style="font-size: 13px;"><?php _e($default_labels['de'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
							<input class="wpsp_required" type="text" id="create_ticket_guest_email" name="guest_email"  style="width: 95%; margin-top: 10px;" /><br><br>
						<?php
						break;
						case 'ds': ?>
							<span class="label label-info" style="font-size: 13px;"><?php _e($default_labels['ds'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
							<input class="wpsp_required" type="text" id="create_ticket_subject" name="create_ticket_subject"  style="width: 95%; margin-top: 10px;"/><br><br>
						<?php
						break;
						case 'dd': ?>
							<span class="label label-info" style="font-size: 13px;"><?php _e($default_labels['dd'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
							<textarea id="create_ticket_body_guest" name="create_ticket_body" style="margin-top: 10px; width: 95%;" ></textarea><br><br>
						<?php
						break;
						case 'dc': ?>
							<div>
								<span class="label label-info hide_fields_support_plus" style="font-size: 13px;"><?php _e($default_labels['dc'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
								<select id="create_ticket_category" class="hide_fields_support_plus" name="create_ticket_category" style="margin-top: 10px;" onchange = "cat_wise_custom_field(this)">
								<option value=""></option> 
                                                                    <?php
                                                                        $categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories ORDER BY name" );
									foreach ($categories as $category){
                                                                            echo '<option value="'.$category->id.'">'.stripcslashes($category->name).'</option>';
									}
									?>
								</select><br><br>
							</div>
						<?php
						break;
						case 'dp': ?>
							<div>
								<span class="label label-info hide_fields_support_plus" style="font-size: 13px;"><?php _e($default_labels['dp'],'wp-support-plus-responsive-ticket-system');?></span><code>*</code><br>
								<select id="create_ticket_priority" class="hide_fields_support_plus" name="create_ticket_priority" style="margin-top: 10px;">
                                                                <option value="" ></option>	
                                                                    <?php 
									foreach ($priorities as $priority){
								        echo '<option value="'.strtolower($priority->name).'" >'.__($priority->name,'wp-support-plus-responsive-ticket-system').'</option>';
									}
									?>
								</select>
							</div>
						<?php
						break;
						case 'da':
							if($generalSettings['allow_attachment_for_guest_ticket']==1)
							{ ?>
								<div>
									<span class="label label-info"><?php _e($default_labels['da'],'wp-support-plus-responsive-ticket-system');?></span><br>
									<div class="wpsp_frm_attachment_container">
                                                                            <input type="file" id="wpsp_frm_attachment_input_create" class="wpsp_frm_attachment_input">
                                                                            <div id="wpsp_frm_attachment_copy_create" class="wpsp_frm_attachment" style="display: none;">
                                                                                <span class="wpsp_frm_attachment_name"></span><br>
                                                                                <span class="wpsp_frm_attachment_percentage">[0%]</span>
                                                                                <span class="wpsp_frm_attachment_remove"></span>
                                                                            </div>
                                                                            <div id="wpsp_frm_attachment_list_create" class="wpsp_frm_attachment_list"></div>
                                                                            <div id="wpsp_frm_attachment_ids_container_create" class="wpsp_frm_attachment_ids_container"></div>
                                                                        </div>
								</div><br>
							<?php
							}
						break;
					}
				}
				}
			}
			?>
			<?php if($generalSettings['enable_user_selection_public_private']==1){ ?>
			<input <?php echo ($generalSettings['default_ticket_type']==1)?'checked="checked"':'';?> type="checkbox" name="create_ticket_type"  id="create_ticket_type" />	
			<span class="label label-info" style="font-size: 13px;"><?php _e($advancedSettings['ticket_label_alice'][6],'wp-support-plus-responsive-ticket-system');_e(' Public','wp-support-plus-responsive-ticket-system');?></span><br><br/>
		<?php }else {?>
				<input type="hidden" id="create_ticket_type" name="create_ticket_type" value="<?php echo $generalSettings['default_ticket_type'];?>">
		<?php }
		
			if($generalSettings['google_nocaptcha_key'] && $generalSettings['google_nocaptcha_secret']){?>
				<div class="g-recaptcha" data-sitekey="<?php echo $generalSettings['google_nocaptcha_key'];?>"></div>
			<?php }?>
			
			<input type="hidden" name="action" value="createNewTicket">
			<input type="hidden" name="user_id" value="0">
			<input type="hidden" name="type" value="guest">
			<br>
			<input type="submit" id="wpsp_submit" class="btn btn-success" value="<?php _e($advancedSettings['ticket_label_alice'][7],'wp-support-plus-responsive-ticket-system');?>">
			<input type="button" id="wpsp_reset" class="btn btn-success" value="<?php _e('Reset Form','wp-support-plus-responsive-ticket-system');?>" onClick="resetForm();" />
		</form>
                <?php
                 do_action('wpsp_after_guest_ticket_form');
                ?>
                <?php if($CKEditorSettings['guestUserFront']=='1'){?>
                    <script type="text/javascript">
                        jQuery(document).ready(function(){
                                CKEDITOR.replace(document.getElementById('create_ticket_body_guest'));
                        });
                    </script>
                <?php }?>
	</div>
	<!-- Tickets Tab Body End Here -->
</div>
<?php
if($dateflag){
    add_action('wp_footer','wpsp_datepicket_add_to_footer',200);
    function wpsp_datepicket_add_to_footer(){
        $advancedSettings=get_option( 'wpsp_advanced_settings' );
        ?>
        <script>
        jQuery('.wpsp_datepicker').datepicker({
            dateFormat : '<?php echo $advancedSettings['datecustfield'];?>',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050',                      
            defaultDate:'+0',                      
            onSelect: function (selected) {
                var dt1 = new Date(selected);
                dt1.setDate(dt1.getDate());
                jQuery(this).datepicker(dt1);
            }
        });
        </script>
        <?php
    }
}
?>

<script>
    function wpsp_create_ticket_frm_extra_validation(){
        var flag=true;
        <?php do_action('wpsp_create_ticket_guest_frm_extra_validation');?>
        return flag;
    }
</script>
<script>
    var wpsp_attachment_counter=0;
    var wpsp_attachment_share_lock=false;
</script>
<?php if(in_array('da',$advancedSettingsFieldOrder['display_fields']) && $generalSettings['allow_attachment_for_guest_ticket']==1){?>
<script>
    jQuery('#wpsp_frm_attachment_input_create').change(function() {
        wpspUploadAttachment(this.files,'create');
    });
</script>
<?php }?>
<script>
 function resetForm(){
     if(confirm(display_ticket_data.reset_form)){
         jQuery('#frmCreateNewTicketGeuest')[0].reset();
         for (instance in CKEDITOR.instances){
                 CKEDITOR.instances[instance].setData(" ");
         }
     }
 }
</script>