<?php
/**
 * Single video template.
 *
 * @package NovaTube
 */

get_header();

while ( have_posts() ) :
	the_post();

	$novatube_post_id = get_the_ID();
	$novatube_views   = novatube_get_views( $novatube_post_id );
	$novatube_duration = novatube_get_duration( $novatube_post_id );
	$novatube_rating   = novatube_get_rating( $novatube_post_id );
	$novatube_permalink = get_permalink();
	$novatube_share_url = rawurlencode( $novatube_permalink );
	$novatube_share_text = rawurlencode( get_the_title() );
	$novatube_categories = get_the_terms( $novatube_post_id, 'video_category' );
	$novatube_content    = get_the_content();
	$novatube_has_body   = '' !== trim( wp_strip_all_tags( $novatube_content ) );
	$novatube_related    = novatube_get_related_videos( $novatube_post_id, 8 );
	?>
	<main id="main" class="site-main">
		<div class="container section">
			<?php novatube_breadcrumbs(); ?>

			<div class="layout layout--sidebar layout--single">
				<div class="layout__main">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'video-single' ); ?>>
						<div class="player-frame">
							<?php echo novatube_get_video_embed( $novatube_post_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized player HTML. ?>
						</div>

						<div class="video-head">
							<div class="video-head__main">
								<?php if ( ! empty( $novatube_categories ) && ! is_wp_error( $novatube_categories ) ) : ?>
									<a class="video-category" href="<?php echo esc_url( get_term_link( $novatube_categories[0] ) ); ?>"><?php echo esc_html( $novatube_categories[0]->name ); ?></a>
								<?php endif; ?>

								<h1 class="video-title"><?php the_title(); ?></h1>

								<div class="video-stats">
									<span class="stat"><?php novatube_the_icon( 'eye', 16 ); ?><?php echo esc_html( novatube_format_count( $novatube_views ) . ' ' . __( 'views', 'novatube' ) ); ?></span>
									<span class="stat"><?php novatube_the_icon( 'calendar', 16 ); ?><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
									<?php if ( $novatube_duration ) : ?>
										<span class="stat"><?php novatube_the_icon( 'clock', 16 ); ?><?php echo esc_html( $novatube_duration ); ?></span>
									<?php endif; ?>
									<?php if ( novatube_is_hd( $novatube_post_id ) ) : ?>
										<span class="badge badge--hd"><?php esc_html_e( 'HD', 'novatube' ); ?></span>
									<?php endif; ?>
									<?php if ( $novatube_rating['count'] > 0 ) : ?>
										<span class="stat stat--rating"><?php novatube_the_icon( 'star', 15 ); ?><?php echo esc_html( number_format_i18n( $novatube_rating['rating'], 1 ) ); ?><span class="stat__muted">/ 5</span></span>
									<?php endif; ?>
								</div>
							</div>

							<div class="video-actions">
								<button type="button" class="btn btn--ghost btn--sm js-copy-link" data-url="<?php echo esc_url( $novatube_permalink ); ?>">
									<?php novatube_the_icon( 'link', 16 ); ?>
									<span><?php esc_html_e( 'Copy link', 'novatube' ); ?></span>
								</button>
								<a class="icon-btn" href="<?php echo esc_url( 'https://twitter.com/intent/tweet?url=' . $novatube_share_url . '&text=' . $novatube_share_text ); ?>" target="_blank" rel="noopener noreferrer nofollow" aria-label="<?php esc_attr_e( 'Share on X', 'novatube' ); ?>">
									<?php novatube_the_icon( 'x', 16 ); ?>
								</a>
								<a class="icon-btn" href="<?php echo esc_url( 'mailto:?subject=' . $novatube_share_text . '&body=' . $novatube_share_url ); ?>" aria-label="<?php esc_attr_e( 'Share by email', 'novatube' ); ?>">
									<?php novatube_the_icon( 'mail', 17 ); ?>
								</a>
								<?php edit_post_link( __( 'Edit', 'novatube' ), '<span class="edit-link">', '</span>' ); ?>
							</div>
						</div>

						<?php novatube_ad( 'player' ); ?>

						<div class="video-rating-bar">
							<?php novatube_rating_widget( $novatube_post_id ); ?>
						</div>

						<?php
						$novatube_cat_links  = novatube_get_term_links( $novatube_post_id, 'video_category', 8 );
						$novatube_model_links = novatube_get_term_links( $novatube_post_id, 'model', 10 );
						$novatube_tag_links  = novatube_get_term_links( $novatube_post_id, 'video_tag', 12 );
						?>

						<?php if ( $novatube_cat_links || $novatube_model_links || $novatube_tag_links ) : ?>
							<div class="video-taxonomies">
								<?php if ( $novatube_cat_links ) : ?>
									<div class="video-taxonomy">
										<span class="video-taxonomy__label"><?php esc_html_e( 'Categories', 'novatube' ); ?></span>
										<div class="chips"><?php echo wp_kses_post( $novatube_cat_links ); ?></div>
									</div>
								<?php endif; ?>

								<?php if ( $novatube_model_links ) : ?>
									<div class="video-taxonomy">
										<span class="video-taxonomy__label"><?php esc_html_e( 'Models', 'novatube' ); ?></span>
										<div class="chips"><?php echo wp_kses_post( $novatube_model_links ); ?></div>
									</div>
								<?php endif; ?>

								<?php if ( $novatube_tag_links ) : ?>
									<div class="video-taxonomy">
										<span class="video-taxonomy__label"><?php esc_html_e( 'Tags', 'novatube' ); ?></span>
										<div class="chips"><?php echo wp_kses_post( $novatube_tag_links ); ?></div>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php if ( $novatube_has_body || has_excerpt() ) : ?>
							<section class="video-description">
								<h2><?php esc_html_e( 'About this video', 'novatube' ); ?></h2>
								<div class="entry-content video-description__content js-video-description">
									<?php
									if ( $novatube_has_body ) {
										the_content();
									} else {
										echo '<p>' . esc_html( get_the_excerpt() ) . '</p>';
									}
									?>
								</div>
								<button type="button" class="read-more js-read-more" hidden aria-expanded="false">
									<span class="read-more__more"><?php esc_html_e( 'Read more', 'novatube' ); ?></span>
									<span class="read-more__less"><?php esc_html_e( 'Show less', 'novatube' ); ?></span>
									<?php novatube_the_icon( 'chevron', 15 ); ?>
								</button>
							</section>
						<?php endif; ?>

						<?php if ( $novatube_related ) : ?>
							<section class="video-section related-videos" aria-labelledby="related-heading">
								<div class="section-head">
									<h2 class="section-title" id="related-heading"><?php esc_html_e( 'Related videos', 'novatube' ); ?></h2>
									<a class="section-link" href="<?php echo esc_url( novatube_videos_url() ); ?>">
										<?php esc_html_e( 'Browse all', 'novatube' ); ?>
										<?php novatube_the_icon( 'arrow-right', 16 ); ?>
									</a>
								</div>
								<div class="video-grid">
									<?php foreach ( $novatube_related as $novatube_related_id ) : ?>
										<?php get_template_part( 'template-parts/video-card', null, array( 'post_id' => $novatube_related_id ) ); ?>
									<?php endforeach; ?>
								</div>
							</section>
						<?php endif; ?>

						<?php
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
						?>
					</article>
				</div>

				<?php get_sidebar(); ?>
			</div>
		</div>
	</main>
	<?php
endwhile;

get_footer();
