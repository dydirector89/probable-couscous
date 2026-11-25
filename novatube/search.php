<?php
/**
 * Search results.
 *
 * @package NovaTube
 */

get_header();

$novatube_found  = (int) $GLOBALS['wp_query']->found_posts;
$novatube_videos = array();
$novatube_others = array();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		if ( 'video' === get_post_type() ) {
			$novatube_videos[] = get_the_ID();
		} else {
			$novatube_others[] = get_the_ID();
		}
	}
}
?>
<main id="main" class="site-main">
	<div class="container section">
		<?php novatube_breadcrumbs(); ?>

		<header class="page-hero">
			<span class="eyebrow"><?php novatube_the_icon( 'search', 14 ); ?><?php esc_html_e( 'Search results', 'novatube' ); ?></span>
			<div class="page-hero__row">
				<div>
					<h1 class="page-title">
						<?php
						/* translators: %s: search query. */
						printf( esc_html__( 'Results for “%s”', 'novatube' ), esc_html( get_search_query() ) );
						?>
					</h1>
					<?php if ( $novatube_found ) : ?>
						<p class="page-description">
							<?php
							/* translators: %s: number of results. */
							printf( esc_html( _n( '%s result found', '%s results found', $novatube_found, 'novatube' ) ), esc_html( number_format_i18n( $novatube_found ) ) );
							?>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</header>

		<div class="layout layout--sidebar">
			<div class="layout__main">
				<?php if ( $novatube_videos || $novatube_others ) : ?>
					<?php if ( $novatube_videos ) : ?>
						<section class="video-section" aria-labelledby="search-videos-heading">
							<div class="section-head">
								<h2 class="section-title" id="search-videos-heading"><?php esc_html_e( 'Videos', 'novatube' ); ?></h2>
							</div>
							<div class="video-grid">
								<?php foreach ( $novatube_videos as $novatube_video_id ) : ?>
									<?php get_template_part( 'template-parts/video-card', null, array( 'post_id' => $novatube_video_id ) ); ?>
								<?php endforeach; ?>
							</div>
						</section>
					<?php endif; ?>

					<?php if ( $novatube_others ) : ?>
						<section class="post-section" aria-labelledby="search-articles-heading">
							<div class="section-head">
								<h2 class="section-title" id="search-articles-heading"><?php esc_html_e( 'Articles & pages', 'novatube' ); ?></h2>
							</div>
							<div class="post-list">
								<?php foreach ( $novatube_others as $novatube_post_id ) : ?>
									<?php get_template_part( 'template-parts/post-card', null, array( 'post_id' => $novatube_post_id ) ); ?>
								<?php endforeach; ?>
							</div>
						</section>
					<?php endif; ?>

					<?php novatube_pagination(); ?>
				<?php else : ?>
					<?php
					get_template_part(
						'template-parts/content-none',
						null,
						array(
							'title'   => __( 'No matches found', 'novatube' ),
							'message' => __( 'We could not find anything for that search. Try a different keyword, or browse the categories below.', 'novatube' ),
						)
					);
					?>
				<?php endif; ?>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</main>
<?php
get_footer();
