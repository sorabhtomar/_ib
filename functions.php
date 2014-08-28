<?php
/**
 * IB Theme functions and definitions
 *
 * @package IB Theme
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 750; /* pixels */

if ( ! function_exists( 'ib_setup' ) ) :
/**
 * Set up theme defaults and register support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function ib_setup() {
	global $cap, $content_width;

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	if ( function_exists( 'add_theme_support' ) ) {

		/**
		 * Add default posts and comments RSS feed links to head
		*/
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Enable support for Post Thumbnails on posts and pages
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		*/
		add_theme_support( 'post-thumbnails' );

		/**
		 * Enable support for Post Formats
		*/
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

		/**
		 * Setup the WordPress core custom background feature.
		*/
		add_theme_support( 'custom-background', apply_filters( 'ib_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

	}

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on IB Theme, use a find and replace
	 * to change 'ib' to the name of your theme in all the template files
	*/
	load_theme_textdomain( 'ib', get_template_directory() . '/languages' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	*/
	register_nav_menus( array(
		'primary'  => __( 'Header bottom menu', 'ib' ),
	) );

}
endif; // ib_setup
add_action( 'after_setup_theme', 'ib_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function ib_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'ib' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'ib_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function ib_scripts() {

	// load bootstrap css
	wp_enqueue_style( 'ib-bootstrap', get_template_directory_uri() . '/includes/resources/bootstrap/css/bootstrap.css' );

	// load IB Theme styles
	wp_enqueue_style( 'ib-style', get_stylesheet_uri() );

	// load bootstrap js
	wp_enqueue_script('ib-bootstrapjs', get_template_directory_uri().'/includes/resources/bootstrap/js/bootstrap.js', array('jquery') );

	// load bootstrap wp js
	wp_enqueue_script( 'ib-bootstrapwp', get_template_directory_uri() . '/includes/js/bootstrap-wp.js', array('jquery') );

	wp_enqueue_script( 'ib-skip-link-focus-fix', get_template_directory_uri() . '/includes/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'ib-keyboard-image-navigation', get_template_directory_uri() . '/includes/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

}
add_action( 'wp_enqueue_scripts', 'ib_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/includes/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/includes/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/includes/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/includes/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/includes/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
require get_template_directory() . '/includes/bootstrap-wp-navwalker.php';

function html_tag_schema() {
    $schema = 'http://schema.org/';
 
    // Is single post
    if(is_single()) {
        $type = "Article";
    }
    
    if(is_home()) {
        $type = "BlogPage";
    }
 
    // Is author page
    elseif( is_author() ) {
        $type = 'ProfilePage';
    }
    
    // Is search results page
    elseif( is_search() ) {
        $type = 'SearchResultsPage';
    }
 
    else {
        $type = 'WebPage';
    }
 
    echo 'itemscope="itemscope" itemtype="' . $schema . $type . '"';
}