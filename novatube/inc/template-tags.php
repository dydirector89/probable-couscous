<?php
/**
 * Template helpers and front-end features.
 *
 * @package NovaTube
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return an inline SVG icon.
 *
 * @param string $name Icon name.
 * @param int    $size Pixel size.
 * @return string
 */
function novatube_icon( $name, $size = 20 ) {
	$size = absint( $size );
	$open = '<svg class="icon icon--' . esc_attr( $name ) . '" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" aria-hidden="true" focusable="false"';

	$icons = array(
		'play'        => '<path d="M8 5.5v13a1 1 0 0 0 1.5.87l11-6.5a1 1 0 0 0 0-1.74l-11-6.5A1 1 0 0 0 8 5.5Z" fill="currentColor"/>',
		'search'      => '<circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="m20 20-3.5-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'eye'         => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"/>',
		'clock'       => '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/><path d="M12 7v5l3.5 2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'star'        => '<path d="m12 2.5 2.95 5.98 6.6.96-4.78 4.65 1.13 6.57L12 17.56l-5.9 3.1 1.13-6.57L2.45 9.44l6.6-.96L12 2.5Z" fill="currentColor"/>',
		'menu'        => '<path d="M4 7h16M4 12h16M4 17h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'close'       => '<path d="m6 6 12 12M18 6 6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'arrow-up'    => '<path d="M12 19V5m0 0-6 6m6-6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'arrow-right' => '<path d="M5 12h14m0 0-6-6m6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'share'       => '<circle cx="18" cy="5" r="2.5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="6" cy="12" r="2.5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="18" cy="19" r="2.5" fill="none" stroke="currentColor" stroke-width="2"/><path d="m8.3 10.8 7.4-4.3m-7.4 6.7 7.4 4.3" fill="none" stroke="currentColor" stroke-width="2"/>',
		'link'        => '<path d="M10 13a5 5 0 0 0 7.07 0l2.12-2.12a5 5 0 0 0-7.07-7.07L10.7 5.22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M14 11a5 5 0 0 0-7.07 0L4.8 13.12a5 5 0 0 0 7.07 7.07l1.42-1.41" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'check'       => '<path d="m4 12.5 5 5L20 6.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'chevron'     => '<path d="m6 9 6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'film'        => '<rect x="3" y="4" width="18" height="16" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><path d="M7 4v16M17 4v16M3 9h4M17 9h4M3 15h4M17 15h4" fill="none" stroke="currentColor" stroke-width="2"/>',
		'fire'        => '<path d="M12 22c4.4 0 7-2.9 7-6.7 0-2.8-1.5-5.2-4-7.3.3 2-1 3.4-2 4.1.2-2.9-1.2-5.7-3.8-7.6.3 3-4.2 5.5-4.2 10.8C5 19.1 7.6 22 12 22Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>',
		'shuffle'     => '<path d="M3 7h3.5c4.5 0 6.5 10 11 10H21m0 0-3-3m3 3-3 3M3 17h3.5c1.7 0 3-1.4 4.3-3.2M14.2 9.6C15.4 8 16.6 7 18 7h3m0 0-3-3m3 3-3 3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'mail'        => '<rect x="3" y="5" width="18" height="14" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><path d="m4 7 8 6 8-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'external'    => '<path d="M14 4h6v6m0-6-9 9M20 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'x'           => '<path d="M4 4h4.6l4.1 5.8L17.8 4H20l-6.3 7.1L20.4 20H15.8l-4.5-6.3L5.9 20H3.6l6.8-7.7L4 4Z" fill="currentColor"/>',
		'instagram'   => '<rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.2" cy="6.8" r="1.2" fill="currentColor"/>',
		'telegram'    => '<path d="m21 4-3 15-6.2-4.8L8.5 17l-1-5L4 10.5 21 4Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="m8.5 17 8.2-9.5" fill="none" stroke="currentColor" stroke-width="2"/>',
		'user'        => '<circle cx="12" cy="8" r="4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M4 21c.8-4 4-6 8-6s7.2 2 8 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'calendar'    => '<rect x="3" y="5" width="18" height="16" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><path d="M8 3v4M16 3v4M3 10h18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'flag'        => '<path d="M5 21V4m0 1h13l-2 4 2 4H5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'grid'        => '<rect x="4" y="4" width="6" height="6" rx="1" fill="none" stroke="currentColor" stroke-width="2"/><rect x="14" y="4" width="6" height="6" rx="1" fill="none" stroke="currentColor" stroke-width="2"/><rect x="4" y="14" width="6" height="6" rx="1" fill="none" stroke="currentColor" stroke-width="2"/><rect x="14" y="14" width="6" height="6" rx="1" fill="none" stroke="currentColor" stroke-width="2"/>',
		'list'        => '<path d="M8 6h13M8 12h13M8 18h13M3.5 6h.01M3.5 12h.01M3.5 18h.01" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'bookmark'    => '<path d="M6 4.5A1.5 1.5 0 0 1 7.5 3h9A1.5 1.5 0 0 1 18 4.5V21l-6-3.6L6 21V4.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>',
		'download'    => '<path d="M12 3v12m0 0-4.5-4.5M12 15l4.5-4.5M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'sun'         => '<circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'moon'        => '<path d="M20.5 14.2A8.5 8.5 0 0 1 9.8 3.5 8.5 8.5 0 1 0 20.5 14.2Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>',
		'filter'      => '<path d="M4 6h16M7 12h10M10 18h4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'copy'        => '<rect x="9" y="9" width="11" height="11" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><path d="M15 5.5A1.5 1.5 0 0 0 13.5 4h-8A1.5 1.5 0 0 0 4 5.5v8A1.5 1.5 0 0 0 5.5 15" fill="none" stroke="currentColor" stroke-width="2"/>',
		'chevron-left'=> '<path d="m15 6-6 6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'chevron-right'=>'<path d="m9 6 6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'info'        => '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/><path d="M12 11v5m0-8h.01" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'tag'         => '<path d="m20 13-7 7a2 2 0 0 1-2.8 0L3 12.8V4h8.8L20 12.2a2 2 0 0 1 0 2.8Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="8" cy="8" r="1.5" fill="currentColor"/>',
		'folder'      => '<path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h3.2a2 2 0 0 1 1.6.8l1 1.2h7.2A2.5 2.5 0 0 1 21 9.5v7A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5v-9Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>',
		'disc'        => '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="2.5" fill="none" stroke="currentColor" stroke-width="2"/>',
		'monitor'     => '<rect x="3" y="4" width="18" height="13" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><path d="M8 21h8M12 17v4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'volume'      => '<path d="M11 5 6.5 9H3v6h3.5L11 19V5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M15 9.5a4 4 0 0 1 0 5M18 7a8 8 0 0 1 0 10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'plus'        => '<path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'arrow-down'  => '<path d="M12 5v14m0 0 6-6m-6 6-6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'check-circle'=> '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/><path d="m8 12.5 2.7 2.7L16.5 9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
		'history'     => '<path d="M3 12a9 9 0 1 0 2.6-6.4M3 4v5h5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 8v4.5l3 1.8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'sliders'     => '<path d="M4 7h9M17 7h3M4 17h3M11 17h9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="15" cy="7" r="2" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="9" cy="17" r="2" fill="none" stroke="currentColor" stroke-width="2"/>',
		'expand'      => '<path d="M8 3H5a2 2 0 0 0-2 2v3M16 3h3a2 2 0 0 1 2 2v3M8 21H5a2 2 0 0 1-2-2v-3M16 21h3a2 2 0 0 0 2-2v-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return $open . '>' . $icons[ $name ] . '</svg>';
}

/**
 * Echo an inline SVG icon.
 *
 * @param string $name Icon name.
 * @param int    $size Pixel size.
 */
function novatube_the_icon( $name, $size = 20 ) {
	echo novatube_icon( $name, $size ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG markup.
}

/**
 * Get the video archive URL.
 *
 * @return string
 */
function novatube_videos_url() {
	$url = get_post_type_archive_link( 'video' );

	return $url ? $url : home_url( '/' );
}

/**
 * URL that redirects to a random video.
 *
 * @return string
 */
function novatube_random_url() {
	return add_query_arg( 'novatube_random', '1', home_url( '/' ) );
}

/**
 * Format a count compactly (1.2K, 3.4M).
 *
 * @param int|float $number Number to format.
 * @return string
 */
function novatube_format_count( $number ) {
	$number = (float) $number;

	if ( $number >= 1000000 ) {
		$decimals = $number >= 10000000 ? 0 : 1;
		/* translators: %s: number of millions. */
		return sprintf( __( '%sM', 'novatube' ), number_format_i18n( $number / 1000000, $decimals ) );
	}

	if ( $number >= 1000 ) {
		$decimals = $number >= 10000 ? 0 : 1;
		/* translators: %s: number of thousands. */
		return sprintf( __( '%sK', 'novatube' ), number_format_i18n( $number / 1000, $decimals ) );
	}

	return number_format_i18n( $number );
}

/**
 * Get a video's view count.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function novatube_get_views( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	return (int) get_post_meta( $post_id, '_novatube_views', true );
}

/**
 * Get a normalised duration string.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function novatube_get_duration( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$raw     = trim( (string) get_post_meta( $post_id, '_novatube_duration', true ) );

	if ( '' === $raw ) {
		return '';
	}

	if ( ctype_digit( $raw ) ) {
		$seconds = (int) $raw;
		$hours   = (int) floor( $seconds / 3600 );
		$minutes = (int) floor( ( $seconds % 3600 ) / 60 );
		$secs    = $seconds % 60;

		if ( $hours > 0 ) {
			return sprintf( '%d:%02d:%02d', $hours, $minutes, $secs );
		}

		return sprintf( '%02d:%02d', $minutes, $secs );
	}

	if ( preg_match( '/^\d{1,3}:\d{2}$/', $raw ) ) {
		list( $minutes, $seconds ) = array_map( 'intval', explode( ':', $raw ) );

		return sprintf( '%02d:%02d', $minutes, $seconds );
	}

	if ( preg_match( '/^\d{1,2}:\d{2}:\d{2}$/', $raw ) ) {
		return $raw;
	}

	return '';
}

/**
 * Convert a duration string to ISO 8601 (e.g. PT12M34S).
 *
 * @param string $duration Duration in mm:ss or hh:mm:ss.
 * @return string
 */
function novatube_duration_iso( $duration ) {
	if ( ! $duration || ! preg_match( '/\d/', $duration ) ) {
		return '';
	}

	$parts = array_reverse( array_map( 'intval', explode( ':', $duration ) ) );
	$secs  = isset( $parts[0] ) ? $parts[0] : 0;
	$mins  = isset( $parts[1] ) ? $parts[1] : 0;
	$hours = isset( $parts[2] ) ? $parts[2] : 0;

	if ( $hours + $mins + $secs <= 0 ) {
		return '';
	}

	return 'PT' . ( $hours ? $hours . 'H' : '' ) . ( $mins ? $mins . 'M' : '' ) . ( $secs ? $secs . 'S' : '' );
}

/**
 * Get rating data.
 *
 * @param int $post_id Post ID.
 * @return array{rating: float, count: int}
 */
function novatube_get_rating( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	return array(
		'rating' => (float) get_post_meta( $post_id, '_novatube_rating', true ),
		'count'  => (int) get_post_meta( $post_id, '_novatube_rating_count', true ),
	);
}

/**
 * Is this video marked HD?
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function novatube_is_hd( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	return '1' === (string) get_post_meta( $post_id, '_novatube_hd', true );
}

/**
 * Get the placeholder thumbnail URL.
 *
 * @return string
 */
function novatube_placeholder_url() {
	return NOVATUBE_URI . '/assets/img/placeholder.svg';
}

/**
 * Build a video thumbnail image.
 *
 * @param int          $post_id Post ID.
 * @param string|int[] $size    Image size.
 * @param array        $attr    Extra attributes.
 * @return string
 */
function novatube_get_thumbnail( $post_id = 0, $size = 'novatube-card', $attr = array() ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	$defaults = array(
		'alt'      => get_the_title( $post_id ),
		'loading'  => 'lazy',
		'decoding' => 'async',
	);

	$attr = wp_parse_args( $attr, $defaults );

	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail( $post_id, $size, $attr );
	}

	$attr['src'] = novatube_placeholder_url();

	return '<img src="' . esc_url( $attr['src'] ) . '" alt="' . esc_attr( $attr['alt'] ) . '" class="novatube-placeholder" loading="' . esc_attr( $attr['loading'] ) . '" decoding="async" width="1280" height="720">';
}

/**
 * Get a rendered video card.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function novatube_get_video_card( $post_id ) {
	ob_start();
	get_template_part( 'template-parts/video-card', null, array( 'post_id' => $post_id ) );
	return (string) ob_get_clean();
}

/**
 * Render the video card.
 *
 * @param int $post_id Post ID.
 */
function novatube_video_card( $post_id = 0 ) {
	echo novatube_get_video_card( $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Template output is escaped.
}

/**
 * Render a video player for a post.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function novatube_get_video_embed( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$embed   = trim( (string) get_post_meta( $post_id, '_novatube_embed', true ) );
	$poster  = get_the_post_thumbnail_url( $post_id, 'novatube-hero' );
	$title   = get_the_title( $post_id );
	$html    = '';

	if ( '' === $embed ) {
		$html = '<div class="nt-player__empty">' . novatube_icon( 'film', 42 ) . '<p>' . esc_html__( 'No video source has been added yet.', 'novatube' ) . '</p>';
		if ( $poster ) {
			$html = '<div class="nt-player__empty nt-player__empty--poster" style="background-image:url(' . esc_url( $poster ) . ');"><span>' . esc_html__( 'Video coming soon', 'novatube' ) . '</span></div>';
		}
		return $html;
	}

	// Direct video file.
	if ( preg_match( '#^https?://\S+\.(mp4|webm|ogg|ogv|m4v)(\?\S*)?$#i', $embed ) ) {
		$type  = 'video/mp4';
		$ext   = strtolower( pathinfo( wp_parse_url( $embed, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
		$types = array(
			'webm' => 'video/webm',
			'ogg'  => 'video/ogg',
			'ogv'  => 'video/ogg',
			'm4v'  => 'video/x-m4v',
		);

		if ( isset( $types[ $ext ] ) ) {
			$type = $types[ $ext ];
		}

		$html  = '<video class="novatube-player" controls playsinline preload="metadata"';
		$html .= $poster ? ' poster="' . esc_url( $poster ) . '"' : '';
		$html .= '>';
		$html .= '<source src="' . esc_url( $embed ) . '" type="' . esc_attr( $type ) . '">';
		$html .= esc_html__( 'Your browser does not support HTML video.', 'novatube' );
		$html .= '</video>';
	} elseif ( preg_match( '#^https?://#i', $embed ) && preg_match( '#(youtube\.com|youtu\.be|vimeo\.com)#i', $embed ) ) {
		// oEmbed-aware providers.
		$oembed = wp_oembed_get( $embed, array( 'width' => 1120 ) );

		if ( $oembed ) {
			$html = $oembed;
		} else {
			$html = '<iframe class="novatube-player" src="' . esc_url( $embed ) . '" title="' . esc_attr( $title ) . '" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen" allowfullscreen></iframe>';
		}
	} elseif ( preg_match( '#^https?://#i', $embed ) ) {
		// Unknown URL: treat it as an iframe source.
		$html = '<iframe class="novatube-player" src="' . esc_url( $embed ) . '" title="' . esc_attr( $title ) . '" loading="lazy" allow="autoplay; fullscreen" allowfullscreen></iframe>';
	} else {
		// Sanitised embed code saved by the meta box.
		$html = wp_kses( $embed, novatube_allowed_embed_html() );
	}

	/**
	 * Filter the final player HTML.
	 *
	 * @param string $html    Player HTML.
	 * @param int    $post_id Video ID.
	 */
	return (string) apply_filters( 'novatube_embed_html', $html, $post_id );
}

/**
 * Get taxonomy links for a video.
 *
 * @param int    $post_id  Post ID.
 * @param string $taxonomy Taxonomy.
 * @param int    $limit    Maximum number of terms.
 * @return string
 */
function novatube_get_term_links( $post_id, $taxonomy, $limit = 4 ) {
	$terms = get_the_terms( $post_id, $taxonomy );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}

	$terms = array_slice( $terms, 0, $limit );
	$links = array();

	foreach ( $terms as $term ) {
		$link = get_term_link( $term );

		if ( is_wp_error( $link ) ) {
			continue;
		}

		$links[] = '<a class="nt-chip" href="' . esc_url( $link ) . '">' . esc_html( $term->name ) . '</a>';
	}

	return implode( '', $links );
}

/**
 * Get featured videos for the hero.
 *
 * @param int $limit Number of videos.
 * @return int[]
 */
function novatube_get_featured_videos( $limit = 3 ) {
	$ids = get_posts(
		array(
			'post_type'      => 'video',
			'posts_per_page' => $limit,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => '_novatube_featured',
					'value' => '1',
				),
			),
		)
	);

	if ( empty( $ids ) ) {
		$ids = get_posts(
			array(
				'post_type'      => 'video',
				'posts_per_page' => $limit,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
	}

	return array_map( 'intval', $ids );
}

/**
 * Get popular videos by views.
 *
 * @param int   $limit   Number of videos.
 * @param int[] $exclude IDs to exclude.
 * @return int[]
 */
function novatube_get_popular_videos( $limit = 5, $exclude = array() ) {
	$args = array(
		'post_type'      => 'video',
		'posts_per_page' => $limit,
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'post__not_in'   => array_map( 'intval', $exclude ),
	);

	$popular = get_posts(
		array_merge(
			$args,
			array(
				'meta_key' => '_novatube_views', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'orderby'  => 'meta_value_num',
				'order'    => 'DESC',
			)
		)
	);

	if ( empty( $popular ) ) {
		$popular = get_posts( $args );
	}

	return array_map( 'intval', $popular );
}

/**
 * Get related videos for a video.
 *
 * @param int $post_id Video ID.
 * @param int $limit   Number of videos.
 * @return int[]
 */
function novatube_get_related_videos( $post_id, $limit = 8 ) {
	$term_ids = wp_get_post_terms( $post_id, 'video_category', array( 'fields' => 'ids' ) );
	$ids      = array();

	if ( ! empty( $term_ids ) && ! is_wp_error( $term_ids ) ) {
		$ids = get_posts(
			array(
				'post_type'      => 'video',
				'posts_per_page' => $limit,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'post__not_in'   => array( $post_id ),
				'orderby'        => 'rand',
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'video_category',
						'field'    => 'term_id',
						'terms'    => array_map( 'intval', $term_ids ),
					),
				),
			)
		);
	}

	if ( empty( $ids ) ) {
		$ids = get_posts(
			array(
				'post_type'      => 'video',
				'posts_per_page' => $limit,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'post__not_in'   => array( $post_id ),
			)
		);
	}

	return array_map( 'intval', $ids );
}

/**
 * Output breadcrumb navigation.
 */
function novatube_breadcrumbs() {
	$items = array(
		array(
			'url'   => home_url( '/' ),
			'label' => __( 'Home', 'novatube' ),
		),
	);

	if ( is_singular( 'video' ) ) {
		$items[] = array(
			'url'   => novatube_videos_url(),
			'label' => __( 'Videos', 'novatube' ),
		);

		$terms = get_the_terms( get_the_ID(), 'video_category' );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$link = get_term_link( $terms[0] );

			if ( ! is_wp_error( $link ) ) {
				$items[] = array(
					'url'   => $link,
					'label' => $terms[0]->name,
				);
			}
		}

		$items[] = array(
			'url'   => '',
			'label' => get_the_title(),
		);
	} elseif ( is_singular( 'post' ) ) {
		$blog_id = (int) get_option( 'page_for_posts' );

		if ( $blog_id ) {
			$items[] = array(
				'url'   => get_permalink( $blog_id ),
				'label' => get_the_title( $blog_id ),
			);
		}

		$cats = get_the_category();

		if ( ! empty( $cats ) ) {
			$items[] = array(
				'url'   => get_category_link( $cats[0]->term_id ),
				'label' => $cats[0]->name,
			);
		}

		$items[] = array(
			'url'   => '',
			'label' => get_the_title(),
		);
	} elseif ( is_singular() ) {
		$items[] = array(
			'url'   => '',
			'label' => get_the_title(),
		);
	} elseif ( is_tax() ) {
		$items[] = array(
			'url'   => novatube_videos_url(),
			'label' => __( 'Videos', 'novatube' ),
		);
		$items[] = array(
			'url'   => '',
			'label' => single_term_title( '', false ),
		);
	} elseif ( is_post_type_archive( 'video' ) ) {
		$items[] = array(
			'url'   => '',
			'label' => __( 'All videos', 'novatube' ),
		);
	} elseif ( is_search() ) {
		$items[] = array(
			'url'   => '',
			/* translators: %s: search query. */
			'label' => sprintf( __( 'Search: %s', 'novatube' ), get_search_query() ),
		);
	} elseif ( is_404() ) {
		$items[] = array(
			'url'   => '',
			'label' => __( 'Page not found', 'novatube' ),
		);
	} elseif ( is_archive() ) {
		$items[] = array(
			'url'   => '',
			'label' => get_the_archive_title(),
		);
	}

	if ( count( $items ) < 2 && ! is_front_page() ) {
		return;
	}

	echo '<nav class="nt-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'novatube' ) . '"><ol>';

	foreach ( $items as $index => $item ) {
		$is_last = ( count( $items ) - 1 ) === $index;

		echo '<li>';

		if ( ! $is_last && ! empty( $item['url'] ) ) {
			echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a>';
		} else {
			echo '<span aria-current="page">' . esc_html( wp_trim_words( $item['label'], 8, '…' ) ) . '</span>';
		}

		echo '</li>';
	}

	echo '</ol></nav>';
}

/**
 * Output numbered pagination.
 *
 * @param WP_Query|null $query Optional query.
 */
function novatube_pagination( $query = null ) {
	$query = $query ? $query : $GLOBALS['wp_query'];

	if ( $query->max_num_pages < 2 ) {
		return;
	}

	$links = paginate_links(
		array(
			'total'     => (int) $query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'type'      => 'array',
			'mid_size'  => 2,
			'end_size'  => 1,
			'prev_text' => novatube_icon( 'chevron', 16 ) . '<span>' . esc_html__( 'Previous', 'novatube' ) . '</span>',
			'next_text' => '<span>' . esc_html__( 'Next', 'novatube' ) . '</span>' . novatube_icon( 'chevron', 16 ),
		)
	);

	if ( empty( $links ) ) {
		return;
	}
	?>
	<nav class="nt-pagination" aria-label="<?php esc_attr_e( 'Pagination', 'novatube' ); ?>">
		<ul class="nt-pagination__list">
			<?php foreach ( $links as $link ) : ?>
				<li><?php echo wp_kses_post( $link ); ?></li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
}

/**
 * Output category chips for navigation.
 */
function novatube_category_chips() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'video_category',
			'hide_empty' => true,
			'number'     => 12,
			'orderby'    => 'count',
			'order'      => 'DESC',
		)
	);

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return;
	}

	$current = is_tax( 'video_category' ) ? get_queried_object_id() : 0;
	$is_all  = is_front_page() || is_post_type_archive( 'video' );
	?>
	<div class="nt-category-rail">
		<div class="nt-container nt-category-rail__inner">
			<a class="nt-chip <?php echo $is_all && ! $current ? 'is-active' : ''; ?>" href="<?php echo esc_url( novatube_videos_url() ); ?>">
				<?php esc_html_e( 'All videos', 'novatube' ); ?>
			</a>
			<?php foreach ( $terms as $term ) : ?>
				<a class="nt-chip <?php echo (int) $current === (int) $term->term_id ? 'is-active' : ''; ?>" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
					<?php echo esc_html( $term->name ); ?>
					<span class="nt-chip__count"><?php echo esc_html( number_format_i18n( $term->count ) ); ?></span>
				</a>
			<?php endforeach; ?>
			<a class="nt-chip nt-chip--accent" href="<?php echo esc_url( novatube_videos_url() ); ?>">
				<?php esc_html_e( 'Browse all', 'novatube' ); ?>
				<?php novatube_the_icon( 'arrow-right', 14 ); ?>
			</a>
		</div>
	</div>
	<?php
}

/**
 * Get the items assigned to a nav menu location.
 *
 * @param string $location Menu location.
 * @return array<int, WP_Post>
 */
function novatube_get_nav_menu_items( $location = 'primary' ) {
	$locations = get_nav_menu_locations();

	if ( empty( $locations[ $location ] ) ) {
		return array();
	}

	$items = wp_get_nav_menu_items( (int) $locations[ $location ] );

	return is_array( $items ) ? $items : array();
}

/**
 * Whether a menu item is the current item.
 *
 * @param WP_Post $item Menu item.
 * @return bool
 */
function novatube_nav_item_is_current( $item ) {
	$classes = (array) $item->classes;

	foreach ( array( 'current-menu-item', 'current_page_item', 'current-menu-parent', 'current_page_parent', 'current-menu-ancestor' ) as $class ) {
		if ( in_array( $class, $classes, true ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Render the primary navigation as Astryx-style nav items.
 */
function novatube_primary_nav() {
	$items = novatube_get_nav_menu_items( 'primary' );

	if ( empty( $items ) ) {
		novatube_primary_nav_fallback();
		return;
	}

	$top      = array();
	$children = array();

	foreach ( $items as $item ) {
		if ( (int) $item->menu_item_parent ) {
			$children[ (int) $item->menu_item_parent ][] = $item;
		} else {
			$top[] = $item;
		}
	}

	foreach ( $top as $item ) {
		$current = novatube_nav_item_is_current( $item );
		$sub     = isset( $children[ $item->ID ] ) ? $children[ $item->ID ] : array();

		if ( $sub ) {
			?>
			<details class="nt-nav__item<?php echo $current ? ' is-current' : ''; ?>">
				<summary class="nt-nav__summary"><?php echo esc_html( $item->title ); ?><?php novatube_the_icon( 'chevron', 14 ); ?></summary>
				<div class="nt-nav__panel nt-nav__panel--single">
					<a class="nt-nav__panel-link" href="<?php echo esc_url( $item->url ); ?>"><?php esc_html_e( 'Overview', 'novatube' ); ?></a>
					<?php foreach ( $sub as $child ) : ?>
						<a class="nt-nav__panel-link" href="<?php echo esc_url( $child->url ); ?>"><?php echo esc_html( $child->title ); ?></a>
					<?php endforeach; ?>
				</div>
			</details>
			<?php
		} else {
			?>
			<div class="nt-nav__item<?php echo $current ? ' is-current' : ''; ?>">
				<a class="nt-nav__link" href="<?php echo esc_url( $item->url ); ?>"><?php echo esc_html( $item->title ); ?></a>
			</div>
			<?php
		}
	}
}

/**
 * Primary menu fallback.
 *
 * @param array $args Menu args (unused, kept for compatibility).
 */
function novatube_primary_nav_fallback( $args = array() ) {
	unset( $args );

	$terms   = get_terms(
		array(
			'taxonomy'   => 'video_category',
			'hide_empty' => false,
			'number'     => 8,
			'orderby'    => 'count',
			'order'      => 'DESC',
		)
	);
	$blog_id = (int) get_option( 'page_for_posts' );
	?>
	<div class="nt-nav__item<?php echo is_front_page() ? ' is-current' : ''; ?>">
		<a class="nt-nav__link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'novatube' ); ?></a>
	</div>
	<div class="nt-nav__item<?php echo is_post_type_archive( 'video' ) && ! isset( $_GET['sort'] ) ? ' is-current' : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>">
		<a class="nt-nav__link" href="<?php echo esc_url( novatube_videos_url() ); ?>"><?php esc_html_e( 'Videos', 'novatube' ); ?></a>
	</div>
	<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
		<details class="nt-nav__item">
			<summary class="nt-nav__summary"><?php esc_html_e( 'Categories', 'novatube' ); ?><?php novatube_the_icon( 'chevron', 14 ); ?></summary>
			<div class="nt-nav__panel">
				<?php foreach ( $terms as $term ) : ?>
					<a class="nt-nav__panel-link" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
						<?php echo esc_html( $term->name ); ?>
						<span><?php echo esc_html( number_format_i18n( $term->count ) ); ?></span>
					</a>
				<?php endforeach; ?>
				<a class="nt-nav__panel-all" href="<?php echo esc_url( novatube_videos_url() ); ?>">
					<?php esc_html_e( 'Browse all videos', 'novatube' ); ?>
					<?php novatube_the_icon( 'arrow-right', 14 ); ?>
				</a>
			</div>
		</details>
	<?php endif; ?>
	<div class="nt-nav__item">
		<a class="nt-nav__link" href="<?php echo esc_url( add_query_arg( 'sort', 'popular', novatube_videos_url() ) ); ?>"><?php esc_html_e( 'Popular', 'novatube' ); ?></a>
	</div>
	<div class="nt-nav__item">
		<a class="nt-nav__link" href="<?php echo esc_url( add_query_arg( 'sort', 'rating', novatube_videos_url() ) ); ?>"><?php esc_html_e( 'Top rated', 'novatube' ); ?></a>
	</div>
	<?php if ( $blog_id ) : ?>
		<div class="nt-nav__item<?php echo is_home() ? ' is-current' : ''; ?>">
			<a class="nt-nav__link" href="<?php echo esc_url( get_permalink( $blog_id ) ); ?>"><?php echo esc_html( get_the_title( $blog_id ) ); ?></a>
		</div>
	<?php endif; ?>
	<?php
}

/**
 * Render the mobile drawer navigation.
 */
function novatube_drawer_nav() {
	$items = novatube_get_nav_menu_items( 'primary' );

	if ( $items ) {
		$top      = array();
		$children = array();

		foreach ( $items as $item ) {
			if ( (int) $item->menu_item_parent ) {
				$children[ (int) $item->menu_item_parent ][] = $item;
			} else {
				$top[] = $item;
			}
		}

		echo '<ul class="nt-drawer__nav">';

		foreach ( $top as $item ) {
			$sub = isset( $children[ $item->ID ] ) ? $children[ $item->ID ] : array();

			if ( $sub ) {
				echo '<li><details>';
				echo '<summary>' . esc_html( $item->title ) . novatube_icon( 'chevron', 15 ) . '</summary>';
				echo '<ul class="nt-drawer__sub">';
				echo '<li><a href="' . esc_url( $item->url ) . '">' . esc_html__( 'Overview', 'novatube' ) . '</a></li>';
				foreach ( $sub as $child ) {
					echo '<li><a href="' . esc_url( $child->url ) . '">' . esc_html( $child->title ) . '</a></li>';
				}
				echo '</ul></details></li>';
			} else {
				echo '<li><a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a></li>';
			}
		}

		echo '</ul>';
		return;
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'video_category',
			'hide_empty' => false,
			'number'     => 10,
			'orderby'    => 'count',
			'order'      => 'DESC',
		)
	);
	$blog_id = (int) get_option( 'page_for_posts' );
	?>
	<ul class="nt-drawer__nav">
		<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'novatube' ); ?><?php novatube_the_icon( 'chevron-right', 15 ); ?></a></li>
		<li><a href="<?php echo esc_url( novatube_videos_url() ); ?>"><?php esc_html_e( 'All videos', 'novatube' ); ?><?php novatube_the_icon( 'chevron-right', 15 ); ?></a></li>
		<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
			<li>
				<details>
					<summary><?php esc_html_e( 'Categories', 'novatube' ); ?><?php novatube_the_icon( 'chevron', 15 ); ?></summary>
					<ul class="nt-drawer__sub">
						<?php foreach ( $terms as $term ) : ?>
							<li><a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?><span class="nt-count"><?php echo esc_html( number_format_i18n( $term->count ) ); ?></span></a></li>
						<?php endforeach; ?>
					</ul>
				</details>
			</li>
		<?php endif; ?>
		<li><a href="<?php echo esc_url( add_query_arg( 'sort', 'popular', novatube_videos_url() ) ); ?>"><?php esc_html_e( 'Most viewed', 'novatube' ); ?><?php novatube_the_icon( 'fire', 15 ); ?></a></li>
		<li><a href="<?php echo esc_url( add_query_arg( 'sort', 'rating', novatube_videos_url() ) ); ?>"><?php esc_html_e( 'Top rated', 'novatube' ); ?><?php novatube_the_icon( 'star', 15 ); ?></a></li>
		<li><a href="<?php echo esc_url( novatube_random_url() ); ?>"><?php esc_html_e( 'Surprise me', 'novatube' ); ?><?php novatube_the_icon( 'shuffle', 15 ); ?></a></li>
		<?php if ( $blog_id ) : ?>
			<li><a href="<?php echo esc_url( get_permalink( $blog_id ) ); ?>"><?php echo esc_html( get_the_title( $blog_id ) ); ?><?php novatube_the_icon( 'chevron-right', 15 ); ?></a></li>
		<?php endif; ?>
	</ul>
	<?php
}

/**
 * Footer menu fallback: top categories.
 */
function novatube_footer_nav_fallback() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'video_category',
			'hide_empty' => true,
			'number'     => 6,
			'orderby'    => 'count',
			'order'      => 'DESC',
		)
	);

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return;
	}

	echo '<ul class="nt-footer__list">';

	foreach ( $terms as $term ) {
		echo '<li><a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '<span class="nt-count">' . esc_html( number_format_i18n( $term->count ) ) . '</span></a></li>';
	}

	echo '</ul>';
}

/**
 * Output social links from the Customizer.
 */
function novatube_social_links() {
	$networks = array(
		'x'         => array(
			'label' => __( 'X', 'novatube' ),
			'icon'  => 'x',
		),
		'instagram' => array(
			'label' => __( 'Instagram', 'novatube' ),
			'icon'  => 'instagram',
		),
		'telegram'  => array(
			'label' => __( 'Telegram', 'novatube' ),
			'icon'  => 'telegram',
		),
	);

	$links = array();

	foreach ( $networks as $key => $network ) {
		$url = get_theme_mod( 'novatube_social_' . $key, '' );

		if ( ! $url ) {
			continue;
		}

		$links[] = '<a class="nt-social" href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer nofollow" aria-label="' . esc_attr( $network['label'] ) . '">' . novatube_icon( $network['icon'], 18 ) . '</a>';
	}

	if ( $links ) {
		echo '<div class="nt-socials">' . implode( '', $links ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above.
	}
}

/**
 * Get an ad slot.
 *
 * @param string $location Ad location.
 * @return string
 */
function novatube_get_ad( $location = 'sidebar' ) {
	$html = (string) get_theme_mod( 'novatube_ad_' . $location, '' );

	if ( '' === trim( $html ) ) {
		return '';
	}

	$allowed = novatube_allowed_embed_html();
	$allowed['ins'] = array(
		'class'                   => true,
		'style'                   => true,
		'data-ad-client'          => true,
		'data-ad-slot'            => true,
		'data-ad-format'          => true,
		'data-full-width-responsive' => true,
	);

	return '<aside class="nt-ad nt-ad--' . esc_attr( $location ) . '"><span class="nt-ad__label">' . esc_html__( 'Advertisement', 'novatube' ) . '</span><div class="nt-ad__inner">' . wp_kses( $html, $allowed ) . '</div></aside>';
}

/**
 * Echo an ad slot.
 *
 * @param string $location Ad location.
 */
function novatube_ad( $location = 'sidebar' ) {
	echo novatube_get_ad( $location ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized in helper.
}

/**
 * Render the sort tabs for video archives.
 */
function novatube_sort_tabs() {
	$current = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'latest'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( is_tax() ) {
		$term = get_queried_object();
		$base = get_term_link( $term );
	} else {
		$base = novatube_videos_url();
	}

	if ( is_wp_error( $base ) ) {
		$base = novatube_videos_url();
	}

	$tabs = array(
		'latest'  => __( 'Latest', 'novatube' ),
		'popular' => __( 'Most viewed', 'novatube' ),
		'rating'  => __( 'Top rated', 'novatube' ),
		'oldest'  => __( 'Oldest', 'novatube' ),
	);
	?>
	<div class="nt-segmented" role="navigation" aria-label="<?php esc_attr_e( 'Sort videos', 'novatube' ); ?>">
		<?php foreach ( $tabs as $key => $label ) : ?>
			<?php
			$url = 'latest' === $key ? $base : add_query_arg( 'sort', $key, $base );
			?>
			<a class="nt-segmented__item <?php echo $current === $key ? 'is-active' : ''; ?>" href="<?php echo esc_url( $url ); ?>"<?php echo $current === $key ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $label ); ?></a>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Sort video archives.
 *
 * @param WP_Query $query Query.
 */
function novatube_sort_video_archive( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_post_type_archive( 'video' ) && ! $query->is_tax( array( 'video_category', 'video_tag', 'model' ) ) ) {
		return;
	}

	$sort = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	switch ( $sort ) {
		case 'popular':
			$query->set( 'meta_key', '_novatube_views' );
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', 'DESC' );
			break;
		case 'rating':
			$query->set( 'meta_key', '_novatube_rating' );
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', 'DESC' );
			break;
		case 'oldest':
			$query->set( 'orderby', 'date' );
			$query->set( 'order', 'ASC' );
			break;
	}
}
add_action( 'pre_get_posts', 'novatube_sort_video_archive' );



/**
 * Print VideoObject structured data.
 */
function novatube_video_schema() {
	if ( ! is_singular( 'video' ) ) {
		return;
	}

	$post_id = get_queried_object_id();
	$post    = get_post( $post_id );
	$rating  = novatube_get_rating( $post_id );
	$duration = novatube_duration_iso( novatube_get_duration( $post_id ) );
	$image    = get_the_post_thumbnail_url( $post_id, 'novatube-hero' );

	$schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'VideoObject',
		'name'          => get_the_title( $post_id ),
		'description'   => wp_trim_words( wp_strip_all_tags( get_the_excerpt( $post ) ), 55, '…' ),
		'uploadDate'    => get_the_date( 'c', $post_id ),
		'isFamilyFriendly' => false,
		'interactionStatistic' => array(
			'@type'               => 'InteractionCounter',
			'interactionType'     => 'https://schema.org/WatchAction',
			'userInteractionCount' => novatube_get_views( $post_id ),
		),
		'publisher'     => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( $duration ) {
		$schema['duration'] = $duration;
	}

	if ( $image ) {
		$schema['thumbnailUrl'] = $image;
	}

	if ( $rating['count'] > 0 && $rating['rating'] > 0 ) {
		$schema['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => round( $rating['rating'], 2 ),
			'ratingCount' => $rating['count'],
			'bestRating'  => 5,
			'worstRating' => 1,
		);
	}

	$json = wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	$json = str_replace( '</', '<\/', (string) $json );

	echo '<script type="application/ld+json">' . $json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON encoded.
}
add_action( 'wp_head', 'novatube_video_schema', 30 );

/**
 * Reading time for articles.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function novatube_reading_time( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );

	/* translators: %s: reading time in minutes. */
	return sprintf( _n( '%s min read', '%s min read', $minutes, 'novatube' ), number_format_i18n( $minutes ) );
}

/**
 * Relative published date for cards.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function novatube_relative_date( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$time    = get_post_time( 'U', false, $post_id );

	if ( ! $time ) {
		return '';
	}

	if ( ( time() - $time ) < MONTH_IN_SECONDS ) {
		/* translators: %s: human-readable time difference. */
		return sprintf( __( '%s ago', 'novatube' ), human_time_diff( $time, time() ) );
	}

	return get_the_date( '', $post_id );
}

/**
 * Output a compact rating badge.
 *
 * @param int $post_id Post ID.
 */
function novatube_rating_badge( $post_id = 0 ) {
	$rating = novatube_get_rating( $post_id );

	if ( $rating['count'] < 1 || $rating['rating'] <= 0 ) {
		return;
	}

	echo '<span class="nt-badge nt-badge--yellow">' . novatube_icon( 'star', 12 ) . '<span>' . esc_html( number_format_i18n( $rating['rating'], 1 ) ) . '</span></span>';
}

/**
 * Output the interactive rating widget.
 *
 * @param int $post_id Post ID.
 */
function novatube_rating_widget( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$rating  = novatube_get_rating( $post_id );
	$rounded = (int) round( $rating['rating'] );
	?>
	<div class="nt-rating" data-video-id="<?php echo esc_attr( $post_id ); ?>">
		<div class="nt-rating__stars" role="group" aria-label="<?php esc_attr_e( 'Rate this video', 'novatube' ); ?>">
			<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
				<button type="button" class="nt-star <?php echo $i <= $rounded ? 'is-filled' : ''; ?>" data-value="<?php echo esc_attr( $i ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: star rating. */ __( 'Rate %d out of 5', 'novatube' ), $i ) ); ?>">
					<?php novatube_the_icon( 'star', 18 ); ?>
				</button>
			<?php endfor; ?>
		</div>
		<span class="nt-rating__value"><span class="js-rating-value"><?php echo esc_html( number_format_i18n( $rating['rating'], 1 ) ); ?></span><span>/ 5</span></span>
		<span class="nt-rating__count">(<span class="js-rating-count"><?php echo esc_html( number_format_i18n( $rating['count'] ) ); ?></span> <span class="js-rating-label"><?php echo esc_html( _n( 'vote', 'votes', $rating['count'], 'novatube' ) ); ?></span>)</span>
		<span class="nt-rating__hint js-rating-hint"><?php esc_html_e( 'Click a star to rate', 'novatube' ); ?></span>
	</div>
	<?php
}

/**
 * Add a no-sidebar fallback module for popular videos.
 */
function novatube_default_sidebar() {
	$popular = novatube_get_popular_videos( 5 );

	if ( $popular ) :
		?>
		<section class="nt-widget nt-widget--trending">
			<h2 class="nt-widget__title"><?php novatube_the_icon( 'fire', 17 ); ?><?php esc_html_e( 'Trending now', 'novatube' ); ?></h2>
			<ol class="nt-trending">
				<?php foreach ( $popular as $index => $video_id ) : ?>
					<li class="nt-trending__item">
						<span class="nt-trending__rank"><?php echo esc_html( number_format_i18n( $index + 1 ) ); ?></span>
						<a class="nt-trending__thumb" href="<?php echo esc_url( get_permalink( $video_id ) ); ?>" tabindex="-1" aria-hidden="true">
							<?php echo novatube_get_thumbnail( $video_id, 'novatube-thumb' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped helper. ?>
							<?php $duration = novatube_get_duration( $video_id ); ?>
							<?php if ( $duration ) : ?>
								<span class="nt-badge nt-badge--duration"><?php echo esc_html( $duration ); ?></span>
							<?php endif; ?>
						</a>
						<div class="nt-trending__body">
							<h3 class="nt-trending__title"><a href="<?php echo esc_url( get_permalink( $video_id ) ); ?>"><?php echo esc_html( get_the_title( $video_id ) ); ?></a></h3>
							<span class="nt-trending__meta"><?php novatube_the_icon( 'eye', 12 ); ?><?php echo esc_html( novatube_format_count( novatube_get_views( $video_id ) ) ); ?></span>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</section>
		<?php
	endif;

	$terms = get_terms(
		array(
			'taxonomy'   => 'video_category',
			'hide_empty' => true,
			'number'     => 10,
			'orderby'    => 'count',
			'order'      => 'DESC',
		)
	);

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
		?>
		<section class="nt-widget nt-widget--categories">
			<h2 class="nt-widget__title"><?php novatube_the_icon( 'grid', 17 ); ?><?php esc_html_e( 'Categories', 'novatube' ); ?></h2>
			<ul class="nt-cat-list">
				<?php foreach ( $terms as $term ) : ?>
					<li>
						<a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?><span class="nt-cat-list__count"><?php echo esc_html( number_format_i18n( $term->count ) ); ?></span></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
		<?php
	endif;
}
