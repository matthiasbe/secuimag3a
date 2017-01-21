<?php 
require_once WCE_PLUGIN_DIR."asset/lib/google_noCaptcha/recaptchalib.php";

// your secret key
$secret = $generalSettings['google_nocaptcha_secret'];
// empty response
$response = null;
// check secret key
$reCaptcha = new ReCaptcha($secret);

// if submitted check response
if (isset($_POST["g-recaptcha-response"]) && $_POST["g-recaptcha-response"]) {
	$response = $reCaptcha->verifyResponse(
			$_SERVER["REMOTE_ADDR"],
			$_POST["g-recaptcha-response"]
	);
}

if ($response == null || !$response->success) {
	die(__("Sorry No Robots allowed here!!!",'wp-support-plus-responsive-ticket-system'));
}
?>