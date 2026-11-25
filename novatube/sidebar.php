<?php
/**
 * Sidebar.
 *
 * @package NovaTube
 */

?>
<aside id="secondary" class="sidebar widget-area" aria-label="<?php esc_attr_e( 'Sidebar', 'novatube' ); ?>">
	<?php
	if ( is_active_sidebar( 'novatube-sidebar' ) ) {
		dynamic_sidebar( 'novatube-sidebar' );
	} else {
		novatube_default_sidebar();
	}

	novatube_ad( 'sidebar' );
	?>
</aside>
