<?php
/**
 * Single article template.
 *
 * @package NovaTube
 */

get_header();

while ( have_posts() ) :
	the_post();

	$novatube_post_id   = get_the_ID();
	$novatube_share_url = rawurlencode( get_permalink() );
	$novatube_share_txt = rawurlencode( get_the_title() );
	$novatube_cats      = get_the_category();
	$novatube_related   = array();
	$novatube_cat_ids   = wp_list_pluck( $novatube_cats, 'term_id' );
	$novatube_author_id = (int) get_the_author_meta( 'ID' );
	$novatube_author    = $novatube_author_id ? get_the_author_meta( 'display_name', $novatube_author_id ) : '';

	if ( $novatube_cat_ids ) {
		$novatube_related = get_posts(
			array(
				'post_type'      => 'post',
				'posts_per_page' => 3,
				'fields'         => 'ids',
				'post__not_in'   => array( $novatube_post_id ),
				'category__in'   => array_map( 'intval', $novatube_cat_ids ),
				'no_found_rows'  => true,
			)
		);
	}
	?>
	<main id="main" class="site-main">
		<div class="container section">
			<?php novatube_breadcrumbs(); ?>

			<div class="layout layout--sidebar">
				<div class="layout__main">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-single' ); ?>>
						<header class="article-head">
							<?php if ( ! empty( $novatube_cats ) ) : ?>
								<a class="video-category" href="<?php echo esc_url( get_category_link( $novatube_cats[0]->term_id ) ); ?>"><?php echo esc_html( $novatube_cats[0]->name ); ?></a>
							<?php endif; ?>

							<h1 class="article-title"><?php the_title(); ?></h1>

							<div class="article-meta">
								<?php if ( $novatube_author ) : ?>
									<?php echo get_avatar( $novatube_author_id, 36, '', '', array( 'class' => 'article-meta__avatar' ) ); ?>
									<span class="article-meta__author"><?php esc_html_e( 'By', 'novatube' ); ?> <a href="<?php echo esc_url( get_author_posts_url( $novatube_author_id ) ); ?>"><?php echo esc_html( $novatube_author ); ?></a></span>
									<span class="meta-dot" aria-hidden="true"></span>
								<?php endif; ?>
								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								<span class="meta-dot" aria-hidden="true"></span>
								<span><?php echo esc_html( novatube_reading_time( $novatube_post_id ) ); ?></span>
								<?php if ( comments_open() || get_comments_number() ) : ?>
									<span class="meta-dot" aria-hidden="true"></span>
									<a href="#comments"><?php echo esc_html( get_comments_number_text( __( 'No comments', 'novatube' ), __( '1 comment', 'novatube' ), __( '% comments', 'novatube' ) ) ); ?></a>
								<?php endif; ?>
							</div>
						</header>

						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="article-hero">
								<?php the_post_thumbnail( 'novatube-hero', array( 'class' => 'article-hero__image' ) ); ?>
							</figure>
						<?php endif; ?>

						<div class="entry-content article-content">
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

						<footer class="article-footer">
							<?php
							$novatube_tags = get_the_tag_list( '', '' );
							if ( $novatube_tags ) :
								?>
								<div class="article-tags">
									<span class="video-taxonomy__label"><?php esc_html_e( 'Tags', 'novatube' ); ?></span>
									<div class="chips"><?php echo wp_kses_post( $novatube_tags ); ?></div>
								</div>
							<?php endif; ?>

							<div class="article-share">
								<span><?php esc_html_e( 'Share:', 'novatube' ); ?></span>
								<button type="button" class="icon-btn js-copy-link" data-url="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'Copy link', 'novatube' ); ?>"><?php novatube_the_icon( 'link', 16 ); ?></button>
								<a class="icon-btn" href="<?php echo esc_url( 'https://twitter.com/intent/tweet?url=' . $novatube_share_url . '&text=' . $novatube_share_txt ); ?>" target="_blank" rel="noopener noreferrer nofollow" aria-label="<?php esc_attr_e( 'Share on X', 'novatube' ); ?>"><?php novatube_the_icon( 'x', 16 ); ?></a>
								<a class="icon-btn" href="<?php echo esc_url( 'mailto:?subject=' . $novatube_share_txt . '&body=' . $novatube_share_url ); ?>" aria-label="<?php esc_attr_e( 'Share by email', 'novatube' ); ?>"><?php novatube_the_icon( 'mail', 17 ); ?></a>
							</div>
						</footer>

						<?php edit_post_link( __( 'Edit article', 'novatube' ), '<p class="edit-link">', '</p>' ); ?>

						<?php if ( $novatube_author ) : ?>
							<div class="author-box">
								<?php echo get_avatar( $novatube_author_id, 72, '', '', array( 'class' => 'author-box__avatar' ) ); ?>
								<div class="author-box__body">
									<span class="author-box__label"><?php esc_html_e( 'Written by', 'novatube' ); ?></span>
									<h2 class="author-box__name"><a href="<?php echo esc_url( get_author_posts_url( $novatube_author_id ) ); ?>"><?php echo esc_html( $novatube_author ); ?></a></h2>
									<?php if ( get_the_author_meta( 'description', $novatube_author_id ) ) : ?>
										<p><?php echo esc_html( get_the_author_meta( 'description', $novatube_author_id ) ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>

						<?php
						the_post_navigation(
							array(
								'prev_text' => '<span class="post-nav__label">' . esc_html__( 'Previous article', 'novatube' ) . '</span><span class="post-nav__title">%title</span>',
								'next_text' => '<span class="post-nav__label">' . esc_html__( 'Next article', 'novatube' ) . '</span><span class="post-nav__title">%title</span>',
								'class'     => 'post-navigation',
							)
						);
						?>

						<?php if ( $novatube_related ) : ?>
							<section class="related-posts" aria-labelledby="related-posts-heading">
								<div class="section-head">
									<h2 class="section-title" id="related-posts-heading"><?php esc_html_e( 'Keep reading', 'novatube' ); ?></h2>
								</div>
								<div class="post-list post-list--grid">
									<?php foreach ( $novatube_related as $novatube_related_id ) : ?>
										<?php get_template_part( 'template-parts/post-card', null, array( 'post_id' => (int) $novatube_related_id ) ); ?>
									<?php endforeach; ?>
								</div>
							</section>
						<?php endif; ?>

						<?php
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
						?>
					</article>
				</div>

				<?php get_sidebar(); ?>
			</div>
		</div>
	</main>
	<?php
endwhile;

get_footer();
