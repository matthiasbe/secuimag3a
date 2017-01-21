<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

//Roll & Capability
if(!get_role('wp_support_plus_agent')){
	add_role( 'wp_support_plus_agent', 'Support Agent' );
	$role = get_role( 'wp_support_plus_agent' );
	$role->add_cap( 'manage_support_plus_ticket' );
	$role->add_cap( 'read' );
	$role = get_role( 'administrator' );
	$role->add_cap( 'manage_support_plus_ticket' );
}

//supervisor roll- added in 2.0
if(!get_role('wp_support_plus_supervisor')){
	add_role( 'wp_support_plus_supervisor', 'Support Supervisor' );
	$role = get_role( 'wp_support_plus_supervisor' );
	$role->add_cap( 'manage_support_plus_ticket' );
	$role->add_cap( 'manage_support_plus_agent' );
	$role->add_cap( 'read' );
	$role = get_role( 'administrator' );
	$role->add_cap( 'manage_support_plus_agent' );
}

$installed_version = get_option( 'wp_support_plus_version' );
if ( current_filter() != 'plugins_loaded' || $installed_version != WPSP_VERSION ) {
	
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
        $sql = "CREATE TABLE {$wpdb->prefix}wpsp_ticket (
		id integer NOT NULL AUTO_INCREMENT,
		subject TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		created_by integer,
		updated_by INT NOT NULL DEFAULT '0',
		assigned_to VARCHAR( 30 ) NULL DEFAULT '0',
		guest_name TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		guest_email TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		type TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		status TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		cat_id integer,
		create_time datetime,
		update_time datetime,
		priority TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		ticket_type INT NULL DEFAULT '0',
                extension_meta TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                agent_created INT NULL DEFAULT '0',
		PRIMARY KEY  (id)
	);";
	dbDelta( $sql );
        
	// wpsp_ticket_thread
	$sql = "CREATE TABLE {$wpdb->prefix}wpsp_ticket_thread (
		id integer NOT NULL AUTO_INCREMENT,
		ticket_id integer,
		body LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		attachment_ids TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		create_time datetime,
		created_by integer,
		guest_name TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		guest_email TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		is_note INT NULL DEFAULT '0',
		PRIMARY KEY  (id)
	);";
	dbDelta( $sql );
        
	// wpsp_attachments
	$sql = "CREATE TABLE {$wpdb->prefix}wpsp_attachments (
		id integer NOT NULL AUTO_INCREMENT,
		filename TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		filetype TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		filepath TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		fileurl TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                download_key varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
                active integer(2) DEFAULT 1,
                upload_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (id)
	);";
	dbDelta( $sql );

	// wpsp_catagories
	$sql = "CREATE TABLE {$wpdb->prefix}wpsp_catagories (
		id integer NOT NULL AUTO_INCREMENT,
		name TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		default_assignee VARCHAR( 30 ) NULL DEFAULT '0',
		PRIMARY KEY  (id)
	);";
	dbDelta( $sql );
	
	//check for General category
	$generaCategory = $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}wpsp_catagories where id=1" );
	if(!$generaCategory){
		$wpdb->insert($wpdb->prefix.'wpsp_catagories',array('name'=>'General'));
	}

	$sql = "CREATE TABLE {$wpdb->prefix}wpsp_agent_settings (
		id integer NOT NULL AUTO_INCREMENT,
		agent_id integer NULL DEFAULT NULL,
		signature LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		skype_id TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		skype_chat_availability INT NOT NULL DEFAULT '0',
		skype_call_availability INT NOT NULL DEFAULT '0',
		PRIMARY KEY  (id)
	);";
	dbDelta( $sql );

	$sql = "CREATE TABLE {$wpdb->prefix}wpsp_panel_custom_menu (
		id integer NOT NULL AUTO_INCREMENT,
		menu_text varchar(50),
		menu_icon varchar(200),
		redirect_url varchar(200),
		PRIMARY KEY  (id)
	);";
	dbDelta( $sql );

	$sql = "CREATE TABLE {$wpdb->prefix}wpsp_custom_fields (
		id integer NOT NULL AUTO_INCREMENT,
		label varchar(200),
		required INT NULL DEFAULT '0',
		field_type INT NULL DEFAULT '1',
		field_options TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                field_categories varchar(100) DEFAULT '0',
		PRIMARY KEY  (id)
	);";
	dbDelta( $sql );
        
         $coloums=$wpdb->get_results("SHOW COLUMNS FROM {$wpdb->prefix}wpsp_custom_fields like '%isVarFeild'");
         if(count($coloums)==0){
             $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_custom_fields ADD isVarFeild int(11) DEFAULT 0");
        }
        
	// wpsp_faq_catagories
	$sql = "CREATE TABLE {$wpdb->prefix}wpsp_faq_catagories (
		id integer NOT NULL AUTO_INCREMENT,
		name TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		PRIMARY KEY  (id)
	);";
	dbDelta( $sql );
        //check for General FAQ category
        $defaultFAQ = $wpdb->get_var("select name from {$wpdb->prefix}wpsp_faq_catagories where id=1");
        if (is_null($defaultFAQ)) {
            $wpdb->insert(
                        $wpdb->prefix . "wpsp_faq_catagories", 
                        array(
                            'id' => 1,
                            'name' => __('General','wp-support-plus-responsive-ticket-system')
                        )
                    );
        }
        // wpsp_faq
	$sql = "CREATE TABLE {$wpdb->prefix}wpsp_faq (
		id integer NOT NULL AUTO_INCREMENT,
		question TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		answer LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		category_id INT NOT NULL DEFAULT '1',
		PRIMARY KEY  (id)
	);";
	dbDelta( $sql );
        
        $tables=$wpdb->get_results("show tables like '%wpsp_canned_reply'");
        if(count($tables)==0){
            $sql = "CREATE TABLE {$wpdb->prefix}wpsp_canned_reply(
                    id integer NOT NULL AUTO_INCREMENT,
                    title TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                    reply LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                    uID integer NOT NULL,
                    PRIMARY KEY  (id)
            );";
            $wpdb->query($sql);
        }
        
        //Add column if not present.
        $coloums=$wpdb->get_results("SHOW COLUMNS FROM {$wpdb->prefix}wpsp_canned_reply like '%sid'");
        if(count($coloums)==0){
            $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_canned_reply ADD sid varchar(50) NULL DEFAULT NULL");
        }

	$sql = "CREATE TABLE {$wpdb->prefix}wpsp_custom_status (
		id integer NOT NULL AUTO_INCREMENT,
		name TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		color varchar(10),
		PRIMARY KEY  (id)
	);";
	dbDelta( $sql );

	if( get_option( 'wpsp_default_status_priority_names' ) === false ){
            $defaultStatusName = $wpdb->get_var( "SELECT count(name) FROM {$wpdb->prefix}wpsp_custom_status where name='open'" );
            if(!$defaultStatusName){
                    $wpdb->insert($wpdb->prefix.'wpsp_custom_status',array('name'=>'open','color'=>'#d9534f'));
                    $wpdb->insert($wpdb->prefix.'wpsp_custom_status',array('name'=>'pending','color'=>'#f0ad4e'));
                    $wpdb->insert($wpdb->prefix.'wpsp_custom_status',array('name'=>'closed','color'=>'#5cb85c'));
            }
            /*
             * multiple default status bug fix start
             */
            else if($defaultStatusName > 1){
                    $wpdb->delete($wpdb->prefix.'wpsp_custom_status',array('name'=>'open'));
                    $wpdb->delete($wpdb->prefix.'wpsp_custom_status',array('name'=>'pending'));
                    $wpdb->delete($wpdb->prefix.'wpsp_custom_status',array('name'=>'closed'));
                    $wpdb->insert($wpdb->prefix.'wpsp_custom_status',array('name'=>'open','color'=>'#d9534f'));
                    $wpdb->insert($wpdb->prefix.'wpsp_custom_status',array('name'=>'pending','color'=>'#f0ad4e'));
                    $wpdb->insert($wpdb->prefix.'wpsp_custom_status',array('name'=>'closed','color'=>'#5cb85c'));
            }
             
            /*
             * multiple default status bug fix end
             */
        }
        
	$sql = "CREATE TABLE {$wpdb->prefix}wpsp_custom_priority (
		id integer NOT NULL AUTO_INCREMENT,
		name TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
		color varchar(10),
		PRIMARY KEY  (id)
	);";
	dbDelta( $sql );
	
        if( get_option( 'wpsp_default_status_priority_names' ) === false ){
            $defaultPriority = $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}wpsp_custom_priority where id=1" );
            if(!$defaultPriority){
                    $wpdb->insert($wpdb->prefix.'wpsp_custom_priority',array('name'=>__('Normal','wp-support-plus-responsive-ticket-system'),'color'=>'#5cb85c'));
                    $wpdb->insert($wpdb->prefix.'wpsp_custom_priority',array('name'=>__('High','wp-support-plus-responsive-ticket-system'),'color'=>'#d9534f'));
                    $wpdb->insert($wpdb->prefix.'wpsp_custom_priority',array('name'=>__('Medium','wp-support-plus-responsive-ticket-system'),'color'=>'#f0ad4e'));
                    $wpdb->insert($wpdb->prefix.'wpsp_custom_priority',array('name'=>__('Low','wp-support-plus-responsive-ticket-system'),'color'=>'#5bc0de'));
            }
        }
	
	// update wp_support_plus_version option to plugin version
	update_option( 'wp_support_plus_version', WPSP_VERSION );
        
        if( get_option( 'wpsp_add_table_indexes' ) === false ){
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_ticket ADD INDEX  `subject` (`subject`(500))");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_ticket ADD INDEX  `assigned_to` (`assigned_to`)");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_ticket ADD INDEX  `cat_id` (  `cat_id` )");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_ticket ADD INDEX  `ticket_type` (  `ticket_type` )");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_ticket ADD INDEX  `created_by` (  `created_by` )");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_ticket ADD INDEX  `update_time` (  `update_time` )");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_ticket_thread ADD INDEX  `ticket_id` (  `ticket_id` )");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_ticket_thread ADD FULLTEXT(  `body` )");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_faq ADD INDEX  `category_id` (  `category_id` )");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_canned_reply ADD INDEX  `uID` (  `uID` )");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_custom_status ADD INDEX  `name` (  `name` (255))");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_custom_priority ADD INDEX  `name` (  `name` (255))");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_ticket ADD INDEX  `status` (  `status` ( 255 ))");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_ticket ADD INDEX  `priority` (  `priority` ( 255 ))");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_ticket ADD INDEX  `agent_created` (  `agent_created` )");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_ticket ADD INDEX  `type` (  `type` ( 255 ))");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_catagories ADD INDEX  `name` (  `name` (255))");
                $wpdb->query("ALTER TABLE {$wpdb->prefix}wpsp_faq_catagories ADD INDEX  `name` (  `name` (255))");
                update_option('wpsp_add_table_indexes',TRUE);
        }
        
        
        $customFields=$wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
	if( get_option( 'wpsp_general_settings' ) === false ) {
		$generalSettings=array(
			'post_id'=>0,
			'enable_support_button'=>0,
			'support_button_position'=>'bottom_left',
			'enable_guest_ticket'=>0,
			'fbAppID' => '',
			'fbAppSecret' => '',
			'support_title' => 'Need Help?',
			'support_phone_number' => '',
			'display_skype_chat' => 1,
			'display_skype_call' => 1,
			'default_ticket_category' => '1',
			'enable_default_login' => '1',
			'enable_user_selection_public_private' => 0,
			'default_ticket_type' => 0,
			'allow_agents_to_assign_tickets' => 0,
			'allow_agents_to_delete_tickets' => 0,
			'allow_attachment_for_guest_ticket' => 0,
			'ticket_status_after_cust_reply' => 'default',
			'google_nocaptcha_key' => '',
			'google_nocaptcha_secret' => '',
			'front_end_submission' => 0,
			'default_new_ticket_status' => 1,
                        'enable_register_guest_user'=>0,
                        'guest_user_role'=>'subscriber'
		);
		update_option('wpsp_general_settings',$generalSettings);
	} 
        
        $generalSettings=get_option( 'wpsp_general_settings' );
        if(!isset($generalSettings['fbAppID'])){
                $generalSettings['fbAppID']='';
                $generalSettings['fbAppSecret']='';
        }
        if(!isset($generalSettings['support_title'])){
                $generalSettings['support_title']='Need Help?';
                $generalSettings['support_phone_number']='';
                $generalSettings['display_skype_chat']=1;
                $generalSettings['display_skype_call']=1;
        }
        if(!isset($generalSettings['enable_slider_menu'])){
                $generalSettings['enable_slider_menu']=1;
        }
        if(!isset($generalSettings['default_ticket_category'])){
                $generalSettings['default_ticket_category']="1";
        }
        if(!isset($generalSettings['enable_default_login'])){
                $generalSettings['enable_default_login']='1';
        }
        if(!isset($generalSettings['enable_user_selection_public_private'])){
                $generalSettings['enable_user_selection_public_private']=0;
                $generalSettings['default_ticket_type']=0;
        }
        if(!isset($generalSettings['allow_agents_to_assign_tickets'])){
                $generalSettings['allow_agents_to_assign_tickets']=0;
                $generalSettings['allow_agents_to_delete_tickets']=0;
        }
        if(!isset($generalSettings['allow_attachment_for_guest_ticket'])){
                $generalSettings['allow_attachment_for_guest_ticket']=0;
        }
        if(!isset($generalSettings['ticket_status_after_cust_reply'])){
                $generalSettings['ticket_status_after_cust_reply']='default';
        }
        if(!isset($generalSettings['google_nocaptcha_key'])){
                $generalSettings['google_nocaptcha_key']='';
                $generalSettings['google_nocaptcha_secret']='';
        }
        if( !isset( $generalSettings['front_end_submission'] ) ) {
                $generalSettings['front_end_submission'] = 0;
        }
        if( !isset( $generalSettings['default_new_ticket_status'] ) ) {
                $generalSettings['default_new_ticket_status'] = 'open';
        }
        if( !isset( $generalSettings['default_login_module'] ) ) {
                $generalSettings['default_login_module'] = 1;
        }
        if(!isset($generalSettings['enable_register_guest_user'])){
                $generalSettings['enable_register_guest_user']=0;
        }
        if( !isset( $generalSettings['guest_user_role'] ) ) {
                $generalSettings['guest_user_role'] = 'subscriber';
        }
        update_option('wpsp_general_settings',$generalSettings);
	
	if( get_option( 'wpsp_email_notification_settings' ) === false ) {
		$administrator_email = get_option('admin_email');
		$from_name = "WordPress";
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}
		$default_from_email = 'wordpress@' . $sitename;
		$emailSettings=array(
				'admin_new_ticket'=>1,
				'admin_reply_ticket'=>1,
				'administrator_emails' => $administrator_email,
				'agent_email_notification' => 1,
				'enable_email_pipe' => 0,
				'default_from_email' => $default_from_email,
				'default_from_name' => $from_name,
				'default_reply_to' => '',
                                'piping_type'=>'cpanel',
                                'imap_encryption'=>'none',
                                'imap_server'=>'',
                                'imap_port'=>'',
                                'imap_username'=>'',
                                'imap_password'=>'',
                                'imap_connection_status' => 0
		);
		update_option('wpsp_email_notification_settings',$emailSettings);
	}
        
        $emailSettings=get_option( 'wpsp_email_notification_settings' );
        if(!isset($emailSettings['default_from_email'])){
                $from_name = "WordPress";
                $sitename = strtolower( $_SERVER['SERVER_NAME'] );
                if ( substr( $sitename, 0, 4 ) == 'www.' ) {
                        $sitename = substr( $sitename, 4 );
                }
                $default_from_email = 'wordpress@' . $sitename;
                $emailSettings['default_from_email']=$default_from_email;
                $emailSettings['default_from_name']=$from_name;
                $emailSettings['default_reply_to']='';
        }
        if(!isset($emailSettings['administrator_emails'])){
                $administrator_email = get_option('admin_email');
                $emailSettings['administrator_emails']=$administrator_email;
        }
        if(!isset($emailSettings['agent_email_notification'])){
                $emailSettings['agent_email_notification']=1;
        }
        if(!isset($emailSettings['enable_email_pipe'])){
                $emailSettings['enable_email_pipe']=0;
        }
        if( !isset( $emailSettings['agent_silent_create'] ) ) {
                $emailSettings['agent_silent_create'] = 0;
        }
        if( !isset( $emailSettings['piping_type'] ) ) {
                $emailSettings['piping_type'] = 'cpanel';
                $emailSettings['imap_encryption'] = 'none';
                $emailSettings['imap_server'] = '';
                $emailSettings['imap_port'] = '';
                $emailSettings['imap_username'] = '';
                $emailSettings['imap_password'] = '';
        }
        if( !isset( $emailSettings['imap_connection_status'] ) ) {
            if($emailSettings['enable_email_pipe'] && $emailSettings['piping_type']=='imap' && $emailSettings['imap_server'] && $emailSettings['imap_port'] && $emailSettings['imap_username'] && $emailSettings['imap_password'] && get_option('wpsp_imap_last_mail_id') ){
                $emailSettings['imap_connection_status'] = 1;
            } else {
                $emailSettings['imap_connection_status'] = 0;
            }
        }
        if(!isset($emailSettings['ignore_emails'])){
           $emailSettings['ignore_emails']=''; 
        }
        update_option('wpsp_email_notification_settings',$emailSettings);
        
	if( get_option( 'wpsp_role_management' ) === false ) {
		$agents = array();
		$supervisors=array();
		$front_roles=array('administrator','wp_support_plus_agent','wp_support_plus_supervisor');
		$roleManage=array(
			'agents' => $agents,
			'supervisors' => $supervisors,
			'front_ticket_all' => true,
			'front_ticket' => $front_roles
		);
		update_option('wpsp_role_management',$roleManage);
	}
        $roleManage=get_option( 'wpsp_role_management' );
        if(!isset($roleManage['agents'])){
            $agents = array();
            $supervisors=array();
            $front_roles=array('administrator','wp_support_plus_agent','wp_support_plus_supervisor');
            $roleManage['agents']=$agents;
            $roleManage['supervisors']=$supervisors;
            $roleManage['front_ticket_all']=true;
            $roleManage['front_ticket']=$front_roles;
        }
        if(!isset($roleManage['front_ticket_all'])){
            $roleManage['front_ticket_all']=true;
        }
        update_option('wpsp_role_management',$roleManage);
	
	// wpsp_customcss_settings
	if( get_option( 'wpsp_customcss_settings' ) === false ) {
		$customCSSSettings="";
		update_option('wpsp_customcss_settings',$customCSSSettings);
	}
	
	// wpsp_advanced_settings
	if( get_option( 'wpsp_advanced_settings' ) === false ) {
		$advancedSettings=array(
			'guest_ticket_submission_message'=>"Thank You. We will shortly get back to you on your given mail address!",
			'pending_ticket_close' => ''
		);
		update_option('wpsp_advanced_settings',$advancedSettings);
	} else {
		$advancedSettings=get_option( 'wpsp_advanced_settings' );
		if(!isset($advancedSettings['pending_ticket_close'])){
			$advancedSettings['pending_ticket_close']='';
		}
		update_option('wpsp_advanced_settings',$advancedSettings);
	}
	
	$advancedSettings=get_option( 'wpsp_advanced_settings' );
	if(!isset($advancedSettings['ticket_label_alice']))
	{
		$advancedSettings['ticket_label_alice']=array(
                    1=>__('Ticket','wp-support-plus-responsive-ticket-system'),
                    2=>__('Tickets','wp-support-plus-responsive-ticket-system'),
                    3=>__('Create New Ticket','wp-support-plus-responsive-ticket-system'),
                    4=>__('Ticket List Settings','wp-support-plus-responsive-ticket-system'),
                    5=>__('Create Ticket As','wp-support-plus-responsive-ticket-system'),
                    6=>__('Make Ticket Public','wp-support-plus-responsive-ticket-system'),
                    7=>__('Submit Ticket','wp-support-plus-responsive-ticket-system'),
                    8=>__('Edit Ticket','wp-support-plus-responsive-ticket-system'),
                    9=>__('Reply Ticket','wp-support-plus-responsive-ticket-system'),
                    10=>__('Delete Ticket','wp-support-plus-responsive-ticket-system'),
                    11=>__('Ticket Type','wp-support-plus-responsive-ticket-system'),
                    12=>__('No. of Tickets','wp-support-plus-responsive-ticket-system'),
                    13=>__('Ticket Creator','wp-support-plus-responsive-ticket-system'),
                    14=>__('Create Ticket Success Email','wp-support-plus-responsive-ticket-system'),
                    15=>__('Delete Ticket Notification Email','wp-support-plus-responsive-ticket-system'),
                    16=>__('New Ticket From Thread','wp-support-plus-responsive-ticket-system'),
                    17=>__('Back to Tickets','wp-support-plus-responsive-ticket-system'),
                    18=>__('Backend Ticket List Fields','wp-support-plus-responsive-ticket-system'),
                    19=>__('Frontend Ticket List Fields','wp-support-plus-responsive-ticket-system'),
                    20=>__('No Tickets Found','wp-support-plus-responsive-ticket-system')
                );
		update_option('wpsp_advanced_settings',$advancedSettings);
	}
        
        $advancedSettings=get_option( 'wpsp_advanced_settings' );
	if(!isset($advancedSettings['wpsp_reply_form_position']))
	{
            $advancedSettings['wpsp_reply_form_position']=1;
            $advancedSettings['wpsp_shortcode_used_in']=1;
            update_option('wpsp_advanced_settings',$advancedSettings);
	}
        
        $advancedSettings=get_option( 'wpsp_advanced_settings' );
        if($advancedSettings['wpsp_shortcode_used_in']==1 && get_option( 'wpsp_shortcode_used_in_settings' ) === false){
            $advancedSettings['wpsp_shortcode_used_in']=0;
            update_option('wpsp_advanced_settings',$advancedSettings);
            update_option('wpsp_shortcode_used_in_settings','1');
        }
        
        $advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['enable_accordion'])||!isset($advancedSettings['hide_selected_status_ticket'])){
                $advancedSettings['enable_accordion']=1;
                $advancedSettings['hide_selected_status_ticket']='none';
                update_option('wpsp_advanced_settings',$advancedSettings);
        }
        
        $advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['ticketId'])){
                $advancedSettings['ticketId']=1;                
                update_option('wpsp_advanced_settings',$advancedSettings);
        }
        
        $generalSettings=get_option('wpsp_general_settings' );
        if(!isset($generalSettings['close_ticket_btn_status_val'])){
            $generalSettings['close_ticket_btn_status_val']='';
            $generalSettings['close_btn_alice']=__('Close Ticket','wp-support-plus-responsive-ticket-system');
            update_option('wpsp_general_settings',$generalSettings);
        }
        
        $advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['logout_Settings'])){
            $advancedSettings['logout_Settings']=1;
            $advancedSettings['admin_bar_Setting']=1;
            update_option('wpsp_advanced_settings',$advancedSettings);
        }
        
        $advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['reply_above'])){
                $advancedSettings['reply_above']=1;                
                update_option('wpsp_advanced_settings',$advancedSettings);
        }
        
        $advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['datecustfield'])){
                $advancedSettings['datecustfield']='dd-mm-yy';
                update_option('wpsp_advanced_settings',$advancedSettings);
        }
        
        $advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['active_tab'])){
                $advancedSettings['active_tab']=1;                
                update_option('wpsp_advanced_settings',$advancedSettings);
        }
        
        $advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['wpsp_ticket_id_prefix'])){
            $advancedSettings['wpsp_ticket_id_prefix']='#';
            update_option('wpsp_advanced_settings',$advancedSettings);
        }

	if( get_option( 'wpsp_advanced_settings_field_order' ) === false ) {
            $advancedSettingsFieldOrder=array();
            $fields_order=array('dn','de','ds');
            foreach($customFields as $field){
                    $fields_order=array_merge($fields_order,array($field->id));
            }
            $fields_order=array_merge($fields_order,array('dd','dc','dp','da'));
            $advancedSettingsFieldOrder['fields_order']=$fields_order;
            update_option('wpsp_advanced_settings_field_order',$advancedSettingsFieldOrder);
        }
	
        $advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
	if(!isset($advancedSettingsFieldOrder['display_fields'])){
		$display_fields=array('dn','de','ds','dd','dc','dp','da');
		foreach($customFields as $field){
			$display_fields=array_merge($display_fields,array($field->id));
		}
		$advancedSettingsFieldOrder['display_fields']=$display_fields;
		update_option('wpsp_advanced_settings_field_order',$advancedSettingsFieldOrder);
	}

	$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
	if(!isset($advancedSettingsFieldOrder['default_fields_label'])){
		$display_field_keys=array('dn','de','ds','dd','dc','dp','da');
		$display_field_labels=array('Name','Email Address','Subject','Description','Category','Priority','Attachments');
		$advancedSettingsFieldOrder['default_fields_label']=array_combine($display_field_keys,$display_field_labels);
		update_option('wpsp_advanced_settings_field_order',$advancedSettingsFieldOrder);
	}
        if(!isset($advancedSettingsFieldOrder['wpsp_default_value_of_subject'])){
            $advancedSettingsFieldOrder['wpsp_default_value_of_subject']=__("NA", 'wp-support-plus-responsive-ticket-system' );
            update_option('wpsp_advanced_settings_field_order',$advancedSettingsFieldOrder);
        }
	/***************************************************************************************/
	$advancedSettingsStatusOrder=get_option( 'wpsp_advanced_settings_status_order' );
	if(!isset($advancedSettingsStatusOrder['status_order'])){
		$sql="select id from {$wpdb->prefix}wpsp_custom_status";
		$statusses=$wpdb->get_results($sql);
		$status_order=array();
		foreach ($statusses as $status){
			$status_order=array_merge($status_order,array($status->id));
		}
		$advancedSettingsStatusOrder['status_order']=$status_order;
		update_option('wpsp_advanced_settings_status_order',$advancedSettingsStatusOrder);
	}
        else {
            $sql="select id from {$wpdb->prefix}wpsp_custom_status";
            $statusses=$wpdb->get_results($sql);
            foreach ($statusses as $status){
                if(!in_array($status->id, $advancedSettingsStatusOrder['status_order'])){
                    $wpdb->delete($wpdb->prefix.'wpsp_custom_status',array('id'=>$status->id));
                }
            }
        }

	$advancedSettingsPriorityOrder=get_option( 'wpsp_advanced_settings_priority_order' );
	if(!isset($advancedSettingsPriorityOrder['priority_order'])){
            $sql="select id from {$wpdb->prefix}wpsp_custom_priority";
            $priorities=$wpdb->get_results($sql);
            $priorities_order=array();
            foreach ($priorities as $priority){
                    $priorities_order=array_merge($priorities_order,array($priority->id));
            }
            $advancedSettingsPriorityOrder['priority_order']=$priorities_order;
            update_option('wpsp_advanced_settings_priority_order',$advancedSettingsPriorityOrder);
	}
        else {
            $sql="select id from {$wpdb->prefix}wpsp_custom_priority";
            $priorities=$wpdb->get_results($sql);
            foreach ($priorities as $priority){
                if(!in_array($priority->id, $advancedSettingsPriorityOrder['priority_order'])){
                    $wpdb->delete($wpdb->prefix.'wpsp_custom_priority',array('id'=>$priority->id));
                }
            }
        }
	/***************************************************************************************/
	
	$advancedSettingsTicketList=get_option( 'wpsp_advanced_settings_ticket_list_order' );
	if(!isset($advancedSettingsTicketList['backend_ticket_list'])){
            $ticket_fields_list_backend=array('id','st','sb','rb','ty','ct','at','pt','ut','cdt','udt');
            $ticket_fields_list_backend_2=array(1,1,1,1,1,1,1,1,1,0,0);
            $ticket_fields_list_frontend=array('id','st','sb','ct','at','pt','ut','cdt','udt');
            $ticket_fields_list_frontend_2=array(1,1,1,1,1,1,1,0,0);
            foreach($customFields as $field){
                    $ticket_fields_list_backend=array_merge($ticket_fields_list_backend,array($field->id));
                    $ticket_fields_list_backend_2=array_merge($ticket_fields_list_backend_2,array(0));
                    $ticket_fields_list_frontend=array_merge($ticket_fields_list_frontend,array($field->id));
                    $ticket_fields_list_frontend_2=array_merge($ticket_fields_list_frontend_2,array(0));
            }
            $advancedSettingsTicketList['backend_ticket_list']=array_combine($ticket_fields_list_backend, $ticket_fields_list_backend_2);
            $advancedSettingsTicketList['frontend_ticket_list']=array_combine($ticket_fields_list_frontend, $ticket_fields_list_frontend_2);
            update_option('wpsp_advanced_settings_ticket_list_order',$advancedSettingsTicketList);
	}
	else
	{
		$backend_field_keys=array_keys($advancedSettingsTicketList['backend_ticket_list']);
		$backend_field_values=array_values($advancedSettingsTicketList['backend_ticket_list']);
		$frontend_field_keys=array_keys($advancedSettingsTicketList['frontend_ticket_list']);
		$frontend_field_values=array_values($advancedSettingsTicketList['frontend_ticket_list']);
		if(!in_array("cdt",$backend_field_keys)){
			$backend_field_keys=array_merge($backend_field_keys,array("cdt"));
			$backend_field_values=array_merge($backend_field_values,array(0));
			$backend_field_keys=array_merge($backend_field_keys,array("udt"));
			$backend_field_values=array_merge($backend_field_values,array(0));
			$frontend_field_keys=array_merge($frontend_field_keys,array("cdt"));
			$frontend_field_values=array_merge($frontend_field_values,array(0));
			$frontend_field_keys=array_merge($frontend_field_keys,array("udt"));
			$frontend_field_values=array_merge($frontend_field_values,array(0));
			$advancedSettingsTicketList['backend_ticket_list']=array_combine($backend_field_keys, $backend_field_values);
			$advancedSettingsTicketList['frontend_ticket_list']=array_combine($frontend_field_keys, $frontend_field_values);
			update_option('wpsp_advanced_settings_ticket_list_order',$advancedSettingsTicketList);
		}
	}
        if(!isset($advancedSettingsTicketList['backend_ticket_list']['acd'])){
            $advancedSettingsTicketList['backend_ticket_list']['acd']=0;
        }
        update_option('wpsp_advanced_settings_ticket_list_order',$advancedSettingsTicketList);
	if( get_option( 'wpsp_ticket_list_date_format' ) === false ) {
		$dateFormat=array(
				'cdt_backend'=>'',
				'udt_backend'=>'',
				'cdt_frontend'=>'',
				'udt_frontend'=>''
		);
		update_option('wpsp_ticket_list_date_format',$dateFormat);
	}
	
	$advancedSettingsCustomFilterFront=get_option( 'wpsp_advanced_settings_custom_filter_front' );
	if(!isset($advancedSettingsCustomFilterFront['logged_in'])){
		$advancedSettingsCustomFilterFront['logged_in']=array('st','ct','not','tt');
		$advancedSettingsCustomFilterFront['agent_logged_in']=array('st','ct','not','tt');
		$advancedSettingsCustomFilterFront['supervisor_logged_in']=array('st','ct','not','tt');
		update_option('wpsp_advanced_settings_custom_filter_front',$advancedSettingsCustomFilterFront);
	}
	
	if( get_option( 'wpsp_ticket_list_subject_char_length' ) === false ) {
		$subCharLength=array(
				'frontend'=>'20',
				'backend'=>'20'
		);
		update_option('wpsp_ticket_list_subject_char_length',$subCharLength);
	}
	
	//create new ticket email template
	if( get_option( 'wpsp_et_create_new_ticket' ) === false ) {
		$templates=array(
				'customer_name' => __("Customer Name", 'wp-support-plus-responsive-ticket-system' ),
				'customer_email' => __("Customer Email", 'wp-support-plus-responsive-ticket-system' ),
				'ticket_id' => __("Ticket ID", 'wp-support-plus-responsive-ticket-system' ),
				'ticket_subject' => __("Ticket Subject", 'wp-support-plus-responsive-ticket-system' ),
				'ticket_description' => __("Ticket Description", 'wp-support-plus-responsive-ticket-system' ),
				'ticket_category' => __("Ticket Category", 'wp-support-plus-responsive-ticket-system' ),
				'ticket_priotity' => __("Ticket Priority", 'wp-support-plus-responsive-ticket-system' )
		);
		foreach($customFields as $field){
			$ticket_fields_list_backend=array_merge($ticket_fields_list_backend,array($field->id));
			$templates['cust'.$field->id]=$field->label;
		}
		$staff_to_notify=array(
						'administrator'=>'1',
						'supervisor'=>'1',
						'assigned_agent'=>'1',
						'all_agents'=>'0'
		);
		$wpsp_et_create_new_ticket=array(
				'enable_success'=>'1',
				'success_subject'=>__("Your Ticket has been created successfully", 'wp-support-plus-responsive-ticket-system' ),
				'success_body'=>'Dear {customer_name},<br />
								<br />
								Thank you for contacting Support. Your ticket has been created Successfully!<br />
								<br />
								Below are details of your ticket -<br />
								<br />
								<strong>Subject:</strong> {ticket_subject}<br />
								<strong>Description:</strong>
								<p>{ticket_description}</p>
								<br />
								<br />',
				'staff_subject'=>'{ticket_subject}',
				'staff_body'=>'<strong>{customer_name} ({customer_email})</strong> wrote:
								<p>{ticket_description}</p>
								<br />
								<br />',
				'templates'=>$templates,
				'staff_to_notify'=>$staff_to_notify
		);
		update_option('wpsp_et_create_new_ticket',$wpsp_et_create_new_ticket);
	}
        $wpsp_et_create_new_ticket=get_option( 'wpsp_et_create_new_ticket' );
        if(!isset($wpsp_et_create_new_ticket['templates']['ticket_url'])){
            $wpsp_et_create_new_ticket['templates']['ticket_url']=__("Ticket URL", 'wp-support-plus-responsive-ticket-system' );
            update_option('wpsp_et_create_new_ticket',$wpsp_et_create_new_ticket);
        }
        $wpsp_et_create_new_ticket['templates']['ticket_description']=__("Ticket Description", 'wp-support-plus-responsive-ticket-system' );
        update_option('wpsp_et_create_new_ticket',$wpsp_et_create_new_ticket);
	if(!isset($wpsp_et_create_new_ticket['templates']['time_created'])){
            $wpsp_et_create_new_ticket['templates']['time_created']=__("Ticket Created", 'wp-support-plus-responsive-ticket-system' );
            update_option('wpsp_et_create_new_ticket',$wpsp_et_create_new_ticket);
        }
        if(!isset($wpsp_et_create_new_ticket['templates']['agent_created'])){
            $wpsp_et_create_new_ticket['templates']['agent_created']=__("Agent Created", 'wp-support-plus-responsive-ticket-system' );
            update_option('wpsp_et_create_new_ticket',$wpsp_et_create_new_ticket);
        }
	/* Reply Ticket email template
	*/
	
	if( get_option( 'wpsp_et_reply_ticket' ) === false ) {
		$templates=array(
				'reply_by_name' => __("Reply By Name", 'wp-support-plus-responsive-ticket-system' ),
				'reply_by_email' => __("Reply By Email", 'wp-support-plus-responsive-ticket-system' ),
				'ticket_status' => __("Ticket Status", 'wp-support-plus-responsive-ticket-system' ),
				'customer_name' => __("Customer Name", 'wp-support-plus-responsive-ticket-system' ).__("(ticket creator)", 'wp-support-plus-responsive-ticket-system' ),
				'customer_email' => __("Customer Email", 'wp-support-plus-responsive-ticket-system' ).__("(ticket creator)", 'wp-support-plus-responsive-ticket-system' ),
				'ticket_id' => __("Ticket ID", 'wp-support-plus-responsive-ticket-system' ),
				'ticket_subject' => __("Ticket Subject", 'wp-support-plus-responsive-ticket-system' ),
				'reply_description' => __("Reply Description", 'wp-support-plus-responsive-ticket-system' ),
				'ticket_category' => __("Ticket Category", 'wp-support-plus-responsive-ticket-system' ),
				'ticket_priotity' => __("Ticket Priority", 'wp-support-plus-responsive-ticket-system' )
		);
		foreach($customFields as $field){
			$ticket_fields_list_backend=array_merge($ticket_fields_list_backend,array($field->id));
			$templates['cust'.$field->id]=$field->label;
		}
		$notify_to=array(
				'customer'=>'1',
				'administrator'=>'1',
				'supervisor'=>'1',
				'assigned_agent'=>'1',
				'all_agents'=>'0'
		);
		$wpsp_et_reply_ticket=array(
				'reply_subject'=>'{ticket_subject}',
				'reply_body'=>'<strong>{reply_by_name} ({reply_by_email})</strong> wrote:
								<p>{reply_description}</p>
								<br />
								<br />',
				'templates'=>$templates,
				'notify_to'=>$notify_to
		);
		update_option('wpsp_et_reply_ticket',$wpsp_et_reply_ticket);
	}
        $wpsp_et_reply_ticket=get_option( 'wpsp_et_reply_ticket' );
        if(!isset($wpsp_et_reply_ticket['templates']['ticket_url'])){
            $wpsp_et_reply_ticket['templates']['ticket_url']=__("Ticket URL", 'wp-support-plus-responsive-ticket-system' );
            update_option('wpsp_et_reply_ticket',$wpsp_et_reply_ticket);
        }
       
        $wpsp_et_reply_ticket['templates']['reply_description']=__("Reply Description", 'wp-support-plus-responsive-ticket-system' );
        update_option('wpsp_et_reply_ticket',$wpsp_et_reply_ticket);
        
        if(!isset($wpsp_et_reply_ticket['templates']['time_created'])){
            $wpsp_et_reply_ticket['templates']['time_created']=__("Ticket Created", 'wp-support-plus-responsive-ticket-system' );
            update_option('wpsp_et_reply_ticket',$wpsp_et_reply_ticket);
        }
	/*
	 * END Reply Ticket Email Template
	 */
	/* Change Status Email Template email template
	 */	
	if( get_option( 'wpsp_et_change_ticket_status' ) === false ) {
		$notify_to=array(
                    'customer'=>'1',
                    'administrator'=>'1',
                    'supervisor'=>'1',
                    'assigned_agent'=>'1',
                    'all_agents'=>'0'
		);
		$wpsp_et_change_ticket_status=array(
                    'notify_to'=>$notify_to
		);
		update_option('wpsp_et_change_ticket_status',$wpsp_et_change_ticket_status);
	}
        $wpsp_et_change_ticket_status=get_option( 'wpsp_et_change_ticket_status' );
        if(!isset($wpsp_et_change_ticket_status['templates'])){
            $templates=array(
                'customer_name' => __("Customer Name", 'wp-support-plus-responsive-ticket-system' ).__("(ticket creator)", 'wp-support-plus-responsive-ticket-system' ),
                'customer_email' => __("Customer Email", 'wp-support-plus-responsive-ticket-system' ).__("(ticket creator)", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_id' => __("Ticket ID", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_subject' => __("Ticket Subject", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_description' => __("Ticket Description", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_status' => __("Ticket Status", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_category' => __("Ticket Category", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_priotity' => __("Ticket Priority", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_url'=>__("Ticket URL", 'wp-support-plus-responsive-ticket-system' ),
                'updated_by'=>__("User who changed status", 'wp-support-plus-responsive-ticket-system' )
            );
            foreach($customFields as $field){
                $templates['cust'.$field->id]=$field->label;
            }
            $wpsp_et_change_ticket_status['mail_subject']='{ticket_subject}';
            $wpsp_et_change_ticket_status['mail_body']='<strong>Below are details of ticket:</strong><br />
                    <br />
                    ------------------------------------------------------------------------------------------------------------------------------------<br />
                    <strong>Subject:</strong> {ticket_subject}<br />
                    <strong>Status:</strong> {ticket_status}<br />
                    <strong>Category:</strong> {ticket_category}<br />
                    <strong>Priority:</strong> {ticket_priotity}<br />
                    ------------------------------------------------------------------------------------------------------------------------------------<br />
                    <strong>Description:</strong><br />
                    {ticket_description}';
            $wpsp_et_change_ticket_status['templates']=$templates;
            update_option('wpsp_et_change_ticket_status',$wpsp_et_change_ticket_status);
        }
        if(!isset($wpsp_et_change_ticket_status['templates']['time_created'])){
            $wpsp_et_change_ticket_status['templates']['time_created']=__("Ticket Created", 'wp-support-plus-responsive-ticket-system' );
            update_option('wpsp_et_change_ticket_status',$wpsp_et_change_ticket_status);
        }
	/*
	 * END Change Status Email Template
	 */
	/* Assign Agent Email Template email template
	 */
	if( get_option( 'wpsp_et_change_ticket_assign_agent' ) === false ) {
		$notify_to=array(
				'customer'=>'1',
				'administrator'=>'1',
				'supervisor'=>'1',
				'assigned_agent'=>'1',
				'all_agents'=>'0'
		);
		$wpsp_et_change_ticket_assign_agent=array(
				'notify_to'=>$notify_to
		);
		update_option('wpsp_et_change_ticket_assign_agent',$wpsp_et_change_ticket_assign_agent);
	}
        $wpsp_et_change_ticket_assign_agent=get_option( 'wpsp_et_change_ticket_assign_agent' );
        if(!isset($wpsp_et_change_ticket_assign_agent['templates'])){
            $templates=array(
                'customer_name' => __("Customer Name", 'wp-support-plus-responsive-ticket-system' ).__("(ticket creator)", 'wp-support-plus-responsive-ticket-system' ),
                'customer_email' => __("Customer Email", 'wp-support-plus-responsive-ticket-system' ).__("(ticket creator)", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_id' => __("Ticket ID", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_subject' => __("Ticket Subject", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_description' => __("Ticket Description", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_status' => __("Ticket Status", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_category' => __("Ticket Category", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_priotity' => __("Ticket Priority", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_url'=>__("Ticket URL", 'wp-support-plus-responsive-ticket-system' ),
                'updated_by'=>__("User who assigned ticket", 'wp-support-plus-responsive-ticket-system' ),
                'old_assigned_to'=>__("User to whom assigned ticket before", 'wp-support-plus-responsive-ticket-system' ),
                'new_assigned_to'=>__("User to whom assigned ticket now", 'wp-support-plus-responsive-ticket-system' )
            );
            foreach($customFields as $field){
                $templates['cust'.$field->id]=$field->label;
            }
            $wpsp_et_change_ticket_assign_agent['mail_subject']='{updated_by} assigned ticket to {new_assigned_to}';
            $wpsp_et_change_ticket_assign_agent['mail_body']='<strong>Below are details of ticket:</strong><br />
                    <br />
                    ------------------------------------------------------------------------------------------------------------------------------------<br />
                    <strong>Subject:</strong> {ticket_subject}<br />
                    <strong>Status:</strong> {ticket_status}<br />
                    <strong>Category:</strong> {ticket_category}<br />
                    <strong>Priority:</strong> {ticket_priotity}<br />
                    <strong>Previously Assigned:</strong> {old_assigned_to}<br />
                    ------------------------------------------------------------------------------------------------------------------------------------<br />
                    <strong>Description:</strong><br />
                    {ticket_description}';
            $wpsp_et_change_ticket_assign_agent['templates']=$templates;
            update_option('wpsp_et_change_ticket_assign_agent',$wpsp_et_change_ticket_assign_agent);
        }
        if(!isset($wpsp_et_change_ticket_assign_agent['templates']['time_created'])){
            $wpsp_et_change_ticket_assign_agent['templates']['time_created']=__("Ticket Created", 'wp-support-plus-responsive-ticket-system' );
            update_option('wpsp_et_change_ticket_assign_agent',$wpsp_et_change_ticket_assign_agent);
        }
	/*
	 * END Assign Agent Email Template
	 */
	/* Delete Ticket Email Template email template
	 */
	if( get_option( 'wpsp_et_delete_ticket' ) === false ) {
		$notify_to=array(
				'customer'=>'1',
				'administrator'=>'1',
				'supervisor'=>'1',
				'assigned_agent'=>'1',
				'all_agents'=>'0'
		);
		$wpsp_et_delete_ticket=array(
				'notify_to'=>$notify_to
		);
		update_option('wpsp_et_delete_ticket',$wpsp_et_delete_ticket);
	}
        $wpsp_et_delete_ticket=get_option( 'wpsp_et_delete_ticket' );
        if(!isset($wpsp_et_delete_ticket['templates'])){
            $templates=array(
                'customer_name' => __("Customer Name", 'wp-support-plus-responsive-ticket-system' ).__("(ticket creator)", 'wp-support-plus-responsive-ticket-system' ),
                'customer_email' => __("Customer Email", 'wp-support-plus-responsive-ticket-system' ).__("(ticket creator)", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_id' => __("Ticket ID", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_subject' => __("Ticket Subject", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_description' => __("Ticket Description", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_status' => __("Ticket Status", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_category' => __("Ticket Category", 'wp-support-plus-responsive-ticket-system' ),
                'ticket_priotity' => __("Ticket Priority", 'wp-support-plus-responsive-ticket-system' ),
                'updated_by'=>__("User who assigned ticket", 'wp-support-plus-responsive-ticket-system' )
            );
            foreach($customFields as $field){
                $templates['cust'.$field->id]=$field->label;
            }
            $wpsp_et_delete_ticket['mail_subject']='{updated_by} deleted ticket #{ticket_id}';
            $wpsp_et_delete_ticket['mail_body']='<strong>Below ware details of ticket:</strong><br />
                    <br />
                    ------------------------------------------------------------------------------------------------------------------------------------<br />
                    <strong>Subject:</strong> {ticket_subject}<br />
                    <strong>Status:</strong> {ticket_status}<br />
                    <strong>Category:</strong> {ticket_category}<br />
                    <strong>Priority:</strong> {ticket_priotity}<br />
                    ------------------------------------------------------------------------------------------------------------------------------------<br />
                    <strong>Description:</strong><br />
                    {ticket_description}';
            $wpsp_et_delete_ticket['templates']=$templates;
            update_option('wpsp_et_delete_ticket',$wpsp_et_delete_ticket);
        }
    if(!isset($wpsp_et_delete_ticket['templates']['time_created'])){
            $wpsp_et_delete_ticket['templates']['time_created']=__("Ticket Created", 'wp-support-plus-responsive-ticket-system' );
            update_option('wpsp_et_delete_ticket',$wpsp_et_delete_ticket);
        }
	/*
	 * END Delete Ticket Email Template
	 */

	/*
	* Default Status names
	*/
	if( get_option( 'wpsp_default_status_priority_names' ) === false ) {
		$statusses=array(
			'open'=>'open',
			'pending'=>'pending',
			'closed'=>'closed'
		);
		$priorities=array(
			'Normal'=>'Normal',
			'High'=>'High',
			'Medium'=>'Medium',
			'Low'=>'Low'
		);
		$status_priority=array('status_names'=>$statusses,'priority_names'=>$priorities);
		update_option('wpsp_default_status_priority_names',$status_priority);
	}

	$default_status_priority=get_option( 'wpsp_default_status_priority_names' );
	$values=array('status'=>$default_status_priority['status_names']['open']);
	$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('status'=>'open'));
	$values=array('status'=>$default_status_priority['status_names']['pending']);
	$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('status'=>'pending'));
	$values=array('status'=>$default_status_priority['status_names']['closed']);
	$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('status'=>'closed'));	
	/*
	* End Default Status names
	*/
	$advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['hide_selected_status_ticket_backend'])){
                $advancedSettings['hide_selected_status_ticket_backend']=array();
                update_option('wpsp_advanced_settings',$advancedSettings);
        }

        $advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['modify_raised_by'])){
                $advancedSettings['modify_raised_by']=array();
                update_option('wpsp_advanced_settings',$advancedSettings);
        }

        $advancedSettings=get_option( 'wpsp_advanced_settings' );
        if(!isset($advancedSettings['wpsp_dashboard_menu_label']))
        {
                $advancedSettings['wpsp_dashboard_menu_label']="Support Plus";
                update_option('wpsp_advanced_settings',$advancedSettings);
        }
        
        if( get_option( 'wpsp_ckeditor_settings' ) === false ){
            $CKEditorSettings=array(
                'guestUserFront'=>'1',
                'loginUserFront'=>'1'
            );
            update_option('wpsp_ckeditor_settings',$CKEditorSettings);
        }
        
        if( get_option( 'wpsp_ckeditor_settings' ) === false ){
            $CKEditorSettings=array(
                'guestUserFront'=>'1',
                'loginUserFront'=>'1'
            );
            update_option('wpsp_ckeditor_settings',$CKEditorSettings);
        }
        
        if( get_option( 'wpsp_upload_image_settings' ) === false ){
            $UploadImageSettings=array(
                'leftSupportButton'=>WCE_PLUGIN_URL.'asset/images/support-button-left.png',
                'rightSupportButton'=>WCE_PLUGIN_URL.'asset/images/support-button-right.png',
                'panel_image'=>WCE_PLUGIN_URL.'asset/images/support_icon.jpg'
            );
            update_option('wpsp_upload_image_settings',$UploadImageSettings);
        }
	
	$advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['guest_ticket_redirect'])){
            $advancedSettings['guest_ticket_redirect']=0;
            $advancedSettings['guest_ticket_redirect_url']=__('','wp-support-plus-responsive-ticket-system');
            update_option('wpsp_advanced_settings',$advancedSettings);
        }
	if(!isset($advancedSettingsTicketList['frontend_ticket_list']['rb'])){
           $advancedSettingsTicketList['frontend_ticket_list']['rb']=1;
        }
        update_option('wpsp_advanced_settings_ticket_list_order',$advancedSettingsTicketList);
	$advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['message_for_ticket_url'])){
            $advancedSettings['message_for_ticket_url']=__('This ticket is closed for new replies.','wp-support-plus-responsive-ticket-system');
            update_option('wpsp_advanced_settings',$advancedSettings);
        }
	
	if( get_option( 'wpsp_front_end_display_settings' ) === false ) {            
            $FrontEndDisplaySettings=array(
                
                'wpsp_hideBackToTicket'=>1,
                'wpsp_hideCloseTicket'=>1,
                'wpsp_hideMoreAction'=>1,
                'wpsp_hideChangeStatus'=>1,
                'wpsp_hideCannedReply'=>1,
                'wpsp_hideAssignAgent'=>1,
                'wpsp_hideDeleteTicket'=>1,
                'wpsp_hideCC'=>1,
                'wpsp_hideBCC'=>1,
                'wpsp_hideStatus'=>1,
                'wpsp_hideCategory'=>1,
                'wpsp_hidePriority'=>1,
                'wpsp_hideAttachments'=>1,
                'wpsp_hideAddNotes'=>1,
                'wpsp_hideSubmitReply'=>1,
                'wpsp_hideEmail'=>1,
                'wpsp_hideDaysMonthsYearAgo'=>1,
                'wpsp_hideExactDate'=>1,
                'wpsp_hideExactTime'=>1
                
            );
            update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
	} 
        
        $FrontEndDisplaySettings=get_option( 'wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_hideBackToTicket'])){
                $FrontEndDisplaySettings['wpsp_hideBackToTicket']=1;
                $FrontEndDisplaySettings['wpsp_hideCloseTicket']=1;
                $FrontEndDisplaySettings['wpsp_hideMoreAction']=1;
                $FrontEndDisplaySettings['wpsp_hideExactDate']=1;
                $FrontEndDisplaySettings['wpsp_hideExactTime']=1;
                $FrontEndDisplaySettings['wpsp_hideSubmitReply']=1;
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        
        $FrontEndDisplaySettings=get_option( 'wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_faq_display_setting'])){
                $FrontEndDisplaySettings['wpsp_faq_display_setting']=1;
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_btt_fc'])){
                $FrontEndDisplaySettings['wpsp_btt_fc']='#fff';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_btt_bc'])){
                $FrontEndDisplaySettings['wpsp_btt_bc']='#428bca';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_ct_fc'])){
                $FrontEndDisplaySettings['wpsp_ct_fc']='#fff';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_ct_bc'])){
                $FrontEndDisplaySettings['wpsp_ct_bc']='#428bca';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_ma_fc'])){
                $FrontEndDisplaySettings['wpsp_ma_fc']='#fff';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_ma_bc'])){
                $FrontEndDisplaySettings['wpsp_ma_bc']='#428bca';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_cs_fc'])){
                $FrontEndDisplaySettings['wpsp_cs_fc']='#fff';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_cs_bc'])){
                $FrontEndDisplaySettings['wpsp_cs_bc']='#428bca';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_cr_fc'])){
                $FrontEndDisplaySettings['wpsp_cr_fc']='#fff';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_cr_bc'])){
                $FrontEndDisplaySettings['wpsp_cr_bc']='#428bca';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_aa_fc'])){
                $FrontEndDisplaySettings['wpsp_aa_fc']='#fff';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_aa_bc'])){
                $FrontEndDisplaySettings['wpsp_aa_bc']='#428bca';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_dt_fc'])){
                $FrontEndDisplaySettings['wpsp_dt_fc']='#fff';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_dt_bc'])){
                $FrontEndDisplaySettings['wpsp_dt_bc']='#d43f3a';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_an_fc'])){
                $FrontEndDisplaySettings['wpsp_an_fc']='#fff';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_an_bc'])){
                $FrontEndDisplaySettings['wpsp_an_bc']='#428bca';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_sr_fc'])){
                $FrontEndDisplaySettings['wpsp_sr_fc']='#fff';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_sr_bc'])){
                $FrontEndDisplaySettings['wpsp_sr_bc']='#428bca';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option( 'wpsp_front_end_display_settings' );
	if(!isset($FrontEndDisplaySettings['front_end_display_alice']))
	{
		$FrontEndDisplaySettings['front_end_display_alice']=array(
                    
                    1=>__('Change Status','wp-support-plus-responsive-ticket-system'),
                    2=>__('Canned Reply','wp-support-plus-responsive-ticket-system'),
                    3=>__('Assign Agent','wp-support-plus-responsive-ticket-system'),
                    4=>$advancedSettings['ticket_label_alice'][10],
                    5=>__('CC','wp-support-plus-responsive-ticket-system'),
                    6=>__('BCC','wp-support-plus-responsive-ticket-system'),
                    7=>__('Status','wp-support-plus-responsive-ticket-system'),
                    8=>__('Category','wp-support-plus-responsive-ticket-system'),
                    9=>__('Priority','wp-support-plus-responsive-ticket-system'),
                   10=>__('Attachments','wp-support-plus-responsive-ticket-system'),
                   11=>__('Add Notes','wp-support-plus-responsive-ticket-system'),
                   12=>__('Submit Reply','wp-support-plus-responsive-ticket-system'),
                                       
                );
		update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
	}
        $advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['wpsp_redirect_after_ticket_update'])){
                $advancedSettings['wpsp_redirect_after_ticket_update']=1;                
                update_option('wpsp_advanced_settings',$advancedSettings);
        }
        $FrontEndDisplaySettings=get_option( 'wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_hideChangeRaisedBy'])){
                $FrontEndDisplaySettings['wpsp_hideChangeRaisedBy']=1;
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_cb_bc'])){
                $FrontEndDisplaySettings['wpsp_cb_bc']='#428bca';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option('wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_cb_fc'])){
                $FrontEndDisplaySettings['wpsp_cb_fc']='#fff';                
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option( 'wpsp_front_end_display_settings' );
        if(!isset($FrontEndDisplaySettings['wpsp_ChangeRaisedBy'])){
                $FrontEndDisplaySettings['wpsp_ChangeRaisedBy']=1;
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        $FrontEndDisplaySettings=get_option( 'wpsp_front_end_display_settings' );
 	if(!isset($FrontEndDisplaySettings['front_end_display_alice'][13])){
 	        $FrontEndDisplaySettings['front_end_display_alice'][13]=__('Change Raised By','wp-support-plus-responsive');
                update_option('wpsp_front_end_display_settings',$FrontEndDisplaySettings);
        }
        
        $advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['wpspAttachMaxFileSize'])){
            $advancedSettings['wpspAttachMaxFileSize']=20;
            update_option('wpsp_advanced_settings',$advancedSettings);
        }
        
        $advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['wpspBootstrapJSSetting'])){
            $advancedSettings['wpspBootstrapJSSetting']=1;
            $advancedSettings['wpspBootstrapCSSSetting']=1;
            update_option('wpsp_advanced_settings',$advancedSettings);
        }
        
        $advancedSettings=get_option('wpsp_advanced_settings' );
        if(!isset($advancedSettings['wpsp_attachment_download_url'])){
            $advancedSettings['wpsp_attachment_download_url']=1;
            update_option('wpsp_advanced_settings',$advancedSettings);
        }
        
        if( get_option( 'wpsp_attachment_random_key' ) === false ){
            $sql="select id from {$wpdb->prefix}wpsp_attachments";
            $attachment_ids=$wpdb->get_results($sql);
            foreach($attachment_ids as $attachment_id){
                $key=0;
                do{
                    $key=uniqid().uniqid();
                    $sql="select id from {$wpdb->prefix}wpsp_attachments where download_key='".$key."'";
                    $result=$wpdb->get_var($sql);
                }while ($result);
                $sql="UPDATE {$wpdb->prefix}wpsp_attachments SET download_key = '".$key."' WHERE id = ".$attachment_id->id;
                $wpdb->query($sql);
            }
            update_option('wpsp_attachment_random_key',1);
        }
        
            $advancedSettings=get_option('wpsp_advanced_settings' );         
            if(!isset($advancedSettings['wpspAttachment_bc'])){             
                $advancedSettings['wpspAttachment_bc']='#1aaf1c';             
                update_option('wpsp_advanced_settings',$advancedSettings);         
            }
            
            $advancedSettings=get_option('wpsp_advanced_settings' );         
            if(!isset($advancedSettings['wpspAttachment_pc'])){             
                $advancedSettings['wpspAttachment_pc']='#ff0000';             
                update_option('wpsp_advanced_settings',$advancedSettings);         
            }
}

?>
