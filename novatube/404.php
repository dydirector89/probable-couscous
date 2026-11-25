<?php
/**
 * 404 template.
 *
 * @package NovaTube
 */

get_header();
?>
<main id="main" class="site-main">
	<div class="container section">
		<div class="error-page">
			<div class="error-page__code" aria-hidden="true">404</div>
			<span class="eyebrow"><?php novatube_the_icon( 'film', 14 ); ?><?php esc_html_e( 'Signal lost', 'novatube' ); ?></span>
			<h1 class="page-title"><?php esc_html_e( 'This page has left the building', 'novatube' ); ?></h1>
			<p class="page-description"><?php esc_html_e( 'The link may be broken, or the video may have been removed. Try a search or head back to the homepage.', 'novatube' ); ?></p>

			<div class="error-page__search"><?php get_search_form(); ?></div>

			<div class="error-page__actions">
				<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to homepage', 'novatube' ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( novatube_videos_url() ); ?>"><?php esc_html_e( 'Browse videos', 'novatube' ); ?></a>
			</div>
		</div>

		<?php
		$novatube_popular = novatube_get_popular_videos( 4 );

		if ( $novatube_popular ) :
			?>
			<section class="video-section" aria-labelledby="popular-404-heading">
				<div class="section-head">
					<h2 class="section-title" id="popular-404-heading"><?php novatube_the_icon( 'fire', 19 ); ?><?php esc_html_e( 'Popular right now', 'novatube' ); ?></h2>
				</div>
				<div class="video-grid">
					<?php foreach ( $novatube_popular as $novatube_video_id ) : ?>
						<?php get_template_part( 'template-parts/video-card', null, array( 'post_id' => $novatube_video_id ) ); ?>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
