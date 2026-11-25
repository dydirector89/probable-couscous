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
		<div class="container section">
			<?php novatube_breadcrumbs(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-single narrow' ); ?>>
				<header class="page-head">
					<span class="eyebrow"><?php novatube_the_icon( 'film', 14 ); ?><?php esc_html_e( 'Page', 'novatube' ); ?></span>
					<h1 class="page-title"><?php the_title(); ?></h1>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="article-hero">
						<?php the_post_thumbnail( 'novatube-hero', array( 'class' => 'article-hero__image' ) ); ?>
					</figure>
				<?php endif; ?>

				<div class="entry-content">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'novatube' ),
							'after'  => '</nav>',
						)
					);
					?>
				</div>

				<?php edit_post_link( __( 'Edit page', 'novatube' ), '<p class="edit-link">', '</p>' ); ?>

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
