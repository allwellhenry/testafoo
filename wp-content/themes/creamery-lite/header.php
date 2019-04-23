<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="container">
 *
 * @package Creamery Lite
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php endif; ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
$creamery_lite_show_slider 	  		        = get_theme_mod('creamery_lite_show_slider', false);
$creamery_lite_show_servicespart 	  	    = get_theme_mod('creamery_lite_show_servicespart', false);
$show_creamery_lite_welcomepage	            = get_theme_mod('show_creamery_lite_welcomepage', false);
?>
<div id="site-holder" <?php if( get_theme_mod( 'sitebox_layout' ) ) { echo 'class="boxlayout"'; } ?>>
<?php
if ( is_front_page() && !is_home() ) {
	if( !empty($creamery_lite_show_slider)) {
	 	$inner_cls = '';
	}
	else {
		$inner_cls = 'siteinner';
	}
}
else {
$inner_cls = 'siteinner';
}
?>

<div class="site-header <?php echo $inner_cls; ?>">       
<div class="hdrblack">
<div class="container">    
  <div class="logo">
        <?php creamery_lite_the_custom_logo(); ?>
        <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
            <?php $description = get_bloginfo( 'description', 'display' );
            if ( $description || is_customize_preview() ) : ?>
                <p><?php echo esc_html($description); ?></p>
            <?php endif; ?>
  </div><!-- logo -->

       <div class="toggle">
         <a class="toggleMenu" href="#"><?php esc_html_e('Menu','creamery-lite'); ?></a>
       </div><!-- toggle --> 
       <div class="sitenav">                   
         <div class="header_left"><?php wp_nav_menu( array('theme_location' => 'leftmenu') ); ?></div><!--header_left-->   
         <div class="header_right"><?php wp_nav_menu( array('theme_location' => 'rightmenu') ); ?></div><!--header_right-->   
       </div><!--.sitenav -->

<div class="clear"></div>  

</div><!-- container --> 
</div><!-- .hdrblack -->  
</div><!--.site-header --> 

<?php 
if ( is_front_page() && !is_home() ) {
if($creamery_lite_show_slider != '') {
	for($i=1; $i<=3; $i++) {
	  if( get_theme_mod('creamery_lite_sliderpage'.$i,false)) {
		$slider_Arr[] = absint( get_theme_mod('creamery_lite_sliderpage'.$i,true));
	  }
	}
?>                
                
<?php if(!empty($slider_Arr)){ ?>
<div id="slider" class="nivoSlider">
<?php 
$i=1;
$slidequery = new WP_Query( array( 'post_type' => 'page', 'post__in' => $slider_Arr, 'orderby' => 'post__in' ) );
while( $slidequery->have_posts() ) : $slidequery->the_post();
$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID)); 
$thumbnail_id = get_post_thumbnail_id( $post->ID );
$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); 
?>
<?php if(!empty($image)){ ?>
<img src="<?php echo esc_url( $image ); ?>" title="#slidecaption<?php echo $i; ?>" alt="<?php echo esc_attr($alt); ?>" />
<?php }else{ ?>
<img src="<?php echo esc_url( get_template_directory_uri() ) ; ?>/images/slides/slider-default.jpg" title="#slidecaption<?php echo $i; ?>" alt="<?php echo esc_attr($alt); ?>" />
<?php } ?>
<?php $i++; endwhile; ?>
</div>   

<?php 
$j=1;
$slidequery->rewind_posts();
while( $slidequery->have_posts() ) : $slidequery->the_post(); ?>                 
    <div id="slidecaption<?php echo $j; ?>" class="nivo-html-caption">        
    	<h2><?php the_title(); ?></h2>
    	<p><?php echo esc_html( wp_trim_words( get_the_content(), 20, '' ) );  ?></p> 
    <?php
    $creamery_lite_slider_readmore = get_theme_mod('creamery_lite_slider_readmore');
    if( !empty($creamery_lite_slider_readmore) ){ ?>
    	<a class="slide_more" href="<?php the_permalink(); ?>"><?php echo esc_html($creamery_lite_slider_readmore); ?></a>
    <?php } ?>       
    </div> 
    <div class="zig-zag-top"></div>     
<?php $j++; 
endwhile;
wp_reset_postdata(); ?>  
<div class="clear"></div>        
<?php } ?>
<?php } } ?>
       
        
<?php if ( is_front_page() && ! is_home() ) {
if( $creamery_lite_show_servicespart != ''){ ?>  
<section id="cake_servicesarea">
<div class="container"> 
	<?php
    $creamery_lite_servicesbox_title = get_theme_mod('creamery_lite_servicesbox_title');
    if( !empty($creamery_lite_servicesbox_title) ){ ?>
      <h2 class="section_title"><?php echo esc_html($creamery_lite_servicesbox_title); ?></h2>
    <?php } ?> 
    
    <?php
    $creamery_lite_servicesbox_description = get_theme_mod('creamery_lite_servicesbox_description');
    if( !empty($creamery_lite_servicesbox_description) ){ ?>
      <div class="shortdesc"><?php echo esc_html($creamery_lite_servicesbox_description); ?></div>
    <?php } ?>
                                              
<?php 
for($n=1; $n<=4; $n++) {    
if( get_theme_mod('creamery_lite_cakepagebox'.$n,false)) {      
	$queryvar = new WP_Query('page_id='.absint(get_theme_mod('creamery_lite_cakepagebox'.$n,true)) );		
	while( $queryvar->have_posts() ) : $queryvar->the_post(); ?> 
    
	<div class="cake_fourcolumn <?php if($n % 4 == 0) { echo "last_column"; } ?>">                                    
		<?php if(has_post_thumbnail() ) { ?>
		<div class="thumbbx"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail();?></a></div>
		<?php } ?>
		<div class="four-pagecontent">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>                                     
		<p><?php echo esc_html( wp_trim_words( get_the_content(), 16, '...' ) );  ?></p>   
		                                  
		</div>                                   
	</div>
	<?php endwhile;
	wp_reset_postdata();                                  
} } ?>                                 
<div class="clear"></div>  
</div><!-- .container -->                  
</section><!-- #cake_servicesarea-->                      	      
<?php } ?>

<?php if( $show_creamery_lite_welcomepage != ''){ ?>  
<section id="welcomepage-area">
<div class="container">                               
<?php 
if( get_theme_mod('creamery_lite_welcomepage',false)) {     
$queryvar = new WP_Query('page_id='.absint(get_theme_mod('creamery_lite_welcomepage',true)) );			
    while( $queryvar->have_posts() ) : $queryvar->the_post(); ?>   
   
     <div class="cake_imgcolumn"><?php the_post_thumbnail();?></div>                              
     <div class="cake_contentcolumn">   
     <h3><?php the_title(); ?></h3>   
      <p><?php echo esc_html( wp_trim_words( get_the_content(), 110, '...' ) );  ?></p> 
      <a class="learnmore" href="<?php the_permalink(); ?>"><?php esc_html_e('Read More','creamery-lite'); ?></a> 
    </div>                                      
    <?php endwhile;
     wp_reset_postdata(); ?>                                    
    <?php } ?>                                 
<div class="clear"></div>                       
</div><!-- container -->
</section><!-- #welcomepage-area-->
<?php } ?>

<?php } ?>