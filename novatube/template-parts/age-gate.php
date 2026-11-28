<?php
/**
 * Age gate dialog.
 *
 * @package NovaTube
 */

$novatube_age_title = get_theme_mod( 'novatube_age_title', __( 'Are you 18 or older?', 'novatube' ) );
$novatube_age_text  = get_theme_mod( 'novatube_age_text', __( 'This website contains adult material and is intended for adults only. By entering, you confirm that you are at least 18 years old and that viewing adult content is legal where you live.', 'novatube' ) );
$novatube_age_enter = get_theme_mod( 'novatube_age_enter', __( 'I am 18 or older — Enter', 'novatube' ) );
$novatube_age_exit  = get_theme_mod( 'novatube_age_exit_url', 'https://www.google.com/' );
?>
<dialog class="nt-dialog nt-age-gate" id="novatube-age-gate" aria-labelledby="novatube-age-title" aria-describedby="novatube-age-description">
	<div class="nt-dialog__body">
		<span class="nt-age-gate__mark"><?php novatube_the_icon( 'play', 21 ); ?></span>

		<h2 class="nt-age-gate__title" id="novatube-age-title"><?php echo esc_html( $novatube_age_title ); ?></h2>
		<p class="nt-age-gate__text" id="novatube-age-description"><?php echo esc_html( $novatube_age_text ); ?></p>

		<div class="nt-age-gate__actions">
			<button class="nt-btn nt-btn--primary nt-btn--lg nt-btn--block js-age-enter" type="button" data-age-enter>
				<?php novatube_the_icon( 'check', 18 ); ?>
				<?php echo esc_html( $novatube_age_enter ); ?>
			</button>
			<a class="nt-btn nt-btn--ghost nt-btn--block" href="<?php echo esc_url( $novatube_age_exit ); ?>">
				<?php esc_html_e( 'I am under 18 — Leave', 'novatube' ); ?>
			</a>
		</div>

		<p class="nt-age-gate__legal"><?php esc_html_e( 'By entering you agree to our terms and confirm that adult content is legal in your jurisdiction. Your choice is stored only on this device.', 'novatube' ); ?></p>
	</div>
</dialog>
