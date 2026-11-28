<?php
/**
 * The homepage: latest video grid, popular picks, and sidebar.
 *
 * @package NovaTube
 */

get_header();

$novatube_per_page = max( 4, min( 60, (int) get_theme_mod( 'novatube_videos_per_page', 20 ) ) );
$novatube_paged    = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

$novatube_query = new WP_Query(
	array(
		'post_type'           => 'video',
		'post_status'         => 'publish',
		'posts_per_page'      => $novatube_per_page,
		'paged'               => $novatube_paged,
		'ignore_sticky_posts' => true,
	)
);

$novatube_latest_title = get_theme_mod( 'novatube_latest_title', __( 'Latest videos', 'novatube' ) );
$novatube_latest_ids   = wp_list_pluck( $novatube_query->posts, 'ID' );
$novatube_popular      = get_theme_mod( 'novatube_show_popular', true ) ? novatube_get_popular_videos( 4, $novatube_latest_ids ) : array();
?>
<main id="main" class="site-main">
	<?php
	if ( get_theme_mod( 'novatube_show_hero', true ) ) {
		get_template_part( 'template-parts/hero' );
	}
	?>

	<div class="nt-container nt-section">
		<div class="nt-layout">
			<div class="nt-layout__main">
				<header class="nt-home-intro">
					<div class="nt-home-intro__main">
						<span class="nt-eyebrow"><?php novatube_the_icon( 'film', 13 ); ?><?php esc_html_e( 'Fresh from the studio', 'novatube' ); ?></span>
						<h1 class="nt-home-intro__title" id="latest-heading">
							<?php echo esc_html( $novatube_latest_title ? $novatube_latest_title : __( 'Latest videos', 'novatube' ) ); ?>
							<?php if ( $novatube_query->found_posts ) : ?>
								<span class="nt-count"><?php echo esc_html( number_format_i18n( $novatube_query->found_posts ) ); ?></span>
							<?php endif; ?>
						</h1>
						<p class="nt-home-intro__sub"><?php esc_html_e( 'New uploads every day, sorted newest first. Hover a card to preview, tap the bookmark to save it for later.', 'novatube' ); ?></p>
					</div>
					<div class="nt-home-intro__actions">
						<a class="nt-btn nt-btn--secondary nt-btn--sm" href="<?php echo esc_url( novatube_videos_url() ); ?>">
							<?php esc_html_e( 'Browse all', 'novatube' ); ?>
							<?php novatube_the_icon( 'arrow-right', 15 ); ?>
						</a>
						<a class="nt-btn nt-btn--ghost nt-btn--sm" href="<?php echo esc_url( novatube_random_url() ); ?>">
							<?php novatube_the_icon( 'shuffle', 15 ); ?>
							<?php esc_html_e( 'Surprise me', 'novatube' ); ?>
						</a>
					</div>
				</header>

				<?php if ( $novatube_query->have_posts() ) : ?>
					<div class="nt-grid nt-grid--videos" id="latest-videos">
						<?php
						while ( $novatube_query->have_posts() ) {
							$novatube_query->the_post();
							get_template_part( 'template-parts/video-card', null, array( 'post_id' => get_the_ID() ) );
						}
						wp_reset_postdata();
						?>
					</div>

					<?php if ( $novatube_query->max_num_pages > $novatube_paged ) : ?>
						<div class="nt-load-more-wrap">
							<button type="button" class="nt-btn nt-btn--secondary nt-btn--lg js-load-more" data-page="<?php echo esc_attr( $novatube_paged ); ?>" data-max="<?php echo esc_attr( $novatube_query->max_num_pages ); ?>" aria-controls="latest-videos">
								<?php novatube_the_icon( 'plus', 17 ); ?>
								<span class="js-load-label"><?php esc_html_e( 'Load more videos', 'novatube' ); ?></span>
								<span class="nt-spinner" aria-hidden="true" hidden></span>
							</button>
						</div>
					<?php endif; ?>
				<?php else : ?>
					<?php
					get_template_part(
						'template-parts/content-none',
						null,
						array(
							'title'   => __( 'No videos yet', 'novatube' ),
							'message' => __( 'The stage is ready. Add your first video from the WordPress dashboard and it will appear here automatically.', 'novatube' ),
						)
					);
					?>
				<?php endif; ?>

				<?php novatube_ad( 'home' ); ?>

				<?php if ( $novatube_popular ) : ?>
					<section class="nt-section--tight" aria-labelledby="popular-heading">
						<div class="nt-section-head">
							<div class="nt-section-head__main">
								<span class="nt-eyebrow"><?php novatube_the_icon( 'fire', 13 ); ?><?php esc_html_e( 'Most watched', 'novatube' ); ?></span>
								<h2 class="nt-section-title" id="popular-heading"><?php esc_html_e( 'Popular this week', 'novatube' ); ?></h2>
							</div>
							<div class="nt-section-head__actions">
								<a class="nt-section-link" href="<?php echo esc_url( add_query_arg( 'sort', 'popular', novatube_videos_url() ) ); ?>">
									<?php esc_html_e( 'See all popular', 'novatube' ); ?>
									<?php novatube_the_icon( 'arrow-right', 15 ); ?>
								</a>
							</div>
						</div>

						<div class="nt-grid nt-grid--videos">
							<?php foreach ( $novatube_popular as $novatube_popular_id ) : ?>
								<?php get_template_part( 'template-parts/video-card', null, array( 'post_id' => $novatube_popular_id ) ); ?>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endif; ?>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</main>
<?php
get_footer();
