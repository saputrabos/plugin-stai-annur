<?php
/**
 * Plugin Name: STAI An-Nur
 * Description: Blok Gutenberg STAI An-Nur untuk banner dan judul kampus.
 * Version: 1.0.0
 * Author: STAI An-Nur
 * License: GPL-2.0-or-later
 * Text Domain: stai-annur
 * Requires at least: 6.3
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/includes/image-utils.php';

/**
 * Add the plugin's block category to the inserter.
 *
 * @param array $categories Existing block categories.
 * @return array
 */
function stai_annur_block_category( $categories ) {
	$categories[] = array(
		'slug'  => 'stai-annur',
		'title' => __( 'STAI An-Nur', 'stai-annur' ),
		'icon'  => null,
	);

	return $categories;
}
add_filter( 'block_categories_all', 'stai_annur_block_category' );

/**
 * Register the plugin's Gutenberg blocks.
 *
 * @return void
 */
function stai_annur_register_blocks() {
	register_block_type( __DIR__ . '/blocks/hero-banner' );
	register_block_type( __DIR__ . '/blocks/hero-title' );
	register_block_type( __DIR__ . '/blocks/hero-slider' );
	register_block_type( __DIR__ . '/blocks/hero-banner-2' );
	register_block_type( __DIR__ . '/blocks/hero-post-slider' );
	register_block_type( __DIR__ . '/blocks/hero-card-slider' );
	register_block_type( __DIR__ . '/blocks/hero-card-slider-2' );

	wp_enqueue_block_style(
		'stai-annur/hero-banner',
		array(
			'handle' => 'stai-annur-inter-font',
			'src'    => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
		)
	);

	wp_enqueue_block_style(
		'stai-annur/hero-slider',
		array(
			'handle' => 'stai-annur-inter-font',
			'src'    => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
		)
	);

	wp_enqueue_block_style(
		'stai-annur/hero-banner-2',
		array(
			'handle' => 'stai-annur-inter-font',
			'src'    => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
		)
	);

	wp_enqueue_block_style(
		'stai-annur/hero-post-slider',
		array(
			'handle' => 'stai-annur-inter-font',
			'src'    => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
		)
	);

	wp_enqueue_block_style(
		'stai-annur/hero-card-slider',
		array(
			'handle' => 'stai-annur-inter-font',
			'src'    => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
		)
	);

	wp_enqueue_block_style(
		'stai-annur/hero-card-slider-2',
		array(
			'handle' => 'stai-annur-inter-font',
			'src'    => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
		)
	);
}
add_action( 'init', 'stai_annur_register_blocks' );

/**
 * Load shared image-source controls in the block editor.
 *
 * @return void
 */
function stai_annur_enqueue_image_controls() {
	$script_path = __DIR__ . '/assets/js/image-controls.js';

	wp_enqueue_script(
		'stai-annur-image-controls',
		plugin_dir_url( __FILE__ ) . 'assets/js/image-controls.js',
		array( 'wp-block-editor', 'wp-components', 'wp-compose', 'wp-element', 'wp-hooks', 'wp-i18n' ),
		filemtime( $script_path ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'stai_annur_enqueue_image_controls' );

/**
 * Load the plugin's single-post assets.
 *
 * @return void
 */
function stai_annur_enqueue_single_post_assets() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$plugin_url  = plugin_dir_url( __FILE__ );
	$plugin_path = plugin_dir_path( __FILE__ );

	wp_enqueue_style(
		'stai-annur-single-post',
		$plugin_url . 'assets/css/single-post.css',
		array(),
		filemtime( $plugin_path . 'assets/css/single-post.css' )
	);

	wp_enqueue_script(
		'stai-annur-single-post',
		$plugin_url . 'assets/js/single-post.js',
		array(),
		filemtime( $plugin_path . 'assets/js/single-post.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'stai_annur_enqueue_single_post_assets' );

/**
 * Use the plugin template for standard blog posts.
 *
 * @param string $template Current theme template.
 * @return string
 */
function stai_annur_single_post_template( $template ) {
	if ( ! is_singular( 'post' ) ) {
		return $template;
	}

	$plugin_template = plugin_dir_path( __FILE__ ) . 'templates/single-post.php';

	if ( file_exists( $plugin_template ) ) {
		return $plugin_template;
	}

	return $template;
}
add_filter( 'template_include', 'stai_annur_single_post_template', 99 );
