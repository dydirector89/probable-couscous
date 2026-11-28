<?php
/**
 * AJAX handlers for load more, ratings, views, and live search.
 *
 * @package NovaTube
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load more videos for the homepage grid.
 */
function novatube_ajax_load_more() {
	check_ajax_referer( 'novatube_ajax', 'nonce' );

	$page     = isset( $_POST['page'] ) ? max( 2, absint( $_POST['page'] ) ) : 2;
	$per_page = max( 1, min( 60, (int) get_theme_mod( 'novatube_videos_per_page', 20 ) ) );

	$query = new WP_Query(
		array(
			'post_type'           => 'video',
			'post_status'         => 'publish',
			'posts_per_page'      => $per_page,
			'paged'               => $page,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => false,
		)
	);

	ob_start();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/video-card', null, array( 'post_id' => get_the_ID() ) );
		}
	}

	$html = (string) ob_get_clean();
	wp_reset_postdata();

	wp_send_json_success(
		array(
			'html' => $html,
			'page' => $page,
			'max'  => (int) $query->max_num_pages,
		)
	);
}
add_action( 'wp_ajax_novatube_load_more', 'novatube_ajax_load_more' );
add_action( 'wp_ajax_nopriv_novatube_load_more', 'novatube_ajax_load_more' );

/**
 * Save a video rating.
 */
function novatube_ajax_rate_video() {
	check_ajax_referer( 'novatube_rate', 'nonce' );

	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
	$value   = isset( $_POST['rating'] ) ? absint( $_POST['rating'] ) : 0;

	if ( ! $post_id || 'video' !== get_post_type( $post_id ) || 'publish' !== get_post_status( $post_id ) || $value < 1 || $value > 5 ) {
		wp_send_json_error( array( 'message' => __( 'Invalid rating.', 'novatube' ) ), 400 );
	}

	$cookie_name = 'novatube_rated_' . $post_id;

	if ( isset( $_COOKIE[ $cookie_name ] ) ) {
		wp_send_json_error( array( 'message' => __( 'You have already rated this video.', 'novatube' ) ), 409 );
	}

	$old_rating = (float) get_post_meta( $post_id, '_novatube_rating', true );
	$old_count  = (int) get_post_meta( $post_id, '_novatube_rating_count', true );

	$new_count  = $old_count + 1;
	$new_rating = ( ( $old_rating * $old_count ) + $value ) / $new_count;

	update_post_meta( $post_id, '_novatube_rating', round( $new_rating, 2 ) );
	update_post_meta( $post_id, '_novatube_rating_count', $new_count );

	if ( ! headers_sent() ) {
		setcookie(
			$cookie_name,
			'1',
			array(
				'expires'  => time() + YEAR_IN_SECONDS,
				'path'     => '/',
				'secure'   => is_ssl(),
				'httponly' => true,
				'samesite' => 'Lax',
			)
		);
	}

	wp_send_json_success(
		array(
			'rating'  => number_format_i18n( $new_rating, 1 ),
			'count'   => number_format_i18n( $new_count ),
			'label'   => _n( 'vote', 'votes', $new_count, 'novatube' ),
			'message' => __( 'Thanks for rating!', 'novatube' ),
		)
	);
}
add_action( 'wp_ajax_novatube_rate_video', 'novatube_ajax_rate_video' );
add_action( 'wp_ajax_nopriv_novatube_rate_video', 'novatube_ajax_rate_video' );

/**
 * Count a video view once per browser.
 *
 * The view is counted after the age gate is accepted (or immediately when the
 * gate is disabled), so a visitor who leaves never inflates the counter.
 */
function novatube_ajax_count_view() {
	check_ajax_referer( 'novatube_view', 'nonce' );

	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

	if ( ! $post_id || 'video' !== get_post_type( $post_id ) || 'publish' !== get_post_status( $post_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid video.', 'novatube' ) ), 400 );
	}

	if ( current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_success( array( 'counted' => false ) );
	}

	$viewed = array();

	if ( isset( $_COOKIE['novatube_viewed'] ) ) {
		$viewed = array_filter( array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_COOKIE['novatube_viewed'] ) ) ) ) );
	}

	if ( in_array( $post_id, $viewed, true ) ) {
		wp_send_json_success(
			array(
				'counted' => false,
				'views'   => novatube_format_count( novatube_get_views( $post_id ) ),
			)
		);
	}

	$views = novatube_get_views( $post_id ) + 1;
	update_post_meta( $post_id, '_novatube_views', $views );

	$viewed[] = $post_id;
	$viewed   = array_slice( array_unique( $viewed ), -40 );

	if ( ! headers_sent() ) {
		setcookie(
			'novatube_viewed',
			implode( ',', $viewed ),
			array(
				'expires'  => time() + MONTH_IN_SECONDS,
				'path'     => '/',
				'secure'   => is_ssl(),
				'httponly' => true,
				'samesite' => 'Lax',
			)
		);
	}

	wp_send_json_success(
		array(
			'counted' => true,
			'views'   => novatube_format_count( $views ),
		)
	);
}
add_action( 'wp_ajax_novatube_count_view', 'novatube_ajax_count_view' );
add_action( 'wp_ajax_nopriv_novatube_count_view', 'novatube_ajax_count_view' );

/**
 * Render a compact live-search item.
 *
 * @param int    $post_id Post ID.
 * @param string $type    Type label.
 * @return string
 */
function novatube_get_search_item_html( $post_id, $type = '' ) {
	$title = get_the_title( $post_id );
	$url   = get_permalink( $post_id );

	if ( ! $type ) {
		$type = 'video' === get_post_type( $post_id ) ? __( 'Video', 'novatube' ) : __( 'Article', 'novatube' );
	}

	$meta = $type;

	if ( 'video' === get_post_type( $post_id ) ) {
		$views = novatube_get_views( $post_id );
		$meta  = $views ? sprintf( /* translators: 1: type, 2: view count. */ __( '%1$s · %2$s views', 'novatube' ), $type, novatube_format_count( $views ) ) : $type;
	}

	ob_start();
	?>
	<a class="nt-search__item" href="<?php echo esc_url( $url ); ?>">
		<span class="nt-search__thumb">
			<?php echo novatube_get_thumbnail( $post_id, 'novatube-thumb', array( 'alt' => $title ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>
		</span>
		<span class="nt-search__text">
			<span class="nt-search__title"><?php echo esc_html( $title ); ?></span>
			<span class="nt-search__meta"><?php echo esc_html( $meta ); ?></span>
		</span>
		<?php novatube_the_icon( 'arrow-right', 15 ); ?>
	</a>
	<?php
	return (string) ob_get_clean();
}

/**
 * Live search results for the search dialog.
 */
function novatube_ajax_search() {
	check_ajax_referer( 'novatube_search', 'nonce' );

	$term = isset( $_POST['term'] ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';

	if ( mb_strlen( $term ) < 2 ) {
		wp_send_json_success(
			array(
				'html'  => '<div class="nt-search__empty">' . esc_html__( 'Type at least two characters to search.', 'novatube' ) . '</div>',
				'count' => 0,
			)
		);
	}

	$query = new WP_Query(
		array(
			'post_type'           => array( 'video', 'post', 'page' ),
			'post_status'         => 'publish',
			's'                   => $term,
			'posts_per_page'      => 8,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	$videos = array();
	$others = array();

	foreach ( $query->posts as $result ) {
		if ( 'video' === $result->post_type ) {
			$videos[] = $result;
		} else {
			$others[] = $result;
		}
	}

	$html = '';

	if ( $videos ) {
		$html .= '<div class="nt-search__group"><div class="nt-search__group-label">' . esc_html__( 'Videos', 'novatube' ) . '</div>';

		foreach ( $videos as $result ) {
			$html .= novatube_get_search_item_html( $result->ID, __( 'Video', 'novatube' ) );
		}

		$html .= '</div>';
	}

	if ( $others ) {
		$html .= '<div class="nt-search__group"><div class="nt-search__group-label">' . esc_html__( 'Articles & pages', 'novatube' ) . '</div>';

		foreach ( $others as $result ) {
			$html .= novatube_get_search_item_html( $result->ID, __( 'Article', 'novatube' ) );
		}

		$html .= '</div>';
	}

	if ( ! $html ) {
		$html = '<div class="nt-search__empty">' . esc_html__( 'No matches. Try another keyword.', 'novatube' ) . '</div>';
	}

	wp_send_json_success(
		array(
			'html'  => $html,
			'count' => count( $query->posts ),
			'url'   => add_query_arg( 's', rawurlencode( $term ), home_url( '/' ) ),
		)
	);
}
add_action( 'wp_ajax_novatube_search', 'novatube_ajax_search' );
add_action( 'wp_ajax_nopriv_novatube_search', 'novatube_ajax_search' );
