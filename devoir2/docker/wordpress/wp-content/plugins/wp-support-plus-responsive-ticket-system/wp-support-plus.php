<?php 
/**
 * Plugin Name: WP Support Plus
 * Plugin URI: https://wordpress.org/plugins/wp-support-plus-responsive-ticket-system-ticket-system/
 * Description: Easy to use Customer Support System in Wordpress itself!
 * Version: 7.1.3
 * Author: Pradeep Makone
 * Author URI: http://profiles.wordpress.org/pradeepmakone07/
 * Requires at least: 4.4
 * Tested up to: 4.7
 * Text Domain: wp-support-plus-responsive-ticket-system
 * Domain Path: /lang
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class WPSupportPlus{
	public function __construct() {
                $this->define_constants();
		add_action( 'init', array($this,'load_textdomain') );
		add_action( 'plugins_loaded', array($this,'installation') );
		register_activation_hook( __FILE__, array($this,'installation') );
                register_deactivation_hook( __FILE__, array($this,'deactivate') );
		$this->include_files();
                
                add_action( 'init', array($this,'load_wpsp_actions') );
		
		//output buffer for faq
		add_action('init', array($this,'do_output_buffer'));
		add_action('wp_footer',array($this,'close_pending_tickets'));
                
                $advancedSettings=get_option( 'wpsp_advanced_settings' );
                if($advancedSettings['admin_bar_Setting']==1){
                    add_action( 'wp_before_admin_bar_render', array( $this, 'admin_bar' ) );
                }
                /*
                 * WPSP Cron
                 */
                if (!wp_next_scheduled('wpsp_offer_and_update_checker')) {
                    wp_schedule_event(time(), 'daily', 'wpsp_offer_and_update_checker');
                }
                if (!wp_next_scheduled('wpsp_attachment_garbage_collection')) {
                    wp_schedule_event(time(), 'daily', 'wpsp_attachment_garbage_collection');
                }
                include( WCE_PLUGIN_DIR.'pipe/imap/wpsp_cron.php' );
                $cron=new WPSPCron();
                add_action( 'wpsp_offer_and_update_checker', array( $cron, 'check_offer_and_update'));
                add_action( 'wpsp_attachment_garbage_collection', array( $cron, 'attachment_garbage_collection'));
                
                /*
                 * add and publish open ticket page for ticket URL links
                 */
                add_action( 'wp_loaded', array ( $this, 'create_open_ticket_page' ) );
                
                /*
                 * Popup for woocommerce and other tasks
                 */
                add_action('wp_footer',array($this,'wpsp_front_popup'));
                add_action('admin_footer',array($this,'wpsp_front_popup'));
                   
	}
        
        function load_wpsp_actions(){
            include( WCE_PLUGIN_DIR.'includes/admin/actions/load_wpsp_actions.php' );
        }
        
        function wpsp_front_popup(){
            include( WCE_PLUGIN_DIR.'includes/woo/wpsp_front_popup.php' );
        }

	function do_output_buffer() {
		if ((isset($_REQUEST['page']) && $_REQUEST['page']=='wp-support-plus-faq')||(isset($_REQUEST['page']) && $_REQUEST['page']=='wp-support-plus-Canned-Reply')){
                    ob_start();
		}
                if(isset($_REQUEST['ticket_attachment'])){
                    include( WCE_PLUGIN_DIR.'includes/admin/attachment/download_attachment.php' );
                }
	}
	
	function load_textdomain(){
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wp-support-plus-responsive-ticket-system' );
                load_textdomain( 'wp-support-plus-responsive-ticket-system', WP_LANG_DIR . '/wpsp/wp-support-plus-responsive-ticket-system-' . $locale . '.mo' );
		load_plugin_textdomain( 'wp-support-plus-responsive-ticket-system', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );
	}
	
	function close_pending_tickets(){
		include( WCE_PLUGIN_DIR.'includes/admin/close_pending_tickets.php' );
	}
	
	private function define_constants() {
		define( 'WPSP_STORE_URL', "https://www.wpsupportplus.com/" );
                define( 'WCE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		define( 'WCE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		define( 'WPSP_VERSION', '7.1.3' );
	}
        
        function create_open_ticket_page(){
            if( get_option( 'wpsp_ticket_open_page_shortcode' ) === false ){
                $new_post = array(
                    'post_title' => 'Open Ticket',
                    'post_content' => '[wpsp_open_ticket]',
                    'post_status' => 'publish',
                    'post_type' => 'page'
                );
                $post_id = wp_insert_post($new_post);
                update_option('wpsp_ticket_open_page_shortcode',$post_id);
            }
        }

        private function include_files(){
		if (is_admin()) {
			include( WCE_PLUGIN_DIR.'includes/admin/admin.php' );
		}
		else {
 			include( WCE_PLUGIN_DIR.'includes/shortcode.php' );
 			include( WCE_PLUGIN_DIR.'includes/support_button.php' );
		}
	}
	
	function installation(){
            include( WCE_PLUGIN_DIR.'includes/admin/installation.php' );
	}
        
        function deactivate(){
            include( WCE_PLUGIN_DIR.'includes/admin/uninstall.php' );
        }
        
        function admin_bar() {
            global $current_user;
            $advancedSettings=get_option( 'wpsp_advanced_settings' );
            $current_user=wp_get_current_user();
            if($current_user->has_cap('manage_support_plus_ticket')){
                $GLOBALS[ 'wp_admin_bar' ]->add_menu(
                    array(
                        'id'    => 'wp-support-plus-admin-bar',
                        'title' => $advancedSettings['wpsp_dashboard_menu_label'],
                        'href'  => admin_url( 'admin.php?page=wp-support-plus' )
                    )
                );
            }
	}	
}

$GLOBALS['WPSupportPlus'] =new WPSupportPlus();

/*
 * includ EDD updator class
 */
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    include( WCE_PLUGIN_DIR.'asset/lib/EDD_SL_Plugin_Updater.php' );
}
?>
