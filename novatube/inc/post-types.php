<?php
/**
 * Video post type and taxonomies.
 *
 * @package NovaTube
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the video post type.
 */
function novatube_register_post_types() {
	$labels = array(
		'name'                  => _x( 'Videos', 'Post type general name', 'novatube' ),
		'singular_name'         => _x( 'Video', 'Post type singular name', 'novatube' ),
		'menu_name'             => _x( 'Videos', 'Admin Menu text', 'novatube' ),
		'name_admin_bar'        => _x( 'Video', 'Add New on Toolbar', 'novatube' ),
		'add_new'               => __( 'Add New', 'novatube' ),
		'add_new_item'          => __( 'Add New Video', 'novatube' ),
		'new_item'              => __( 'New Video', 'novatube' ),
		'edit_item'             => __( 'Edit Video', 'novatube' ),
		'view_item'             => __( 'View Video', 'novatube' ),
		'all_items'             => __( 'All Videos', 'novatube' ),
		'search_items'          => __( 'Search Videos', 'novatube' ),
		'not_found'             => __( 'No videos found.', 'novatube' ),
		'not_found_in_trash'    => __( 'No videos found in Trash.', 'novatube' ),
		'featured_image'        => __( 'Video Thumbnail', 'novatube' ),
		'set_featured_image'    => __( 'Set video thumbnail', 'novatube' ),
		'remove_featured_image' => __( 'Remove video thumbnail', 'novatube' ),
		'use_featured_image'    => __( 'Use as video thumbnail', 'novatube' ),
		'archives'              => __( 'Video Archives', 'novatube' ),
		'attributes'            => __( 'Video Attributes', 'novatube' ),
		'insert_into_item'      => __( 'Insert into video', 'novatube' ),
		'uploaded_to_this_item' => __( 'Uploaded to this video', 'novatube' ),
		'filter_items_list'     => __( 'Filter videos list', 'novatube' ),
		'items_list_navigation' => __( 'Videos list navigation', 'novatube' ),
		'items_list'            => __( 'Videos list', 'novatube' ),
	);

	register_post_type(
		'video',
		array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_rest'       => true,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-video-alt3',
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'has_archive'        => 'videos',
			'hierarchical'       => false,
			'rewrite'            => array(
				'slug'       => 'video',
				'with_front' => false,
			),
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'revisions' ),
		)
	);
}
add_action( 'init', 'novatube_register_post_types' );

/**
 * Register taxonomies used by videos.
 */
function novatube_register_taxonomies() {
	register_taxonomy(
		'video_category',
		array( 'video' ),
		array(
			'labels'            => array(
				'name'              => _x( 'Video Categories', 'taxonomy general name', 'novatube' ),
				'singular_name'     => _x( 'Video Category', 'taxonomy singular name', 'novatube' ),
				'search_items'      => __( 'Search Video Categories', 'novatube' ),
				'all_items'         => __( 'All Video Categories', 'novatube' ),
				'parent_item'       => __( 'Parent Video Category', 'novatube' ),
				'parent_item_colon' => __( 'Parent Video Category:', 'novatube' ),
				'edit_item'         => __( 'Edit Video Category', 'novatube' ),
				'update_item'       => __( 'Update Video Category', 'novatube' ),
				'add_new_item'      => __( 'Add New Video Category', 'novatube' ),
				'new_item_name'     => __( 'New Video Category Name', 'novatube' ),
				'menu_name'         => __( 'Categories', 'novatube' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'         => 'video-category',
				'with_front'   => false,
				'hierarchical' => true,
			),
		)
	);

	register_taxonomy(
		'video_tag',
		array( 'video' ),
		array(
			'labels'            => array(
				'name'                       => _x( 'Video Tags', 'taxonomy general name', 'novatube' ),
				'singular_name'              => _x( 'Video Tag', 'taxonomy singular name', 'novatube' ),
				'search_items'               => __( 'Search Video Tags', 'novatube' ),
				'popular_items'              => __( 'Popular Video Tags', 'novatube' ),
				'all_items'                  => __( 'All Video Tags', 'novatube' ),
				'edit_item'                  => __( 'Edit Video Tag', 'novatube' ),
				'update_item'                => __( 'Update Video Tag', 'novatube' ),
				'add_new_item'               => __( 'Add New Video Tag', 'novatube' ),
				'new_item_name'              => __( 'New Video Tag Name', 'novatube' ),
				'separate_items_with_commas' => __( 'Separate tags with commas', 'novatube' ),
				'add_or_remove_items'        => __( 'Add or remove tags', 'novatube' ),
				'choose_from_most_used'      => __( 'Choose from the most used tags', 'novatube' ),
				'menu_name'                  => __( 'Tags', 'novatube' ),
			),
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'video-tag',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'model',
		array( 'video' ),
		array(
			'labels'            => array(
				'name'                       => _x( 'Models', 'taxonomy general name', 'novatube' ),
				'singular_name'              => _x( 'Model', 'taxonomy singular name', 'novatube' ),
				'search_items'               => __( 'Search Models', 'novatube' ),
				'popular_items'              => __( 'Popular Models', 'novatube' ),
				'all_items'                  => __( 'All Models', 'novatube' ),
				'edit_item'                  => __( 'Edit Model', 'novatube' ),
				'update_item'                => __( 'Update Model', 'novatube' ),
				'add_new_item'               => __( 'Add New Model', 'novatube' ),
				'new_item_name'              => __( 'New Model Name', 'novatube' ),
				'separate_items_with_commas' => __( 'Separate models with commas', 'novatube' ),
				'add_or_remove_items'        => __( 'Add or remove models', 'novatube' ),
				'choose_from_most_used'      => __( 'Choose from the most used models', 'novatube' ),
				'menu_name'                  => __( 'Models', 'novatube' ),
			),
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'model',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'novatube_register_taxonomies', 6 );

/**
 * Flush rewrite rules when the theme is activated.
 */
function novatube_flush_rewrites() {
	novatube_register_post_types();
	novatube_register_taxonomies();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'novatube_flush_rewrites' );

/**
 * Admin thumbnail column for videos.
 *
 * @param array<string, string> $columns Current columns.
 * @return array<string, string>
 */
function novatube_video_columns( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		if ( 'title' === $key ) {
			$new['novatube_thumb'] = __( 'Thumbnail', 'novatube' );
		}
		$new[ $key ] = $label;

		if ( 'title' === $key ) {
			$new['novatube_duration'] = __( 'Duration', 'novatube' );
			$new['novatube_views']    = __( 'Views', 'novatube' );
			$new['novatube_rating']   = __( 'Rating', 'novatube' );
		}
	}

	return $new;
}
add_filter( 'manage_video_posts_columns', 'novatube_video_columns' );

/**
 * Render custom admin columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function novatube_video_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'novatube_thumb':
			if ( has_post_thumbnail( $post_id ) ) {
				echo get_the_post_thumbnail( $post_id, array( 80, 45 ), array( 'style' => 'width:80px;height:auto;border-radius:4px;' ) );
			} else {
				echo '—';
			}
			break;

		case 'novatube_duration':
			$duration = get_post_meta( $post_id, '_novatube_duration', true );
			echo $duration ? esc_html( $duration ) : '—';
			break;

		case 'novatube_views':
			echo esc_html( number_format_i18n( (int) get_post_meta( $post_id, '_novatube_views', true ) ) );
			break;

		case 'novatube_rating':
			$rating = (float) get_post_meta( $post_id, '_novatube_rating', true );
			$count  = (int) get_post_meta( $post_id, '_novatube_rating_count', true );

			if ( $rating > 0 ) {
				printf(
					/* translators: 1: rating out of five, 2: number of votes. */
					esc_html__( '%1$s / 5 (%2$s)', 'novatube' ),
					esc_html( number_format_i18n( $rating, 1 ) ),
					esc_html( number_format_i18n( $count ) )
				);
			} else {
				echo '—';
			}
			break;
	}
}
add_action( 'manage_video_posts_custom_column', 'novatube_video_column_content', 10, 2 );

/**
 * Make video admin columns sortable.
 *
 * @param array<string, string> $columns Sortable columns.
 * @return array<string, string>
 */
function novatube_video_sortable_columns( $columns ) {
	$columns['novatube_views']  = 'novatube_views';
	$columns['novatube_rating'] = 'novatube_rating';
	return $columns;
}
add_filter( 'manage_edit-video_sortable_columns', 'novatube_video_sortable_columns' );

/**
 * Handle sorting by custom meta in the admin list table.
 *
 * @param WP_Query $query Query object.
 */
function novatube_admin_video_orderby( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$orderby = $query->get( 'orderby' );

	if ( 'novatube_views' === $orderby ) {
		$query->set( 'meta_key', '_novatube_views' );
		$query->set( 'orderby', 'meta_value_num' );
	}

	if ( 'novatube_rating' === $orderby ) {
		$query->set( 'meta_key', '_novatube_rating' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}
add_action( 'pre_get_posts', 'novatube_admin_video_orderby' );

/**
 * Show a helpful hint on the video archive admin screen.
 */
function novatube_admin_video_notice() {
	$screen = get_current_screen();

	if ( ! $screen || 'edit-video' !== $screen->id ) {
		return;
	}

	$counts = wp_count_posts( 'video' );
	$total  = 0;

	foreach ( array( 'publish', 'draft', 'pending', 'future', 'private' ) as $status ) {
		$total += isset( $counts->$status ) ? (int) $counts->$status : 0;
	}

	if ( $total ) {
		return;
	}

	echo '<div class="notice notice-info"><p>';
	echo esc_html__( 'Add a Video Embed URL or embed code, a thumbnail, and a duration from the “Video Details” panel when editing a video.', 'novatube' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'novatube_admin_video_notice' );
