<?php
/**
 * Video details meta box and registered post meta.
 *
 * @package NovaTube
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * HTML tags permitted in the video embed field.
 *
 * Scripts are intentionally never allowed. Site owners who need a player
 * that requires custom JavaScript can extend this through the
 * `novatube_embed_allowed_html` filter or the `novatube_embed_html` filter.
 *
 * @return array<string, array<string, bool>>
 */
function novatube_allowed_embed_html() {
	$allowed = wp_kses_allowed_html( 'post' );

	$allowed['iframe'] = array(
		'src'             => true,
		'width'           => true,
		'height'          => true,
		'frameborder'     => true,
		'allow'           => true,
		'allowfullscreen' => true,
		'loading'         => true,
		'referrerpolicy'  => true,
		'scrolling'       => true,
		'title'           => true,
		'class'           => true,
		'id'              => true,
		'name'            => true,
		'sandbox'         => true,
	);

	$allowed['video'] = array(
		'src'          => true,
		'poster'       => true,
		'controls'     => true,
		'preload'      => true,
		'autoplay'     => true,
		'muted'        => true,
		'loop'         => true,
		'playsinline'  => true,
		'width'        => true,
		'height'       => true,
		'class'        => true,
		'id'           => true,
		'controlslist' => true,
	);

	$allowed['source'] = array(
		'src'  => true,
		'type' => true,
	);

	$allowed['track'] = array(
		'src'     => true,
		'kind'    => true,
		'srclang' => true,
		'label'   => true,
		'default' => true,
	);

	/**
	 * Filter the allowed HTML for the video embed field.
	 *
	 * @param array $allowed Allowed HTML map.
	 */
	return apply_filters( 'novatube_embed_allowed_html', $allowed );
}

/**
 * Sanitize the embed field. Keeps plain URLs intact, filters HTML embeds.
 *
 * @param string $value Raw value.
 * @return string
 */
function novatube_sanitize_embed( $value ) {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	if ( preg_match( '#^https?://\S+$#i', $value ) ) {
		return esc_url_raw( $value );
	}

	return wp_kses( $value, novatube_allowed_embed_html() );
}

/**
 * Sanitize a video duration.
 *
 * Accepts "12:34", "1:02:03", or a number of seconds.
 *
 * @param string $value Raw value.
 * @return string
 */
function novatube_sanitize_duration( $value ) {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	if ( preg_match( '/^\d{1,3}:\d{2}(?::\d{2})?$/', $value ) ) {
		return $value;
	}

	if ( ctype_digit( $value ) ) {
		return (string) absint( $value );
	}

	return '';
}

/**
 * Sanitize a rating between 0 and 5.
 *
 * @param mixed $value Raw value.
 * @return float
 */
function novatube_sanitize_rating( $value ) {
	return round( max( 0, min( 5, (float) $value ) ), 2 );
}

/**
 * Register post meta for videos.
 */
function novatube_register_video_meta() {
	$auth = static function () {
		return current_user_can( 'edit_posts' );
	};

	$fields = array(
		'_novatube_embed'        => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_embed',
		),
		'_novatube_duration'     => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_duration',
		),
		'_novatube_release_date' => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_video_date',
		),
		'_novatube_site'         => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_video_text',
		),
		'_novatube_file_name'    => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_video_text',
		),
		'_novatube_file_size'    => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_video_text',
		),
		'_novatube_resolution'   => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_video_text',
		),
		'_novatube_bitrate'      => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_video_text',
		),
		'_novatube_fps'          => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_video_text',
		),
		'_novatube_audio'        => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_video_text',
		),
		'_novatube_trailer'      => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_video_url',
		),
		'_novatube_preview'      => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_preview_urls',
		),
		'_novatube_downloads'    => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_link_lines',
		),
		'_novatube_spare_links'  => array(
			'type'              => 'string',
			'sanitize_callback' => 'novatube_sanitize_link_lines',
		),
	);

	foreach ( $fields as $key => $field ) {
		register_post_meta(
			'video',
			$key,
			array(
				'type'              => $field['type'],
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => $field['sanitize_callback'],
				'auth_callback'     => $auth,
			)
		);
	}

	register_post_meta(
		'video',
		'_novatube_hd',
		array(
			'type'              => 'boolean',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'auth_callback'     => $auth,
		)
	);

	register_post_meta(
		'video',
		'_novatube_featured',
		array(
			'type'              => 'boolean',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'auth_callback'     => $auth,
		)
	);

	register_post_meta(
		'video',
		'_novatube_views',
		array(
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => $auth,
		)
	);

	register_post_meta(
		'video',
		'_novatube_rating',
		array(
			'type'              => 'number',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'novatube_sanitize_rating',
			'auth_callback'     => $auth,
		)
	);

	register_post_meta(
		'video',
		'_novatube_rating_count',
		array(
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => $auth,
		)
	);
}
add_action( 'init', 'novatube_register_video_meta', 20 );

/**
 * Add the Video Details meta box.
 */
function novatube_add_video_meta_box() {
	add_meta_box(
		'novatube-video-details',
		__( 'Video Details', 'novatube' ),
		'novatube_render_video_meta_box',
		'video',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_video', 'novatube_add_video_meta_box' );

/**
 * Render a text input row inside the meta box.
 *
 * @param string $id      Field ID.
 * @param string $name    Field name.
 * @param string $label   Label.
 * @param string $value   Value.
 * @param string $hint    Description.
 * @param string $type    Input type.
 * @param string $extra   Extra attributes.
 */
function novatube_meta_input( $id, $name, $label, $value, $hint = '', $type = 'text', $extra = '' ) {
	?>
	<p>
		<label for="<?php echo esc_attr( $id ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label><br>
		<input type="<?php echo esc_attr( $type ); ?>" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" class="regular-text" value="<?php echo esc_attr( $value ); ?>" <?php echo $extra; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static attributes. ?>>
		<?php if ( $hint ) : ?>
			<span class="description"><?php echo esc_html( $hint ); ?></span>
		<?php endif; ?>
	</p>
	<?php
}

/**
 * Render the Video Details meta box.
 *
 * @param WP_Post $post Current post.
 */
function novatube_render_video_meta_box( $post ) {
	wp_nonce_field( 'novatube_save_video_meta', 'novatube_video_meta_nonce' );

	$embed        = get_post_meta( $post->ID, '_novatube_embed', true );
	$duration     = get_post_meta( $post->ID, '_novatube_duration', true );
	$release_date = get_post_meta( $post->ID, '_novatube_release_date', true );
	$site         = get_post_meta( $post->ID, '_novatube_site', true );
	$file_name    = get_post_meta( $post->ID, '_novatube_file_name', true );
	$file_size    = get_post_meta( $post->ID, '_novatube_file_size', true );
	$resolution   = get_post_meta( $post->ID, '_novatube_resolution', true );
	$bitrate      = get_post_meta( $post->ID, '_novatube_bitrate', true );
	$fps          = get_post_meta( $post->ID, '_novatube_fps', true );
	$audio        = get_post_meta( $post->ID, '_novatube_audio', true );
	$trailer      = get_post_meta( $post->ID, '_novatube_trailer', true );
	$preview      = get_post_meta( $post->ID, '_novatube_preview', true );
	$downloads    = get_post_meta( $post->ID, '_novatube_downloads', true );
	$spare_links  = get_post_meta( $post->ID, '_novatube_spare_links', true );
	$hd           = get_post_meta( $post->ID, '_novatube_hd', true );
	$featured     = get_post_meta( $post->ID, '_novatube_featured', true );
	$views        = (int) get_post_meta( $post->ID, '_novatube_views', true );
	$rating       = (float) get_post_meta( $post->ID, '_novatube_rating', true );
	$rating_count = (int) get_post_meta( $post->ID, '_novatube_rating_count', true );
	?>
	<div class="novatube-meta">
		<div class="novatube-meta__section">
			<h3 class="novatube-meta__heading"><?php esc_html_e( 'Playback & release', 'novatube' ); ?></h3>

			<p class="novatube-meta__row">
				<label for="novatube-embed"><strong><?php esc_html_e( 'Video embed URL or code', 'novatube' ); ?></strong></label>
			</p>
			<textarea id="novatube-embed" name="novatube_embed" rows="5" class="large-text code" placeholder="<?php esc_attr_e( 'https://example.com/video.mp4 — or paste an iframe embed code', 'novatube' ); ?>"><?php echo esc_textarea( $embed ); ?></textarea>
			<p class="description">
				<?php esc_html_e( 'Accepts a direct .mp4/.webm URL, a YouTube or Vimeo URL, or an iframe embed code. Scripts inside pasted embed codes are removed.', 'novatube' ); ?>
			</p>

			<div class="novatube-meta__grid">
				<?php
				novatube_meta_input( 'novatube-duration', 'novatube_duration', __( 'Duration', 'novatube' ), $duration, __( 'mm:ss, hh:mm:ss, or seconds.', 'novatube' ) );
				novatube_meta_input( 'novatube-release-date', 'novatube_release_date', __( 'Release date', 'novatube' ), $release_date, '', 'date' );
				novatube_meta_input( 'novatube-site', 'novatube_site', __( 'Site / studio', 'novatube' ), $site, __( 'Shown on the video page.', 'novatube' ) );
				novatube_meta_input( 'novatube-trailer', 'novatube_trailer', __( 'Trailer URL', 'novatube' ), $trailer, __( 'Optional official trailer link.', 'novatube' ), 'url' );
				?>
			</div>
		</div>

		<div class="novatube-meta__section">
			<h3 class="novatube-meta__heading"><?php esc_html_e( 'File details', 'novatube' ); ?></h3>
			<div class="novatube-meta__grid novatube-meta__grid--three">
				<?php
				novatube_meta_input( 'novatube-file-name', 'novatube_file_name', __( 'File name', 'novatube' ), $file_name, __( 'For example, Studio.26.11.25.Title.XXX.1080p.MP4-WRB.mp4', 'novatube' ) );
				novatube_meta_input( 'novatube-file-size', 'novatube_file_size', __( 'File size', 'novatube' ), $file_size, __( 'For example, 1483 MB.', 'novatube' ) );
				novatube_meta_input( 'novatube-resolution', 'novatube_resolution', __( 'Resolution', 'novatube' ), $resolution, __( 'For example, 1920x1080.', 'novatube' ) );
				novatube_meta_input( 'novatube-bitrate', 'novatube_bitrate', __( 'Bitrate', 'novatube' ), $bitrate, __( 'For example, 10200 kbits/s.', 'novatube' ) );
				novatube_meta_input( 'novatube-fps', 'novatube_fps', __( 'Frame rate', 'novatube' ), $fps, __( 'For example, 25.00 FPS.', 'novatube' ) );
				novatube_meta_input( 'novatube-audio', 'novatube_audio', __( 'Audio', 'novatube' ), $audio, __( 'For example, 48000 Hz, 189 kbits/s.', 'novatube' ) );
				?>
			</div>
		</div>

		<div class="novatube-meta__section">
			<h3 class="novatube-meta__heading"><?php esc_html_e( 'Downloads & preview', 'novatube' ); ?></h3>

			<p>
				<label for="novatube-downloads"><strong><?php esc_html_e( 'Download links', 'novatube' ); ?></strong></label>
				<textarea id="novatube-downloads" name="novatube_downloads" rows="4" class="large-text code" placeholder="<?php esc_attr_e( 'Rapidgator | https://example.com/file.mp4 | 1483 MB', 'novatube' ); ?>"><?php echo esc_textarea( $downloads ); ?></textarea>
				<span class="description"><?php esc_html_e( 'One link per line: Label | URL | Size. Size is optional.', 'novatube' ); ?></span>
			</p>

			<p>
				<label for="novatube-spare-links"><strong><?php esc_html_e( 'Spare links', 'novatube' ); ?></strong></label>
				<textarea id="novatube-spare-links" name="novatube_spare_links" rows="3" class="large-text code" placeholder="<?php esc_attr_e( 'K2S | https://example.com/backup.mp4', 'novatube' ); ?>"><?php echo esc_textarea( $spare_links ); ?></textarea>
				<span class="description"><?php esc_html_e( 'Backup mirrors, shown behind a “Show spare links” disclosure.', 'novatube' ); ?></span>
			</p>

			<p>
				<label for="novatube-preview"><strong><?php esc_html_e( 'Preview image URLs', 'novatube' ); ?></strong></label>
				<textarea id="novatube-preview" name="novatube_preview" rows="3" class="large-text code" placeholder="<?php esc_attr_e( 'https://example.com/preview-01.jpg', 'novatube' ); ?>"><?php echo esc_textarea( $preview ); ?></textarea>
				<span class="description"><?php esc_html_e( 'One image URL per line. The video thumbnail is used as the poster; preview images open in a lightbox.', 'novatube' ); ?></span>
			</p>
		</div>

		<div class="novatube-meta__section">
			<h3 class="novatube-meta__heading"><?php esc_html_e( 'Stats & flags', 'novatube' ); ?></h3>

			<div class="novatube-meta__grid">
				<?php
				novatube_meta_input( 'novatube-views', 'novatube_views', __( 'Views', 'novatube' ), (string) $views, __( 'New views are added automatically.', 'novatube' ), 'number', 'min="0" step="1"' );
				novatube_meta_input( 'novatube-rating', 'novatube_rating', __( 'Rating (0–5)', 'novatube' ), (string) $rating, '', 'number', 'min="0" max="5" step="0.1"' );
				novatube_meta_input( 'novatube-rating-count', 'novatube_rating_count', __( 'Rating count', 'novatube' ), (string) $rating_count, '', 'number', 'min="0" step="1"' );
				?>
			</div>

			<p>
				<label>
					<input type="checkbox" name="novatube_hd" value="1" <?php checked( $hd, '1' ); ?>>
					<?php esc_html_e( 'HD video (shows an HD badge)', 'novatube' ); ?>
				</label>
			</p>

			<p>
				<label>
					<input type="checkbox" name="novatube_featured" value="1" <?php checked( $featured, '1' ); ?>>
					<?php esc_html_e( 'Feature in the homepage spotlight', 'novatube' ); ?>
				</label>
			</p>

			<p class="description">
				<?php esc_html_e( 'Tip: set a featured image to use as the video poster. If none is set, the theme shows a branded placeholder.', 'novatube' ); ?>
			</p>
		</div>
	</div>
	<?php
}

/**
 * Save the video details.
 *
 * @param int $post_id Post ID.
 */
function novatube_save_video_meta( $post_id ) {
	if ( ! isset( $_POST['novatube_video_meta_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['novatube_video_meta_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'novatube_save_video_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( 'video' !== get_post_type( $post_id ) ) {
		return;
	}

	$text_fields = array(
		'novatube_embed'        => array( '_novatube_embed', 'novatube_sanitize_embed' ),
		'novatube_duration'     => array( '_novatube_duration', 'novatube_sanitize_duration' ),
		'novatube_release_date' => array( '_novatube_release_date', 'novatube_sanitize_video_date' ),
		'novatube_site'         => array( '_novatube_site', 'novatube_sanitize_video_text' ),
		'novatube_file_name'    => array( '_novatube_file_name', 'novatube_sanitize_video_text' ),
		'novatube_file_size'    => array( '_novatube_file_size', 'novatube_sanitize_video_text' ),
		'novatube_resolution'   => array( '_novatube_resolution', 'novatube_sanitize_video_text' ),
		'novatube_bitrate'      => array( '_novatube_bitrate', 'novatube_sanitize_video_text' ),
		'novatube_fps'          => array( '_novatube_fps', 'novatube_sanitize_video_text' ),
		'novatube_audio'        => array( '_novatube_audio', 'novatube_sanitize_video_text' ),
		'novatube_trailer'      => array( '_novatube_trailer', 'novatube_sanitize_video_url' ),
		'novatube_preview'      => array( '_novatube_preview', 'novatube_sanitize_preview_urls' ),
		'novatube_downloads'    => array( '_novatube_downloads', 'novatube_sanitize_link_lines' ),
		'novatube_spare_links'  => array( '_novatube_spare_links', 'novatube_sanitize_link_lines' ),
	);

	foreach ( $text_fields as $field => $meta ) {
		if ( isset( $_POST[ $field ] ) ) {
			$value = call_user_func( $meta[1], wp_unslash( $_POST[ $field ] ) );
			update_post_meta( $post_id, $meta[0], $value );
		}
	}

	if ( isset( $_POST['novatube_views'] ) ) {
		update_post_meta( $post_id, '_novatube_views', absint( $_POST['novatube_views'] ) );
	}

	if ( isset( $_POST['novatube_rating'] ) ) {
		$rating = novatube_sanitize_rating( wp_unslash( $_POST['novatube_rating'] ) );
		update_post_meta( $post_id, '_novatube_rating', $rating );
	}

	if ( isset( $_POST['novatube_rating_count'] ) ) {
		update_post_meta( $post_id, '_novatube_rating_count', absint( $_POST['novatube_rating_count'] ) );
	}

	update_post_meta( $post_id, '_novatube_hd', isset( $_POST['novatube_hd'] ) ? '1' : '0' );
	update_post_meta( $post_id, '_novatube_featured', isset( $_POST['novatube_featured'] ) ? '1' : '0' );
}
add_action( 'save_post_video', 'novatube_save_video_meta' );

/**
 * Ensure every video has numeric meta so archive sorting includes it.
 *
 * @param int $post_id Post ID.
 */
function novatube_ensure_video_defaults( $post_id ) {
	foreach ( array(
		'_novatube_views'        => 0,
		'_novatube_rating'       => 0,
		'_novatube_rating_count' => 0,
	) as $key => $default ) {
		if ( '' === (string) get_post_meta( $post_id, $key, true ) ) {
			update_post_meta( $post_id, $key, $default );
		}
	}
}
add_action( 'save_post_video', 'novatube_ensure_video_defaults', 20 );

/**
 * Backfill numeric meta once so existing libraries sort correctly.
 */
function novatube_backfill_video_meta() {
	if ( get_option( 'novatube_meta_backfilled' ) ) {
		return;
	}

	$ids = get_posts(
		array(
			'post_type'      => 'video',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);

	foreach ( $ids as $post_id ) {
		novatube_ensure_video_defaults( (int) $post_id );
	}

	update_option( 'novatube_meta_backfilled', 1 );
}
add_action( 'after_switch_theme', 'novatube_backfill_video_meta' );
add_action( 'admin_init', 'novatube_backfill_video_meta' );

/**
 * Small stylesheet for the meta box layout.
 *
 * @param string $hook Current admin page.
 */
function novatube_admin_meta_styles( $hook ) {
	if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'video' !== $screen->post_type ) {
		return;
	}

	wp_add_inline_style(
		'wp-admin',
		'.novatube-meta__section{padding:4px 0 18px;margin-bottom:18px;border-bottom:1px solid #dcdcde}.novatube-meta__section:last-child{margin-bottom:0;border-bottom:0;padding-bottom:0}.novatube-meta__heading{margin:0 0 12px;font-size:14px;letter-spacing:.01em}.novatube-meta__grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:4px 18px}.novatube-meta__grid--three{grid-template-columns:repeat(3,minmax(0,1fr))}.novatube-meta__grid p{margin-top:6px}.novatube-meta__grid .regular-text{width:100%}.novatube-meta textarea{font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:12px}.novatube-meta .description{display:block;margin-top:3px}@media (max-width:1100px){.novatube-meta__grid,.novatube-meta__grid--three{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (max-width:600px){.novatube-meta__grid,.novatube-meta__grid--three{grid-template-columns:1fr}}'
	);
}
add_action( 'admin_enqueue_scripts', 'novatube_admin_meta_styles' );
