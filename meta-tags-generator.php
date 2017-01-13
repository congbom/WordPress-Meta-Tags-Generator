<?php 
/*
Plugin Name: Meta Tags Generator
Plugin URI: http://congbom.com/wordpress/plugins/meta-tags-generator/
Description: Generate meta tags. Let your WordPress site optimize with Search engine & Social sharing.
Author: Cong Bom
Version: 1.6
Author URI: http://congbom.com/
Text Domain: meta-tags-generator
*/

/* Text domain */
add_action( 'plugins_loaded', 'meta_tags_generator_load_plugin_textdomain' );
function meta_tags_generator_load_plugin_textdomain() {
	load_plugin_textdomain( 'meta-tags-generator' );
}

/* Main */
add_action('wp_head', 'meta_tags_generator_wp_head' );
function meta_tags_generator_wp_head() { 
	$meta_props = array(
		'author_id' => '',
		'type' => 'website',
		'url' => home_url() . $_SERVER['REQUEST_URI'],
		'image' => get_template_directory_uri() . '/screenshot.png',
		'description' => strip_tags( get_bloginfo('description') ),
	);
	if( is_post_type_archive() ) {
		$post_type = get_post_type_object( get_query_var('post_type') );
		$meta_props['description'] = strip_tags( $post_type->description );
	}
	if( is_tax() || is_category() || is_tag() ) {
		$meta_props['description'] = term_description() ? strip_tags( term_description() ) : $meta_props['description'];
	}
	if( is_search() ) {
		$meta_props['description'] = __('Search result for ', 'meta-tags-generator') . get_query_var('s');
	}
	if( is_author() ) {
		$author_data = get_userdata( get_query_var('author') );
		$meta_props['description'] = $author_data->descriptionn ? strip_tags( $author_data->description ) : $meta_props['description'];
	}
	if( is_single() || is_page() ) {
		global $post;
		$image_source = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		$post_excerpt = $post->post_excerpt ? $post->post_excerpt : wp_trim_words( $post->post_content );
		$meta_props['type'] = 'article';
		$meta_props['description'] = $post_excerpt ? strip_tags( $post_excerpt ) : $meta_props['description'];
		$meta_props['image'] = $image_source ? $image_source[0] : $meta_props['image'];
	}
	if( is_paged() ) {
		$meta_props['description'] .= ' | Page '. get_query_var('paged');
	}?>

	<!-- Meta Tags Generator BEGIN -->
	<meta name="description" content="<?php echo esc_html( $meta_props['description'] ); ?>" />
	<meta property="og:type" content='<?php echo esc_attr( $meta_props['type'] ) ;?>' />
	<meta property="og:title" content="<?php echo esc_attr( wp_get_document_title() ) ;?>" />
	<meta property="og:url" content="<?php echo esc_url( $meta_props['url'] ); ?>" />
	<meta property="og:image" content="<?php echo esc_url( $meta_props['image'] ); ?>" />
	<meta property="og:description" content="<?php echo esc_html( $meta_props['description'] ); ?>" />
	<!-- Meta Tags Generator END -->

	<?php
}
