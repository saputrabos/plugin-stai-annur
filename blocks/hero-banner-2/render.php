<?php
/**
 * Server-side rendering for the Hero Banner 2 block.
 *
 * @var array $attributes Block attributes.
 */

$attributes = wp_parse_args(
	$attributes,
	array(
		'backgroundUrl' => '',
		'backgroundId'  => 0,
		'backgroundSizes' => array(),
		'imageSize'     => 'large',
		'useCustomImageSize' => false,
		'customImageWidth'   => 147,
		'customImageHeight'  => 98,
		'label'         => 'Pendaftaran Mahasiswa Baru',
		'titleFirst'    => 'Daftar Sekarang & Wujudkan',
		'titleSecond'   => 'Masa Depan Gemilang',
		'ctaText'       => 'Daftar Sekarang Di sini!',
		'ctaUrl'        => '',
		'ctaAriaLabel'  => 'Lihat informasi alur pendaftaran mahasiswa',
	)
);

$background_image = stai_annur_get_responsive_image_markup(
	absint( $attributes['backgroundId'] ),
	$attributes['backgroundUrl'],
	$attributes['imageSize'],
	array(
		'class'    => 'anur-registration-background',
		'alt'      => '',
		'loading'  => 'lazy',
		'decoding' => 'async',
		'sizes'    => '(max-width: 600px) 100vw, (max-width: 1280px) 100vw, 1280px',
	),
	$attributes['backgroundSizes'],
	! empty( $attributes['useCustomImageSize'] ) ? $attributes['customImageWidth'] : 0,
	! empty( $attributes['useCustomImageSize'] ) ? $attributes['customImageHeight'] : 0
);
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'anur-registration-hero' ) ); ?>>
	<div class="anur-registration-card">
		<?php echo $background_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="anur-registration-content">
			<?php if ( ! empty( $attributes['label'] ) ) : ?>
				<div class="anur-registration-label"><?php echo esc_html( $attributes['label'] ); ?></div>
			<?php endif; ?>

			<h2 class="anur-registration-title">
				<?php echo esc_html( $attributes['titleFirst'] ); ?><br>
				<?php echo esc_html( $attributes['titleSecond'] ); ?>
				<span class="anur-registration-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" focusable="false"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M19 8v6M22 11h-6" /></svg>
				</span>
			</h2>

			<?php if ( ! empty( $attributes['ctaUrl'] ) ) : ?>
				<a
					href="<?php echo esc_url( $attributes['ctaUrl'] ); ?>"
					class="anur-registration-button"
					aria-label="<?php echo esc_attr( $attributes['ctaAriaLabel'] ); ?>"
				>
					<span><?php echo esc_html( $attributes['ctaText'] ); ?></span>
					<span class="anur-registration-arrow" aria-hidden="true">
						<svg viewBox="0 0 24 24" focusable="false"><path d="M5 12h14M12 5l7 7-7 7" /></svg>
					</span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>
