<?php
/**
 * Theme setup, assets, menus, and widget areas.
 *
 * @package NovaTube
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports and menus.
 */
function novatube_setup() {
	load_theme_textdomain( 'novatube', NOVATUBE_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 64,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'novatube' ),
			'footer'  => esc_html__( 'Footer Menu', 'novatube' ),
		)
	);

	add_image_size( 'novatube-card', 520, 293, true );
	add_image_size( 'novatube-hero', 1280, 720, true );
	add_image_size( 'novatube-thumb', 320, 180, true );

	// Used by oEmbed and responsive embeds.
	$GLOBALS['content_width'] = 1120;
}
add_action( 'after_setup_theme', 'novatube_setup' );

/**
 * Register the main sidebar.
 */
function novatube_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Main Sidebar', 'novatube' ),
			'id'            => 'novatube-sidebar',
			'description'   => esc_html__( 'Shown beside video grids and video pages.', 'novatube' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'novatube_widgets_init' );

/**
 * Enqueue styles and scripts.
 */
function novatube_scripts() {
	$style_path = NOVATUBE_DIR . '/assets/css/main.css';
	$style_ver  = file_exists( $style_path ) ? (string) filemtime( $style_path ) : NOVATUBE_VERSION;

	wp_enqueue_style( 'novatube-style', NOVATUBE_URI . '/assets/css/main.css', array(), $style_ver );

	$script_path = NOVATUBE_DIR . '/assets/js/main.js';
	$script_ver  = file_exists( $script_path ) ? (string) filemtime( $script_path ) : NOVATUBE_VERSION;

	wp_enqueue_script( 'novatube-script', NOVATUBE_URI . '/assets/js/main.js', array(), $script_ver, true );

	wp_localize_script(
		'novatube-script',
		'novatubeData',
		array(
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'loadNonce'  => wp_create_nonce( 'novatube_ajax' ),
			'rateNonce'  => wp_create_nonce( 'novatube_rate' ),
			'ageGate'    => (bool) get_theme_mod( 'novatube_age_gate', true ),
			'i18n'       => array(
				'loading'    => esc_html__( 'Loading…', 'novatube' ),
				'loadMore'   => esc_html__( 'Load more videos', 'novatube' ),
				'noMore'     => esc_html__( 'You’ve reached the end.', 'novatube' ),
				'error'      => esc_html__( 'Something went wrong. Please try again.', 'novatube' ),
				'copied'     => esc_html__( 'Link copied to clipboard', 'novatube' ),
				'copyError'  => esc_html__( 'Could not copy the link. Please copy it manually.', 'novatube' ),
				'rateError'  => esc_html__( 'Your rating could not be saved.', 'novatube' ),
				'rateThanks' => esc_html__( 'Thanks for rating!', 'novatube' ),
				'votes'      => esc_html__( 'votes', 'novatube' ),
				'vote'       => esc_html__( 'vote', 'novatube' ),
			),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'novatube_scripts' );

/**
 * Print customizer colors early in the head.
 */
function novatube_customizer_css() {
	$accent  = sanitize_hex_color( get_theme_mod( 'novatube_accent_color', '#ff3b6b' ) );
	$accent2 = sanitize_hex_color( get_theme_mod( 'novatube_accent_color_2', '#ff8a3d' ) );

	$accent  = $accent ? $accent : '#ff3b6b';
	$accent2 = $accent2 ? $accent2 : '#ff8a3d';

	printf(
		'<style id="novatube-customizer-css">:root{--accent:%1$s;--accent-2:%2$s;}</style>' . "\n",
		esc_html( $accent ),
		esc_html( $accent2 )
	);
}
add_action( 'wp_head', 'novatube_customizer_css', 20 );

/**
 * Add the age-verified class before paint when the visitor already confirmed.
 */
function novatube_age_gate_early_script() {
	if ( ! get_theme_mod( 'novatube_age_gate', true ) ) {
		return;
	}
	?>
	<script id="novatube-age-gate-early">
		try {
			if ( window.localStorage.getItem( 'novatube_age_verified' ) === '1' ) {
				document.documentElement.classList.add( 'novatube-age-verified' );
			}
		} catch ( e ) {}
	</script>
	<?php
}
add_action( 'wp_head', 'novatube_age_gate_early_script', 1 );

/**
 * Body classes.
 *
 * @param string[] $classes Existing classes.
 * @return string[]
 */
function novatube_body_classes( $classes ) {
	$classes[] = 'novatube';

	if ( ! is_active_sidebar( 'novatube-sidebar' ) ) {
		$classes[] = 'no-sidebar-widgets';
	}

	if ( is_singular( 'video' ) ) {
		$classes[] = 'single-video-page';
	}

	return $classes;
}
add_filter( 'body_class', 'novatube_body_classes' );

/**
 * Pingback header.
 */
function novatube_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'novatube_pingback_header' );

/**
 * Shorter excerpts for cards.
 *
 * @return int
 */
function novatube_excerpt_length() {
	return 24;
}
add_filter( 'excerpt_length', 'novatube_excerpt_length' );

/**
 * Excerpt more text.
 *
 * @return string
 */
function novatube_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'novatube_excerpt_more' );

/**
 * Redirect /?novatube_random=1 to a random video.
 */
function novatube_random_video_redirect() {
	if ( ! isset( $_GET['novatube_random'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	$ids = get_posts(
		array(
			'post_type'      => 'video',
			'posts_per_page' => 1,
			'orderby'        => 'rand',
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);

	if ( $ids ) {
		wp_safe_redirect( get_permalink( $ids[0] ) );
		exit;
	}

	wp_safe_redirect( home_url( '/' ) );
	exit;
}
add_action( 'template_redirect', 'novatube_random_video_redirect' );
