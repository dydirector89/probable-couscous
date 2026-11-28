<?php
/**
 * Blog index and fallback template.
 *
 * @package NovaTube
 */

get_header();

$novatube_page_for_posts = (int) get_option( 'page_for_posts' );
$novatube_title          = $novatube_page_for_posts ? get_the_title( $novatube_page_for_posts ) : __( 'Latest articles', 'novatube' );
$novatube_description    = $novatube_page_for_posts ? get_post_field( 'post_content', $novatube_page_for_posts ) : get_bloginfo( 'description' );
?>
<main id="main" class="site-main">
	<div class="nt-container nt-section">
		<?php novatube_breadcrumbs(); ?>

		<header class="nt-page-head">
			<div class="nt-page-head__main">
				<span class="nt-eyebrow"><?php novatube_the_icon( 'folder', 13 ); ?><?php esc_html_e( 'The blog', 'novatube' ); ?></span>
				<h1 class="nt-page-title"><?php echo esc_html( $novatube_title ); ?></h1>
				<?php if ( $novatube_description ) : ?>
					<div class="nt-page-desc"><?php echo wp_kses_post( wpautop( $novatube_description ) ); ?></div>
				<?php endif; ?>
			</div>
		</header>

		<div class="nt-layout">
			<div class="nt-layout__main">
				<?php if ( have_posts() ) : ?>
					<div class="nt-grid nt-grid--posts">
						<?php
						while ( have_posts() ) {
							the_post();
							get_template_part( 'template-parts/post-card', null, array( 'post_id' => get_the_ID() ) );
						}
						?>
					</div>

					<?php novatube_pagination(); ?>
				<?php else : ?>
					<?php
					get_template_part(
						'template-parts/content-none',
						null,
						array(
							'title'   => __( 'No articles published', 'novatube' ),
							'message' => __( 'Nothing has been published here yet. Check back soon or explore the video library.', 'novatube' ),
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
