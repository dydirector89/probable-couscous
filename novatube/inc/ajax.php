<?php
/**
 * AJAX handlers for load more and video ratings.
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
			'rating' => number_format_i18n( $new_rating, 1 ),
			'count'  => number_format_i18n( $new_count ),
			'label'  => _n( 'vote', 'votes', $new_count, 'novatube' ),
			'message' => __( 'Thanks for rating!', 'novatube' ),
		)
	);
}
add_action( 'wp_ajax_novatube_rate_video', 'novatube_ajax_rate_video' );
add_action( 'wp_ajax_nopriv_novatube_rate_video', 'novatube_ajax_rate_video' );
