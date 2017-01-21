<?php 
$generalSettings=get_option( 'wpsp_general_settings' );
$support_permalink=get_permalink($generalSettings['post_id']);
$roleManage=get_option('wpsp_role_management');
$loginUrl=wp_login_url( $support_permalink );

global $wpdb;
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories" );

?>
<div id="loginContainer">
	<?php
	if($generalSettings['enable_default_login']==1){
		include( WCE_PLUGIN_DIR.'includes/loginForm.php' );
	}
	if($generalSettings['enable_default_login']==1 && ($generalSettings['enable_guest_ticket'] || $generalSettings['fbAppID']))
	{?>
	<h3><?php echo __('OR', 'wp-support-plus-responsive-ticket-system');?></h3>
	<?php
	}
	?>
	<?php if($generalSettings['fbAppID']){?>
	
	<div id="facebook_login_btn">
		<fb:login-button scope="public_profile,email" onlogin="checkLoginState();"><?php echo __('Login with Facebook', 'wp-support-plus-responsive-ticket-system');?></fb:login-button>
	</div>
	<?php if($generalSettings['enable_guest_ticket'] && $roleManage['front_ticket_all']){?>
	<h3><?php echo __('OR', 'wp-support-plus-responsive-ticket-system');?></h3>
	<?php }}?>
	<?php if($generalSettings['enable_guest_ticket'] && $roleManage['front_ticket_all']){?>
	
		<?php include( WCE_PLUGIN_DIR.'includes/guestTicketForm.php' );?>
	<?php }?>
</div>
<div id="wsp_wait">
	<img alt="<?php echo __('Please Wait...', 'wp-support-plus-responsive-ticket-system')?>" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>" />
</div>

<?php if($generalSettings['fbAppID']){?>
<script type="text/javascript">
	
	var wsp_fUserName='';
	var wsp_fUserId='';
	var wsp_fUserEmail='';
	
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '<?php echo $generalSettings['fbAppID'];?>',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.5' // use version 2.5
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
  	jQuery('#loginContainer').hide();
	jQuery('#wsp_wait').show();
	console.log('Welcome!  Fetching your information.... \n');
    FB.api('/me',{fields: 'id,name,email'}, function(response) {
    	console.log('Email: '+response.email);
    	wsp_fUserName=response.name;
    	wsp_fUserEmail=response.email;
    	wsp_fUserId='fb_'+response.id;
    	loginGuestFacebook();
    });
  }
</script>
<?php }?>
