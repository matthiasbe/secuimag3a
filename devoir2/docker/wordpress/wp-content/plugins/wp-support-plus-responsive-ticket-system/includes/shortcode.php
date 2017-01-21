<?php   
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class WPSupportPlusFrontEnd{
	public function __construct() {
            add_action( 'wp_enqueue_scripts', array( $this, 'loadScripts') );
            add_action('wp_head',array( $this, 'load_custom_css') );
            add_shortcode( 'wp_support_plus', array( $this, 'support_plus_shortcode' ) );
            add_shortcode( 'wp_support_plus_all_tickets', array( $this, 'support_plus_all_tickets_shortcode' ) );
            add_shortcode( 'wp_support_plus_create_ticket', array( $this, 'support_plus_create_ticket_shortcode' ) );
            add_shortcode( 'wpsp_open_ticket', array( $this, 'wpsp_open_ticket' ) );
	}
	
	function loadScripts(){
            global $post;
            $advancedSettings=get_option( 'wpsp_advanced_settings' );
            $CKEditorSettings=get_option( 'wpsp_ckeditor_settings' );
            if( (is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'wp_support_plus')) || (is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'wp_support_plus_all_tickets')) || (is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'wp_support_plus_create_ticket')) || (is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'wpsp_open_ticket')) || ($advancedSettings['wpsp_shortcode_used_in']==0) ) {
                wp_enqueue_script( 'jquery' );
                wp_enqueue_script( 'jquery-ui-core' );
                wp_enqueue_script( 'jquery-ui-dialog' );
                wp_enqueue_style ( 'wp-jquery-ui-dialog' );
                if($advancedSettings['enable_accordion']==1){
                    wp_enqueue_script( 'jquery-ui-accordion' );
                    wp_enqueue_style( "jquery-ui-css", WCE_PLUGIN_URL . 'asset/css/jquery-ui.min.css' );
                    wp_enqueue_style( "jquery-ui-structure-css", WCE_PLUGIN_URL . 'asset/css/jquery-ui.structure.min.css' );
                    wp_enqueue_style( "jquery-ui-theme-css", WCE_PLUGIN_URL . 'asset/css/jquery-ui.theme.min.css' );
                }
                if($advancedSettings['wpspBootstrapCSSSetting']){
                    wp_enqueue_style('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/css/bootstrap.css?version='.WPSP_VERSION);
                }
                wp_enqueue_style('wpce_display_ticket', WCE_PLUGIN_URL . 'asset/css/display_ticket.css?version='.WPSP_VERSION);
                wp_enqueue_style('wpce_public', WCE_PLUGIN_URL . 'asset/css/public.css?version='.WPSP_VERSION);
                if($advancedSettings['wpspBootstrapJSSetting']){
                    wp_enqueue_script('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/js/bootstrap.min.js?version='.WPSP_VERSION);
                }
                wp_enqueue_script('wpce_public', WCE_PLUGIN_URL . 'asset/js/public.js?version='.WPSP_VERSION);
                wp_enqueue_script('wpce_public_create_ticket', WCE_PLUGIN_URL . 'asset/js/public_create_ticket.js?version='.WPSP_VERSION);

                wp_enqueue_script('wpce_ckeditor_editor', WCE_PLUGIN_URL . 'asset/lib/ckeditor/ckeditor.js?version='.WPSP_VERSION);
                wp_enqueue_script('wpce_ckeditor_jquery_adapter', WCE_PLUGIN_URL . 'asset/lib/ckeditor/adapters/jquery.js?version='.WPSP_VERSION);
               
                wp_enqueue_script('jquery-ui-datepicker');
                wp_enqueue_style('jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

                $isUserLogged=(is_user_logged_in())?1:0;

                $generalSettings=get_option( 'wpsp_general_settings' );

                $localize_script_data=array(
                    'wpsp_ajax_url'=>admin_url( 'admin-ajax.php' ),
                    'wpsp_site_url'=>site_url(),
                    'plugin_url'=>WCE_PLUGIN_URL,
                    'plugin_dir'=>WCE_PLUGIN_DIR,
                    'user_logged_in'=>$isUserLogged,
                    'shortly_get_back'=>  stripcslashes($advancedSettings['guest_ticket_submission_message']),
                    'insert_all_required'=>__('Please Enter all required fields','wp-support-plus-responsive-ticket-system'),
                    'reply_not_empty'=>__('Reply can not be empty!','wp-support-plus-responsive-ticket-system'),
                    'sure_to_delete'=>__('Are you sure to delete this ticket?','wp-support-plus-responsive-ticket-system'),
                    'username_or_password_missing'=>__('Username or Password missing!!!','wp-support-plus-responsive-ticket-system'),
                    'can_not_undone'=>__('Can not be undone','wp-support-plus-responsive-ticket-system'),
                    'reply_ticket_position'=>$advancedSettings['wpsp_reply_form_position'],
                    'wpsp_shortcode_used_in'=>$advancedSettings['wpsp_shortcode_used_in'],
                    'enable_accordion'=>$advancedSettings['enable_accordion'],
                    'user_logged_in'=>(is_user_logged_in())?'1':'0',
                    'ckeditor_enable_for_guest'=>$CKEditorSettings['guestUserFront'],
                    'ckeditor_enable_for_loggedin'=>$CKEditorSettings['loginUserFront'],
                    'sure_to_close_status'=>__('Are you sure?','wp-support-plus-responsive-ticket-system'),
                    'close_status_succes'=>__('Close Ticket ID:','wp-support-plus-responsive-ticket-system'),
                    'display_tab'=>$advancedSettings['active_tab'],
                    'Not_valid_email_address'=>__('Please enter valid email address!','wp-support-plus-responsive-ticket-system'),
                    'not_applicable'=>__('Not Applicable','wp-support-plus-responsive-ticket-system'),
                    'guest_ticket_redirect'=>$advancedSettings['guest_ticket_redirect'],
                    'guest_ticket_redirect_url'=>$advancedSettings['guest_ticket_redirect_url'],
                    'wpsp_redirect_after_ticket_update'=>$advancedSettings['wpsp_redirect_after_ticket_update'],
                    'sure_to_submit_ticket'=>__('Are you sure to submit?','wp-support-plus-responsive-ticket-system'),
                    'sure_to_submit_note'=>__('Are you sure to add note?','wp-support-plus-responsive-ticket-system'),
                    'label_done'=>__('done','wp-support-plus-responsive-ticket-system'),
                    'label_uploading'=>__('uploading...','wp-support-plus-responsive-ticket-system'),
                    'wait_until_upload'=>__('Uploading attachment, please wait!','wp-support-plus-responsive-ticket-system'),
                    'wpspAttachMaxFileSize'=>$advancedSettings['wpspAttachMaxFileSize'],
                    'wpspAttachFileSizeExeeded'=>__('File Size limit exceeded! Allowed limit:','wp-support-plus-responsive-ticket-system').' '.$advancedSettings['wpspAttachMaxFileSize'].__('MB','wp-support-plus-responsive'),
                    'wpspRemoveAttachment'=>__('Remove','wp-support-plus-responsive-ticket-system'),
                    'reset_form'=>__('Reset form data??','wp-support-plus-responsive-ticket-system'),
                    'wpspAttachment_bc'=>$advancedSettings['wpspAttachment_bc'],                     
                    'wpspAttachment_pc'=>$advancedSettings['wpspAttachment_pc']
                );
                wp_localize_script( 'wpce_public', 'display_ticket_data', $localize_script_data );
            }
	}

	function load_custom_css(){
		global $wpdb;
		$customCSSSettings=get_option( 'wpsp_customcss_settings' );
		echo "<style type='text/css'>".$customCSSSettings."</style>";
		$customFieldsDropDown = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields where field_type=2 OR field_type=4" );
		$data="";
		$new_data=array();
		foreach ($customFieldsDropDown as $field){
			$data.=',#cust'.$field->id.'_front';
			$new_data=array_merge($new_data,array($field->id));
		}

		?>
			<script type="text/javascript">
                            var front_custom_filters='<?php echo $data;?>';
                            var front_custom_field_keys="<?php echo implode(',',$new_data);?>";
			</script>
		<?php
	}
        
        function support_plus_shortcode(){
		ob_start();
		?>
		<div class="support_bs">
			<?php 
			if(is_user_logged_in()){
				include( WCE_PLUGIN_DIR.'includes/loggedInUser.php' );
			}
			else {
				include( WCE_PLUGIN_DIR.'includes/guestUser.php' );
			}
			?>
		</div>
		<?php 
                do_action('wpsp_tickets_end_frontend');
		return ob_get_clean();
	}
        
        function support_plus_all_tickets_shortcode(){
		$generalSettings=get_option( 'wpsp_general_settings' );		
		ob_start();
		?>
		<div class="support_bs">
			<?php 
			if(is_user_logged_in()){
                            include( WCE_PLUGIN_DIR.'includes/loggedInUserAllTickets.php' );
			}
                        else {
                            include( WCE_PLUGIN_DIR.'includes/loginForm.php' );
                        }
			?>
		</div>
		<?php
                do_action('wpsp_tickets_end_frontend');
		return ob_get_clean();
	}
        function support_plus_create_ticket_shortcode(){
		ob_start();
		?>
		<div class="support_bs">
			<?php 
			if(is_user_logged_in()){
                            include( WCE_PLUGIN_DIR.'includes/loggedInUserCreateTicket.php' );
			}
                        else {
                            include( WCE_PLUGIN_DIR.'includes/guestUser.php' );
                        }
			?>
		</div>
		<?php 
		return ob_get_clean();
	}
        
        function wpsp_open_ticket(){
            ob_start();
            ?>
            <div class="support_bs">
                <?php 
                include( WCE_PLUGIN_DIR.'includes/openEncryptedTicketID.php' );
                ?>
            </div>
            <?php 
            return ob_get_clean();
        }
}

$GLOBALS['WPSupportPlusFrontEnd'] =new WPSupportPlusFrontEnd();
?>
