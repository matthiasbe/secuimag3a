function wpsp_show_front_popup(){
    jQuery('#wpsp_front_popup,#wpsp_front_popup_inner,#wpsp_front_popup_blank,#wpsp_front_popup_close_btn,#wpsp_front_popup_loading_img').show();
    jQuery('#wpsp_front_popup_body').hide();
}

function wpsp_close_front_popup(){
    jQuery('#wpsp_front_popup,#wpsp_front_popup_inner,#wpsp_front_popup_blank,#wpsp_front_popup_close_btn,#wpsp_front_popup_loading_img').hide();
}