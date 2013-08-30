<?php
/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

/** Set Localization (do not remove) */
load_child_theme_textdomain( 'apparition', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'apparition' ) );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', __( 'Apparition Theme', 'apparition' ) );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/themes/apparition' );

/** Add Viewport meta tag for mobile browsers */
add_action( 'genesis_meta', 'apparition_add_viewport_meta_tag' );
function apparition_add_viewport_meta_tag() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

/** Force full width content layout */
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

/** Add support for custom background */
add_theme_support( 'custom-background' );

/** Create additional color style options */
add_theme_support( 'genesis-style-selector', array(
	'apparition-green' 		=>	__( 'Green', 'apparition' ),
	'apparition-orange'		=>	__( 'Orange', 'apparition' ),
	'apparition-pink'		=>	__( 'Pink', 'apparition' ),
	'apparition-red'		=>	__( 'Red', 'apparition' ),
	'apparition-lightblue'		=>	__( 'Light Blue', 'apparition' ),
	'apparition-darkblue'		=>	__( 'Dark Blue', 'apparition' ),
) );

/** Add support for custom header */
add_theme_support( 'genesis-custom-header', array(
	'width'	=> 1140,
	'height'	=> 120
) );

/** Sets Content Width */
$content_width = 1140;

/** Remove the site description */
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

/** Add support for structural wraps */
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'inner',
	'footer-widgets',
	'footer'
) );

/** Add new image sizes */
add_image_size( 'featured-circle', 300, 300, TRUE );
add_image_size( 'featured-square', 300, 300, TRUE );

/** Unregister layout settings */
genesis_unregister_layout( 'content-sidebar' );
genesis_unregister_layout( 'sidebar-content' );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

/** Unregister secondary sidebar */
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );

/** Add odd/even post class */
function apparition_oddeven_post_class ( $classes ) {
	global $current_class;
	$classes[] = $current_class;
	$current_class = ($current_class == 'odd') ? 'even' : 'odd';
	return $classes;
}
add_filter ( 'post_class' , 'apparition_oddeven_post_class' );
global $current_class;
$current_class = 'odd';

/** Relocate the Genesis post image */
remove_action( 'genesis_post_content', 'genesis_do_post_image' );
add_action( 'genesis_before_post_title', 'genesis_do_post_image' );

/** Add the featured image section */
add_action( 'genesis_before_post_title', 'apparition_featured_image' );
function apparition_featured_image() {
	if ( is_singular( array( 'post', 'page' ) ) && has_post_thumbnail() ){
		echo '<div class="featured-image">';
		echo get_the_post_thumbnail( $thumbnail->ID, 'featured-square' );
		echo '</div>';
	}
}

/** Remove the post info function */
remove_action( 'genesis_before_post_content', 'genesis_post_info' );

/** Reposition the post meta function */
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
add_action( 'genesis_post_content', 'genesis_post_meta' );

/** Customize the post meta function */
add_filter( 'genesis_post_meta', 'post_meta_filter' );
function post_meta_filter($post_meta) {
if ( !is_page() ) {
	$post_meta = '[post_date] [post_author_posts_link] [post_comments] [post_edit]';
	return $post_meta;
}}

/** Modify the size of the Gravatar in the author box */
add_filter( 'genesis_author_box_gravatar_size', 'apparition_author_box_gravatar_size' );
function apparition_author_box_gravatar_size( $size ) {
	return '85';
}

/** Customize the comment submit button text */
add_filter( 'genesis_comment_form_args', 'apparition_comment_form_args' );
function apparition_comment_form_args( $args ) {
	$args['label_submit'] = __( 'Submit', 'apparition' );
	return $args;
}

/** Modify the Genesis content limit read more link */
add_filter( 'get_the_content_more_link', 'apparition_read_more_link' );
function apparition_read_more_link() {
	return '... <a class="more-link" href="' . get_permalink() . '">' . __( '[Continue Reading]', 'apparition' ) .'</a>';
}

/** Add support for 3-column footer widgets */
add_theme_support( 'genesis-footer-widgets', 3 );

add_action('after_setup_theme','bw_images_size');
function bw_images_size() {
	add_image_size('thumbnail-bw', 300, 300, FALSE);
}
add_filter('wp_generate_attachment_metadata','bw_images_filter');
function bw_images_filter($meta) {
	$file = wp_upload_dir();
	$file = trailingslashit($file['path']).$meta['sizes']['thumbnail-bw']['file'];
	list($orig_w, $orig_h, $orig_type) = @getimagesize($file);
	$image = wp_load_image($file);
	imagefilter($image, IMG_FILTER_GRAYSCALE);
	//imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
	switch ($orig_type) {
		case IMAGETYPE_GIF:
			$file = str_replace(".gif", "-bw.gif", $file);
			imagegif( $image, $file );
			break;
		case IMAGETYPE_PNG:
			$file = str_replace(".png", "-bw.png", $file);
			imagepng( $image, $file );
			break;
		case IMAGETYPE_JPEG:
			$file = str_replace(".jpg", "-bw.jpg", $file);
			imagejpeg( $image, $file );
			break;
	}
	return $meta;
}
add_filter( 'wp_nav_menu_items', 'genesis_search_primary_nav_menu', 10, 2 );

function genesis_search_primary_nav_menu( $menu, stdClass $args ){
        
       
        if ( 'primary' != $args->theme_location )
        	return $menu;
        
               if( genesis_get_option( 'nav_extras' ) )
                return $menu;
        
        $menu .= sprintf( '<li class="custom-search">%s</li>', __( genesis_search_form( $echo ) ) );
        
        
        return $menu;
        
}