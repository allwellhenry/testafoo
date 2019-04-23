<?php
/**
 *Creamery Lite About Theme
 *
 * @package Creamery Lite
 */

//about theme info
add_action( 'admin_menu', 'creamery_lite_abouttheme' );
function creamery_lite_abouttheme() {    	
	add_theme_page( __('About Theme Info', 'creamery-lite'), __('About Theme Info', 'creamery-lite'), 'edit_theme_options', 'creamery_lite_guide', 'creamery_lite_mostrar_guide');   
} 

//Info of the theme
function creamery_lite_mostrar_guide() { 	
?>
<div class="wrap-GT">
	<div class="gt-left">
   		   <div class="heading-gt">
			  <h3><?php esc_html_e('About Theme Info', 'creamery-lite'); ?></h3>
		   </div>
          <p><?php esc_html_e('The Creamery Lite is a smooth, clear and minimalistic free food and bakery WordPress theme. This theme is specially designed for bakery and it is a prime example of what a bakery website should look like. It can be used for cake, ice cream parlous, sandwich, muffin, sweets shops, bakeries, dessert cafes, cup cakeries, pastry chefs, gift shop, restaurants or coffee shops. Creamery has an attractive, elegant and great design for all food-based businesses. The simple design is crafted in the flat style and features beautiful homepage sections to show off your baked products. The theme has a responsive design for viewing on mobile device. ','creamery-lite'); ?></p>
<div class="heading-gt"> <?php esc_html_e('Theme Features', 'creamery-lite'); ?></div>
 

<div class="col-2">
  <h4><?php esc_html_e('Theme Customizer', 'creamery-lite'); ?></h4>
  <div class="description"><?php esc_html_e('The built-in customizer panel quickly change aspects of the design and display changes live before saving them.', 'creamery-lite'); ?></div>
</div>

<div class="col-2">
  <h4><?php esc_html_e('Responsive Ready', 'creamery-lite'); ?></h4>
  <div class="description"><?php esc_html_e('The themes layout will automatically adjust and fit on any screen resolution and looks great on any device. Fully optimized for iPhone and iPad.', 'creamery-lite'); ?></div>
</div>

<div class="col-2">
<h4><?php esc_html_e('Cross Browser Compatible', 'creamery-lite'); ?></h4>
<div class="description"><?php esc_html_e('Our themes are tested in all mordern web browsers and compatible with the latest version including Chrome,Firefox, Safari, Opera, IE11 and above.', 'creamery-lite'); ?></div>
</div>

<div class="col-2">
<h4><?php esc_html_e('E-commerce', 'creamery-lite'); ?></h4>
<div class="description"><?php esc_html_e('Fully compatible with WooCommerce plugin. Just install the plugin and turn your site into a full featured online shop and start selling products.', 'creamery-lite'); ?></div>
</div>
<hr />  
</div><!-- .gt-left -->
	
<div class="gt-right">			
        <div>				
            <a href="<?php echo esc_url( CREAMERY_LITE_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e('Live Demo', 'creamery-lite'); ?></a> | 
            <a href="<?php echo esc_url( CREAMERY_LITE_PROTHEME_URL ); ?>" target="_blank"><?php esc_html_e('Purchase Pro', 'creamery-lite'); ?></a> | 
            <a href="<?php echo esc_url( CREAMERY_LITE_THEME_DOC ); ?>" target="_blank"><?php esc_html_e('Documentation', 'creamery-lite'); ?></a>
        </div>		
</div><!-- .gt-right-->
<div class="clear"></div>
</div><!-- .wrap-GT -->
<?php } ?>