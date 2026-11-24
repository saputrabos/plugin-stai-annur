<?php
/**
 * Server-side rendering for the Hero Post Slider block.
 *
 * @var array $attributes Block attributes.
 */

$attributes = wp_parse_args(
	$attributes,
	array(
		'sectionTitle' => 'Artikel Terbaru',
		'postsToShow'  => 12,
	)
);

$posts_to_show = max( 1, min( 12, (int) $attributes['postsToShow'] ) );
$query         = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $posts_to_show,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
$post_count    = (int) $query->post_count;

if ( $post_count > 1 ) {
	wp_enqueue_script(
		'stai-annur-hero-post-slider',
		plugins_url( 'view.js', __FILE__ ),
		array(),
		'1.0.0',
		true
	);
}
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'anur-article-widget' ) ); ?>>
	<header class="anur-article-header">
		<div class="anur-article-heading">
			<h2 class="anur-article-title"><?php echo esc_html( $attributes['sectionTitle'] ); ?></h2>
		</div>

		<?php if ( $post_count > 1 ) : ?>
			<div class="anur-article-controls">
				<button class="anur-article-arrow anur-article-prev" type="button" aria-label="<?php esc_attr_e( 'Artikel sebelumnya', 'stai-annur' ); ?>" disabled>
					<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M19 12H5M12 19l-7-7 7-7" /></svg>
				</button>
				<button class="anur-article-arrow anur-article-next" type="button" aria-label="<?php esc_attr_e( 'Artikel berikutnya', 'stai-annur' ); ?>">
					<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M5 12h14M12 5l7 7-7 7" /></svg>
				</button>
			</div>
		<?php endif; ?>
	</header>

	<div class="anur-article-slider" data-anur-article-slider>
		<div class="anur-article-track">
			<?php if ( $query->have_posts() ) : ?>
				<?php while ( $query->have_posts() ) : ?>
					<?php
					$query->the_post();
					$post_id       = get_the_ID();
					$post_title    = get_the_title();
					$thumbnail_id  = get_post_thumbnail_id( $post_id );
					$thumbnail_url = $thumbnail_id ? get_the_post_thumbnail_url( $post_id, 'large' ) : '';
					$image_alt     = $thumbnail_id ? get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) : '';
					$categories    = get_the_category( $post_id );
					$category_name = 'Artikel';

					if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
						$category_name = $categories[0]->name;
					}

					if ( ! $image_alt ) {
						$image_alt = $post_title;
					}
					?>
					<a class="anur-article-card-link" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Baca: %s', 'stai-annur' ), $post_title ) ); ?>">
						<?php if ( $thumbnail_url ) : ?>
							<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" decoding="async" draggable="false">
						<?php else : ?>
							<span class="anur-article-no-image" aria-hidden="true">
								<svg viewBox="0 0 24 24" focusable="false"><path d="M4 5h16v14H4zM4 15l4-4 4 4 3-3 5 5M9 9h.01" /></svg>
							</span>
						<?php endif; ?>
						<span class="anur-article-info">
							<span class="anur-article-name"><?php echo esc_html( $post_title ); ?></span>
							<span class="anur-article-category">
								<svg aria-hidden="true" viewBox="0 0 24 24" focusable="false"><path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5" /></svg>
								<span><?php echo esc_html( $category_name ); ?></span>
							</span>
						</span>
					</a>
				<?php endwhile; ?>
			<?php else : ?>
				<div class="anur-article-error"><?php esc_html_e( 'Belum ada artikel.', 'stai-annur' ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
wp_reset_postdata();
