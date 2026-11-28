<?php
/**
 * Single video template.
 *
 * Mirrors the reference video page: player, release details, description,
 * trailer, previews, file specs, downloads, spare links, cast/categories,
 * related videos, and comments.
 *
 * @package NovaTube
 */

get_header();

while ( have_posts() ) :
	the_post();

	$novatube_post_id     = get_the_ID();
	$novatube_views       = novatube_get_views( $novatube_post_id );
	$novatube_duration    = novatube_get_duration( $novatube_post_id );
	$novatube_rating      = novatube_get_rating( $novatube_post_id );
	$novatube_permalink   = get_permalink();
	$novatube_share_url   = rawurlencode( $novatube_permalink );
	$novatube_share_text  = rawurlencode( get_the_title() );
	$novatube_details     = novatube_get_video_details( $novatube_post_id );
	$novatube_specs       = novatube_get_video_specs( $novatube_post_id );
	$novatube_categories  = get_the_terms( $novatube_post_id, 'video_category' );
	$novatube_models      = get_the_terms( $novatube_post_id, 'model' );
	$novatube_tags        = get_the_terms( $novatube_post_id, 'video_tag' );
	$novatube_category    = ( ! empty( $novatube_categories ) && ! is_wp_error( $novatube_categories ) ) ? $novatube_categories[0] : null;
	$novatube_content     = get_the_content();
	$novatube_has_body    = '' !== trim( wp_strip_all_tags( $novatube_content ) );
	$novatube_related     = novatube_get_related_videos( $novatube_post_id, 8 );
	$novatube_thumb_url   = get_the_post_thumbnail_url( $novatube_post_id, 'novatube-thumb' );
	$novatube_previews    = $novatube_details['previews'];

	if ( ! $novatube_thumb_url ) {
		$novatube_thumb_url = novatube_placeholder_url();
	}

	if ( empty( $novatube_previews ) && has_post_thumbnail() ) {
		$novatube_previews = array( get_the_post_thumbnail_url( $novatube_post_id, 'novatube-hero' ) );
	}

	$novatube_rail_ids = array();

	if ( $novatube_details['site'] ) {
		$novatube_rail_ids = get_posts(
			array(
				'post_type'      => 'video',
				'posts_per_page' => 4,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'post__not_in'   => array( $novatube_post_id ),
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'   => '_novatube_site',
						'value' => $novatube_details['site'],
					),
				),
			)
		);
	}

	if ( empty( $novatube_rail_ids ) ) {
		$novatube_rail_ids = array_slice( $novatube_related, 0, 4 );
	}

	$novatube_meta_line = trim(
		implode(
			' · ',
			array_filter(
				array(
					$novatube_duration,
					$novatube_details['resolution'],
					$novatube_details['file_size'],
				)
			)
		)
	);
	?>
	<main id="main" class="site-main">
		<div class="nt-container nt-section">
			<?php novatube_breadcrumbs(); ?>

			<div class="nt-video-layout">
				<?php if ( $novatube_rail_ids ) : ?>
					<aside class="nt-video-rail" aria-label="<?php esc_attr_e( 'More videos', 'novatube' ); ?>">
						<div class="nt-panel nt-panel--muted">
							<div class="nt-panel__head">
								<h2 class="nt-panel__title">
									<?php novatube_the_icon( 'film', 15 ); ?>
									<?php
									if ( $novatube_details['site'] ) {
										/* translators: %s: studio name. */
										printf( esc_html__( 'More from %s', 'novatube' ), esc_html( $novatube_details['site'] ) );
									} else {
										esc_html_e( 'More like this', 'novatube' );
									}
									?>
								</h2>
							</div>
							<div class="nt-video-rail__list">
								<?php foreach ( $novatube_rail_ids as $novatube_rail_id ) : ?>
									<?php get_template_part( 'template-parts/video-card', null, array( 'post_id' => $novatube_rail_id, 'compact' => true ) ); ?>
								<?php endforeach; ?>
							</div>
						</div>

						<a class="nt-btn nt-btn--secondary nt-btn--block nt-video-rail__cta" href="<?php echo esc_url( novatube_videos_url() ); ?>">
							<?php esc_html_e( 'Browse all videos', 'novatube' ); ?>
							<?php novatube_the_icon( 'arrow-right', 15 ); ?>
						</a>
					</aside>
				<?php endif; ?>

				<div class="nt-video-layout__main">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'nt-video' ); ?>>
						<header class="nt-video__head">
							<div class="nt-video__eyebrow">
								<?php if ( $novatube_category ) : ?>
									<a class="nt-badge nt-badge--orange" href="<?php echo esc_url( get_term_link( $novatube_category ) ); ?>"><?php echo esc_html( $novatube_category->name ); ?></a>
								<?php endif; ?>

								<?php if ( novatube_is_hd( $novatube_post_id ) ) : ?>
									<span class="nt-badge nt-badge--quality"><?php esc_html_e( 'HD', 'novatube' ); ?></span>
								<?php endif; ?>

								<?php if ( $novatube_details['site'] ) : ?>
									<span class="nt-badge"><?php echo esc_html( $novatube_details['site'] ); ?></span>
								<?php endif; ?>

								<?php if ( get_post_meta( $novatube_post_id, '_novatube_featured', true ) === '1' ) : ?>
									<span class="nt-badge nt-badge--yellow"><?php novatube_the_icon( 'star', 11 ); ?><?php esc_html_e( 'Featured', 'novatube' ); ?></span>
								<?php endif; ?>
							</div>

							<h1 class="nt-video__title"><?php the_title(); ?></h1>

							<div class="nt-video__meta">
								<span class="nt-meta-item"><?php novatube_the_icon( 'eye', 14 ); ?><span class="js-view-count"><?php echo esc_html( novatube_format_count( $novatube_views ) ); ?></span> <?php esc_html_e( 'views', 'novatube' ); ?></span>
								<span class="nt-meta-item"><?php novatube_the_icon( 'calendar', 14 ); ?><time datetime="<?php echo esc_attr( $novatube_details['release_iso'] ? $novatube_details['release_iso'] : get_the_date( 'c' ) ); ?>"><?php echo esc_html( $novatube_details['release_display'] ? $novatube_details['release_display'] : get_the_date() ); ?></time></span>
								<?php if ( $novatube_duration ) : ?>
									<span class="nt-meta-item"><?php novatube_the_icon( 'clock', 14 ); ?><?php echo esc_html( $novatube_duration ); ?></span>
								<?php endif; ?>
								<?php if ( $novatube_rating['count'] > 0 ) : ?>
									<span class="nt-meta-item nt-meta-item--rating"><?php novatube_the_icon( 'star', 14 ); ?><?php echo esc_html( number_format_i18n( $novatube_rating['rating'], 1 ) ); ?><span class="nt-meta-item__muted">/ 5 (<?php echo esc_html( number_format_i18n( $novatube_rating['count'] ) ); ?>)</span></span>
								<?php endif; ?>
								<?php if ( get_comments_number() ) : ?>
									<span class="nt-meta-item"><?php novatube_the_icon( 'mail', 14 ); ?><?php echo esc_html( number_format_i18n( get_comments_number() ) . ' ' . _n( 'comment', 'comments', get_comments_number(), 'novatube' ) ); ?></span>
								<?php endif; ?>
							</div>
						</header>

						<div class="nt-player">
							<div class="nt-player__ratio">
								<?php echo novatube_get_video_embed( $novatube_post_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized player HTML. ?>
							</div>

							<div class="nt-player__bar">
								<div class="nt-player__bar-left">
									<span class="nt-player__quality"><?php novatube_the_icon( 'check-circle', 13 ); ?><?php echo esc_html( $novatube_details['resolution'] ? $novatube_details['resolution'] : __( 'Watch online', 'novatube' ) ); ?></span>
									<?php if ( $novatube_meta_line ) : ?>
										<span class="nt-player__quality"><?php novatube_the_icon( 'disc', 13 ); ?><?php echo esc_html( $novatube_meta_line ); ?></span>
									<?php endif; ?>
								</div>

								<div class="nt-player__bar-right">
									<button class="nt-btn nt-btn--ghost nt-btn--sm js-copy-link" type="button" data-url="<?php echo esc_url( $novatube_permalink ); ?>">
										<?php novatube_the_icon( 'link', 14 ); ?>
										<?php esc_html_e( 'Copy link', 'novatube' ); ?>
									</button>
									<button class="nt-btn nt-btn--ghost nt-btn--sm js-fullscreen" type="button" data-target=".nt-player__ratio">
										<?php novatube_the_icon( 'expand', 14 ); ?>
										<?php esc_html_e( 'Fullscreen', 'novatube' ); ?>
									</button>
								</div>
							</div>
						</div>

						<?php novatube_ad( 'player' ); ?>

						<div class="nt-actionbar">
							<div class="nt-actionbar__rating">
								<?php novatube_rating_widget( $novatube_post_id ); ?>
							</div>

							<div class="nt-actionbar__actions">
								<button class="nt-btn nt-btn--secondary nt-btn--sm js-save" type="button" aria-pressed="false"
									data-id="<?php echo esc_attr( $novatube_post_id ); ?>"
									data-title="<?php echo esc_attr( get_the_title() ); ?>"
									data-url="<?php echo esc_url( $novatube_permalink ); ?>"
									data-thumb="<?php echo esc_url( $novatube_thumb_url ); ?>"
									data-meta="<?php echo esc_attr( $novatube_meta_line ); ?>">
									<?php novatube_the_icon( 'bookmark', 14 ); ?>
									<span class="js-save-label"><?php esc_html_e( 'Save', 'novatube' ); ?></span>
								</button>

								<details class="nt-menu nt-share">
									<summary class="nt-btn nt-btn--secondary nt-btn--sm">
										<?php novatube_the_icon( 'share', 14 ); ?>
										<?php esc_html_e( 'Share', 'novatube' ); ?>
									</summary>
									<div class="nt-menu__panel nt-share__panel">
										<a class="nt-menu__item" href="<?php echo esc_url( 'https://x.com/intent/post?url=' . $novatube_share_url . '&text=' . $novatube_share_text ); ?>" target="_blank" rel="noopener noreferrer nofollow">
											<?php novatube_the_icon( 'x', 15 ); ?><?php esc_html_e( 'Share on X', 'novatube' ); ?>
										</a>
										<a class="nt-menu__item" href="<?php echo esc_url( 'https://t.me/share/url?url=' . $novatube_share_url . '&text=' . $novatube_share_text ); ?>" target="_blank" rel="noopener noreferrer nofollow">
											<?php novatube_the_icon( 'telegram', 15 ); ?><?php esc_html_e( 'Share on Telegram', 'novatube' ); ?>
										</a>
										<a class="nt-menu__item" href="<?php echo esc_url( 'mailto:?subject=' . $novatube_share_text . '&body=' . $novatube_share_url ); ?>">
											<?php novatube_the_icon( 'mail', 15 ); ?><?php esc_html_e( 'Share by email', 'novatube' ); ?>
										</a>
										<div class="nt-menu__sep" aria-hidden="true"></div>
										<button class="nt-menu__item js-copy-link" type="button" data-url="<?php echo esc_url( $novatube_permalink ); ?>">
											<?php novatube_the_icon( 'copy', 15 ); ?><?php esc_html_e( 'Copy link', 'novatube' ); ?>
										</button>
									</div>
								</details>

								<?php if ( $novatube_details['trailer'] ) : ?>
									<a class="nt-btn nt-btn--secondary nt-btn--sm" href="<?php echo esc_url( $novatube_details['trailer'] ); ?>" target="_blank" rel="noopener noreferrer nofollow">
										<?php novatube_the_icon( 'play', 14 ); ?>
										<?php esc_html_e( 'Trailer', 'novatube' ); ?>
									</a>
								<?php endif; ?>

								<?php edit_post_link( __( 'Edit', 'novatube' ), '<span class="nt-actionbar__divider" aria-hidden="true"></span><span class="nt-btn nt-btn--ghost nt-btn--sm">', '</span>' ); ?>
							</div>
						</div>

						<?php if ( $novatube_has_body || has_excerpt() ) : ?>
							<section class="nt-panel nt-description">
								<div class="nt-panel__head">
									<h2 class="nt-panel__title"><?php novatube_the_icon( 'info', 15 ); ?><?php esc_html_e( 'About this video', 'novatube' ); ?></h2>
									<?php if ( $novatube_details['release_display'] ) : ?>
										<span class="nt-panel__sub"><?php echo esc_html( $novatube_details['release_display'] ); ?></span>
									<?php endif; ?>
								</div>

								<div class="nt-description__body js-video-description">
									<?php
									if ( $novatube_has_body ) {
										the_content();
									} else {
										echo '<p>' . esc_html( get_the_excerpt() ) . '</p>';
									}
									?>
								</div>

								<button type="button" class="nt-readmore js-read-more" hidden aria-expanded="false">
									<span class="js-readmore-more"><?php esc_html_e( 'Read more', 'novatube' ); ?></span>
									<span class="js-readmore-less" hidden><?php esc_html_e( 'Show less', 'novatube' ); ?></span>
									<?php novatube_the_icon( 'chevron', 14 ); ?>
								</button>
							</section>
						<?php endif; ?>

						<?php if ( $novatube_previews ) : ?>
							<section class="nt-panel" aria-labelledby="nt-previews-heading">
								<div class="nt-panel__head">
									<h2 class="nt-panel__title" id="nt-previews-heading"><?php novatube_the_icon( 'grid', 15 ); ?><?php esc_html_e( 'Previews', 'novatube' ); ?></h2>
									<span class="nt-panel__sub"><?php esc_html_e( 'Click an image to enlarge', 'novatube' ); ?></span>
								</div>

								<div class="nt-gallery">
									<?php foreach ( $novatube_previews as $novatube_index => $novatube_preview ) : ?>
										<button class="nt-gallery__item js-lightbox" type="button" data-full="<?php echo esc_url( $novatube_preview ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: preview number. */ __( 'Open preview %d', 'novatube' ), $novatube_index + 1 ) ); ?>">
											<img src="<?php echo esc_url( $novatube_preview ); ?>" alt="<?php echo esc_attr( sprintf( /* translators: %s: video title. */ __( '%s preview', 'novatube' ), get_the_title() ) ); ?>" loading="lazy" decoding="async">
										</button>
									<?php endforeach; ?>
								</div>
							</section>
						<?php endif; ?>

						<?php if ( $novatube_specs || $novatube_details['file_name'] ) : ?>
							<section class="nt-panel" aria-labelledby="nt-specs-heading">
								<div class="nt-panel__head">
									<h2 class="nt-panel__title" id="nt-specs-heading"><?php novatube_the_icon( 'monitor', 15 ); ?><?php esc_html_e( 'File details', 'novatube' ); ?></h2>
									<?php if ( $novatube_details['file_size'] ) : ?>
										<span class="nt-badge nt-badge--green"><?php echo esc_html( $novatube_details['file_size'] ); ?></span>
									<?php endif; ?>
								</div>

								<?php if ( $novatube_details['file_name'] ) : ?>
									<p class="nt-info__value nt-info__value--mono nt-video__filename"><?php echo esc_html( $novatube_details['file_name'] ); ?></p>
								<?php endif; ?>

								<div class="nt-info-grid">
									<?php foreach ( $novatube_specs as $novatube_label => $novatube_value ) : ?>
										<?php if ( __( 'File name', 'novatube' ) === $novatube_label && $novatube_details['file_name'] ) : ?>
											<?php continue; ?>
										<?php endif; ?>
										<div class="nt-info">
											<span class="nt-info__label"><?php echo esc_html( $novatube_label ); ?></span>
											<span class="nt-info__value<?php echo ( __( 'File name', 'novatube' ) === $novatube_label ) ? ' nt-info__value--mono' : ''; ?>"><?php echo esc_html( $novatube_value ); ?></span>
										</div>
									<?php endforeach; ?>
								</div>
							</section>
						<?php endif; ?>

						<?php if ( $novatube_details['downloads'] ) : ?>
							<section class="nt-panel" aria-labelledby="nt-downloads-heading">
								<div class="nt-panel__head">
									<h2 class="nt-panel__title" id="nt-downloads-heading"><?php novatube_the_icon( 'download', 15 ); ?><?php esc_html_e( 'Download', 'novatube' ); ?></h2>
									<span class="nt-panel__sub"><?php echo esc_html( sprintf( /* translators: %d: number of links. */ _n( '%d link', '%d links', count( $novatube_details['downloads'] ), 'novatube' ), count( $novatube_details['downloads'] ) ) ); ?></span>
								</div>

								<div class="nt-downloads">
									<?php foreach ( $novatube_details['downloads'] as $novatube_download ) : ?>
										<a class="nt-download" href="<?php echo esc_url( $novatube_download['url'] ); ?>" target="_blank" rel="noopener noreferrer nofollow">
											<span class="nt-download__main">
												<span class="nt-download__label"><?php novatube_the_icon( 'download', 14 ); ?><?php echo esc_html( $novatube_download['label'] ); ?></span>
												<span class="nt-download__meta"><?php echo esc_html( trim( $novatube_download['host'] . ( $novatube_download['size'] ? ' · ' . $novatube_download['size'] : '' ) ) ); ?></span>
											</span>
											<span class="nt-download__arrow"><?php novatube_the_icon( 'arrow-down', 15 ); ?></span>
										</a>
									<?php endforeach; ?>
								</div>

								<p class="nt-downloads__note"><?php esc_html_e( 'Download links open in a new tab and are provided by third-party hosts. Use a trusted download manager and report broken links through the contact page.', 'novatube' ); ?></p>
							</section>
						<?php endif; ?>

						<?php if ( $novatube_details['spare_links'] ) : ?>
							<details class="nt-panel nt-panel--muted nt-spare-links">
								<summary class="nt-spare-links__summary">
									<span class="nt-panel__title"><?php novatube_the_icon( 'link', 15 ); ?><?php esc_html_e( 'Spare links', 'novatube' ); ?></span>
									<span class="nt-badge"><?php echo esc_html( number_format_i18n( count( $novatube_details['spare_links'] ) ) ); ?></span>
									<?php novatube_the_icon( 'chevron', 15 ); ?>
								</summary>

								<div class="nt-downloads nt-downloads--single nt-spare-links__body">
									<?php foreach ( $novatube_details['spare_links'] as $novatube_download ) : ?>
										<a class="nt-download" href="<?php echo esc_url( $novatube_download['url'] ); ?>" target="_blank" rel="noopener noreferrer nofollow">
											<span class="nt-download__main">
												<span class="nt-download__label"><?php novatube_the_icon( 'external', 14 ); ?><?php echo esc_html( $novatube_download['label'] ); ?></span>
												<span class="nt-download__meta"><?php echo esc_html( trim( $novatube_download['host'] . ( $novatube_download['size'] ? ' · ' . $novatube_download['size'] : '' ) ) ); ?></span>
											</span>
											<span class="nt-download__arrow"><?php novatube_the_icon( 'arrow-down', 15 ); ?></span>
										</a>
									<?php endforeach; ?>
								</div>
							</details>
						<?php endif; ?>

						<?php if ( $novatube_details['site'] || $novatube_models || $novatube_categories || $novatube_tags ) : ?>
							<div class="nt-taxonomies nt-video__taxonomies">
								<?php if ( $novatube_details['site'] ) : ?>
									<div class="nt-taxonomy">
										<span class="nt-taxonomy__label"><?php esc_html_e( 'Site', 'novatube' ); ?></span>
										<div class="nt-taxonomy__list">
											<a class="nt-chip" href="<?php echo esc_url( add_query_arg( 's', $novatube_details['site'], home_url( '/' ) ) ); ?>"><?php echo esc_html( $novatube_details['site'] ); ?></a>
										</div>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $novatube_models ) && ! is_wp_error( $novatube_models ) ) : ?>
									<div class="nt-taxonomy">
										<span class="nt-taxonomy__label"><?php esc_html_e( 'Cast', 'novatube' ); ?></span>
										<div class="nt-model-chips">
											<?php foreach ( $novatube_models as $novatube_model ) : ?>
												<a class="nt-model-chip" href="<?php echo esc_url( get_term_link( $novatube_model ) ); ?>">
													<span class="nt-model-chip__avatar"><?php echo esc_html( mb_substr( $novatube_model->name, 0, 1 ) ); ?></span>
													<?php echo esc_html( $novatube_model->name ); ?>
												</a>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $novatube_categories ) && ! is_wp_error( $novatube_categories ) ) : ?>
									<div class="nt-taxonomy">
										<span class="nt-taxonomy__label"><?php esc_html_e( 'Categories', 'novatube' ); ?></span>
										<div class="nt-taxonomy__list">
											<?php foreach ( $novatube_categories as $novatube_term ) : ?>
												<a class="nt-chip" href="<?php echo esc_url( get_term_link( $novatube_term ) ); ?>"><?php echo esc_html( $novatube_term->name ); ?></a>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $novatube_tags ) && ! is_wp_error( $novatube_tags ) ) : ?>
									<div class="nt-taxonomy">
										<span class="nt-taxonomy__label"><?php esc_html_e( 'Tags', 'novatube' ); ?></span>
										<div class="nt-taxonomy__list">
											<?php foreach ( $novatube_tags as $novatube_tag ) : ?>
												<a class="nt-chip" href="<?php echo esc_url( get_term_link( $novatube_tag ) ); ?>">#<?php echo esc_html( $novatube_tag->name ); ?></a>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php if ( $novatube_related ) : ?>
							<section class="nt-video__related" aria-labelledby="related-heading">
								<div class="nt-section-head">
									<div class="nt-section-head__main">
										<span class="nt-eyebrow"><?php novatube_the_icon( 'fire', 13 ); ?><?php esc_html_e( 'Keep watching', 'novatube' ); ?></span>
										<h2 class="nt-section-title" id="related-heading"><?php esc_html_e( 'You Might Also Like', 'novatube' ); ?></h2>
									</div>
									<div class="nt-section-head__actions">
										<a class="nt-section-link" href="<?php echo esc_url( novatube_videos_url() ); ?>">
											<?php esc_html_e( 'Browse all', 'novatube' ); ?>
											<?php novatube_the_icon( 'arrow-right', 15 ); ?>
										</a>
									</div>
								</div>

								<div class="nt-grid nt-grid--related">
									<?php foreach ( array_slice( $novatube_related, 0, 8 ) as $novatube_related_id ) : ?>
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
