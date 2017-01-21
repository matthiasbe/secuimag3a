function wpsp_getCreateTicketShortcode(){
    jQuery('#create_ticket_container').hide();
    jQuery('#create_ticket .wait').show();
    var data = {
        'action': 'getCreateTicketForm',
        'backend': 0
    };
    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#create_ticket_container').html(response);
        jQuery('#create_ticket .wait').hide();
        jQuery('#create_ticket_container').show();

        if( (display_ticket_data.user_logged_in=='1' && display_ticket_data.ckeditor_enable_for_loggedin=='1') || (display_ticket_data.user_logged_in=='0' && display_ticket_data.ckeditor_enable_for_guest=='1') ){
            CKEDITOR.replace(document.getElementById('create_ticket_body'));
        }

        jQuery( '#frmCreateNewTicket' ).unbind('submit');
        jQuery( '#frmCreateNewTicket' ).submit(function( e ) {
            if(validateTicketSubmit()){
                jQuery('#create_ticket_container').hide();
                jQuery('#create_ticket .wait').show();

                var dataform=new FormData( this );
                if( (display_ticket_data.user_logged_in=='1' && display_ticket_data.ckeditor_enable_for_loggedin=='1') || (display_ticket_data.user_logged_in=='0' && display_ticket_data.ckeditor_enable_for_guest=='1') ){
                    dataform.append("create_ticket_body", CKEDITOR.instances['create_ticket_body'].getData().trim());
                    dataform.append("ckeditor_enabled","1");
                } else {
                    dataform.append("ckeditor_enabled","0");
                }
                
                jQuery.ajax( {
                    url: display_ticket_data.wpsp_ajax_url,
                    type: 'POST',
                    data: dataform,
                    processData: false,
                    contentType: false
                }) 
                .done(function( msg ) {
                    if(msg==1){
                        jQuery('#create_ticket .wait').hide();
                        jQuery('#create_ticket_container').show();
                        jQuery('#create_ticket_container').html(display_ticket_data.shortly_get_back);
                        
                        var body = jQuery("html, body");
                        var p = jQuery( ".support_bs" );
                        var position = p.position();
                    }
                    else{
                        jQuery('#create_ticket_container').html(msg);
                        jQuery('#create_ticket .wait').hide();
                        jQuery('#create_ticket_container').show();
                        
                    }
                });
            }
            e.preventDefault();
        });
    });
}