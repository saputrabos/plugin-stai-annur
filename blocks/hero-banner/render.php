<?php
/**
 * Server-side rendering for the Hero Banner block.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Saved block content.
 * @var WP_Block $block      Parsed block instance.
 */

$attributes = wp_parse_args(
	$attributes,
	array(
		'label'           => 'Perguruan Tinggi Islam',
		'title'           => 'STAI An-Nur',
		'titleAccent'     => 'KH. M. Badruddin',
		'subtitle'        => 'Kampus yang memadukan pendidikan akademik, nilai keislaman, dan pembentukan karakter untuk mempersiapkan generasi masa depan.',
		'statNumber'      => '6+',
		'statDescription' => 'Keunggulan Kuliah di Kampus Kami',
		'backgroundUrl'   => '',
		'brochureText'    => 'Unduh Brosur',
		'brochureUrl'     => '',
		'contactUrl'      => '',
	)
);

$title       = sanitize_text_field( $attributes['title'] );
$title_accent = sanitize_text_field( $attributes['titleAccent'] );
$share_text  = trim( $title . ' ' . $title_accent );
$share_url   = 'https://wa.me/?text=' . rawurlencode( $share_text . "\n\n" . get_permalink() );
$style       = '';

if ( ! empty( $attributes['backgroundUrl'] ) ) {
	$style = 'background-image: url(' . esc_url( $attributes['backgroundUrl'] ) . ');';
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'anur-hero-widget' ) ); ?>>
	<div class="anur-hero-wrapper">
		<div class="anur-hero-card" <?php echo $style ? 'style="' . esc_attr( $style ) . '"' : ''; ?>>
			<div class="anur-hero-label">
				<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M3 21h18M5 18h14M6 18V8l6-4 6 4v10M9 10h.01M12 10h.01M15 10h.01M9 14h.01M12 14h.01M15 14h.01M11 21v-4h2v4" /></svg>
				<span><?php echo esc_html( $attributes['label'] ); ?></span>
			</div>

			<h2 class="anur-hero-title">
				<?php echo esc_html( $title ); ?><br>
				<span><?php echo esc_html( $title_accent ); ?></span>
			</h2>

			<p class="anur-hero-subtitle"><?php echo esc_html( $attributes['subtitle'] ); ?></p>

			<div class="anur-hero-highlight">
				<span class="anur-hero-number"><?php echo esc_html( $attributes['statNumber'] ); ?></span>
				<span class="anur-hero-description"><?php echo nl2br( esc_html( $attributes['statDescription'] ) ); ?></span>
			</div>

			<div class="anur-hero-actions">
				<?php if ( ! empty( $attributes['brochureUrl'] ) ) : ?>
				<a
					class="anur-hero-brochure"
					href="<?php echo esc_url( $attributes['brochureUrl'] ); ?>"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php echo esc_attr( sprintf( __( '%s STAI An-Nur', 'stai-annur' ), $attributes['brochureText'] ) ); ?>"
				>
					<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zM14 2v6h6M8 13h8M8 17h8" /></svg>
					<span><?php echo esc_html( $attributes['brochureText'] ); ?></span>
				</a>
				<?php endif; ?>

				<a
					class="anur-hero-action"
					href="<?php echo esc_url( $share_url ); ?>"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php esc_attr_e( 'Bagikan melalui WhatsApp', 'stai-annur' ); ?>"
					title="<?php esc_attr_e( 'Bagikan melalui WhatsApp', 'stai-annur' ); ?>"
				>
					<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7Z" /></svg>
				</a>

				<?php if ( ! empty( $attributes['contactUrl'] ) ) : ?>
				<a
					class="anur-hero-action"
					href="<?php echo esc_url( $attributes['contactUrl'] ); ?>"
					aria-label="<?php esc_attr_e( 'Hubungi STAI An-Nur', 'stai-annur' ); ?>"
					title="<?php esc_attr_e( 'Hubungi STAI An-Nur', 'stai-annur' ); ?>"
				>
					<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 11.19 18a19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.09 3.18 2 2 0 0 1 4.08 1h3a2 2 0 0 1 2 1.72c.12.96.36 1.9.7 2.8a2 2 0 0 1-.45 2.11L8.06 8.94a16 16 0 0 0 6 6l1.31-1.27a2 2 0 0 1 2.11-.45c.9.34 1.84.58 2.8.7A2 2 0 0 1 22 16.92Z" /></svg>
				</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
