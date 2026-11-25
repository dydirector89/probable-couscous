<?php
/**
 * Video card.
 *
 * @package NovaTube
 */

$novatube_video_id = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();

if ( ! $novatube_video_id || 'video' !== get_post_type( $novatube_video_id ) ) {
	return;
}

$novatube_duration   = novatube_get_duration( $novatube_video_id );
$novatube_views      = novatube_get_views( $novatube_video_id );
$novatube_permalink  = get_permalink( $novatube_video_id );
$novatube_title      = get_the_title( $novatube_video_id );
$novatube_categories = get_the_terms( $novatube_video_id, 'video_category' );
$novatube_category   = ( ! empty( $novatube_categories ) && ! is_wp_error( $novatube_categories ) ) ? $novatube_categories[0] : null;
?>
<article class="video-card" data-video-id="<?php echo esc_attr( $novatube_video_id ); ?>">
	<a class="video-card__media" href="<?php echo esc_url( $novatube_permalink ); ?>" aria-hidden="true" tabindex="-1">
		<span class="video-card__image">
			<?php
			echo novatube_get_thumbnail(
				$novatube_video_id,
				'novatube-card',
				array(
					'alt'   => $novatube_title,
					'sizes' => '(max-width: 560px) 50vw, (max-width: 1080px) 33vw, 300px',
				)
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper.
			?>
		</span>
		<span class="video-card__shade"></span>
		<span class="video-card__play"><?php novatube_the_icon( 'play', 26 ); ?></span>

		<?php if ( novatube_is_hd( $novatube_video_id ) ) : ?>
			<span class="badge badge--hd"><?php esc_html_e( 'HD', 'novatube' ); ?></span>
		<?php endif; ?>

		<?php if ( $novatube_duration ) : ?>
			<span class="badge badge--duration"><?php echo esc_html( $novatube_duration ); ?></span>
		<?php endif; ?>
	</a>

	<div class="video-card__body">
		<?php if ( $novatube_category ) : ?>
			<a class="video-card__category" href="<?php echo esc_url( get_term_link( $novatube_category ) ); ?>"><?php echo esc_html( $novatube_category->name ); ?></a>
		<?php endif; ?>

		<h3 class="video-card__title"><a href="<?php echo esc_url( $novatube_permalink ); ?>"><?php echo esc_html( $novatube_title ); ?></a></h3>

		<div class="video-card__meta">
			<span class="meta-item"><?php novatube_the_icon( 'eye', 14 ); ?><?php echo esc_html( novatube_format_count( $novatube_views ) ); ?></span>
			<span class="meta-dot" aria-hidden="true"></span>
			<span class="meta-item"><?php echo esc_html( novatube_relative_date( $novatube_video_id ) ); ?></span>
		</div>
	</div>
</article>
