<?php
/**
 * Video detail meta helpers.
 *
 * Stores the extra release, file, download, trailer, and preview data used by
 * the video page layout. Values are plain strings so they stay editable in the
 * classic editor and visible through the REST API.
 *
 * @package NovaTube
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize a short video text field.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function novatube_sanitize_video_text( $value ) {
	return sanitize_text_field( (string) $value );
}

/**
 * Sanitize a video URL field.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function novatube_sanitize_video_url( $value ) {
	return esc_url_raw( trim( (string) $value ) );
}

/**
 * Sanitize a release date (Y-m-d).
 *
 * @param mixed $value Raw value.
 * @return string
 */
function novatube_sanitize_video_date( $value ) {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	if ( preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $value, $matches ) && checkdate( (int) $matches[2], (int) $matches[3], (int) $matches[1] ) ) {
		return $value;
	}

	return '';
}

/**
 * Sanitize a newline-separated list of preview image URLs.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function novatube_sanitize_preview_urls( $value ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $value );
	$urls  = array();

	foreach ( (array) $lines as $line ) {
		$url = esc_url_raw( trim( $line ) );

		if ( $url && ! in_array( $url, $urls, true ) ) {
			$urls[] = $url;
		}
	}

	return implode( "\n", $urls );
}

/**
 * Sanitize download rows.
 *
 * Each line uses `Label | URL | Size`. A bare URL is also accepted.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function novatube_sanitize_link_lines( $value ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $value );
	$rows  = array();

	foreach ( (array) $lines as $line ) {
		$line = trim( $line );

		if ( '' === $line ) {
			continue;
		}

		$parts = array_map( 'trim', explode( '|', $line ) );
		$url   = '';

		if ( isset( $parts[1] ) ) {
			$label = sanitize_text_field( $parts[0] );
			$url   = esc_url_raw( $parts[1] );
			$size  = isset( $parts[2] ) ? sanitize_text_field( $parts[2] ) : '';
		} else {
			$label = '';
			$url   = esc_url_raw( $parts[0] );
			$size  = '';
		}

		if ( ! $url || ! preg_match( '#^https?://#i', $url ) ) {
			continue;
		}

		$host = wp_parse_url( $url, PHP_URL_HOST );

		if ( ! $label ) {
			$label = $host ? $host : __( 'Download', 'novatube' );
		}

		$rows[] = implode( ' | ', array_filter( array( $label, $url, $size ), 'strlen' ) );
	}

	return implode( "\n", $rows );
}

/**
 * Parse sanitized download rows into structured items.
 *
 * @param string $raw Raw meta value.
 * @return array<int, array{label: string, url: string, size: string, host: string}>
 */
function novatube_parse_link_lines( $raw ) {
	$raw  = trim( (string) $raw );
	$rows = array();

	if ( '' === $raw ) {
		return $rows;
	}

	$lines = preg_split( '/\r\n|\r|\n/', $raw );

	foreach ( (array) $lines as $line ) {
		$line = trim( $line );

		if ( '' === $line ) {
			continue;
		}

		$parts = array_map( 'trim', explode( '|', $line ) );
		$url   = '';

		if ( isset( $parts[1] ) ) {
			$label = $parts[0];
			$url   = $parts[1];
			$size  = isset( $parts[2] ) ? $parts[2] : '';
		} else {
			$label = '';
			$url   = $parts[0];
			$size  = '';
		}

		if ( ! $url || ! preg_match( '#^https?://#i', $url ) ) {
			continue;
		}

		$host = (string) wp_parse_url( $url, PHP_URL_HOST );

		if ( ! $label ) {
			$label = $host ? $host : __( 'Download', 'novatube' );
		}

		$rows[] = array(
			'label' => $label,
			'url'   => $url,
			'size'  => $size,
			'host'  => preg_replace( '/^www\./i', '', $host ),
		);
	}

	return $rows;
}

/**
 * Get all extra details for a video.
 *
 * @param int $post_id Video ID.
 * @return array<string, mixed>
 */
function novatube_get_video_details( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : (int) get_the_ID();
	$date    = (string) get_post_meta( $post_id, '_novatube_release_date', true );
	$date_ts = $date ? strtotime( $date . ' 12:00:00' ) : (int) get_post_time( 'U', false, $post_id );

	$previews_raw = (string) get_post_meta( $post_id, '_novatube_preview', true );
	$previews     = array();

	foreach ( preg_split( '/\r\n|\r|\n/', $previews_raw ) as $line ) {
		$url = esc_url_raw( trim( $line ) );

		if ( $url ) {
			$previews[] = $url;
		}
	}

	return array(
		'release_date'    => $date,
		'release_ts'      => $date_ts ? $date_ts : 0,
		'release_display' => $date_ts ? date_i18n( (string) get_option( 'date_format' ), $date_ts ) : '',
		'release_iso'     => $date_ts ? gmdate( 'c', $date_ts ) : '',
		'site'            => (string) get_post_meta( $post_id, '_novatube_site', true ),
		'file_name'       => (string) get_post_meta( $post_id, '_novatube_file_name', true ),
		'file_size'       => (string) get_post_meta( $post_id, '_novatube_file_size', true ),
		'resolution'      => (string) get_post_meta( $post_id, '_novatube_resolution', true ),
		'bitrate'         => (string) get_post_meta( $post_id, '_novatube_bitrate', true ),
		'fps'             => (string) get_post_meta( $post_id, '_novatube_fps', true ),
		'audio'           => (string) get_post_meta( $post_id, '_novatube_audio', true ),
		'trailer'         => (string) get_post_meta( $post_id, '_novatube_trailer', true ),
		'previews'        => $previews,
		'downloads'       => novatube_parse_link_lines( (string) get_post_meta( $post_id, '_novatube_downloads', true ) ),
		'spare_links'     => novatube_parse_link_lines( (string) get_post_meta( $post_id, '_novatube_spare_links', true ) ),
	);
}

/**
 * Build a label => value list of file and release specs.
 *
 * @param int $post_id Video ID.
 * @return array<string, string>
 */
function novatube_get_video_specs( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : (int) get_the_ID();
	$details = novatube_get_video_details( $post_id );
	$specs   = array();

	if ( $details['file_name'] ) {
		$specs[ __( 'File name', 'novatube' ) ] = $details['file_name'];
	}

	if ( $details['file_size'] ) {
		$specs[ __( 'File size', 'novatube' ) ] = $details['file_size'];
	}

	$duration = novatube_get_duration( $post_id );

	if ( $duration ) {
		$specs[ __( 'Duration', 'novatube' ) ] = $duration;
	}

	if ( $details['resolution'] ) {
		$specs[ __( 'Resolution', 'novatube' ) ] = $details['resolution'];
	}

	if ( $details['bitrate'] ) {
		$specs[ __( 'Bitrate', 'novatube' ) ] = $details['bitrate'];
	}

	if ( $details['fps'] ) {
		$specs[ __( 'Frame rate', 'novatube' ) ] = $details['fps'];
	}

	if ( $details['audio'] ) {
		$specs[ __( 'Audio', 'novatube' ) ] = $details['audio'];
	}

	if ( $details['release_display'] ) {
		$specs[ __( 'Released', 'novatube' ) ] = $details['release_display'];
	}

	if ( $details['site'] ) {
		$specs[ __( 'Site', 'novatube' ) ] = $details['site'];
	}

	return $specs;
}

/**
 * Does a video have any file/download details worth showing?
 *
 * @param int $post_id Video ID.
 * @return bool
 */
function novatube_has_video_details( $post_id = 0 ) {
	$details = novatube_get_video_details( $post_id );

	return (bool) ( $details['downloads'] || $details['spare_links'] || $details['trailer'] || $details['previews'] || novatube_get_video_specs( $post_id ) );
}
