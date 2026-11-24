<?php
/**
 * Responsive image helpers for STAI An-Nur blocks.
 *
 * @package STAI_Annur
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return a supported WordPress image size.
 *
 * @param mixed $size Requested image size.
 * @return string
 */
function stai_annur_sanitize_image_size( $size ) {
	$allowed_sizes = array( 'thumbnail', 'medium', 'medium_large', 'large', 'full' );

	return is_string( $size ) && in_array( $size, $allowed_sizes, true ) ? $size : 'medium_large';
}

/**
 * Resize a supported Unsplash URL to the requested dimensions.
 *
 * @param string $url   Image URL.
 * @param int    $width  Requested image width.
 * @param int    $height Requested image height.
 * @return string
 */
function stai_annur_unsplash_image_url( $url, $width, $height = 0 ) {
	$parts = wp_parse_url( $url );

	if ( empty( $parts['host'] ) || empty( $parts['path'] ) ) {
		return '';
	}

	$host = strtolower( $parts['host'] );

	if ( ! in_array( $host, array( 'images.unsplash.com', 'plus.unsplash.com' ), true ) ) {
		return '';
	}

	$base_url = ( isset( $parts['scheme'] ) ? $parts['scheme'] : 'https' ) . '://' . $host . $parts['path'];
	$query    = array();

	if ( ! empty( $parts['query'] ) ) {
		wp_parse_str( $parts['query'], $query );
	}

	$query['w']    = absint( $width );
	$query['auto'] = 'format';

	if ( $height ) {
		$query['h'] = absint( $height );
	}

	if ( empty( $query['fit'] ) ) {
		$query['fit'] = 'crop';
	}

	return add_query_arg( $query, $base_url );
}

/**
 * Generate responsive markup for an attachment or a supported Unsplash URL.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $url           Fallback image URL.
 * @param string $size          Requested WordPress image size.
 * @param array  $attributes    Image HTML attributes.
 * @param array  $source_sizes  Responsive variants for an external WordPress image.
 * @param int    $custom_width  Optional custom source width.
 * @param int    $custom_height Optional custom source height.
 * @return string
 */
function stai_annur_get_responsive_image_markup( $attachment_id, $url, $size, $attributes = array(), $source_sizes = array(), $custom_width = 0, $custom_height = 0 ) {
	$size          = stai_annur_sanitize_image_size( $size );
	$attachment_id = absint( $attachment_id );
	$custom_width  = absint( $custom_width );
	$custom_height = absint( $custom_height );

	if ( ! $custom_width || ! $custom_height || $custom_width > 3000 || $custom_height > 3000 ) {
		$custom_width  = 0;
		$custom_height = 0;
	}

	if ( $attachment_id && wp_attachment_is_image( $attachment_id ) ) {
		if ( $custom_width && $custom_height ) {
			$attributes['sizes'] = $custom_width . 'px';

			return wp_get_attachment_image( $attachment_id, array( $custom_width, $custom_height ), false, $attributes );
		}

		return wp_get_attachment_image( $attachment_id, $size, false, $attributes );
	}

	$url = esc_url_raw( $url );

	if ( ! $url ) {
		return '';
	}

	$widths = array(
		'thumbnail'    => array( 150, 300 ),
		'medium'       => array( 300, 600 ),
		'medium_large' => array( 480, 768, 1024 ),
		'large'        => array( 640, 1024, 1440 ),
		'full'         => array( 768, 1170, 1600 ),
	);
	$srcset     = array();
	$source_url = '';

	if ( is_array( $source_sizes ) && $source_sizes ) {
		$available_sizes = array();
		$full_ratio      = 0;

		if ( isset( $source_sizes['full']['width'], $source_sizes['full']['height'], $source_sizes['full']['url'] ) ) {
			$full_ratio = (float) $source_sizes['full']['width'] / max( 1, (int) $source_sizes['full']['height'] );
		}

		foreach ( $source_sizes as $name => $source_size ) {
			if ( ! is_array( $source_size ) || empty( $source_size['url'] ) || empty( $source_size['width'] ) ) {
				continue;
			}

			$source_width  = absint( $source_size['width'] );
			$source_height = isset( $source_size['height'] ) ? absint( $source_size['height'] ) : 0;

			$source_url_candidate = esc_url_raw( $source_size['url'] );

			if ( ! $source_width || ! $source_url_candidate ) {
				continue;
			}

			if ( $full_ratio && $source_height && 'full' !== $name ) {
				$source_ratio = (float) $source_width / $source_height;

				if ( abs( $source_ratio - $full_ratio ) / $full_ratio > 0.08 ) {
					continue;
				}
			}

			$available_sizes[ $name ] = array(
				'url'    => $source_url_candidate,
				'width'  => $source_width,
				'height' => $source_height,
			);
		}

		$unique_sizes = array();

		foreach ( $available_sizes as $source_size ) {
			$unique_sizes[ $source_size['width'] ] = $source_size;
		}

		ksort( $unique_sizes, SORT_NUMERIC );

		foreach ( $unique_sizes as $source_size ) {
			$srcset[] = esc_url( $source_size['url'] ) . ' ' . $source_size['width'] . 'w';
		}

		if ( ! $custom_width && isset( $available_sizes[ $size ] ) ) {
			$source_url = $available_sizes[ $size ]['url'];
		} elseif ( ! $custom_width && 'full' === $size && isset( $source_sizes['full']['url'] ) ) {
			$source_url = esc_url_raw( $source_sizes['full']['url'] );
		} else {
			$target_widths = array(
				'thumbnail'    => 150,
				'medium'       => 300,
				'medium_large' => 768,
				'large'        => 1024,
				'full'         => PHP_INT_MAX,
			);
			$target_width = $custom_width ? $custom_width : $target_widths[ $size ];
			$selected_width = 0;

			foreach ( $unique_sizes as $source_size ) {
				if ( $source_size['width'] <= $target_width && $source_size['width'] > $selected_width ) {
					$source_url    = $source_size['url'];
					$selected_width = $source_size['width'];
				}
			}

			if ( ! $source_url && $unique_sizes ) {
				$source_size = reset( $unique_sizes );
				$source_url  = $source_size['url'];
			}
		}
	}

	if ( empty( $source_url ) ) {
		if ( $custom_width && $custom_height ) {
			$custom_widths = array_unique(
				array(
					$custom_width,
					min( 3000, $custom_width * 2 ),
					min( 3000, $custom_width * 3 ),
				)
			);

			foreach ( $custom_widths as $width ) {
				$height  = (int) round( $custom_height * $width / $custom_width );
				$source  = stai_annur_unsplash_image_url( $url, $width, $height );

				if ( $source ) {
					$srcset[] = esc_url( $source ) . ' ' . absint( $width ) . 'w';
				}
			}

			$source_url = stai_annur_unsplash_image_url( $url, $custom_width, $custom_height );
		} else {
			foreach ( $widths[ $size ] as $width ) {
				$source = stai_annur_unsplash_image_url( $url, $width );

				if ( $source ) {
					$srcset[] = esc_url( $source ) . ' ' . absint( $width ) . 'w';
				}
			}

			$source_width = $widths[ $size ][0];
			$source_url   = 'full' === $size ? $url : stai_annur_unsplash_image_url( $url, $source_width );
		}
	}

	if ( ! $source_url ) {
		$source_url = $url;
	}

	$attributes = wp_parse_args(
		$attributes,
		array(
			'alt'      => '',
			'decoding' => 'async',
		)
	);

	$attributes['src'] = esc_url( $source_url );

	if ( $custom_width && $custom_height ) {
		$attributes['sizes'] = $custom_width . 'px';
	}

	if ( $srcset ) {
		$attributes['srcset'] = implode( ', ', $srcset );
	}

	if ( empty( $attributes['sizes'] ) ) {
		$attributes['sizes'] = '100vw';
	}

	$html = '<img';

	foreach ( $attributes as $name => $value ) {
		if ( null === $value || false === $value || ! preg_match( '/^[a-zA-Z_:][a-zA-Z0-9:._-]*$/', $name ) ) {
			continue;
		}

		$html .= ' ' . $name . '="' . esc_attr( is_bool( $value ) ? ( $value ? 'true' : 'false' ) : $value ) . '"';
	}

	return $html . '>';
}
