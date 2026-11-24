<?php
/**
 * Server-side rendering for the Hero Card Slider 2 block.
 *
 * @var array $attributes Block attributes.
 */

$attributes = wp_parse_args(
	$attributes,
	array(
		'title'    => 'Program Studi',
		'moreText' => 'See all',
		'moreUrl'  => '',
		'slides'   => array(),
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
		$code      = isset( $slide['code'] ) && is_scalar( $slide['code'] ) ? sanitize_text_field( (string) $slide['code'] ) : '';
		$alt       = isset( $slide['alt'] ) && is_scalar( $slide['alt'] ) ? sanitize_text_field( (string) $slide['alt'] ) : '';

		$slides[] = array(
			'imageUrl' => $image_url,
			'name'     => $name,
			'code'     => $code,
			'alt'      => $alt ? $alt : $name,
		);
	}
}
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'stai-program-study-widget' ) ); ?>>
	<header class="stai-program-study-header">
		<?php if ( ! empty( $attributes['title'] ) ) : ?>
			<h2 class="stai-program-study-title"><?php echo esc_html( $attributes['title'] ); ?></h2>
		<?php endif; ?>

		<?php if ( ! empty( $attributes['moreUrl'] ) && ! empty( $attributes['moreText'] ) ) : ?>
			<a class="stai-program-study-more" href="<?php echo esc_url( $attributes['moreUrl'] ); ?>">
				<?php echo esc_html( $attributes['moreText'] ); ?>
			</a>
		<?php endif; ?>
	</header>

	<?php if ( $slides ) : ?>
		<div class="stai-program-study-slider" data-program-study-slider>
			<?php foreach ( $slides as $slide ) : ?>
				<article class="stai-program-study-card">
					<?php if ( $slide['imageUrl'] ) : ?>
						<img src="<?php echo esc_url( $slide['imageUrl'] ); ?>" alt="<?php echo esc_attr( $slide['alt'] ); ?>" loading="lazy" decoding="async" draggable="false">
					<?php else : ?>
						<span class="stai-program-study-image-placeholder"><?php esc_html_e( 'Gambar belum dipilih', 'stai-annur' ); ?></span>
					<?php endif; ?>

					<div class="stai-program-study-info">
						<div class="stai-program-study-name"><?php echo esc_html( $slide['name'] ); ?></div>
						<?php if ( $slide['code'] ) : ?>
							<div class="stai-program-study-code">
								<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="m2 8 10-5 10 5-10 5L2 8Z" /><path d="M6 10v5c3.5 2.5 8.5 2.5 12 0v-5M22 8v6" /></svg>
								<span><?php echo esc_html( $slide['code'] ); ?></span>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<p class="stai-program-study-empty"><?php esc_html_e( 'Tambahkan program studi dari pengaturan blok.', 'stai-annur' ); ?></p>
	<?php endif; ?>
</section>
