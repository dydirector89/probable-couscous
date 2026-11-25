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
		'src'         => true,
		'poster'      => true,
		'controls'    => true,
		'preload'     => true,
		'autoplay'    => true,
		'muted'       => true,
		'loop'        => true,
		'playsinline' => true,
		'width'       => true,
		'height'      => true,
		'class'       => true,
		'id'          => true,
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

	register_post_meta(
		'video',
		'_novatube_embed',
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'novatube_sanitize_embed',
			'auth_callback'     => $auth,
		)
	);

	register_post_meta(
		'video',
		'_novatube_duration',
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'novatube_sanitize_duration',
			'auth_callback'     => $auth,
		)
	);

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
 * Render the Video Details meta box.
 *
 * @param WP_Post $post Current post.
 */
function novatube_render_video_meta_box( $post ) {
	wp_nonce_field( 'novatube_save_video_meta', 'novatube_video_meta_nonce' );

	$embed        = get_post_meta( $post->ID, '_novatube_embed', true );
	$duration     = get_post_meta( $post->ID, '_novatube_duration', true );
	$hd           = get_post_meta( $post->ID, '_novatube_hd', true );
	$featured     = get_post_meta( $post->ID, '_novatube_featured', true );
	$views        = (int) get_post_meta( $post->ID, '_novatube_views', true );
	$rating       = (float) get_post_meta( $post->ID, '_novatube_rating', true );
	$rating_count = (int) get_post_meta( $post->ID, '_novatube_rating_count', true );
	?>
	<div class="novatube-meta">
		<p class="novatube-meta__row">
			<label for="novatube-embed"><strong><?php esc_html_e( 'Video embed URL or code', 'novatube' ); ?></strong></label>
		</p>
		<textarea id="novatube-embed" name="novatube_embed" rows="6" class="large-text code" placeholder="<?php esc_attr_e( 'https://example.com/video.mp4 — or paste an iframe embed code', 'novatube' ); ?>"><?php echo esc_textarea( $embed ); ?></textarea>
		<p class="description">
			<?php esc_html_e( 'Accepts a direct .mp4/.webm URL, a YouTube or Vimeo URL, or an iframe embed code. For security, scripts inside pasted embed codes are removed.', 'novatube' ); ?>
		</p>

		<div class="novatube-meta__grid">
			<p>
				<label for="novatube-duration"><strong><?php esc_html_e( 'Duration', 'novatube' ); ?></strong></label><br>
				<input type="text" id="novatube-duration" name="novatube_duration" class="regular-text" value="<?php echo esc_attr( $duration ); ?>" placeholder="12:34">
				<span class="description"><?php esc_html_e( 'mm:ss, hh:mm:ss, or seconds.', 'novatube' ); ?></span>
			</p>

			<p>
				<label for="novatube-views"><strong><?php esc_html_e( 'Views', 'novatube' ); ?></strong></label><br>
				<input type="number" min="0" step="1" id="novatube-views" name="novatube_views" class="small-text" value="<?php echo esc_attr( $views ); ?>">
				<span class="description"><?php esc_html_e( 'New views are added automatically.', 'novatube' ); ?></span>
			</p>

			<p>
				<label for="novatube-rating"><strong><?php esc_html_e( 'Rating (0–5)', 'novatube' ); ?></strong></label><br>
				<input type="number" min="0" max="5" step="0.1" id="novatube-rating" name="novatube_rating" class="small-text" value="<?php echo esc_attr( $rating ); ?>">
			</p>

			<p>
				<label for="novatube-rating-count"><strong><?php esc_html_e( 'Rating count', 'novatube' ); ?></strong></label><br>
				<input type="number" min="0" step="1" id="novatube-rating-count" name="novatube_rating_count" class="small-text" value="<?php echo esc_attr( $rating_count ); ?>">
			</p>
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
				<?php esc_html_e( 'Feature in the homepage hero', 'novatube' ); ?>
			</label>
		</p>

		<p class="description">
			<?php esc_html_e( 'Tip: set a featured image to use as the video thumbnail. If none is set, the theme shows a branded placeholder.', 'novatube' ); ?>
		</p>
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

	if ( isset( $_POST['novatube_embed'] ) ) {
		update_post_meta( $post_id, '_novatube_embed', novatube_sanitize_embed( wp_unslash( $_POST['novatube_embed'] ) ) );
	}

	if ( isset( $_POST['novatube_duration'] ) ) {
		update_post_meta( $post_id, '_novatube_duration', novatube_sanitize_duration( wp_unslash( $_POST['novatube_duration'] ) ) );
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
		'.novatube-meta__grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.novatube-meta textarea{font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:12px}@media (max-width:960px){.novatube-meta__grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (max-width:600px){.novatube-meta__grid{grid-template-columns:1fr}}'
	);
}
add_action( 'admin_enqueue_scripts', 'novatube_admin_meta_styles' );
