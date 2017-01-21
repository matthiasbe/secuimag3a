<?php
$license = trim( $_POST[ 'license'] );
			
// data to send in our API request
$api_params = array( 
        'edd_action'=> 'deactivate_license', 
        'license'   => $license, 
        'item_id'   => $_POST[ 'item_id'],
        'url'       => home_url()
);

// Call the custom API.
$response = wp_remote_post( WPSP_STORE_URL, array(
        'timeout'   => 15,
        'sslverify' => false,
        'body'      => $api_params
) );
// make sure the response came back okay
if ( is_wp_error( $response ) ){
    echo "key deactivation failed!";
} else {
    $license_data = json_decode( wp_remote_retrieve_body( $response ) );
    if($license_data->success){
        delete_option('wpsp_license_key_'.$_POST['addon_slug']);
        echo "key deactivation successfull!";
    } else {
        echo "key deactivation failed!";
    }
}
?>