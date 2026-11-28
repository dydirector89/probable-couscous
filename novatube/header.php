<?php
/**
 * The header.
 *
 * @package NovaTube
 */

$novatube_account_url   = is_user_logged_in() ? admin_url( 'profile.php' ) : wp_login_url();
$novatube_account_label = is_user_logged_in() ? __( 'Account', 'novatube' ) : __( 'Sign in', 'novatube' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> data-astryx-theme="neutral" data-theme="dark">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'novatube' ); ?></a>

<header id="masthead" class="nt-header">
	<div class="nt-container nt-header__inner">
		<button class="nt-iconbtn nt-menu-toggle js-drawer-open" type="button" aria-controls="nt-drawer" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'novatube' ); ?>">
			<?php novatube_the_icon( 'menu', 20 ); ?>
		</button>

		<?php if ( has_custom_logo() ) : ?>
			<div class="nt-brand nt-brand--logo"><?php the_custom_logo(); ?></div>
		<?php else : ?>
			<a class="nt-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<span class="nt-brand__mark"><?php novatube_the_icon( 'play', 17 ); ?></span>
				<span class="nt-brand__text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
				<?php if ( get_bloginfo( 'description' ) ) : ?>
					<span class="nt-brand__tag"><?php echo esc_html( wp_trim_words( get_bloginfo( 'description' ), 3, '' ) ); ?></span>
				<?php endif; ?>
			</a>
		<?php endif; ?>

		<nav class="nt-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'novatube' ); ?>">
			<?php novatube_primary_nav(); ?>
		</nav>

		<div class="nt-header__actions">
			<button class="nt-searchbtn js-search-open" type="button" aria-controls="nt-search" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open search', 'novatube' ); ?>">
				<?php novatube_the_icon( 'search', 16 ); ?>
				<span class="nt-searchbtn__label"><?php esc_html_e( 'Search videos…', 'novatube' ); ?></span>
				<span class="nt-kbd" aria-hidden="true">⌘K</span>
			</button>

			<button class="nt-iconbtn nt-theme-toggle js-theme-toggle" type="button" aria-label="<?php esc_attr_e( 'Toggle colour mode', 'novatube' ); ?>" title="<?php esc_attr_e( 'Toggle colour mode', 'novatube' ); ?>">
				<span class="icon-sun"><?php novatube_the_icon( 'sun', 18 ); ?></span>
				<span class="icon-moon"><?php novatube_the_icon( 'moon', 18 ); ?></span>
			</button>

			<button class="nt-iconbtn nt-iconbtn--outline nt-iconbtn--badge js-saved-open" type="button" aria-controls="nt-saved" aria-expanded="false" aria-label="<?php esc_attr_e( 'Saved videos', 'novatube' ); ?>" title="<?php esc_attr_e( 'Saved videos', 'novatube' ); ?>">
				<?php novatube_the_icon( 'bookmark', 18 ); ?>
				<span class="nt-iconbtn__count js-saved-count" hidden>0</span>
			</button>

			<a class="nt-account" href="<?php echo esc_url( $novatube_account_url ); ?>">
				<?php novatube_the_icon( 'user', 16 ); ?>
				<span><?php echo esc_html( $novatube_account_label ); ?></span>
			</a>
		</div>
	</div>
</header>

<?php
if ( get_theme_mod( 'novatube_show_chips', true ) && ( is_front_page() || is_post_type_archive( 'video' ) || is_tax( array( 'video_category', 'video_tag', 'model' ) ) || is_search() ) ) {
	novatube_category_chips();
}
?>

<dialog class="nt-drawer" id="nt-drawer" aria-labelledby="nt-drawer-title">
	<div class="nt-drawer__inner">
		<div class="nt-drawer__head">
			<a class="nt-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<span class="nt-brand__mark"><?php novatube_the_icon( 'play', 17 ); ?></span>
				<span class="nt-brand__text" id="nt-drawer-title"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
			</a>
			<button class="nt-dialog__close js-dialog-close" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'novatube' ); ?>">
				<?php novatube_the_icon( 'close', 18 ); ?>
			</button>
		</div>

		<div class="nt-drawer__body">
			<div class="nt-drawer__label"><?php esc_html_e( 'Browse', 'novatube' ); ?></div>
			<?php novatube_drawer_nav(); ?>

			<div class="nt-drawer__label"><?php esc_html_e( 'Your space', 'novatube' ); ?></div>
			<ul class="nt-drawer__nav">
				<li>
					<button class="js-saved-open nt-drawer__linkbtn" type="button" aria-controls="nt-saved">
						<?php novatube_the_icon( 'bookmark', 16 ); ?>
						<span><?php esc_html_e( 'Saved videos', 'novatube' ); ?></span>
						<span class="nt-count js-saved-count" hidden>0</span>
					</button>
				</li>
				<li>
					<button class="js-theme-toggle nt-drawer__linkbtn" type="button">
						<?php novatube_the_icon( 'moon', 16 ); ?>
						<span><?php esc_html_e( 'Toggle colour mode', 'novatube' ); ?></span>
					</button>
				</li>
				<li>
					<a href="<?php echo esc_url( $novatube_account_url ); ?>">
						<?php novatube_the_icon( 'user', 16 ); ?>
						<span><?php echo esc_html( $novatube_account_label ); ?></span>
					</a>
				</li>
			</ul>
		</div>

		<div class="nt-drawer__foot">
			<a class="nt-btn nt-btn--primary nt-btn--block" href="<?php echo esc_url( novatube_random_url() ); ?>">
				<?php novatube_the_icon( 'shuffle', 16 ); ?>
				<?php esc_html_e( 'Surprise me', 'novatube' ); ?>
			</a>
		</div>
	</div>
</dialog>

<dialog class="nt-dialog nt-dialog--search" id="nt-search" aria-labelledby="nt-search-title">
	<div class="nt-dialog__head">
		<div>
			<h2 class="nt-dialog__title" id="nt-search-title"><?php esc_html_e( 'Search NovaTube', 'novatube' ); ?></h2>
			<p class="nt-dialog__sub"><?php esc_html_e( 'Find videos, models, categories and articles.', 'novatube' ); ?></p>
		</div>
		<button class="nt-dialog__close js-dialog-close" type="button" aria-label="<?php esc_attr_e( 'Close search', 'novatube' ); ?>">
			<?php novatube_the_icon( 'close', 18 ); ?>
		</button>
	</div>

	<form class="nt-search__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" data-search-form>
		<div class="nt-search-field">
			<?php novatube_the_icon( 'search', 18 ); ?>
			<label class="screen-reader-text" for="nt-search-input"><?php esc_html_e( 'Search for:', 'novatube' ); ?></label>
			<input type="search" id="nt-search-input" name="s" placeholder="<?php esc_attr_e( 'Search videos, models, categories…', 'novatube' ); ?>" autocomplete="off" data-search-input>
			<span class="nt-kbd" aria-hidden="true">Esc</span>
		</div>
	</form>

	<div class="nt-dialog__body">
		<div class="nt-search__results" data-search-results>
			<div class="nt-search__empty"><?php esc_html_e( 'Start typing to see instant results.', 'novatube' ); ?></div>
		</div>
	</div>

	<div class="nt-search__footer">
		<div class="nt-search__hints">
			<span class="nt-search__hint"><span class="nt-kbd">↑</span><span class="nt-kbd">↓</span><?php esc_html_e( 'navigate', 'novatube' ); ?></span>
			<span class="nt-search__hint"><span class="nt-kbd">↵</span><?php esc_html_e( 'open', 'novatube' ); ?></span>
		</div>
		<a class="nt-search__hint" href="<?php echo esc_url( novatube_videos_url() ); ?>"><?php esc_html_e( 'Browse all videos', 'novatube' ); ?><?php novatube_the_icon( 'arrow-right', 13 ); ?></a>
	</div>
</dialog>

<dialog class="nt-dialog nt-dialog--saved" id="nt-saved" aria-labelledby="nt-saved-title">
	<div class="nt-dialog__head">
		<div>
			<h2 class="nt-dialog__title" id="nt-saved-title"><?php esc_html_e( 'Saved videos', 'novatube' ); ?></h2>
			<p class="nt-dialog__sub"><?php esc_html_e( 'Your private watchlist is stored on this device.', 'novatube' ); ?></p>
		</div>
		<button class="nt-dialog__close js-dialog-close" type="button" aria-label="<?php esc_attr_e( 'Close saved videos', 'novatube' ); ?>">
			<?php novatube_the_icon( 'close', 18 ); ?>
		</button>
	</div>
	<div class="nt-dialog__body">
		<div class="nt-saved__list" data-saved-list></div>
		<div class="nt-search__empty" data-saved-empty hidden><?php esc_html_e( 'No saved videos yet. Tap the bookmark on any video to keep it here.', 'novatube' ); ?></div>
	</div>
	<div class="nt-dialog__foot">
		<a class="nt-btn nt-btn--secondary nt-btn--sm" href="<?php echo esc_url( novatube_videos_url() ); ?>"><?php esc_html_e( 'Browse videos', 'novatube' ); ?></a>
	</div>
</dialog>

<dialog class="nt-dialog nt-dialog--lightbox" id="nt-lightbox" aria-label="<?php esc_attr_e( 'Preview image', 'novatube' ); ?>">
	<button class="nt-lightbox__close js-dialog-close" type="button" aria-label="<?php esc_attr_e( 'Close preview', 'novatube' ); ?>">
		<?php novatube_the_icon( 'close', 18 ); ?>
	</button>
	<div class="nt-dialog__body">
		<button class="nt-lightbox__nav nt-lightbox__nav--prev" type="button" data-lightbox-prev aria-label="<?php esc_attr_e( 'Previous image', 'novatube' ); ?>">
			<?php novatube_the_icon( 'chevron', 18 ); ?>
		</button>
		<img class="nt-lightbox__image" data-lightbox-image src="" alt="">
		<button class="nt-lightbox__nav nt-lightbox__nav--next" type="button" data-lightbox-next aria-label="<?php esc_attr_e( 'Next image', 'novatube' ); ?>">
			<?php novatube_the_icon( 'chevron', 18 ); ?>
		</button>
	</div>
	<span class="nt-lightbox__count" data-lightbox-count></span>
</dialog>

<div class="nt-toast" id="novatube-toast" role="status" aria-live="polite"></div>
