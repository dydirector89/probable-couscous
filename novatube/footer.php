<?php
/**
 * The footer.
 *
 * @package NovaTube
 */

$novatube_legal_pages = array(
	'2257'    => array(
		'label' => __( '18 U.S.C. 2257', 'novatube' ),
		'slugs' => array( '2257', '18-usc-2257', '2257-notice' ),
	),
	'dmca'    => array(
		'label' => __( 'DMCA', 'novatube' ),
		'slugs' => array( 'dmca' ),
	),
	'privacy' => array(
		'label' => __( 'Privacy policy', 'novatube' ),
		'slugs' => array( 'privacy-policy', 'privacy' ),
	),
	'terms'   => array(
		'label' => __( 'Terms of service', 'novatube' ),
		'slugs' => array( 'terms-of-service', 'terms', 'terms-and-conditions' ),
	),
	'contact' => array(
		'label' => __( 'Contact', 'novatube' ),
		'slugs' => array( 'contact-us', 'contact' ),
	),
);

$novatube_legal_links = array();

foreach ( $novatube_legal_pages as $novatube_key => $novatube_page ) {
	foreach ( $novatube_page['slugs'] as $novatube_slug ) {
		$novatube_found = get_page_by_path( $novatube_slug );

		if ( $novatube_found && 'publish' === $novatube_found->post_status ) {
			$novatube_legal_links[ $novatube_key ] = array(
				'label' => $novatube_page['label'],
				'url'   => get_permalink( $novatube_found ),
			);
			break;
		}
	}
}

$novatube_about      = get_theme_mod( 'novatube_footer_about', __( 'A fast, modern video experience with new uploads every day. Curated categories, HD playback, and zero clutter.', 'novatube' ) );
$novatube_disclaimer = get_theme_mod( 'novatube_footer_disclaimer', __( 'All models were 18 years of age or older at the time of production. This website is intended for adults only. Please leave if you are under 18.', 'novatube' ) );
$novatube_copyright  = get_theme_mod( 'novatube_copyright', '' );
$novatube_blog_id    = (int) get_option( 'page_for_posts' );
?>
<footer id="colophon" class="nt-footer">
	<div class="nt-container">
		<div class="nt-footer__grid">
			<div class="nt-footer__brand">
				<?php if ( has_custom_logo() ) : ?>
					<div class="nt-brand nt-brand--logo"><?php the_custom_logo(); ?></div>
				<?php else : ?>
					<a class="nt-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<span class="nt-brand__mark"><?php novatube_the_icon( 'play', 17 ); ?></span>
						<span class="nt-brand__text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
					</a>
				<?php endif; ?>

				<p class="nt-footer__about"><?php echo esc_html( $novatube_about ); ?></p>

				<?php novatube_social_links(); ?>

				<span class="nt-age-pill"><strong>18+</strong><?php esc_html_e( 'Adults only. Models are 18 or older.', 'novatube' ); ?></span>
			</div>

			<div>
				<h2 class="nt-footer__title"><?php esc_html_e( 'Browse', 'novatube' ); ?></h2>
				<ul class="nt-footer__list">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php novatube_the_icon( 'play', 13 ); ?><?php esc_html_e( 'Home', 'novatube' ); ?></a></li>
					<li><a href="<?php echo esc_url( novatube_videos_url() ); ?>"><?php novatube_the_icon( 'film', 13 ); ?><?php esc_html_e( 'All videos', 'novatube' ); ?></a></li>
					<li><a href="<?php echo esc_url( add_query_arg( 'sort', 'popular', novatube_videos_url() ) ); ?>"><?php novatube_the_icon( 'fire', 13 ); ?><?php esc_html_e( 'Most viewed', 'novatube' ); ?></a></li>
					<li><a href="<?php echo esc_url( add_query_arg( 'sort', 'rating', novatube_videos_url() ) ); ?>"><?php novatube_the_icon( 'star', 13 ); ?><?php esc_html_e( 'Top rated', 'novatube' ); ?></a></li>
					<li><a href="<?php echo esc_url( novatube_random_url() ); ?>"><?php novatube_the_icon( 'shuffle', 13 ); ?><?php esc_html_e( 'Surprise me', 'novatube' ); ?></a></li>
					<?php if ( $novatube_blog_id ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $novatube_blog_id ) ); ?>"><?php novatube_the_icon( 'chevron-right', 13 ); ?><?php echo esc_html( get_the_title( $novatube_blog_id ) ); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>

			<div>
				<h2 class="nt-footer__title"><?php esc_html_e( 'Categories', 'novatube' ); ?></h2>
				<?php novatube_footer_nav_fallback(); ?>
			</div>

			<div>
				<h2 class="nt-footer__title"><?php esc_html_e( 'Legal & info', 'novatube' ); ?></h2>
				<ul class="nt-footer__list">
					<?php if ( $novatube_legal_links ) : ?>
						<?php foreach ( $novatube_legal_links as $novatube_link ) : ?>
							<li><a href="<?php echo esc_url( $novatube_link['url'] ); ?>"><?php novatube_the_icon( 'chevron-right', 13 ); ?><?php echo esc_html( $novatube_link['label'] ); ?></a></li>
						<?php endforeach; ?>
					<?php else : ?>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php novatube_the_icon( 'info', 13 ); ?><?php esc_html_e( '18 U.S.C. 2257', 'novatube' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php novatube_the_icon( 'info', 13 ); ?><?php esc_html_e( 'DMCA', 'novatube' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php novatube_the_icon( 'info', 13 ); ?><?php esc_html_e( 'Privacy', 'novatube' ); ?></a></li>
					<?php endif; ?>
					<li><a href="mailto:<?php echo esc_attr( antispambot( get_option( 'admin_email' ) ) ); ?>"><?php novatube_the_icon( 'mail', 13 ); ?><?php esc_html_e( 'Contact us', 'novatube' ); ?></a></li>
				</ul>
			</div>
		</div>

		<div class="nt-footer__disclaimer"><?php echo esc_html( $novatube_disclaimer ); ?></div>

		<div class="nt-footer__bottom">
			<p>
				<?php
				if ( $novatube_copyright ) {
					echo esc_html( $novatube_copyright );
				} else {
					/* translators: 1: year, 2: site name. */
					printf( esc_html__( '© %1$s %2$s. All rights reserved.', 'novatube' ), esc_html( gmdate( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) );
				}
				?>
			</p>
			<p><?php esc_html_e( 'Built for speed. Designed for discovery.', 'novatube' ); ?></p>
		</div>
	</div>
</footer>

<button class="nt-to-top" type="button" aria-label="<?php esc_attr_e( 'Back to top', 'novatube' ); ?>">
	<?php novatube_the_icon( 'arrow-up', 17 ); ?>
</button>

<?php
if ( get_theme_mod( 'novatube_age_gate', true ) ) {
	get_template_part( 'template-parts/age-gate' );
}

wp_footer();
?>
</body>
</html>
