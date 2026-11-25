<?php
/**
 * Post card for articles and search results.
 *
 * @package NovaTube
 */

$novatube_post_id = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();

if ( ! $novatube_post_id ) {
	return;
}
?>
<article id="post-<?php echo esc_attr( $novatube_post_id ); ?>" class="post-card">
	<a class="post-card__thumb" href="<?php echo esc_url( get_permalink( $novatube_post_id ) ); ?>" tabindex="-1" aria-hidden="true">
		<?php
		echo novatube_get_thumbnail(
			$novatube_post_id,
			'novatube-card',
			array(
				'alt'   => get_the_title( $novatube_post_id ),
				'sizes' => '(max-width: 700px) 100vw, 280px',
			)
		); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper.
		?>
	</a>

	<div class="post-card__body">
		<div class="post-card__meta">
			<span><?php echo esc_html( get_the_date( '', $novatube_post_id ) ); ?></span>
			<span class="meta-dot" aria-hidden="true"></span>
			<span><?php echo esc_html( novatube_reading_time( $novatube_post_id ) ); ?></span>
		</div>

		<h2 class="post-card__title"><a href="<?php echo esc_url( get_permalink( $novatube_post_id ) ); ?>"><?php echo esc_html( get_the_title( $novatube_post_id ) ); ?></a></h2>

		<p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $novatube_post_id ), 26, '…' ) ); ?></p>

		<a class="post-card__more" href="<?php echo esc_url( get_permalink( $novatube_post_id ) ); ?>">
			<?php esc_html_e( 'Read article', 'novatube' ); ?>
			<?php novatube_the_icon( 'arrow-right', 15 ); ?>
		</a>
	</div>
</article>
