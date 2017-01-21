jQuery(document).ready(function(){

});
function setShareCanned() {
    var data = {
        'action': 'shareCanned',
        'cid': currentCannedId,
        'cuid': jQuery('#share_user').val()
    };
    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#Modal').modal('hide');
        window.location.reload();
    });
}
function setCurrentCannedId(canned_id,sharedIds){
    currentCannedId=canned_id;
    $options = jQuery('#share_user option');
    $options.each(function(){
        jQuery(this).prop('selected', false);
    });
    
    var sharedIdArr = sharedIds.split(","); 
    $options = jQuery('#share_user option');
    $options.each(function(){
        if(sharedIdArr.indexOf(jQuery(this).val()) > -1){
            jQuery(this).prop('selected', true);
        }
    });
}
