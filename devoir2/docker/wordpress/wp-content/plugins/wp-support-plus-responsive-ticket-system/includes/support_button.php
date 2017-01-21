<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class WPSupportPlusButton{
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'loadScripts') );
		add_action( 'wp_footer', array( $this, 'showButton') );
	}
	
	function loadScripts(){
            global $current_user;
            $current_user=wp_get_current_user();
            $generalSettings=get_option( 'wpsp_general_settings' );
            if($generalSettings['enable_support_button']){
                $roleManage=get_option('wpsp_role_management');
                $allowed_roles=array_intersect($roleManage['front_ticket'],$current_user->roles);
                $flag=true;
                if ( ! is_user_logged_in() && ! $generalSettings['enable_guest_ticket'] && ! $generalSettings['enable_default_login'] && ! $generalSettings['fbAppID']) $flag=false;
                if(!($roleManage['front_ticket_all']) && count($allowed_roles)<1) $flag=false;
                if($flag){
                    wp_enqueue_script( 'jquery' );
                    wp_enqueue_script( 'jquery-ui-core' );
                    wp_enqueue_style('wpce_support_button', WCE_PLUGIN_URL . 'asset/css/support_button.css');
                    wp_enqueue_script( 'wpce_support_button', WCE_PLUGIN_URL . 'asset/js/support_button.js');
                    $localize_script_data=array(
                        'wpsp_ajax_url'=>admin_url( 'admin-ajax.php' ),
                        'wpsp_site_url'=>site_url(),
                        'plugin_url'=>WCE_PLUGIN_URL
                    );
                    wp_localize_script( 'wpce_support_button', 'display_button_data', $localize_script_data );
                }
            }
	}
	
	function showButton(){
		global $current_user;
		$current_user=wp_get_current_user();
		$generalSettings=get_option( 'wpsp_general_settings' );
                $UploadImageSettings=get_option( 'wpsp_upload_image_settings' );
		if ($generalSettings['enable_support_button']){
			$flag=true;
			if ( ! is_user_logged_in() && ! $generalSettings['enable_guest_ticket'] && ! $generalSettings['enable_default_login'] && ! $generalSettings['fbAppID']) $flag=false;
			$roleManage=get_option('wpsp_role_management');
			$allowed_roles=array_intersect($roleManage['front_ticket'],$current_user->roles);
			if(!($roleManage['front_ticket_all']) && count($allowed_roles)<1) $flag=false;
			if ($flag){
				$support_permalink=get_permalink($generalSettings['post_id']);
				$imageURL='';
				$style="";
				switch ($generalSettings['support_button_position']){
					case 'top_left':   $imageURL= $UploadImageSettings['leftSupportButton'];
										$style="top: 35px;left: 0px;";
										$panel_style="left: -300px;top: 39px;";
										$animate_direction='left';
                                                                                break;
					case 'top_right':  $imageURL= $UploadImageSettings['rightSupportButton'];
										$style="top: 35px;right: 0px;";
										$panel_style="right: -300px;top: 39px;";
										$animate_direction='right';
										break;
					case 'bottom_left': $imageURL= $imageURL= $UploadImageSettings['leftSupportButton'];
										$style="bottom: 35px;left: 0px;";
										$panel_style="left: -300px;bottom: 10px;";
										$animate_direction='left';
										break;
					case 'bottom_right': $imageURL= $UploadImageSettings['rightSupportButton'];
										$style="bottom: 35px;right: 0px;";
										$panel_style="right: -300px;bottom: 10px;";
										$animate_direction='right';
										break;
				}
				?>
				<img id="wpsp_support_btn" alt="support" src="<?php echo $imageURL;?>" style="<?php echo $style;?>" />
				<div id="wpsp_support_front_panel" style="<?php echo $panel_style;?>">
					<?php include( WCE_PLUGIN_DIR.'includes/support_panel.php' );?>
				</div>
				
				<script type="text/javascript">
					jQuery(document).ready(function(){
						<?php if($generalSettings['enable_slider_menu']){?>
							jQuery('#wpsp_support_btn').click(function(){
								open_support_panel();
							});
							jQuery('#support_panel_close').click(function(){
								close_support_panel();
							});
							jQuery('#support_page_redirect').click(function(){
								window.location.href="<?php echo $support_permalink;?>";
							});
						<?php }else {?>
							jQuery('#wpsp_support_btn').click(function(){
								window.location.href="<?php echo $support_permalink;?>";
							});
						<?php }?>
					});
					function open_support_panel(){
						jQuery('#wpsp_support_front_panel').animate({ "<?php echo $animate_direction;?>": "+=300px" }, "slow" );
					}
					function close_support_panel(){
						jQuery('#wpsp_support_front_panel').animate({ "<?php echo $animate_direction;?>": "-=300px" }, "slow" );
					}
				</script>
				<?php
			}
		}
	}
}

$GLOBALS['WPSupportPlusButton'] =new WPSupportPlusButton();
?>
