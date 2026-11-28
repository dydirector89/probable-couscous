<?php
/**
 * Page template.
 *
 * @package NovaTube
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<main id="main" class="site-main">
		<div class="nt-container--narrow nt-section">
			<?php novatube_breadcrumbs(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'nt-article' ); ?>>
				<header class="nt-article__head">
					<span class="nt-eyebrow"><?php novatube_the_icon( 'folder', 13 ); ?><?php esc_html_e( 'Page', 'novatube' ); ?></span>
					<h1 class="nt-article__title"><?php the_title(); ?></h1>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="nt-article__thumb">
						<?php the_post_thumbnail( 'novatube-hero' ); ?>
					</figure>
				<?php endif; ?>

				<div class="nt-prose">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<nav class="nt-pagination">' . esc_html__( 'Pages:', 'novatube' ),
							'after'  => '</nav>',
						)
					);
					?>
				</div>

				<?php edit_post_link( __( 'Edit page', 'novatube' ), '<p class="nt-page-desc">', '</p>' ); ?>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</article>
		</div>
	</main>
	<?php
endwhile;

get_footer();
