<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly?>
<h3><?php _e('Add-On licenses','wp-support-plus-responsive-ticket-system');?></h3>
<?php
$license_flag=true;
?>
<script type="text/javascript">
    wpsp_store_url='<?php echo WPSP_STORE_URL;?>';
    wpsp_license_url='<?php echo home_url();?>';
    wpsp_check_license_status=new Array();
</script>
<table id="wpsp_addon_license_tbl">
    <tr>
        <th><?php _e('Add-On','wp-support-plus-responsive-ticket-system');?></th>
        <th><?php _e('License Key','wp-support-plus-responsive-ticket-system');?></th>
        <th><?php _e('Status','wp-support-plus-responsive-ticket-system');?></th>
    </tr>
    <?php
    if(class_exists('WPSupportPlusEmailPipe')){
        $license_key=get_option('wpsp_license_key_emailpipe');
        ?>
        <tr>
            <td><?php _e('Email Piping','wp-support-plus-responsive-ticket-system');?></td>
            <td>
                <input type="text" id="wpsp_lisense_txt_emailpipe" class="wpsp_lisense_txt" value="<?php echo $license_key;?>">
                <?php if(!$license_key){?>
                <button onclick="wpsp_act_license('emailpipe',41)"><?php _e('Save & Activate','wp-support-plus-responsive-ticket-system');?></button>
                <?php } else {?>
                        <button onclick="wpsp_dact_license('emailpipe',41,'<?php echo $license_key;?>')"><?php _e('Deactivate','wp-support-plus-responsive-ticket-system');?></button>
                <?php }?>
                <img id="wpsp_license_ajax_loading_emailpipe" class="wpsp_license_ajax_loading" src="<?php echo WCE_PLUGIN_URL.'asset/images/loading_small.gif';?>"/><br>
                <div id="wpsp_license_error_div_emailpipe" class="wpsp_license_error_div"></div>
            </td>
            <td>
                <?php if(!$license_key){?>
                        <?php _e('Inactive','wp-support-plus-responsive-ticket-system');?>
                <?php } else {?>
                        <script type="text/javascript">
                            wpsp_check_license_status.push(['emailpipe',41,'<?php echo $license_key;?>']);
                        </script>
                        <div id="wpsp_lic_status_emailpipe" class="wpsp_lic_status"></div>
                        <img id="wpsp_lic_status_ajax_img_emailpipe" class="wpsp_lic_status_ajax_img" src="<?php echo WCE_PLUGIN_URL.'asset/images/loading_small.gif';?>"/>
                <?php }?>
            </td>
        </tr>
        <?php
        $license_flag=false;
    }
    
    if(class_exists('WPSupportPlusWoocommerce')){
        $license_key=get_option('wpsp_license_key_woo');
        ?>
        <tr>
            <td><?php _e('Woocommerce','wp-support-plus-responsive-ticket-system');?></td>
            <td>
                <input type="text" id="wpsp_lisense_txt_woo" class="wpsp_lisense_txt" value="<?php echo $license_key;?>">
                <?php if(!$license_key){?>
                        <button onclick="wpsp_act_license('woo',54)"><?php _e('Save & Activate','wp-support-plus-responsive-ticket-system');?></button>
                <?php } else {?>
                        <button onclick="wpsp_dact_license('woo',54,'<?php echo $license_key;?>')"><?php _e('Deactivate','wp-support-plus-responsive-ticket-system');?></button>
                <?php }?>
                <img id="wpsp_license_ajax_loading_woo" class="wpsp_license_ajax_loading" src="<?php echo WCE_PLUGIN_URL.'asset/images/loading_small.gif';?>"/><br>
                <div id="wpsp_license_error_div_woo" class="wpsp_license_error_div"></div>
            </td>
            <td>
                <?php if(!$license_key){?>
                        <?php _e('Inactive','wp-support-plus-responsive-ticket-system');?>
                <?php } else {?>
                        <script type="text/javascript">
                            wpsp_check_license_status.push(['woo',54,'<?php echo $license_key;?>']);
                        </script>
                        <div id="wpsp_lic_status_woo" class="wpsp_lic_status"></div>
                        <img id="wpsp_lic_status_ajax_img_woo" class="wpsp_lic_status_ajax_img" src="<?php echo WCE_PLUGIN_URL.'asset/images/loading_small.gif';?>"/>
                <?php }?>
            </td>
        </tr>
        <?php
        $license_flag=false;
    }
    
    if(class_exists('WPSupportPlusExportTicket')){
        $license_key=get_option('wpsp_license_key_exportticket');
        ?>
        <tr>
            <td><?php _e('Export Ticket','wp-support-plus-responsive-ticket-system');?></td>
            <td>
                <input type="text" id="wpsp_lisense_txt_exportticket" class="wpsp_lisense_txt" value="<?php echo $license_key;?>">
                <?php if(!$license_key){?>
                        <button onclick="wpsp_act_license('exportticket',56)"><?php _e('Save & Activate','wp-support-plus-responsive-ticket-system');?></button>
                <?php } else {?>
                        <button onclick="wpsp_dact_license('exportticket',56,'<?php echo $license_key;?>')"><?php _e('Deactivate','wp-support-plus-responsive-ticket-system');?></button>
                <?php }?>
                <img id="wpsp_license_ajax_loading_exportticket" class="wpsp_license_ajax_loading" src="<?php echo WCE_PLUGIN_URL.'asset/images/loading_small.gif';?>"/><br>
                <div id="wpsp_license_error_div_exportticket" class="wpsp_license_error_div"></div>
            </td>
            <td>
                <?php if(!$license_key){?>
                        <?php _e('Inactive','wp-support-plus-responsive-ticket-system');?>
                <?php } else {?>
                        <script type="text/javascript">
                            wpsp_check_license_status.push(['exportticket',56,'<?php echo $license_key;?>']);
                        </script>
                        <div id="wpsp_lic_status_exportticket" class="wpsp_lic_status"></div>
                        <img id="wpsp_lic_status_ajax_img_exportticket" class="wpsp_lic_status_ajax_img" src="<?php echo WCE_PLUGIN_URL.'asset/images/loading_small.gif';?>"/>
                <?php }?>
            </td>
        </tr>
        <?php
        $license_flag=false;
    }
    
    if(class_exists('WPSP_EDD')){
        $license_key=get_option('wpsp_license_key_edd');
        ?>
        <tr>
            <td><?php _e('Easy Digital Downloads','wp-support-plus-responsive-ticket-system');?></td>
            <td>
                <input type="text" id="wpsp_lisense_txt_edd" class="wpsp_lisense_txt" value="<?php echo $license_key;?>">
                <?php if(!$license_key){?>
                        <button onclick="wpsp_act_license('edd',684)"><?php _e('Save & Activate','wp-support-plus-responsive-ticket-system');?></button>
                <?php } else {?>
                        <button onclick="wpsp_dact_license('edd',684,'<?php echo $license_key;?>')"><?php _e('Deactivate','wp-support-plus-responsive-ticket-system');?></button>
                <?php }?>
                <img id="wpsp_license_ajax_loading_edd" class="wpsp_license_ajax_loading" src="<?php echo WCE_PLUGIN_URL.'asset/images/loading_small.gif';?>"/><br>
                <div id="wpsp_license_error_div_edd" class="wpsp_license_error_div"></div>
            </td>
            <td>
                <?php if(!$license_key){?>
                        <?php _e('Inactive','wp-support-plus-responsive-ticket-system');?>
                <?php } else {?>
                        <script type="text/javascript">
                            wpsp_check_license_status.push(['edd',684,'<?php echo $license_key;?>']);
                        </script>
                        <div id="wpsp_lic_status_edd" class="wpsp_lic_status"></div>
                        <img id="wpsp_lic_status_ajax_img_edd" class="wpsp_lic_status_ajax_img" src="<?php echo WCE_PLUGIN_URL.'asset/images/loading_small.gif';?>"/>
                <?php }?>
            </td>
        </tr>
        <?php
        $license_flag=false;
    }
    
    if(class_exists('WPSP_COMPANY')){
        $license_key=get_option('wpsp_license_key_company');
        ?>
        <tr>
            <td><?php _e('Company / Usergroups','wp-support-plus-responsive-ticket-system');?></td>
            <td>
                <input type="text" id="wpsp_lisense_txt_company" class="wpsp_lisense_txt" value="<?php echo $license_key;?>">
                <?php if(!$license_key){?>
                        <button onclick="wpsp_act_license('company',1148)"><?php _e('Save & Activate','wp-support-plus-responsive-ticket-system');?></button>
                <?php } else {?>
                        <button onclick="wpsp_dact_license('company',1148,'<?php echo $license_key;?>')"><?php _e('Deactivate','wp-support-plus-responsive-ticket-system');?></button>
                <?php }?>
                <img id="wpsp_license_ajax_loading_company" class="wpsp_license_ajax_loading" src="<?php echo WCE_PLUGIN_URL.'asset/images/loading_small.gif';?>"/><br>
                <div id="wpsp_license_error_div_company" class="wpsp_license_error_div"></div>
            </td>
            <td>
                <?php if(!$license_key){?>
                        <?php _e('Inactive','wp-support-plus-responsive-ticket-system');?>
                <?php } else {?>
                        <script type="text/javascript">
                            wpsp_check_license_status.push(['company',1148,'<?php echo $license_key;?>']);
                        </script>
                        <div id="wpsp_lic_status_company" class="wpsp_lic_status"></div>
                        <img id="wpsp_lic_status_ajax_img_company" class="wpsp_lic_status_ajax_img" src="<?php echo WCE_PLUGIN_URL.'asset/images/loading_small.gif';?>"/>
                <?php }?>
            </td>
        </tr>
        <?php
        $license_flag=false;
    }
    
    if(class_exists('WPSP_TIMER')){
        $license_key=get_option('wpsp_license_key_timer');
        ?>
        <tr>
            <td><?php _e('Timer','wp-support-plus-responsive-ticket-system');?></td>
            <td>
                <input type="text" id="wpsp_lisense_txt_timer" class="wpsp_lisense_txt" value="<?php echo $license_key;?>">
                <?php if(!$license_key){?>
                        <button onclick="wpsp_act_license('timer',1151)"><?php _e('Save & Activate','wp-support-plus-responsive-ticket-system');?></button>
                <?php } else {?>
                        <button onclick="wpsp_dact_license('timer',1151,'<?php echo $license_key;?>')"><?php _e('Deactivate','wp-support-plus-responsive-ticket-system');?></button>
                <?php }?>
                <img id="wpsp_license_ajax_loading_timer" class="wpsp_license_ajax_loading" src="<?php echo WCE_PLUGIN_URL.'asset/images/loading_small.gif';?>"/><br>
                <div id="wpsp_license_error_div_timer" class="wpsp_license_error_div"></div>
            </td>
            <td>
                <?php if(!$license_key){?>
                        <?php _e('Inactive','wp-support-plus-responsive-ticket-system');?>
                <?php } else {?>
                        <script type="text/javascript">
                            wpsp_check_license_status.push(['timer',1151,'<?php echo $license_key;?>']);
                        </script>
                        <div id="wpsp_lic_status_timer" class="wpsp_lic_status"></div>
                        <img id="wpsp_lic_status_ajax_img_timer" class="wpsp_lic_status_ajax_img" src="<?php echo WCE_PLUGIN_URL.'asset/images/loading_small.gif';?>"/>
                <?php }?>
            </td>
        </tr>
        <?php
        $license_flag=false;
    }
    
    if($license_flag){
        ?>
        <tr>
            <td colspan="3" style="text-align: center; padding: 30px;"><?php _e('No Add-Ons activated','wp-support-plus-responsive-ticket-system');?>. <a href="https://www.wpsupportplus.com/add-ons/" target="__blank"><?php _e('See available Add-Ons','wp-support-plus-responsive-ticket-system');?></a>.</td>
        </tr>
        <?php
    }
    
    ?>
</table>
<script type="text/javascript">
    jQuery(document).ready(function (){
        if(wpsp_check_license_status.length){
            jQuery(wpsp_check_license_status).each(function (){
                wpsp_check_license(jQuery(this)[0],jQuery(this)[1],jQuery(this)[2]);
            });
        }
    });
</script>