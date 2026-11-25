<?php
/**
 * The header.
 *
 * @package NovaTube
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'novatube' ); ?></a>

<header id="masthead" class="site-header">
	<div class="container header-bar">
		<button class="nav-toggle" type="button" aria-controls="primary-menu-wrap" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'novatube' ); ?>">
			<?php novatube_the_icon( 'menu', 24 ); ?>
			<?php novatube_the_icon( 'close', 24 ); ?>
		</button>

		<div class="site-branding">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<span class="logo__mark"><?php novatube_the_icon( 'play', 17 ); ?></span>
					<span class="logo__text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<div class="header-search">
			<?php get_search_form(); ?>
		</div>

		<div class="header-actions">
			<a class="btn btn--ghost btn--shuffle" href="<?php echo esc_url( novatube_random_url() ); ?>">
				<?php novatube_the_icon( 'shuffle', 18 ); ?>
				<span class="btn__label"><?php esc_html_e( 'Shuffle', 'novatube' ); ?></span>
			</a>

			<?php if ( is_user_logged_in() ) : ?>
				<?php
				$current_user = wp_get_current_user();
				$first_name   = $current_user->first_name ? $current_user->first_name : $current_user->display_name;
				?>
				<a class="btn btn--primary" href="<?php echo esc_url( admin_url() ); ?>">
					<?php novatube_the_icon( 'user', 17 ); ?>
					<span class="btn__label"><?php echo esc_html( wp_trim_words( $first_name, 1, '' ) ); ?></span>
				</a>
			<?php elseif ( get_option( 'users_can_register' ) ) : ?>
				<a class="btn btn--primary" href="<?php echo esc_url( wp_registration_url() ); ?>">
					<span class="btn__label"><?php esc_html_e( 'Join free', 'novatube' ); ?></span>
				</a>
			<?php else : ?>
				<a class="btn btn--primary" href="<?php echo esc_url( wp_login_url() ); ?>">
					<?php novatube_the_icon( 'user', 17 ); ?>
					<span class="btn__label"><?php esc_html_e( 'Sign in', 'novatube' ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</div>

	<div class="primary-menu-wrap" id="primary-menu-wrap">
		<div class="container">
			<nav class="main-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'novatube' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'nav-list',
						'fallback_cb'    => 'novatube_primary_nav_fallback',
						'depth'          => 2,
					)
				);
				?>
			</nav>
			<a class="nav-random" href="<?php echo esc_url( novatube_random_url() ); ?>">
				<?php novatube_the_icon( 'shuffle', 16 ); ?>
				<?php esc_html_e( 'Surprise me', 'novatube' ); ?>
			</a>
		</div>
	</div>
</header>

<?php
if ( get_theme_mod( 'novatube_show_chips', true ) && ( is_front_page() || is_post_type_archive( 'video' ) || is_tax( array( 'video_category', 'video_tag', 'model' ) ) || is_search() ) ) {
	novatube_category_chips();
}
