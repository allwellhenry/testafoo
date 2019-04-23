<?php    
/**
 *Creamery Lite Theme Customizer
 *
 * @package Creamery Lite
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function creamery_lite_customize_register( $wp_customize ) {	
	
	function creamery_lite_sanitize_dropdown_pages( $page_id, $setting ) {
	  // Ensure $input is an absolute integer.
	  $page_id = absint( $page_id );
	
	  // If $page_id is an ID of a published page, return it; otherwise, return the default.
	  return ( 'publish' == get_post_status( $page_id ) ? $page_id : $setting->default );
	}

	function creamery_lite_sanitize_checkbox( $checked ) {
		// Boolean check.
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}  
		
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	
	 //Panel for section & control
	$wp_customize->add_panel( 'creamery_lite_panel_area', array(
		'priority' => null,
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title' => __( 'Theme Options Panel', 'creamery-lite' ),		
	) );
	
	//Layout Options
	$wp_customize->add_section('layout_option',array(
		'title' => __('Site Layout','creamery-lite'),			
		'priority' => 1,
		'panel' => 	'creamery_lite_panel_area',          
	));		
	
	$wp_customize->add_setting('sitebox_layout',array(
		'sanitize_callback' => 'creamery_lite_sanitize_checkbox',
	));	 

	$wp_customize->add_control( 'sitebox_layout', array(
    	'section'   => 'layout_option',    	 
		'label' => __('Check to Box Layout','creamery-lite'),
		'description' => __('If you want to box layout please check the Box Layout Option.','creamery-lite'),
    	'type'      => 'checkbox'
     )); //Layout Section 
	
	$wp_customize->add_setting('creamery_lite_color_scheme',array(
		'default' => '#e6a84c',
		'sanitize_callback' => 'sanitize_hex_color'
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'creamery_lite_color_scheme',array(
			'label' => __('Color Scheme','creamery-lite'),			
			'description' => __('More color options in PRO Version','creamery-lite'),
			'section' => 'colors',
			'settings' => 'creamery_lite_color_scheme'
		))
	);	
	
	// Slider Section		
	$wp_customize->add_section( 'creamery_lite_slider_options', array(
		'title' => __('Slider Section', 'creamery-lite'),
		'priority' => null,
		'description' => __('Default image size for slider is 1400 x 800 pixel.','creamery-lite'), 
		'panel' => 	'creamery_lite_panel_area',           			
    ));
	
	$wp_customize->add_setting('creamery_lite_sliderpage1',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'creamery_lite_sanitize_dropdown_pages'
	));
	
	$wp_customize->add_control('creamery_lite_sliderpage1',array(
		'type' => 'dropdown-pages',
		'label' => __('Select page for slide one:','creamery-lite'),
		'section' => 'creamery_lite_slider_options'
	));	
	
	$wp_customize->add_setting('creamery_lite_sliderpage2',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'creamery_lite_sanitize_dropdown_pages'
	));
	
	$wp_customize->add_control('creamery_lite_sliderpage2',array(
		'type' => 'dropdown-pages',
		'label' => __('Select page for slide two:','creamery-lite'),
		'section' => 'creamery_lite_slider_options'
	));	
	
	$wp_customize->add_setting('creamery_lite_sliderpage3',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'creamery_lite_sanitize_dropdown_pages'
	));
	
	$wp_customize->add_control('creamery_lite_sliderpage3',array(
		'type' => 'dropdown-pages',
		'label' => __('Select page for slide three:','creamery-lite'),
		'section' => 'creamery_lite_slider_options'
	));	// Slider Section	
	
	$wp_customize->add_setting('creamery_lite_slider_readmore',array(
		'default' => null,
		'sanitize_callback' => 'sanitize_text_field'	
	));
	
	$wp_customize->add_control('creamery_lite_slider_readmore',array(	
		'type' => 'text',
		'label' => __('Add slider Read more button name here','creamery-lite'),
		'section' => 'creamery_lite_slider_options',
		'setting' => 'creamery_lite_slider_readmore'
	)); // Slider Read More Button Text
	
	$wp_customize->add_setting('creamery_lite_show_slider',array(
		'default' => false,
		'sanitize_callback' => 'creamery_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'creamery_lite_show_slider', array(
	    'settings' => 'creamery_lite_show_slider',
	    'section'   => 'creamery_lite_slider_options',
	     'label'     => __('Check To Show This Section','creamery-lite'),
	   'type'      => 'checkbox'
	 ));//Show Slider Section	
	 
	 
	 // four boxes Services panel
	$wp_customize->add_section('creamery_lite_services_part', array(
		'title' => __('Services Section','creamery-lite'),
		'description' => __('Select pages from the dropdown for services section','creamery-lite'),
		'priority' => null,
		'panel' => 	'creamery_lite_panel_area',          
	));	
	
	$wp_customize->add_setting('creamery_lite_servicesbox_title',array(
		'default' => null,
		'sanitize_callback' => 'sanitize_text_field'	
	));
	
	$wp_customize->add_control('creamery_lite_servicesbox_title',array(	
		'type' => 'text',
		'label' => __('Add services title here','creamery-lite'),
		'section' => 'creamery_lite_services_part',
		'setting' => 'creamery_lite_servicesbox_title'
	)); 
	
	$wp_customize->add_setting('creamery_lite_servicesbox_description',array(
		'default' => null,
		'sanitize_callback' => 'sanitize_text_field'	
	));
	
	$wp_customize->add_control('creamery_lite_servicesbox_description',array(	
		'type' => 'text',
		'label' => __('Add services short description here','creamery-lite'),
		'section' => 'creamery_lite_services_part',
		'setting' => 'creamery_lite_servicesbox_description'
	)); 
	
	$wp_customize->add_setting('creamery_lite_cakepagebox1',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'creamery_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'creamery_lite_cakepagebox1',array(
		'type' => 'dropdown-pages',			
		'section' => 'creamery_lite_services_part',
	));		
	
	$wp_customize->add_setting('creamery_lite_cakepagebox2',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'creamery_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'creamery_lite_cakepagebox2',array(
		'type' => 'dropdown-pages',			
		'section' => 'creamery_lite_services_part',
	));
	
	$wp_customize->add_setting('creamery_lite_cakepagebox3',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'creamery_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'creamery_lite_cakepagebox3',array(
		'type' => 'dropdown-pages',			
		'section' => 'creamery_lite_services_part',
	));
	
	$wp_customize->add_setting('creamery_lite_cakepagebox4',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'creamery_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'creamery_lite_cakepagebox4',array(
		'type' => 'dropdown-pages',			
		'section' => 'creamery_lite_services_part',
	));
	
	
	$wp_customize->add_setting('creamery_lite_show_servicespart',array(
		'default' => false,
		'sanitize_callback' => 'creamery_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'creamery_lite_show_servicespart', array(
	   'settings' => 'creamery_lite_show_servicespart',
	   'section'   => 'creamery_lite_services_part',
	   'label'     => __('Check To Show This Section','creamery-lite'),
	   'type'      => 'checkbox'
	 ));//Show services Part	
	 
	 
	 // Welcome section 
	$wp_customize->add_section('creamery_lite_welcomesection', array(
		'title' => __('Welcome Section','creamery-lite'),
		'description' => __('Select Pages from the dropdown for welcome section','creamery-lite'),
		'priority' => null,
		'panel' => 	'creamery_lite_panel_area',          
	));		
	
	$wp_customize->add_setting('creamery_lite_welcomepage',array(
		'default' => '0',			
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'creamery_lite_sanitize_dropdown_pages'
	));
 
	$wp_customize->add_control(	'creamery_lite_welcomepage',array(
		'type' => 'dropdown-pages',			
		'section' => 'creamery_lite_welcomesection',
	));		
	
	$wp_customize->add_setting('show_creamery_lite_welcomepage',array(
		'default' => false,
		'sanitize_callback' => 'creamery_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'show_creamery_lite_welcomepage', array(
	    'settings' => 'show_creamery_lite_welcomepage',
	    'section'   => 'creamery_lite_welcomesection',
	    'label'     => __('Check To Show This Section','creamery-lite'),
	    'type'      => 'checkbox'
	));//Show welcome Section 
	 
	 
	  //Header social icons
	$wp_customize->add_section('ftr_social_sec',array(
		'title' => __('Footer social icons','creamery-lite'),
		'description' => __( 'Add social icons link here to display icons in footer', 'creamery-lite' ),			
		'priority' => null,
		'panel' => 	'creamery_lite_panel_area', 
	));
	
	$wp_customize->add_setting('fb_link',array(
		'default' => null,
		'sanitize_callback' => 'esc_url_raw'	
	));
	
	$wp_customize->add_control('fb_link',array(
		'label' => __('Add facebook link here','creamery-lite'),
		'section' => 'ftr_social_sec',
		'setting' => 'fb_link'
	));	
	
	$wp_customize->add_setting('twitt_link',array(
		'default' => null,
		'sanitize_callback' => 'esc_url_raw'
	));
	
	$wp_customize->add_control('twitt_link',array(
		'label' => __('Add twitter link here','creamery-lite'),
		'section' => 'ftr_social_sec',
		'setting' => 'twitt_link'
	));
	
	$wp_customize->add_setting('gplus_link',array(
		'default' => null,
		'sanitize_callback' => 'esc_url_raw'
	));
	
	$wp_customize->add_control('gplus_link',array(
		'label' => __('Add google plus link here','creamery-lite'),
		'section' => 'ftr_social_sec',
		'setting' => 'gplus_link'
	));
	
	$wp_customize->add_setting('linked_link',array(
		'default' => null,
		'sanitize_callback' => 'esc_url_raw'
	));
	
	$wp_customize->add_control('linked_link',array(
		'label' => __('Add linkedin link here','creamery-lite'),
		'section' => 'ftr_social_sec',
		'setting' => 'linked_link'
	));
	
	$wp_customize->add_setting('show_ftr_socialicons',array(
		'default' => false,
		'sanitize_callback' => 'creamery_lite_sanitize_checkbox',
		'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'show_ftr_socialicons', array(
	   'settings' => 'show_ftr_socialicons',
	   'section'   => 'ftr_social_sec',
	   'label'     => __('Check To show This Section','creamery-lite'),
	   'type'      => 'checkbox'
	 ));//Show footer Social icons Section 		 
	
		 
}
add_action( 'customize_register', 'creamery_lite_customize_register' );

function creamery_lite_custom_css(){ 
?>
	<style type="text/css"> 					
        a, .blog_post_list h2 a:hover,
        #sidebar ul li a:hover,								
        .blog_post_list h3 a:hover,					
        .recent-post h6:hover,					
        .cake_fourcolumn:hover .button,									
        .postmeta a:hover,
        .button:hover,
		.cake_imgcolumn h3 span,
        .footercolumn ul li a:hover, 
        .footercolumn ul li.current_page_item a,      
        .cake_fourcolumn:hover h3 a,
		.social-icons a:hover,	
        .header-top a:hover,
        .sitenav ul li a:hover, 
        .sitenav ul li.current-menu-item a,
        .sitenav ul li.current-menu-parent a.parent,
        .sitenav ul li.current-menu-item ul.sub-menu li a:hover				
            { color:<?php echo esc_html( get_theme_mod('creamery_lite_color_scheme','#e6a84c')); ?>;}					 
            
        .pagination ul li .current, .pagination ul li a:hover, 
        #commentform input#submit:hover,					
        .nivo-controlNav a.active,
        .learnmore,
		.nivo-caption .slide_more,											
        #sidebar .search-form input.search-submit,				
        .wpcf7 input[type='submit'],				
        nav.pagination .page-numbers.current,       		
        .toggle a	
            { background-color:<?php echo esc_html( get_theme_mod('creamery_lite_color_scheme','#e6a84c')); ?>;}	
         	
    </style> 
<?php                 
}
         
add_action('wp_head','creamery_lite_custom_css');	 

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function creamery_lite_customize_preview_js() {
	wp_enqueue_script( 'creamery_lite_customizer', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20171016', true );
}
add_action( 'customize_preview_init', 'creamery_lite_customize_preview_js' );