<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Creamery Lite
 */
$show_ftr_socialicons 	  	= get_theme_mod('show_ftr_socialicons', false);
 

// Email subject and body text.
$subject = 'wp_mail function test';
$message = 'This is a test of the wp_mail function: wp_mail is working';
$headers = '';

// send test message using wp_mail function.
$sent_message = wp_mail( "enrico@pressable.com", $subject, $message, $headers );
//display message based on the result.
if ( $sent_message ) {
    // The message was sent.
    echo 'The test message was sent. Check your email inbox.';
} else {
    // The message was not sent.
    echo 'The message was not sent!';
}

?>

<div class="footer-wrapper">
  <div class="container">            	
                <div class="design-by">
				  <?php bloginfo('name'); ?>. <?php esc_html_e('All Rights Reserved', 'creamery-lite');?>  <a href="<?php echo esc_url( __( 'https://gracethemes.com/themes/free-food-bakery-wordpress-theme/', 'creamery-lite' ) ); ?>" target="_blank"><?php printf( __( 'Theme by %s', 'creamery-lite' ), 'Grace Themes' ); ?></a>
                </div>
                <div class="copyright"> 
				<?php if( $show_ftr_socialicons != ''){ ?> 
                   <div class="social-icons">                     
                   <?php esc_html_e('Follow Us ', 'creamery-lite');?>                                   
                   <?php $fb_link = get_theme_mod('fb_link');
                    if( !empty($fb_link) ){ ?>
                    <a title="facebook" class="fab fa-facebook-f" target="_blank" href="<?php echo esc_url($fb_link); ?>"></a>
                   <?php } ?>
                
                   <?php $twitt_link = get_theme_mod('twitt_link');
                    if( !empty($twitt_link) ){ ?>
                    <a title="twitter" class="fab fa-twitter" target="_blank" href="<?php echo esc_url($twitt_link); ?>"></a>
                   <?php } ?>
            
                  <?php $gplus_link = get_theme_mod('gplus_link');
                    if( !empty($gplus_link) ){ ?>
                    <a title="google-plus" class="fab fa-google-plus" target="_blank" href="<?php echo esc_url($gplus_link); ?>"></a>
                  <?php }?>
            
                  <?php $linked_link = get_theme_mod('linked_link');
                    if( !empty($linked_link) ){ ?>
                    <a title="linkedin" class="fab fa-linkedin" target="_blank" href="<?php echo esc_url($linked_link); ?>"></a>
                  <?php } ?>                  
               </div><!--end .social-icons--> 
             <?php } ?> </div><!--end .copyright-->
                
                 <div class="clear"></div>
            </div><!--end .container-->           
        </div>        
</div><!--#end site-holder-->

<?php wp_footer(); ?>
</body>
</html>