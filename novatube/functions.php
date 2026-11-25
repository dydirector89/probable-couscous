<?php
/**
 * NovaTube functions and definitions.
 *
 * @package NovaTube
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NOVATUBE_VERSION', '1.0.0' );
define( 'NOVATUBE_DIR', get_template_directory() );
define( 'NOVATUBE_URI', get_template_directory_uri() );

require NOVATUBE_DIR . '/inc/setup.php';
require NOVATUBE_DIR . '/inc/post-types.php';
require NOVATUBE_DIR . '/inc/meta-boxes.php';
require NOVATUBE_DIR . '/inc/template-tags.php';
require NOVATUBE_DIR . '/inc/customizer.php';
require NOVATUBE_DIR . '/inc/ajax.php';
