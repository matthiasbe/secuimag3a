<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class WPSupportPlusAdmin {
	
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'loadScripts') );
		add_action( 'admin_menu', array($this,'custom_menu_page') );		
	}
	
	function loadScripts(){
		$advancedSettings=get_option( 'wpsp_advanced_settings');
                wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
                wp_enqueue_script( 'jquery-ui-dialog' );
                wp_enqueue_style ( 'wp-jquery-ui-dialog' );
                wp_enqueue_script('wpsp_admin', WCE_PLUGIN_URL.'asset/js/admin.js?version='.WPSP_VERSION);
		if($advancedSettings['enable_accordion']==1){
                    /* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
                    * Update 18 - Thread accordion
                    * jQuery accordion for threads
                    */ 
                   wp_enqueue_script( 'jquery-ui-accordion' );
                   wp_enqueue_style("jquery-ui-css", WCE_PLUGIN_URL . 'asset/css/jquery-ui.min.css');
                   wp_enqueue_style("jquery-ui-structure-css", WCE_PLUGIN_URL . 'asset/css/jquery-ui.structure.min.css');
                   wp_enqueue_style("jquery-ui-theme-css", WCE_PLUGIN_URL . 'asset/css/jquery-ui.theme.min.css');
                   /* END CLOUGH I.T. SOLUTIONS MODIFICATION
                    */
                }
		wp_enqueue_style('wpce_admin', WCE_PLUGIN_URL . 'asset/css/admin.css?version='.WPSP_VERSION);
                wp_enqueue_script('jquery-ui-datepicker');
                wp_enqueue_style('jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	}
	
	function custom_menu_page(){
		$advancedSettings=get_option( 'wpsp_advanced_settings' );
		add_menu_page( 'WP Support Plus', $advancedSettings['wpsp_dashboard_menu_label'], 'manage_support_plus_ticket', 'wp-support-plus', array($this,'tickets'),WCE_PLUGIN_URL.'asset/images/support.png', '51.66' );
		add_submenu_page( 'wp-support-plus', 'WP Support Plus FAQ',  __('FAQ','wp-support-plus-responsive-ticket-system'), 'manage_support_plus_agent', 'wp-support-plus-faq', array($this,'faq') );
		add_submenu_page( 'wp-support-plus', 'WP Support Plus Canned Reply',  __('Canned Reply','wp-support-plus-responsive-ticket-system'), 'manage_support_plus_ticket', 'wp-support-plus-Canned-Reply', array($this,'canned_reply') );
                add_submenu_page( 'wp-support-plus', 'WP Support Plus Statistics', __('Statistics','wp-support-plus-responsive-ticket-system'), 'manage_support_plus_agent', 'wp-support-plus-statistics', array($this,'statistics') );
		add_submenu_page( 'wp-support-plus', 'WP Support Plus Settings', __('Settings','wp-support-plus-responsive-ticket-system'), 'manage_options', 'wp-support-plus-settings', array($this,'settings') );
		add_submenu_page( 'wp-support-plus', 'WP Support Plus Advanced Settings', __('Advanced Settings','wp-support-plus-responsive-ticket-system'), 'manage_options', 'wp-support-plus-advanced-settings', array($this,'advancedsettings') );
		add_submenu_page( 'wp-support-plus', 'WP Support Plus Email Templates', __('Email Templates','wp-support-plus-responsive-ticket-system'), 'manage_options', 'wp-support-plus-email-templates', array($this,'email_templates') );
                add_submenu_page( 'wp-support-plus', 'WP Support Plus Add-ons', __('Add-ons','wp-support-plus-responsive-ticket-system'), 'manage_options', 'wp-support-plus-add-ons', array($this,'addons') );
		add_submenu_page( 'wp-support-plus', 'WP Support Plus Support', __('Doc & Support','wp-support-plus-responsive-ticket-system'), 'manage_options', 'wp-support-plus-support', array($this,'support') );
	}
	
	function tickets(){
            $advancedSettings=get_option( 'wpsp_advanced_settings' );
            //Load Bootstrap

            wp_enqueue_script('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/js/bootstrap.min.js?version='.WPSP_VERSION);
            wp_enqueue_style('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/css/bootstrap.min.css?version='.WPSP_VERSION);
            wp_enqueue_script('wpce_display_ticket', WCE_PLUGIN_URL . 'asset/js/display_ticket.js?version='.WPSP_VERSION);

            wp_enqueue_style('wpce_display_ticket', WCE_PLUGIN_URL . 'asset/css/display_ticket.css?version='.WPSP_VERSION);
            
            $confirmsg=apply_filters('wpsp_change_confirm_msg_backend','Are you sure to submit?');
            
            $localize_script_data=array(
                'wpsp_ajax_url'=>admin_url( 'admin-ajax.php' ),
                'wpsp_site_url'=>site_url(),
                'plugin_url'=>WCE_PLUGIN_URL,
                'plugin_dir'=>WCE_PLUGIN_DIR,
                'insert_all_required'=>__('Please Enter all required fields','wp-support-plus-responsive-ticket-system'),
                'reply_not_empty'=>__('Reply can not be empty!','wp-support-plus-responsive-ticket-system'),
                'sure_to_delete'=>__('Are you sure to delete this ticket?','wp-support-plus-responsive-ticket-system'),
                'sure_to_clone'=>__('Are you sure to clone this ticket?','wp-support-plus-responsive-ticket-system'),
                'sure_to_delete_mult'=>__('Are you sure to delete these tickets?','wp-support-plus-responsive-ticket-system'),
                'can_not_undone'=>__('Can not be undone','wp-support-plus-responsive-ticket-system'),
                'reply_ticket_position'=>$advancedSettings['wpsp_reply_form_position'],
                'wpsp_shortcode_used_in'=>$advancedSettings['wpsp_shortcode_used_in'],
                'enable_accordion'=>$advancedSettings['enable_accordion'],
                'ticketId'=>$advancedSettings['ticketId'],
                'clone_succes'=>__('Clone Ticket ID:','wp-support-plus-responsive-ticket-system'),
                'sure_to_close_status'=>__('Are you sure?','wp-support-plus-responsive-ticket-system'),
                'close_status_succes'=>__('Close Ticket ID:','wp-support-plus-responsive-ticket-system'),
                'Not_valid_email_address'=>__('Please enter valid email address!','wp-support-plus-responsive-ticket-system'),
                'not_applicable'=>__('Not Applicable','wp-support-plus-responsive-ticket-system'),
                'wpsp_redirect_after_ticket_update'=>$advancedSettings['wpsp_redirect_after_ticket_update'],
                'sure_to_submit_ticket'=>__($confirmsg,'wp-support-plus-responsive-ticket-system'),
                'sure_to_submit_note'=>__('Are you sure to add note?','wp-support-plus-responsive-ticket-system'),
                'wpspAttachMaxFileSize'=>$advancedSettings['wpspAttachMaxFileSize'],
                'wpspAttachFileSizeExeeded'=>__('File Size limit exceeded! Allowed limit:','wp-support-plus-responsive-ticket-system').' '.$advancedSettings['wpspAttachMaxFileSize'].__('MB','wp-support-plus-responsive-ticket-system'),
                'wpspRemoveAttachment'=>__('Remove','wp-support-plus-responsive-ticket-system'),
                'wait_until_upload'=>__('Uploading attachment, please wait!','wp-support-plus-responsive-ticket-system'),
                'reset_form'=>__('Reset form data??','wp-support-plus-responsive-ticket-system'),
                'sure_to_delete_thread'=>__('Are you sure to delete this thread?','wp-support-plus-responsive-ticket-system'),
                'wpspAttachment_bc'=>$advancedSettings['wpspAttachment_bc'],                 
                'wpspAttachment_pc'=>$advancedSettings['wpspAttachment_pc']
            );
            wp_localize_script( 'wpce_display_ticket', 'display_ticket_data', $localize_script_data );

            wp_enqueue_script('wpce_ckeditor_editor', WCE_PLUGIN_URL . 'asset/lib/ckeditor/ckeditor.js?version='.WPSP_VERSION);
            wp_enqueue_script('wpce_ckeditor_jquery_adapter', WCE_PLUGIN_URL . 'asset/lib/ckeditor/adapters/jquery.js?version='.WPSP_VERSION);

            global $current_user;
            $current_user=wp_get_current_user();
            $generalSettings=get_option( 'wpsp_general_settings' );
            $this->check_offer();
            ?>
            <div class="panel panel-primary wpsp_admin_panel">
              <div class="panel-heading">
                <h3 class="panel-title"><?php _e('WP Support Plus','wp-support-plus-responsive-ticket-system');?></h3>
                <span class="wpsp_support_admin_welcome"><?php echo __('Welcome','wp-support-plus-responsive-ticket-system').", ".$current_user->display_name;?></span>
              </div>
              <div class="panel-body">
                <?php include( WCE_PLUGIN_DIR.'includes/admin/display_ticket.php' );?>
              </div>
            </div>
            <?php 
            do_action('wpsp_tickets_end_backend');
	}
        
        function addons(){
            $this->check_offer();
            include( WCE_PLUGIN_DIR.'includes/admin/add_ons.php' );
        }
	
	function settings(){
		//Load Bootstrap
		wp_enqueue_script('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/js/bootstrap.min.js?version='.WPSP_VERSION);
		wp_enqueue_style('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/css/bootstrap.min.css?version='.WPSP_VERSION);
		wp_enqueue_script('wpce_admin_settings', WCE_PLUGIN_URL . 'asset/js/admin_settings.js?version='.WPSP_VERSION);
		wp_enqueue_style('wpce_admin_settings', WCE_PLUGIN_URL . 'asset/css/admin_settings.css?version='.WPSP_VERSION);
		
                $pipe_active=0;
                if(class_exists('WPSupportPlusEmailPipe')){
                    $pipe_active=1;
                }
                
		$localize_script_data=array(
				'wpsp_ajax_url'=>admin_url( 'admin-ajax.php' ),
				'wpsp_site_url'=>site_url(),
				'plugin_url'=>WCE_PLUGIN_URL,
				'plugin_dir'=>WCE_PLUGIN_DIR,
				'insert_cat_name'=>__('Please insert category name!','wp-support-plus-responsive-ticket-system'),
				'insert_admin_email_add'=>__('Please insert adminstrator email address!','wp-support-plus-responsive-ticket-system'),
				'insert_menu_text'=>__('Please insert menu text','wp-support-plus-responsive-ticket-system'),
				'insert_redirection_url'=>__('Please insert Redirect URL','wp-support-plus-responsive-ticket-system'),
				'sure'=>__('Are you sure?','wp-support-plus-responsive-ticket-system'),
				'insert_field_label'=>__('Please insert field label!','wp-support-plus-responsive-ticket-system'),
				'insert_field_options'=>__('Please insert field options!','wp-support-plus-responsive-ticket-system'),
                                'select_user'=>__('Please select user','wp-support-plus-responsive-ticket-system'),
                                'test_imap_error'=>__('Please test your IMAP connection first!','wp-support-plus-responsive-ticket-system'),
                                'pipe_active'=>$pipe_active
		);
		wp_localize_script( 'wpce_admin_settings', 'display_ticket_data', $localize_script_data );
		
		add_thickbox();
		$this->check_offer();
		?>
		<div class="panel panel-primary wpsp_admin_panel" >
		  <div class="panel-heading">
		    <h3 class="panel-title"><?php _e('WP Support Plus Settings','wp-support-plus-responsive-ticket-system');?></h3>
		  </div>
		  <div class="panel-body">
		    <?php include( WCE_PLUGIN_DIR.'includes/admin/admin_settings.php' );?>
		  </div>
		</div>
		<?php 
	}

	function advancedsettings(){
		//Load Bootstrap
		wp_enqueue_script('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/js/bootstrap.min.js?version='.WPSP_VERSION);
		wp_enqueue_style('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/css/bootstrap.min.css?version='.WPSP_VERSION);
		wp_enqueue_script( 'my-jquery-ui' );
		wp_enqueue_script('jquery-ui-dropable');
   		wp_enqueue_script('jquery-ui-dragable');
   		wp_enqueue_script('jquery-ui-selectable');
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script('wpce_advanced_settings', WCE_PLUGIN_URL . 'asset/js/advanced_settings.js?version='.WPSP_VERSION);
		wp_enqueue_style('wpce_advanced_settings', WCE_PLUGIN_URL . 'asset/css/admin_settings.css?version='.WPSP_VERSION);
		wp_enqueue_script('jquery-ui-datepicker');
                wp_enqueue_style('jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		
                $localize_script_data=array(
                    'wpsp_ajax_url'=>admin_url( 'admin-ajax.php' ),
                    'wpsp_site_url'=>site_url(),
                    'plugin_url'=>WCE_PLUGIN_URL,
                    'plugin_dir'=>WCE_PLUGIN_DIR,
                    'insert_cat_name'=>__('Please insert category name!','wp-support-plus-responsive-ticket-system'),
                    'insert_admin_email_add'=>__('Please insert adminstrator email address!','wp-support-plus-responsive-ticket-system'),
                    'insert_menu_text'=>__('Please insert menu text','wp-support-plus-responsive-ticket-system'),
                    'insert_redirection_url'=>__('Please insert Redirect URL','wp-support-plus-responsive-ticket-system'),
                    'sure'=>__('Are you sure?','wp-support-plus-responsive-ticket-system'),
                    'insert_field_label'=>__('Please insert field label!','wp-support-plus-responsive-ticket-system'),
                    'custom_status_warning'=>__(' All the tickets belonging to this status will get moved to pending status','wp-support-plus-responsive-ticket-system'),
                    'insert_integer_value'=>__('Please insert integer value','wp-support-plus-responsive-ticket-system'),
                    'custom_priority_warning'=>__(' All the tickets belonging to this priority will get moved to normal priority','wp-support-plus-responsive-ticket-system'),
                    'export_date_missing'=>__('Missing From date or To date!','wp-support-plus-responsive-ticket-system'),
                    'select_image'=>__('Please select at least one image!','wp-support-plus-responsive-ticket-system')
                    
		);
		wp_localize_script( 'wpce_advanced_settings', 'display_ticket_data', $localize_script_data );
		wp_enqueue_script('wpce_ckeditor_editor', WCE_PLUGIN_URL . 'asset/lib/ckeditor/ckeditor.js?version='.WPSP_VERSION);
		wp_enqueue_script('wpce_ckeditor_jquery_adapter', WCE_PLUGIN_URL . 'asset/lib/ckeditor/adapters/jquery.js?version='.WPSP_VERSION);
		$this->check_offer();
		?>
		<div class="panel panel-primary wpsp_admin_panel" >
		  <div class="panel-heading">
		    <h3 class="panel-title"><?php _e('WP Support Plus Settings','wp-support-plus-responsive-ticket-system');?></h3>
		  </div>
		  <div class="panel-body">
		    <?php include( WCE_PLUGIN_DIR.'includes/admin/advanced_settings.php' );?>
		  </div>
		</div>
		<?php
	}
	
	function support(){
		$this->check_offer();
		?>
                <br>
                <p class="wpsp_support_page_paragraph">Please check <b><a target="_blank" href="http://pradeepmakone.com/documentation/">Documentation</a></b> before creating Support Ticket.</p>
                <p class="wpsp_support_page_paragraph"><b><a target="_blank" href="https://www.wpsupportplus.com/support/">Click here</a></b> to Create Support Ticket for <b>WP Support Plus</b>. Support ticket is one to one conversation between you and our support team, so you can share login details and access required to check issue on your site.</p>
                <p class="wpsp_support_page_paragraph"><i>(Support Ticket will be replied on priority of users. Our customers who purchased at least one Add-On will get high priority while replying to tickets.)</i></p>
		<?php 
	}
	
	function statistics(){
		wp_enqueue_script('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/js/bootstrap.min.js?version='.WPSP_VERSION);
		wp_enqueue_style('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/css/bootstrap.min.css?version='.WPSP_VERSION);
		$this->check_offer();
		?>
		<div class="panel panel-primary wpsp_admin_panel">
		  <div class="panel-heading">
		    <h3 class="panel-title"><?php _e('WP Support Plus Statistics','wp-support-plus-responsive-ticket-system');?></h3>
		  </div>
		  <div class="panel-body">
		    <?php include( WCE_PLUGIN_DIR.'includes/admin/statistics.php' );?>
		  </div>
		</div>
		<?php 
	}

	function faq(){
		wp_enqueue_script('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/js/bootstrap.min.js?version='.WPSP_VERSION);
		wp_enqueue_style('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/css/bootstrap.min.css?version='.WPSP_VERSION);
                $this->check_offer();
		?>
		<div class="panel panel-primary wpsp_admin_panel">
		  <div class="panel-heading">
		    <h3 class="panel-title"><?php _e('WP Support Plus FAQ','wp-support-plus-responsive-ticket-system');?></h3>
		  </div>
		  <div class="panel-body">
		    <?php include( WCE_PLUGIN_DIR.'includes/admin/faq.php' );?>
		  </div>
		</div>
		<?php
	}
	
	function email_templates(){
		wp_enqueue_script('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/js/bootstrap.min.js?version='.WPSP_VERSION);
		wp_enqueue_style('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/css/bootstrap.min.css?version='.WPSP_VERSION);
		wp_enqueue_style('wpce_advanced_settings', WCE_PLUGIN_URL . 'asset/css/admin_settings.css?version='.WPSP_VERSION);
		wp_enqueue_script('wpce_email_template_settings', WCE_PLUGIN_URL . 'asset/js/email_template.js?version='.WPSP_VERSION);
		$localize_script_data=array(
				'wpsp_ajax_url'=>admin_url( 'admin-ajax.php' ),
				'wpsp_site_url'=>site_url(),
				'plugin_url'=>WCE_PLUGIN_URL,
				'plugin_dir'=>WCE_PLUGIN_DIR
		);
		wp_localize_script( 'wpce_email_template_settings', 'display_ticket_data', $localize_script_data );
		wp_enqueue_script('wpce_ckeditor_editor', WCE_PLUGIN_URL . 'asset/lib/ckeditor/ckeditor.js?version='.WPSP_VERSION);
		wp_enqueue_script('wpce_ckeditor_jquery_adapter', WCE_PLUGIN_URL . 'asset/lib/ckeditor/adapters/jquery.js?version='.WPSP_VERSION);
		$this->check_offer();
		?>
		<div class="panel panel-primary wpsp_admin_panel">
		  <div class="panel-heading">
		    <h3 class="panel-title"><?php _e('Email Templates','wp-support-plus-responsive-ticket-system');?></h3>
		  </div>
		  <div class="panel-body">
		    <?php include( WCE_PLUGIN_DIR.'includes/admin/emailTemplates.php' );?>
		  </div>
		</div>
		<?php
	}
        
        function canned_reply(){
		wp_enqueue_script('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/js/bootstrap.min.js?version='.WPSP_VERSION);
		wp_enqueue_style('wpce_bootstrap', WCE_PLUGIN_URL . 'asset/js/bootstrap/css/bootstrap.min.css?version='.WPSP_VERSION);
                wp_enqueue_script('wpce_canned_reply', WCE_PLUGIN_URL . 'asset/js/canned.js?version='.WPSP_VERSION);
                $localize_script_data=array(
				'wpsp_ajax_url'=>admin_url( 'admin-ajax.php' ),
				'wpsp_site_url'=>site_url(),
				'plugin_url'=>WCE_PLUGIN_URL,
				'plugin_dir'=>WCE_PLUGIN_DIR
		);
                wp_localize_script( 'wpce_canned_reply', 'display_ticket_data', $localize_script_data );
		$this->check_offer();
		?>
		<div class="panel panel-primary wpsp_admin_panel">
		  <div class="panel-heading">
		    <h3 class="panel-title"><?php _e('WP Support Plus Canned Reply','wp-support-plus-responsive-ticket-system');?></h3>
		  </div>
		  <div class="panel-body">
		    <?php include( WCE_PLUGIN_DIR.'includes/admin/canned.php' );?>
		  </div>
		</div>
		<?php
	}
        
        function check_offer(){
            if(!$this->is_addon_activated()){?>
                <div id="wpsp_addon_offer">
                    Subscribe to our <span style="text-decoration: underline"><strong><a href="https://www.wpsupportplus.com/newsletter/">Newsletter</a></strong></span> and get <strong>20%</strong> discount on purchase of Add-Ons for the first transaction. See <span style="text-decoration: underline"><a href="https://www.wpsupportplus.com/add-ons/"><strong>Add-Ons</strong></a></span> available for WP Support Plus.
                    <small><i>(This Ad will be removed automatically when you have at least one Add-On installed)</i></small>
                </div>
            <?php }
        }
        
        function is_addon_activated(){
            if(class_exists('WPSupportPlusEmailPipe') || class_exists('WPSupportPlusWoocommerce') || class_exists('WPSupportPlusExportTicket') || class_exists('WPSP_EDD') || class_exists('WPSP_TIMER') || class_exists('WPSP_COMPANY') ){
                return true;
            }
            return false;
        }

}

$GLOBALS['WPSupportPlusAdmin'] =new WPSupportPlusAdmin();
?>
