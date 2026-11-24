<?php
/**
 * Server-side rendering for the Hero Card Slider block.
 *
 * @var array $attributes Block attributes.
 */

$attributes = wp_parse_args(
	$attributes,
	array(
		'title'     => 'Keunggulan Kami',
		'slides'    => array(),
		'imageSize' => 'thumbnail',
		'useCustomImageSize' => false,
		'customImageWidth'   => 147,
		'customImageHeight'  => 98,
	)
);

$image_size = stai_annur_sanitize_image_size( $attributes['imageSize'] );
$custom_image_width = ! empty( $attributes['useCustomImageSize'] ) ? $attributes['customImageWidth'] : 0;
$custom_image_height = ! empty( $attributes['useCustomImageSize'] ) ? $attributes['customImageHeight'] : 0;
$slides = array();

if ( is_array( $attributes['slides'] ) ) {
	foreach ( $attributes['slides'] as $slide ) {
		if ( ! is_array( $slide ) ) {
			continue;
		}

		$image_url = isset( $slide['imageUrl'] ) && is_string( $slide['imageUrl'] ) ? esc_url( $slide['imageUrl'] ) : '';
		$image_id  = isset( $slide['imageId'] ) ? absint( $slide['imageId'] ) : 0;
		$name      = isset( $slide['name'] ) && is_scalar( $slide['name'] ) ? sanitize_text_field( (string) $slide['name'] ) : '';
		$detail    = isset( $slide['detail'] ) && is_scalar( $slide['detail'] ) ? sanitize_text_field( (string) $slide['detail'] ) : '';
		$alt       = isset( $slide['alt'] ) && is_scalar( $slide['alt'] ) ? sanitize_text_field( (string) $slide['alt'] ) : '';
		$details   = array_filter( array_map( 'trim', explode( '|', $detail ) ), 'strlen' );

		$slides[] = array(
			'imageUrl' => $image_url,
			'imageId'  => $image_id,
			'name'     => $name,
			'detail'   => $details,
			'alt'      => $alt ? $alt : $name,
		);
	}
}

$slide_count = count( $slides );

if ( $slide_count > 1 ) {
	wp_enqueue_script(
		'stai-annur-hero-card-slider',
		plugins_url( 'view.js', __FILE__ ),
		array(),
		filemtime( __DIR__ . '/view.js' ),
		true
	);
}
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'anur-benefits-widget' ) ); ?> data-anur-benefits-slider>
	<div class="anur-benefits-header">
		<div class="anur-benefits-heading">
			<?php if ( ! empty( $attributes['title'] ) ) : ?>
				<h2 class="anur-benefits-title"><?php echo esc_html( $attributes['title'] ); ?></h2>
			<?php endif; ?>
		</div>

		<?php if ( $slide_count > 1 ) : ?>
			<div class="anur-benefits-controls">
				<button class="anur-benefits-arrow anur-benefits-prev" type="button" disabled aria-label="<?php esc_attr_e( 'Keunggulan sebelumnya', 'stai-annur' ); ?>">
					<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M19 12H5m7-7-7 7 7 7" /></svg>
				</button>
				<button class="anur-benefits-arrow anur-benefits-next" type="button" aria-label="<?php esc_attr_e( 'Keunggulan berikutnya', 'stai-annur' ); ?>">
					<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M5 12h14m-7-7 7 7-7 7" /></svg>
				</button>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( $slide_count > 0 ) : ?>
		<div class="anur-benefits-slider">
			<div class="anur-benefits-track">
				<?php foreach ( $slides as $index => $slide ) : ?>
					<div class="anur-benefit-slide" aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
						<div class="anur-benefit-card">
							<div class="anur-benefit-icon">
								<?php if ( $slide['imageUrl'] ) : ?>
									<?php
									echo stai_annur_get_responsive_image_markup(
										$slide['imageId'],
										$slide['imageUrl'],
										$image_size,
										array(
											'alt'       => $slide['alt'],
											'loading'   => 0 === $index ? 'eager' : 'lazy',
											'decoding'  => 'async',
											'draggable' => 'false',
											'sizes'     => '58px',
										),
										array(),
										$custom_image_width,
										$custom_image_height
									); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
								<?php else : ?>
									<span class="anur-benefit-image-placeholder"><?php esc_html_e( 'Ikon belum dipilih', 'stai-annur' ); ?></span>
								<?php endif; ?>
							</div>

							<div class="anur-benefit-info">
								<div class="anur-benefit-name"><?php echo esc_html( $slide['name'] ); ?></div>
								<?php if ( $slide['detail'] ) : ?>
									<div class="anur-benefit-detail">
										<?php foreach ( $slide['detail'] as $detail_index => $detail ) : ?>
											<?php if ( $detail_index > 0 ) : ?>
												<b aria-hidden="true">•</b>
											<?php endif; ?>
											<span><?php echo esc_html( $detail ); ?></span>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>

							<div class="anur-benefit-check" aria-hidden="true">
								<svg viewBox="0 0 24 24" focusable="false"><path d="m5 12 4 4L19 6" /></svg>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php else : ?>
		<p class="anur-benefit-empty"><?php esc_html_e( 'Tambahkan kartu keunggulan dari pengaturan blok.', 'stai-annur' ); ?></p>
	<?php endif; ?>
</section>
