<?php
/**
 * The footer.
 *
 * @package NovaTube
 */

$novatube_legal_pages = array(
	'2257'    => array( '2257', '18-usc-2257', '2257-notice' ),
	'dmca'    => array( 'dmca' ),
	'privacy' => array( 'privacy-policy', 'privacy' ),
	'terms'   => array( 'terms-of-service', 'terms', 'terms-and-conditions' ),
	'contact' => array( 'contact-us', 'contact' ),
);

$novatube_about      = get_theme_mod( 'novatube_footer_about', __( 'A fast, modern video experience with new uploads every day. Curated categories, HD playback, and zero clutter.', 'novatube' ) );
$novatube_disclaimer = get_theme_mod( 'novatube_footer_disclaimer', __( 'All models were 18 years of age or older at the time of production. This website is intended for adults only. Please leave if you are under 18.', 'novatube' ) );
$novatube_copyright  = get_theme_mod( 'novatube_copyright', '' );
?>
<footer id="colophon" class="site-footer">
	<div class="container">
		<div class="footer-grid">
			<div class="footer-brand">
				<a class="logo logo--footer" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<span class="logo__mark"><?php novatube_the_icon( 'play', 17 ); ?></span>
					<span class="logo__text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
				</a>

				<?php if ( $novatube_about ) : ?>
					<p class="footer-about"><?php echo wp_kses_post( $novatube_about ); ?></p>
				<?php endif; ?>

				<?php novatube_social_links(); ?>

				<div class="age-pill">
					<span aria-hidden="true">18+</span>
					<?php esc_html_e( 'Adults only', 'novatube' ); ?>
				</div>
			</div>

			<div class="footer-col">
				<h2><?php esc_html_e( 'Explore', 'novatube' ); ?></h2>
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_class'     => 'footer-menu',
							'depth'          => 1,
						)
					);
					?>
				<?php else : ?>
					<?php novatube_footer_nav_fallback(); ?>
				<?php endif; ?>
				<ul class="footer-menu">
					<li><a href="<?php echo esc_url( novatube_videos_url() ); ?>"><?php esc_html_e( 'All videos', 'novatube' ); ?></a></li>
					<li><a href="<?php echo esc_url( novatube_random_url() ); ?>"><?php esc_html_e( 'Random video', 'novatube' ); ?></a></li>
				</ul>
			</div>

			<div class="footer-col">
				<h2><?php esc_html_e( 'Information', 'novatube' ); ?></h2>
				<ul class="footer-menu">
					<?php
					foreach ( $novatube_legal_pages as $novatube_page ) {
						$novatube_found = null;

						foreach ( $novatube_page as $novatube_slug ) {
							$novatube_found = get_page_by_path( $novatube_slug );
							if ( $novatube_found ) {
								break;
							}
						}

						if ( $novatube_found ) {
							echo '<li><a href="' . esc_url( get_permalink( $novatube_found ) ) . '">' . esc_html( get_the_title( $novatube_found ) ) . '</a></li>';
						}
					}
					?>
					<li><a href="<?php echo esc_url( home_url( '/?feed=rss2' ) ); ?>"><?php esc_html_e( 'RSS feed', 'novatube' ); ?></a></li>
				</ul>
			</div>
		</div>

		<?php if ( $novatube_disclaimer ) : ?>
			<div class="footer-disclaimer"><?php echo wp_kses_post( $novatube_disclaimer ); ?></div>
		<?php endif; ?>

		<div class="footer-bottom">
			<p class="footer-copy">
				<?php
				if ( $novatube_copyright ) {
					echo esc_html( $novatube_copyright );
				} else {
					/* translators: 1: current year, 2: site name. */
					echo esc_html( sprintf( __( '© %1$s %2$s. All rights reserved.', 'novatube' ), date_i18n( 'Y' ), get_bloginfo( 'name' ) ) );
				}
				?>
			</p>
			<p class="footer-credit">
				<?php esc_html_e( '18+ · Adults only · All rights reserved', 'novatube' ); ?>
			</p>
		</div>
	</div>
</footer>

<button class="to-top" type="button" aria-label="<?php esc_attr_e( 'Back to top', 'novatube' ); ?>">
	<?php novatube_the_icon( 'arrow-up', 20 ); ?>
</button>

<div class="toast" id="novatube-toast" role="status" aria-live="polite"></div>

<?php
if ( get_theme_mod( 'novatube_age_gate', true ) ) {
	get_template_part( 'template-parts/age-gate' );
}

wp_footer();
?>
</body>
</html>
