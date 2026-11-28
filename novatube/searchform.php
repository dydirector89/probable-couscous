<?php
/**
 * Search form.
 *
 * @package NovaTube
 */

$novatube_search_id = wp_unique_id( 'search-field-' );
?>
<form role="search" method="get" class="nt-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $novatube_search_id ); ?>"><?php esc_html_e( 'Search for:', 'novatube' ); ?></label>
	<div class="nt-searchform__field">
		<?php novatube_the_icon( 'search', 18 ); ?>
		<input type="search" id="<?php echo esc_attr( $novatube_search_id ); ?>" class="nt-searchform__input" placeholder="<?php esc_attr_e( 'Search videos, models, categories…', 'novatube' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
		<button type="submit" class="nt-btn nt-btn--primary nt-btn--sm"><?php esc_html_e( 'Search', 'novatube' ); ?></button>
	</div>
</form>
