<?php
/**
 * Server-side rendering for the Hero Slider block.
 *
 * @var array $attributes Block attributes.
 */

$attributes = wp_parse_args(
	$attributes,
	array(
		'slides'         => array(),
		'addressLine1'   => 'Jalan Diponegoro IV/262, Bululawang,',
		'addressLine2'   => 'Kabupaten Malang, Jawa Timur 65171',
		'mapUrl'         => '',
		'autoplay'       => true,
		'autoplayDelay'  => 3500,
	)
);

$slides = array();

if ( is_array( $attributes['slides'] ) ) {
	foreach ( $attributes['slides'] as $slide ) {
		if ( ! is_array( $slide ) ) {
			continue;
		}

		$image_url = isset( $slide['imageUrl'] ) && is_string( $slide['imageUrl'] ) ? esc_url( $slide['imageUrl'] ) : '';
		$name      = isset( $slide['name'] ) && is_scalar( $slide['name'] ) ? sanitize_text_field( (string) $slide['name'] ) : '';
		$location  = isset( $slide['location'] ) && is_scalar( $slide['location'] ) ? sanitize_text_field( (string) $slide['location'] ) : '';
		$alt       = isset( $slide['alt'] ) && is_scalar( $slide['alt'] ) ? sanitize_text_field( (string) $slide['alt'] ) : '';

		$slides[] = array(
			'imageUrl' => $image_url,
			'name'     => $name,
			'location' => $location,
			'alt'      => $alt ? $alt : $name,
		);
	}
}

$slide_count = count( $slides );
$active_index = max( 0, min( 1, $slide_count - 1 ) );
$delay        = isset( $attributes['autoplayDelay'] ) ? max( 2000, min( 10000, (int) $attributes['autoplayDelay'] ) ) : 3500;
$autoplay     = ! empty( $attributes['autoplay'] ) && $slide_count > 1;

if ( $slide_count > 1 ) {
	wp_enqueue_script(
		'stai-annur-hero-slider',
		plugins_url( 'view.js', __FILE__ ),
		array(),
		'1.0.0',
		true
	);
}
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'anur-building-widget' ) ); ?>>
	<?php if ( $slide_count > 0 ) : ?>
		<div
			class="anur-building-area"
			data-anur-building-slider
			data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
			data-autoplay-delay="<?php echo esc_attr( (string) $delay ); ?>"
		>
			<?php if ( $slide_count > 1 ) : ?>
				<button type="button" class="anur-building-nav anur-building-prev" aria-label="<?php esc_attr_e( 'Slide sebelumnya', 'stai-annur' ); ?>">
					<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="m15 18-6-6 6-6" /></svg>
				</button>
			<?php endif; ?>

			<div class="anur-building-wrapper" role="region" aria-roledescription="<?php esc_attr_e( 'carousel', 'stai-annur' ); ?>" aria-label="<?php esc_attr_e( 'Galeri gedung STAI An-Nur', 'stai-annur' ); ?>">
				<div class="anur-building-slider">
					<?php foreach ( $slides as $index => $slide ) : ?>
						<button
							type="button"
							class="anur-building-card<?php echo $index === $active_index ? ' anur-building-active' : ''; ?>"
							aria-pressed="<?php echo $index === $active_index ? 'true' : 'false'; ?>"
							aria-label="<?php echo esc_attr( sprintf( __( '%1$s, %2$s', 'stai-annur' ), $slide['name'], $slide['location'] ) ); ?>"
						>
							<?php if ( $slide['imageUrl'] ) : ?>
								<img src="<?php echo esc_url( $slide['imageUrl'] ); ?>" alt="<?php echo esc_attr( $slide['alt'] ); ?>" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>" decoding="async">
							<?php else : ?>
								<span class="anur-building-image-placeholder"><?php esc_html_e( 'Gambar gedung belum dipilih', 'stai-annur' ); ?></span>
							<?php endif; ?>
							<span class="anur-building-overlay" aria-hidden="true"></span>
							<span class="anur-building-info">
								<span class="anur-building-name"><?php echo esc_html( $slide['name'] ); ?></span>
								<?php if ( $slide['location'] ) : ?>
									<span class="anur-building-location">
										<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z" /><circle cx="12" cy="10" r="2.5" /></svg>
										<?php echo esc_html( $slide['location'] ); ?>
									</span>
								<?php endif; ?>
							</span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( $slide_count > 1 ) : ?>
				<button type="button" class="anur-building-nav anur-building-next" aria-label="<?php esc_attr_e( 'Slide berikutnya', 'stai-annur' ); ?>">
					<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="m9 18 6-6-6-6" /></svg>
				</button>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $attributes['addressLine1'] ) || ! empty( $attributes['addressLine2'] ) ) : ?>
		<div class="anur-campus-location">
			<div class="anur-location-address">
				<?php if ( ! empty( $attributes['addressLine1'] ) ) : ?>
					<strong><?php echo esc_html( $attributes['addressLine1'] ); ?></strong>
				<?php endif; ?>
				<?php if ( ! empty( $attributes['addressLine1'] ) && ! empty( $attributes['addressLine2'] ) ) : ?>
					<br>
				<?php endif; ?>
				<?php echo esc_html( $attributes['addressLine2'] ); ?>
			</div>

			<?php if ( ! empty( $attributes['mapUrl'] ) ) : ?>
				<a
					class="anur-location-button"
					href="<?php echo esc_url( $attributes['mapUrl'] ); ?>"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php esc_attr_e( 'Lihat lokasi STAI An-Nur di Google Maps', 'stai-annur' ); ?>"
				>
					<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z" /><circle cx="12" cy="10" r="2.5" /></svg>
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
