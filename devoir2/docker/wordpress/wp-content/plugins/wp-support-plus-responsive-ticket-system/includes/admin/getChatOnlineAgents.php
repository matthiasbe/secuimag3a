<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$agentAvailable=false;

$args=array('role'=>'administrator');
$administrators=get_users( $args );

$args=array('role'=>'wp_support_plus_supervisor');
$supervisors=get_users( $args );

$args=array('role'=>'wp_support_plus_agent');
$agents=get_users( $args );
foreach ($administrators as $administrator){
	$sql="select id,signature,skype_id,skype_chat_availability,skype_call_availability FROM {$wpdb->prefix}wpsp_agent_settings WHERE  agent_id=".$administrator->ID;
	$currentAgentSettings = $wpdb->get_row( $sql );
	
	if(!$wpdb->num_rows){
		$values=array(
				'agent_id'=>$administrator->ID,
				'signature'=>'',
				'skype_id'=>'',
				'skype_chat_availability'=>0,
				'skype_call_availability'=>0
		);
		$wpdb->insert($wpdb->prefix.'wpsp_agent_settings',$values);
		$sql="select id,signature,skype_id,skype_chat_availability,skype_call_availability FROM {$wpdb->prefix}wpsp_agent_settings WHERE  agent_id=".$administrator->ID;
		$currentAgentSettings = $wpdb->get_row( $sql );
	}
	
	if($currentAgentSettings->skype_chat_availability){
		$agentAvailable=true;
	?>
		<a href="skype:<?php echo $currentAgentSettings->skype_id;?>?chat">
		<div class="onlineAgent">
			<img class="supportAgentImage" src="<?php echo get_gravatar($administrator->user_email,50);?>" >
			<div class="supportAgentInfo">
				<div class="supportAgentName"><?php _e($administrator->display_name,'wp-support-plus-responsive-ticket-system');?></div>
				<small><b><?php _e('Skype ID:','wp-support-plus-responsive-ticket-system');?></b> <?php _e($currentAgentSettings->skype_id,'wp-support-plus-responsive-ticket-system');?></small>
			</div>
		</div>
		</a>
	<?php }
}
foreach ($supervisors as $supervisor){
	$sql="select id,signature,skype_id,skype_chat_availability,skype_call_availability FROM {$wpdb->prefix}wpsp_agent_settings WHERE  agent_id=".$supervisor->ID;
	$currentAgentSettings = $wpdb->get_row( $sql );

	if(!$wpdb->num_rows){
		$values=array(
				'agent_id'=>$supervisor->ID,
				'signature'=>'',
				'skype_id'=>'',
				'skype_chat_availability'=>0,
				'skype_call_availability'=>0
		);
		$wpdb->insert($wpdb->prefix.'wpsp_agent_settings',$values);
		$sql="select id,signature,skype_id,skype_chat_availability,skype_call_availability FROM {$wpdb->prefix}wpsp_agent_settings WHERE  agent_id=".$supervisor->ID;
		$currentAgentSettings = $wpdb->get_row( $sql );
	}

	if($currentAgentSettings->skype_chat_availability){
		$agentAvailable=true;
		?>
		<a href="skype:<?php echo $currentAgentSettings->skype_id;?>?chat">
		<div class="onlineAgent">
			<img class="supportAgentImage" src="<?php echo get_gravatar($supervisor->user_email,50);?>" >
			<div class="supportAgentInfo">
				<div class="supportAgentName"><?php _e($supervisor->display_name,'wp-support-plus-responsive-ticket-system');?></div>
				<small><b><?php _e('Skype ID:','wp-support-plus-responsive-ticket-system');?></b> <?php echo $currentAgentSettings->skype_id;?></small>
			</div>
		</div>
		</a>
	<?php }
}
foreach ($agents as $agent){
	$sql="select id,signature,skype_id,skype_chat_availability,skype_call_availability FROM {$wpdb->prefix}wpsp_agent_settings WHERE  agent_id=".$agent->ID;
	$currentAgentSettings = $wpdb->get_row( $sql );

	if(!$wpdb->num_rows){
		$values=array(
				'agent_id'=>$agent->ID,
				'signature'=>'',
				'skype_id'=>'',
				'skype_chat_availability'=>0,
				'skype_call_availability'=>0
		);
		$wpdb->insert($wpdb->prefix.'wpsp_agent_settings',$values);
		$sql="select id,signature,skype_id,skype_chat_availability,skype_call_availability FROM {$wpdb->prefix}wpsp_agent_settings WHERE  agent_id=".$agent->ID;
		$currentAgentSettings = $wpdb->get_row( $sql );
	}

	if($currentAgentSettings->skype_chat_availability){
		$agentAvailable=true;
		?>
		<a href="skype:<?php echo $currentAgentSettings->skype_id;?>?chat">
		<div class="onlineAgent">
			<img class="supportAgentImage" src="<?php echo get_gravatar($agent->user_email,50);?>" >
			<div class="supportAgentInfo">
				<div class="supportAgentName"><?php _e($agent->display_name,'wp-support-plus-responsive-ticket-system');?></div>
				<small><b><?php _e('Skype ID:','wp-support-plus-responsive-ticket-system');?></b> <?php _e($currentAgentSettings->skype_id,'wp-support-plus-responsive-ticket-system');?></small>
			</div>
		</div>
		</a>
	<?php }
}
if(!$agentAvailable){
	echo "<h2>".__('Sorry, currently no agent available for chat!!!','wp-support-plus-responsive-ticket-system')."</h2>";
}
?>


<?php 
function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
	$url = '//gravatar.com/avatar/';
	$url .= md5( strtolower( trim( $email ) ) );
	$url .= "?s=$s&d=$d&r=$r";
	if ( $img ) {
		$url = '<img src="' . $url . '"';
		foreach ( $atts as $key => $val )
			$url .= ' ' . $key . '="' . $val . '"';
		$url .= ' />';
	}
	return $url;
}
?>