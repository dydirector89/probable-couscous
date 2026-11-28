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
$novatube_rating     = novatube_get_rating( $novatube_video_id );
$novatube_resolution = (string) get_post_meta( $novatube_video_id, '_novatube_resolution', true );
$novatube_quality    = '';

if ( preg_match( '/(2160|4k)/i', $novatube_resolution ) ) {
	$novatube_quality = '4K';
} elseif ( novatube_is_hd( $novatube_video_id ) ) {
	$novatube_quality = 'HD';
}

$novatube_thumb_url = get_the_post_thumbnail_url( $novatube_video_id, 'novatube-thumb' );

if ( ! $novatube_thumb_url ) {
	$novatube_thumb_url = novatube_placeholder_url();
}

$novatube_compact = ! empty( $args['compact'] );
$novatube_classes = 'nt-vcard' . ( $novatube_compact ? ' nt-vcard--compact' : '' );
?>
<article class="<?php echo esc_attr( $novatube_classes ); ?>" data-video-id="<?php echo esc_attr( $novatube_video_id ); ?>">
	<div class="nt-vcard__media-wrap">
		<a class="nt-vcard__media" href="<?php echo esc_url( $novatube_permalink ); ?>" tabindex="-1" aria-hidden="true">
			<?php
			echo novatube_get_thumbnail(
				$novatube_video_id,
				$novatube_compact ? 'novatube-thumb' : 'novatube-card',
				array(
					'alt'   => $novatube_title,
					'class' => 'nt-vcard__img',
					'sizes' => $novatube_compact ? '96px' : '(max-width: 640px) 50vw, (max-width: 1100px) 33vw, 25vw',
				)
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper.
			?>
			<span class="nt-vcard__shade"></span>
			<span class="nt-vcard__play"><span><?php novatube_the_icon( 'play', 18 ); ?></span></span>

			<span class="nt-vcard__badges">
				<?php if ( $novatube_quality ) : ?>
					<span class="nt-badge nt-badge--quality nt-badge--sm"><?php echo esc_html( $novatube_quality ); ?></span>
				<?php endif; ?>
			</span>

			<?php if ( $novatube_views ) : ?>
				<span class="nt-vcard__views nt-badge nt-badge--views nt-badge--sm"><?php novatube_the_icon( 'eye', 11 ); ?><?php echo esc_html( novatube_format_count( $novatube_views ) ); ?></span>
			<?php endif; ?>

			<?php if ( $novatube_duration ) : ?>
				<span class="nt-vcard__duration nt-badge nt-badge--duration nt-badge--sm"><?php echo esc_html( $novatube_duration ); ?></span>
			<?php endif; ?>
		</a>

		<button class="nt-vcard__save js-save" type="button" aria-pressed="false" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: video title. */ __( 'Save %s', 'novatube' ), $novatube_title ) ); ?>"
			data-id="<?php echo esc_attr( $novatube_video_id ); ?>"
			data-title="<?php echo esc_attr( $novatube_title ); ?>"
			data-url="<?php echo esc_url( $novatube_permalink ); ?>"
			data-thumb="<?php echo esc_url( $novatube_thumb_url ); ?>"
			data-meta="<?php echo esc_attr( trim( $novatube_duration . ( $novatube_duration && $novatube_views ? ' · ' : '' ) . ( $novatube_views ? sprintf( /* translators: %s: view count. */ __( '%s views', 'novatube' ), novatube_format_count( $novatube_views ) ) : '' ) ) ); ?>">
			<?php novatube_the_icon( 'bookmark', 14 ); ?>
		</button>
	</div>

	<div class="nt-vcard__body">
		<div class="nt-vcard__kicker">
			<?php if ( $novatube_category ) : ?>
				<a href="<?php echo esc_url( get_term_link( $novatube_category ) ); ?>"><?php echo esc_html( $novatube_category->name ); ?></a>
				<span class="nt-dot" aria-hidden="true"></span>
			<?php endif; ?>
			<span><?php echo esc_html( novatube_relative_date( $novatube_video_id ) ); ?></span>
		</div>

		<h3 class="nt-vcard__title">
			<a href="<?php echo esc_url( $novatube_permalink ); ?>"><?php echo esc_html( $novatube_title ); ?></a>
		</h3>

		<div class="nt-vcard__meta">
			<?php if ( $novatube_rating['count'] > 0 ) : ?>
				<span class="nt-vcard__meta-item nt-vcard__rating"><?php novatube_the_icon( 'star', 12 ); ?><?php echo esc_html( number_format_i18n( $novatube_rating['rating'], 1 ) ); ?></span>
			<?php endif; ?>
			<span class="nt-vcard__meta-item"><?php novatube_the_icon( 'eye', 12 ); ?><?php echo esc_html( novatube_format_count( $novatube_views ) ); ?></span>
			<?php if ( $novatube_duration ) : ?>
				<span class="nt-vcard__meta-item"><?php novatube_the_icon( 'clock', 12 ); ?><?php echo esc_html( $novatube_duration ); ?></span>
			<?php endif; ?>
		</div>
	</div>
</article>
