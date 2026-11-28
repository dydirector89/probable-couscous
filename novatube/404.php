<?php
/**
 * 404 template.
 *
 * @package NovaTube
 */

get_header();
?>
<main id="main" class="site-main">
	<div class="nt-container nt-section">
		<div class="nt-404">
			<div class="nt-404__code" aria-hidden="true">404</div>
			<span class="nt-eyebrow"><?php novatube_the_icon( 'film', 13 ); ?><?php esc_html_e( 'Signal lost', 'novatube' ); ?></span>
			<h1 class="nt-404__title"><?php esc_html_e( 'This page has left the building', 'novatube' ); ?></h1>
			<p class="nt-404__text"><?php esc_html_e( 'The link may be broken, or the video may have been removed. Try a search or head back to the homepage.', 'novatube' ); ?></p>

			<div class="nt-404__search nt-searchform nt-searchform--compact"><?php get_search_form(); ?></div>

			<div class="nt-404__actions">
				<a class="nt-btn nt-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to homepage', 'novatube' ); ?></a>
				<a class="nt-btn nt-btn--secondary" href="<?php echo esc_url( novatube_videos_url() ); ?>"><?php esc_html_e( 'Browse videos', 'novatube' ); ?></a>
			</div>
		</div>

		<?php
		$novatube_popular = novatube_get_popular_videos( 4 );

		if ( $novatube_popular ) :
			?>
			<section aria-labelledby="popular-404-heading">
				<div class="nt-section-head">
					<div class="nt-section-head__main">
						<span class="nt-eyebrow"><?php novatube_the_icon( 'fire', 13 ); ?><?php esc_html_e( 'Keep watching', 'novatube' ); ?></span>
						<h2 class="nt-section-title" id="popular-404-heading"><?php esc_html_e( 'Popular right now', 'novatube' ); ?></h2>
					</div>
				</div>

				<div class="nt-grid nt-grid--videos">
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
