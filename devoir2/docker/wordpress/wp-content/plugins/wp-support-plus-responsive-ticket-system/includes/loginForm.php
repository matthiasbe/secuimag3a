<?php if($generalSettings['default_login_module']==1){?>
    <div id="wpspLoginAndSignUpDiv">
            <div id="wpspLoginErrorDiv"></div>
            <input type="text" id="wpspLoginUsername" placeholder="<?php _e('Enter Username', 'wp-support-plus-responsive-ticket-system');?>"><br>
            <input type="password" id="wpspLoginPassword" placeholder="<?php _e('Enter Password', 'wp-support-plus-responsive-ticket-system');?>"><br>
            <button type="button" onclick="wpspCheckLogin();" id="wpspLoginSubmit"><?php _e('Login', 'wp-support-plus-responsive-ticket-system');?></button><br>
            <?php _e('Forgot Password?', 'wp-support-plus-responsive-ticket-system');?>&nbsp;
            <a href="<?php echo wp_lostpassword_url();?>"><?php _e("Click Here",'wp-support-plus-responsive-ticket-system');?></a><br>
            <?php if(get_option( 'users_can_register' )){?>
                    <div id="wpsp_registration_link">
                            <?php _e("Don't have account?",'wp-support-plus-responsive-ticket-system');?>&nbsp;
                            <a href="<?php echo wp_registration_url();?>"><?php _e("Register Here",'wp-support-plus-responsive-ticket-system');?></a>
                    </div>
            <?php }?>
    </div>

    <script>
    jQuery(document).ready(function(){
            jQuery("#wpspLoginUsername,#wpspLoginPassword").keyup(function(event){
                if(event.keyCode == 13){
                    wpspCheckLogin();
                }
            });
    });
    </script>
<?php 
}
else {
    $support_page_url=get_permalink($generalSettings['post_id']);
    $login_url=wp_login_url($support_page_url);
    ?>
    <a href="<?php echo $login_url;?>" ><b><center><?php _e('Click Here to Login','wp-support-plus-responsive-ticket-system');?></center></b></a>
    <?php
}
?>