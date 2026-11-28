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
	<div class="nt-container nt-section">
		<?php novatube_breadcrumbs(); ?>

		<header class="nt-page-head">
			<div class="nt-page-head__main">
				<span class="nt-eyebrow"><?php novatube_the_icon( 'search', 13 ); ?><?php esc_html_e( 'Search results', 'novatube' ); ?></span>
				<h1 class="nt-page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Results for “%s”', 'novatube' ), esc_html( get_search_query() ) );
					?>
				</h1>
				<p class="nt-search-summary">
					<?php
					if ( $novatube_found ) {
						/* translators: %s: number of results. */
						printf( esc_html( _n( '%s result found', '%s results found', $novatube_found, 'novatube' ) ), '<strong>' . esc_html( number_format_i18n( $novatube_found ) ) . '</strong>' );
					} else {
						esc_html_e( 'Nothing matched that search. Try a different keyword.', 'novatube' );
					}
					?>
				</p>
			</div>

			<div class="nt-searchform nt-searchform--compact">
				<?php get_search_form(); ?>
			</div>
		</header>

		<div class="nt-layout">
			<div class="nt-layout__main">
				<?php if ( $novatube_videos || $novatube_others ) : ?>
					<?php if ( $novatube_videos ) : ?>
						<section class="nt-video__related" aria-labelledby="search-videos-heading">
							<div class="nt-section-head">
								<div class="nt-section-head__main">
									<span class="nt-eyebrow"><?php novatube_the_icon( 'film', 13 ); ?><?php esc_html_e( 'Videos', 'novatube' ); ?></span>
									<h2 class="nt-section-title" id="search-videos-heading"><?php esc_html_e( 'Matching videos', 'novatube' ); ?></h2>
								</div>
							</div>

							<div class="nt-grid nt-grid--videos">
								<?php foreach ( $novatube_videos as $novatube_video_id ) : ?>
									<?php get_template_part( 'template-parts/video-card', null, array( 'post_id' => $novatube_video_id ) ); ?>
								<?php endforeach; ?>
							</div>
						</section>
					<?php endif; ?>

					<?php if ( $novatube_others ) : ?>
						<section class="nt-section--tight" aria-labelledby="search-articles-heading">
							<div class="nt-section-head">
								<div class="nt-section-head__main">
									<span class="nt-eyebrow"><?php novatube_the_icon( 'folder', 13 ); ?><?php esc_html_e( 'Reading', 'novatube' ); ?></span>
									<h2 class="nt-section-title" id="search-articles-heading"><?php esc_html_e( 'Articles & pages', 'novatube' ); ?></h2>
								</div>
							</div>

							<div class="nt-grid nt-grid--posts">
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
