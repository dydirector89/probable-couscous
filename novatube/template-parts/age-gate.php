<?php
/**
 * Age gate modal.
 *
 * @package NovaTube
 */

$novatube_age_title = get_theme_mod( 'novatube_age_title', __( 'Are you 18 or older?', 'novatube' ) );
$novatube_age_text  = get_theme_mod( 'novatube_age_text', __( 'This website contains adult material and is intended for adults only. By entering, you confirm that you are at least 18 years old and that viewing adult content is legal where you live.', 'novatube' ) );
$novatube_age_enter = get_theme_mod( 'novatube_age_enter', __( 'I am 18 or older — Enter', 'novatube' ) );
$novatube_age_exit  = get_theme_mod( 'novatube_age_exit_url', 'https://www.google.com/' );
?>
<div class="age-gate" id="novatube-age-gate">
	<div class="age-gate__backdrop"></div>
	<div class="age-gate__dialog" role="dialog" aria-modal="true" aria-labelledby="novatube-age-title" aria-describedby="novatube-age-description" tabindex="-1">
		<div class="age-gate__logo">
			<span class="logo__mark"><?php novatube_the_icon( 'play', 18 ); ?></span>
			<span class="logo__text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
		</div>

		<span class="age-gate__badge" aria-hidden="true">18+</span>
		<h2 id="novatube-age-title"><?php echo esc_html( $novatube_age_title ); ?></h2>
		<div class="age-gate__description" id="novatube-age-description"><?php echo wp_kses_post( wpautop( $novatube_age_text ) ); ?></div>

		<div class="age-gate__actions">
			<button type="button" class="btn btn--primary btn--lg" data-age-enter><?php echo esc_html( $novatube_age_enter ); ?></button>
			<a class="btn btn--ghost" href="<?php echo esc_url( $novatube_age_exit ); ?>"><?php esc_html_e( 'Leave site', 'novatube' ); ?></a>
		</div>

		<p class="age-gate__note"><?php esc_html_e( 'By entering you agree to our terms and confirm that adult content is legal in your location.', 'novatube' ); ?></p>
	</div>
</div>
