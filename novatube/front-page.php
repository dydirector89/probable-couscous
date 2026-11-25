<?php
/**
 * The homepage: featured hero, latest video grid, top-rated section.
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
?>
<main id="main" class="site-main">
	<?php
	if ( get_theme_mod( 'novatube_show_hero', true ) ) {
		get_template_part( 'template-parts/hero' );
	}
	?>

	<div class="container section">
		<div class="layout layout--sidebar">
			<div class="layout__main">
				<section class="video-section" aria-labelledby="latest-heading">
					<div class="section-head">
						<h1 class="section-title" id="latest-heading">
							<?php echo esc_html( $novatube_latest_title ? $novatube_latest_title : __( 'Latest videos', 'novatube' ) ); ?>
							<?php if ( $novatube_query->found_posts ) : ?>
								<span class="section-count"><?php echo esc_html( number_format_i18n( $novatube_query->found_posts ) ); ?></span>
							<?php endif; ?>
						</h1>
						<a class="section-link" href="<?php echo esc_url( novatube_videos_url() ); ?>">
							<?php esc_html_e( 'Browse all', 'novatube' ); ?>
							<?php novatube_the_icon( 'arrow-right', 16 ); ?>
						</a>
					</div>

					<?php if ( $novatube_query->have_posts() ) : ?>
						<div class="video-grid" id="latest-videos">
							<?php
							while ( $novatube_query->have_posts() ) {
								$novatube_query->the_post();
								get_template_part( 'template-parts/video-card', null, array( 'post_id' => get_the_ID() ) );
							}
							wp_reset_postdata();
							?>
						</div>

						<?php if ( $novatube_query->max_num_pages > $novatube_paged ) : ?>
							<div class="load-more-wrap">
								<button type="button" class="btn btn--outline btn--lg js-load-more" data-page="<?php echo esc_attr( $novatube_paged ); ?>" data-max="<?php echo esc_attr( $novatube_query->max_num_pages ); ?>" aria-controls="latest-videos">
									<span class="js-load-label"><?php esc_html_e( 'Load more videos', 'novatube' ); ?></span>
									<span class="spinner" aria-hidden="true"></span>
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
				</section>

				<?php
				if ( get_theme_mod( 'novatube_show_popular', true ) ) :
					$novatube_top_rated = get_posts(
						array(
							'post_type'      => 'video',
							'posts_per_page' => 4,
							'fields'         => 'ids',
							'no_found_rows'  => true,
							'meta_key'       => '_novatube_rating', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							'orderby'        => 'meta_value_num',
							'order'          => 'DESC',
							'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
								array(
									'key'     => '_novatube_rating',
									'value'   => 0,
									'compare' => '>',
									'type'    => 'NUMERIC',
								),
							),
						)
					);

					if ( $novatube_top_rated ) :
						?>
						<section class="video-section video-section--top" aria-labelledby="top-heading">
							<div class="section-head">
								<h2 class="section-title" id="top-heading">
									<?php novatube_the_icon( 'fire', 19 ); ?>
									<?php esc_html_e( 'Top rated', 'novatube' ); ?>
								</h2>
								<a class="section-link" href="<?php echo esc_url( add_query_arg( 'sort', 'rating', novatube_videos_url() ) ); ?>">
									<?php esc_html_e( 'See more', 'novatube' ); ?>
									<?php novatube_the_icon( 'arrow-right', 16 ); ?>
								</a>
							</div>

							<div class="video-grid">
								<?php foreach ( $novatube_top_rated as $novatube_top_id ) : ?>
									<?php get_template_part( 'template-parts/video-card', null, array( 'post_id' => $novatube_top_id ) ); ?>
								<?php endforeach; ?>
							</div>
						</section>
					<?php endif; ?>
				<?php endif; ?>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</main>
<?php
get_footer();
