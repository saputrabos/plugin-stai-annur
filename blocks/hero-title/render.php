<?php
/**
 * Server-side rendering for the Hero Title block.
 *
 * @var array $attributes Block attributes.
 */

$attributes = wp_parse_args(
	$attributes,
	array(
		'label'           => 'Raih Sukses Bersama Kami',
		'titleFirst'      => 'Kampus Generasi',
		'titleSecond'     => 'Shalihin dan Shalihat',
		'description'     => 'Membentuk generasi unggul berilmu, berakhlak, berkarakter, dan siap menghadapi tantangan masa depan dengan penuh percaya diri.',
		'showLabel'       => true,
		'showTitle'       => true,
		'showDescription' => true,
	)
);
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'anur-campus-intro' ) ); ?>>
	<div class="anur-campus-shape anur-campus-shape-1" aria-hidden="true"></div>
	<div class="anur-campus-shape anur-campus-shape-2" aria-hidden="true"></div>

	<div class="anur-campus-content">
		<?php if ( $attributes['showLabel'] ) : ?>
			<div class="anur-campus-label">
				<span class="anur-campus-label-star" aria-hidden="true">✦</span>
				<span><?php echo esc_html( $attributes['label'] ); ?></span>
			</div>
		<?php endif; ?>

		<?php if ( $attributes['showTitle'] ) : ?>
			<h2 class="anur-campus-title">
				<span class="anur-campus-title-dark"><?php echo esc_html( $attributes['titleFirst'] ); ?></span>
				<span class="anur-campus-title-highlight"><br><?php echo esc_html( $attributes['titleSecond'] ); ?></span>
			</h2>
		<?php endif; ?>

		<?php if ( $attributes['showDescription'] ) : ?>
			<p class="anur-campus-description"><?php echo esc_html( $attributes['description'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
