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

$novatube_categories = get_the_category( $novatube_post_id );
$novatube_category   = ! empty( $novatube_categories ) ? $novatube_categories[0] : null;
?>
<article id="post-<?php echo esc_attr( $novatube_post_id ); ?>" class="nt-post-card">
	<a class="nt-post-card__thumb" href="<?php echo esc_url( get_permalink( $novatube_post_id ) ); ?>" tabindex="-1" aria-hidden="true">
		<?php
		echo novatube_get_thumbnail(
			$novatube_post_id,
			'novatube-card',
			array(
				'alt'   => get_the_title( $novatube_post_id ),
				'sizes' => '(max-width: 700px) 100vw, 420px',
			)
		); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper.
		?>
	</a>

	<div class="nt-post-card__body">
		<?php if ( $novatube_category ) : ?>
			<div class="nt-post-card__kicker"><?php echo esc_html( $novatube_category->name ); ?></div>
		<?php endif; ?>

		<h2 class="nt-post-card__title">
			<a href="<?php echo esc_url( get_permalink( $novatube_post_id ) ); ?>"><?php echo esc_html( get_the_title( $novatube_post_id ) ); ?></a>
		</h2>

		<p class="nt-post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $novatube_post_id ), 22, '…' ) ); ?></p>

		<div class="nt-post-card__meta">
			<span><?php echo esc_html( get_the_date( '', $novatube_post_id ) ); ?></span>
			<span class="nt-dot" aria-hidden="true"></span>
			<span><?php echo esc_html( novatube_reading_time( $novatube_post_id ) ); ?></span>
		</div>
	</div>
</article>
