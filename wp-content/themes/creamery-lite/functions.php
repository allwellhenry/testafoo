<?php        
/**
 * Creamery Lite functions and definitions
 *
 * @package Creamery Lite
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */

if ( ! function_exists( 'creamery_lite_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.  
 */
function creamery_lite_setup() {		
	global $content_width;   
    if ( ! isset( $content_width ) ) {
        $content_width = 680; /* pixels */
    }	

	load_theme_textdomain( 'creamery-lite', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support('woocommerce');
	add_theme_support('html5');
	add_theme_support( 'post-thumbnails' );	
	add_theme_support( 'title-tag' );	
	add_theme_support( 'custom-logo', array(
		'height'      => 50,
		'width'       => 150,
		'flex-height' => true,
	) );	
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff'
	) );
	register_nav_menus( array(
		'leftmenu' => __( 'Header Left Menu', 'creamery-lite' ),	
		'rightmenu' => __( 'Header Right Menu', 'creamery-lite' ),			
	) );
	add_editor_style( 'editor-style.css' );
} 
endif; // creamery_lite_setup
add_action( 'after_setup_theme', 'creamery_lite_setup' );
function creamery_lite_widgets_init() { 	
	
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'creamery-lite' ),
		'description'   => __( 'Appears on blog page sidebar', 'creamery-lite' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
}
add_action( 'widgets_init', 'creamery_lite_widgets_init' );


function creamery_lite_font_url(){
		$font_url = '';	
		
		/* Translators: If there are any character that are not
		* supported by Kaushan Script, trsnalate this to off, do not
		* translate into your own language.
		*/
		$kaushanscript = _x('on','Kaushan Script:on or off','creamery-lite');
		
		/* Translators: If there are any character that are not
		* supported by Assistant, trsnalate this to off, do not
		* translate into your own language.
		*/
		$assistant = _x('on','Assistant:on or off','creamery-lite');		
		
		/* Translators: If there are any character that are not
		* supported by Open Sans, trsnalate this to off, do not
		* translate into your own language.
		*/
		$opensans = _x('on','Open Sans:on or off','creamery-lite');	
		
		    if('off' !== $opensans || 'off' !== $assistant || 'off' !== $kaushanscript ){
			    $font_family = array();
			
			if('off' !== $assistant){
				$font_family[] = 'Assistant:300,400,600';
			}
			
			if('off' !== $opensans){
				$font_family[] = 'Open Sans:300,400,600,900';
			}
			
			if('off' !== $kaushanscript){
				$font_family[] = 'Kaushan Script:300,400,600,900';
			}			
						
			$query_args = array(
				'family'	=> urlencode(implode('|',$font_family)),
			);
			
			$font_url = add_query_arg($query_args,'//fonts.googleapis.com/css');
		}
		
	return $font_url;
	}


function creamery_lite_scripts() {
	wp_enqueue_style('creamery-lite-font', creamery_lite_font_url(), array());
	wp_enqueue_style( 'creamery-lite-basic-style', get_stylesheet_uri() );	
	wp_enqueue_style( 'nivo-slider', get_template_directory_uri()."/css/nivo-slider.css" );
	wp_enqueue_style( 'fontawesome-all-style', get_template_directory_uri().'/fontsawesome/css/fontawesome-all.css' );
	wp_enqueue_style( 'creamery-lite-responsive', get_template_directory_uri()."/css/responsive.css" );
	wp_enqueue_script( 'jquery-nivo-slider', get_template_directory_uri() . '/js/jquery.nivo.slider.js', array('jquery') );
	wp_enqueue_script( 'creamery-lite-editable', get_template_directory_uri() . '/js/editable.js' );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'creamery_lite_scripts' );

function creamery_lite_ie_stylesheet(){
	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style('creamery-lite-ie', get_template_directory_uri().'/css/ie.css', array( 'creamery-lite-style' ), '20160928' );
	wp_style_add_data('creamery-lite-ie','conditional','lt IE 10');
	
	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'creamery-lite-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'creamery-lite-style' ), '20160928' );
	wp_style_add_data( 'creamery-lite-ie8', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'creamery-lite-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'creamery-lite-style' ), '20160928' );
	wp_style_add_data( 'creamery-lite-ie7', 'conditional', 'lt IE 8' );	
	}
add_action('wp_enqueue_scripts','creamery_lite_ie_stylesheet');

define('CREAMERY_LITE_THEME_DOC','https://gracethemes.com/documentation/creamery-doc/#homepage-lite','creamery-lite');
define('CREAMERY_LITE_PROTHEME_URL','https://gracethemes.com/themes/food-bakery-wordpress-theme/','creamery-lite');
define('CREAMERY_LITE_LIVE_DEMO','https://gracethemes.com/demo/creamery/','creamery-lite');

if ( ! function_exists( 'creamery_lite_the_custom_logo' ) ) :
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 *
 */
function creamery_lite_the_custom_logo() {
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}
endif;

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom template for about theme.
 */
if ( is_admin() ) { 
require get_template_directory() . '/inc/about-themes.php';
}

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';