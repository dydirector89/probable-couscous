<?php
/**
 * Empty state.
 *
 * @package NovaTube
 */

$novatube_title   = isset( $args['title'] ) ? $args['title'] : __( 'Nothing here yet', 'novatube' );
$novatube_message = isset( $args['message'] ) ? $args['message'] : __( 'There is no content to show right now. Try a search or check back soon.', 'novatube' );
?>
<div class="nt-empty">
	<span class="nt-empty__icon"><?php novatube_the_icon( 'film', 22 ); ?></span>
	<h2 class="nt-empty__title"><?php echo esc_html( $novatube_title ); ?></h2>
	<p class="nt-empty__text"><?php echo esc_html( $novatube_message ); ?></p>

	<div class="nt-empty__search"><?php get_search_form(); ?></div>

	<?php if ( current_user_can( 'edit_posts' ) ) : ?>
		<div class="nt-empty__actions">
			<a class="nt-btn nt-btn--primary" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=video' ) ); ?>">
				<?php novatube_the_icon( 'plus', 16 ); ?>
				<?php esc_html_e( 'Add your first video', 'novatube' ); ?>
			</a>
			<a class="nt-btn nt-btn--secondary" href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=video_category&post_type=video' ) ); ?>">
				<?php esc_html_e( 'Create categories', 'novatube' ); ?>
			</a>
		</div>
	<?php endif; ?>
</div>
