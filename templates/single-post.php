<?php
/**
 * Single post template.
 *
 * @package STAI_Annur
 */

get_header();
?>

<div id="anur-single-post" class="anur-single-post">
	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();

			$post_id       = get_the_ID();
			$post_title    = get_the_title();
			$post_url      = get_permalink();
			$raw_content   = get_post_field( 'post_content', $post_id );
			$word_count    = preg_match_all( '/[\p{L}\p{N}]+/u', wp_strip_all_tags( $raw_content ), $matches );
			$reading_time  = max( 1, (int) ceil( (int) $word_count / 200 ) );
			$categories    = get_the_category();
			$primary_category = ! empty( $categories ) ? $categories[0] : null;
			$share_text    = rawurlencode( $post_title . ' ' . $post_url );
			$encoded_url   = rawurlencode( $post_url );
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'anur-post' ); ?>>
				<header class="anur-post-hero">
					<div class="anur-container">
						<nav class="anur-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'stai-annur' ); ?>">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'stai-annur' ); ?></a>
							<span aria-hidden="true">/</span>
							<?php if ( $primary_category ) : ?>
								<a href="<?php echo esc_url( get_category_link( $primary_category ) ); ?>">
									<?php echo esc_html( $primary_category->name ); ?>
								</a>
							<?php else : ?>
								<span><?php esc_html_e( 'Artikel', 'stai-annur' ); ?></span>
							<?php endif; ?>
						</nav>

						<?php if ( $primary_category ) : ?>
							<a class="anur-category-badge" href="<?php echo esc_url( get_category_link( $primary_category ) ); ?>">
								<?php echo esc_html( $primary_category->name ); ?>
							</a>
						<?php endif; ?>

						<h1 class="anur-post-title"><?php echo esc_html( $post_title ); ?></h1>

						<div class="anur-post-meta">
							<div class="anur-meta-item">
								<span class="anur-meta-icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" focusable="false">
										<rect x="3" y="5" width="18" height="16" rx="2"></rect>
										<path d="M16 3v4M8 3v4M3 11h18"></path>
									</svg>
								</span>
								<span class="anur-meta-text">
									<small><?php esc_html_e( 'Publikasi', 'stai-annur' ); ?></small>
									<strong>
										<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
											<?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
										</time>
									</strong>
								</span>
							</div>
							<div class="anur-meta-item">
								<span class="anur-meta-icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" focusable="false">
										<circle cx="12" cy="12" r="9"></circle>
										<path d="M12 7v5l3 2"></path>
									</svg>
								</span>
								<span class="anur-meta-text">
									<small><?php esc_html_e( 'Waktu baca', 'stai-annur' ); ?></small>
									<strong><?php echo esc_html( sprintf( _n( '%d menit', '%d menit', $reading_time, 'stai-annur' ), $reading_time ) ); ?></strong>
								</span>
							</div>
						</div>
					</div>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="anur-featured-wrap">
						<figure class="anur-featured">
							<?php
							the_post_thumbnail(
								'large',
								array(
									'loading'       => 'eager',
									'fetchpriority' => 'high',
									'decoding'      => 'async',
									'sizes'         => '(max-width: 600px) calc(100vw - 28px), (max-width: 1220px) calc(100vw - 40px), 1180px',
								)
							);
							?>
						</figure>
					</div>
				<?php endif; ?>

				<div class="anur-main">
					<div class="anur-container">
						<div class="anur-content-grid">
							<div class="anur-content-column">
								<div class="anur-content entry-content">
									<?php
									the_content();

									wp_link_pages(
										array(
											'before' => '<nav class="anur-pages" aria-label="' . esc_attr__( 'Halaman artikel', 'stai-annur' ) . '">',
											'after'  => '</nav>',
										)
									);
									?>
								</div>

								<?php
								$post_tags = get_the_tags();
								if ( $post_tags ) :
									?>
									<div class="anur-tags" aria-label="<?php esc_attr_e( 'Tag artikel', 'stai-annur' ); ?>">
										<?php foreach ( $post_tags as $tag ) : ?>
											<a class="anur-tag" href="<?php echo esc_url( get_tag_link( $tag ) ); ?>">
												<?php echo esc_html( $tag->name ); ?>
											</a>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>

								<div class="anur-share">
									<span class="anur-share-label"><?php esc_html_e( 'Bagikan artikel', 'stai-annur' ); ?></span>
									<div class="anur-share-buttons">
										<a class="anur-share-btn" href="<?php echo esc_url( 'https://api.whatsapp.com/send?text=' . $share_text ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Bagikan ke WhatsApp', 'stai-annur' ); ?>">WA</a>
										<a class="anur-share-btn" href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Bagikan ke Facebook', 'stai-annur' ); ?>">f</a>
										<a class="anur-share-btn" href="<?php echo esc_url( 'https://twitter.com/intent/tweet?url=' . $encoded_url . '&text=' . rawurlencode( $post_title ) ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Bagikan ke X', 'stai-annur' ); ?>">X</a>
										<a class="anur-share-btn" href="<?php echo esc_url( 'https://t.me/share/url?url=' . $encoded_url . '&text=' . rawurlencode( $post_title ) ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Bagikan ke Telegram', 'stai-annur' ); ?>">TG</a>
										<a class="anur-share-btn" href="<?php echo esc_url( 'mailto:?subject=' . rawurlencode( $post_title ) . '&body=' . $share_text ); ?>" aria-label="<?php esc_attr_e( 'Bagikan melalui email', 'stai-annur' ); ?>">@</a>
										<button class="anur-share-btn" type="button" data-anur-copy-link="<?php echo esc_url( $post_url ); ?>" aria-label="<?php esc_attr_e( 'Salin tautan artikel', 'stai-annur' ); ?>">URL</button>
									</div>
									<span class="anur-share-status" role="status" aria-live="polite"></span>
								</div>
							</div>

							<aside class="anur-sidebar" aria-label="<?php esc_attr_e( 'Artikel terbaru', 'stai-annur' ); ?>">
								<div class="anur-sidebar-card">
									<div class="anur-sidebar-title">
										<span class="anur-title-line" aria-hidden="true"></span>
										<h2><?php esc_html_e( 'Artikel Terbaru', 'stai-annur' ); ?></h2>
									</div>

									<?php
									$latest_posts = new WP_Query(
										array(
											'post_type'           => 'post',
											'posts_per_page'      => 5,
											'post__not_in'        => array( $post_id ),
											'post_status'         => 'publish',
											'ignore_sticky_posts' => true,
											'no_found_rows'       => true,
										)
									);

									if ( $latest_posts->have_posts() ) :
										?>
										<div class="anur-latest-list">
											<?php
											while ( $latest_posts->have_posts() ) :
												$latest_posts->the_post();
												?>
												<article class="anur-latest-item">
													<a class="anur-latest-image" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
														<?php if ( has_post_thumbnail() ) : ?>
															<?php the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy' ) ); ?>
														<?php endif; ?>
													</a>
													<div>
														<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
														<time class="anur-latest-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
															<?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
														</time>
													</div>
												</article>
											<?php endwhile; ?>
										</div>
										<?php
										wp_reset_postdata();
									else :
										?>
										<p><?php esc_html_e( 'Belum ada artikel lain.', 'stai-annur' ); ?></p>
									<?php endif; ?>
								</div>
							</aside>
						</div>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<div class="anur-container anur-not-found">
			<p><?php esc_html_e( 'Artikel tidak ditemukan.', 'stai-annur' ); ?></p>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
