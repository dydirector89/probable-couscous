<?php
/**
 * Archive template for videos, taxonomies, and standard archives.
 *
 * @package NovaTube
 */

get_header();

$novatube_is_video_archive = is_post_type_archive( 'video' ) || is_tax( array( 'video_category', 'video_tag', 'model' ) );
$novatube_description      = '';
$novatube_eyebrow          = __( 'Archive', 'novatube' );

if ( $novatube_is_video_archive ) {
	if ( is_post_type_archive( 'video' ) ) {
		$novatube_title   = __( 'All videos', 'novatube' );
		$novatube_eyebrow = __( 'The library', 'novatube' );
	} else {
		$novatube_title = single_term_title( '', false );

		if ( is_tax( 'model' ) ) {
			$novatube_eyebrow = __( 'Model', 'novatube' );
		} elseif ( is_tax( 'video_tag' ) ) {
			$novatube_eyebrow = __( 'Tag', 'novatube' );
		} else {
			$novatube_eyebrow = __( 'Category', 'novatube' );
		}

		$novatube_description = term_description();
	}
} elseif ( is_category() ) {
	$novatube_title       = single_cat_title( '', false );
	$novatube_eyebrow     = __( 'Category', 'novatube' );
	$novatube_description = category_description();
} elseif ( is_tag() ) {
	$novatube_title   = single_tag_title( '', false );
	$novatube_eyebrow = __( 'Tag', 'novatube' );
} elseif ( is_author() ) {
	$novatube_title   = get_the_author();
	$novatube_eyebrow = __( 'Author', 'novatube' );
} elseif ( is_year() || is_month() || is_day() ) {
	$novatube_title   = get_the_archive_title();
	$novatube_eyebrow = __( 'Archive', 'novatube' );
} else {
	$novatube_title = get_the_archive_title();
}

$novatube_found = (int) $GLOBALS['wp_query']->found_posts;
?>
<main id="main" class="site-main">
	<div class="container section">
		<?php novatube_breadcrumbs(); ?>

		<header class="page-hero">
			<span class="eyebrow"><?php novatube_the_icon( 'film', 14 ); ?><?php echo esc_html( $novatube_eyebrow ); ?></span>
			<div class="page-hero__row">
				<div>
					<h1 class="page-title"><?php echo esc_html( wp_strip_all_tags( $novatube_title ) ); ?></h1>
					<?php if ( $novatube_description ) : ?>
						<div class="page-description"><?php echo wp_kses_post( wpautop( $novatube_description ) ); ?></div>
					<?php endif; ?>
				</div>
				<?php if ( $novatube_found ) : ?>
					<span class="page-count">
						<?php
						if ( $novatube_is_video_archive ) {
							/* translators: %s: video count. */
							echo esc_html( sprintf( _n( '%s video', '%s videos', $novatube_found, 'novatube' ), number_format_i18n( $novatube_found ) ) );
						} else {
							/* translators: %s: post count. */
							echo esc_html( sprintf( _n( '%s article', '%s articles', $novatube_found, 'novatube' ), number_format_i18n( $novatube_found ) ) );
						}
						?>
					</span>
				<?php endif; ?>
			</div>

			<?php if ( $novatube_is_video_archive ) : ?>
				<?php novatube_sort_tabs(); ?>
			<?php endif; ?>
		</header>

		<div class="layout layout--sidebar">
			<div class="layout__main">
				<?php if ( have_posts() ) : ?>
					<?php if ( $novatube_is_video_archive ) : ?>
						<div class="video-grid">
							<?php
							while ( have_posts() ) {
								the_post();
								get_template_part( 'template-parts/video-card', null, array( 'post_id' => get_the_ID() ) );
							}
							?>
						</div>
					<?php else : ?>
						<div class="post-list">
							<?php
							while ( have_posts() ) {
								the_post();
								get_template_part( 'template-parts/post-card', null, array( 'post_id' => get_the_ID() ) );
							}
							?>
						</div>
					<?php endif; ?>

					<?php novatube_pagination(); ?>
				<?php else : ?>
					<?php
					get_template_part(
						'template-parts/content-none',
						null,
						array(
							'title'   => __( 'No videos found', 'novatube' ),
							'message' => __( 'This archive is empty right now. Try another category or use the search box.', 'novatube' ),
						)
					);
					?>
				<?php endif; ?>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</main>
<?php
get_footer();
