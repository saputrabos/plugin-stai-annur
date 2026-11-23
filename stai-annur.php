<?php
/**
 * Plugin Name: STAI An-Nur
 * Description: Blok Gutenberg STAI An-Nur, dimulai dengan Hero Banner.
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
 * Register the Hero Banner block.
 *
 * @return void
 */
function stai_annur_register_blocks() {
	register_block_type( __DIR__ . '/blocks/hero-banner' );

	wp_enqueue_block_style(
		'stai-annur/hero-banner',
		array(
			'handle' => 'stai-annur-inter-font',
			'src'    => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
		)
	);
}
add_action( 'init', 'stai_annur_register_blocks' );
