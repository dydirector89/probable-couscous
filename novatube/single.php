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
		<div class="nt-container nt-section">
			<?php novatube_breadcrumbs(); ?>

			<div class="nt-layout">
				<div class="nt-layout__main">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'nt-article' ); ?>>
						<header class="nt-article__head">
							<span class="nt-eyebrow"><?php novatube_the_icon( 'folder', 13 ); ?><?php esc_html_e( 'Article', 'novatube' ); ?></span>
							<h1 class="nt-article__title"><?php the_title(); ?></h1>

							<div class="nt-article__meta">
								<?php if ( ! empty( $novatube_cats ) ) : ?>
									<a class="nt-chip" href="<?php echo esc_url( get_category_link( $novatube_cats[0]->term_id ) ); ?>"><?php echo esc_html( $novatube_cats[0]->name ); ?></a>
									<span class="nt-dot" aria-hidden="true"></span>
								<?php endif; ?>

								<?php if ( $novatube_author ) : ?>
									<span><?php novatube_the_icon( 'user', 13 ); ?><?php echo esc_html__( 'by', 'novatube' ) . ' ' . esc_html( $novatube_author ); ?></span>
									<span class="nt-dot" aria-hidden="true"></span>
								<?php endif; ?>

								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								<span class="nt-dot" aria-hidden="true"></span>
								<span><?php echo esc_html( novatube_reading_time( $novatube_post_id ) ); ?></span>

								<?php if ( comments_open() || get_comments_number() ) : ?>
									<span class="nt-dot" aria-hidden="true"></span>
									<a href="#comments"><?php echo esc_html( get_comments_number_text( __( 'No comments', 'novatube' ), __( '1 comment', 'novatube' ), __( '% comments', 'novatube' ) ) ); ?></a>
								<?php endif; ?>
							</div>
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

						<?php
						$novatube_tags = get_the_tag_list( '', '' );

						if ( $novatube_tags ) :
							?>
							<div class="nt-taxonomies">
								<div class="nt-taxonomy">
									<span class="nt-taxonomy__label"><?php esc_html_e( 'Tags', 'novatube' ); ?></span>
									<div class="nt-taxonomy__list nt-tags"><?php echo wp_kses_post( $novatube_tags ); ?></div>
								</div>
							</div>
						<?php endif; ?>

						<div class="nt-actionbar nt-article__share">
							<div class="nt-actionbar__rating">
								<span class="nt-eyebrow"><?php novatube_the_icon( 'share', 13 ); ?><?php esc_html_e( 'Share this article', 'novatube' ); ?></span>
							</div>
							<div class="nt-actionbar__actions">
								<button type="button" class="nt-btn nt-btn--secondary nt-btn--sm js-copy-link" data-url="<?php echo esc_url( get_permalink() ); ?>">
									<?php novatube_the_icon( 'link', 14 ); ?><?php esc_html_e( 'Copy link', 'novatube' ); ?>
								</button>
								<a class="nt-btn nt-btn--secondary nt-btn--sm" href="<?php echo esc_url( 'https://x.com/intent/post?url=' . $novatube_share_url . '&text=' . $novatube_share_txt ); ?>" target="_blank" rel="noopener noreferrer nofollow">
									<?php novatube_the_icon( 'x', 14 ); ?><?php esc_html_e( 'X', 'novatube' ); ?>
								</a>
								<a class="nt-btn nt-btn--secondary nt-btn--sm" href="<?php echo esc_url( 'mailto:?subject=' . $novatube_share_txt . '&body=' . $novatube_share_url ); ?>">
									<?php novatube_the_icon( 'mail', 14 ); ?><?php esc_html_e( 'Email', 'novatube' ); ?>
								</a>
							</div>
						</div>

						<?php edit_post_link( __( 'Edit article', 'novatube' ), '<p class="nt-page-desc">', '</p>' ); ?>

						<?php if ( $novatube_author && get_the_author_meta( 'description', $novatube_author_id ) ) : ?>
							<section class="nt-panel nt-author">
								<div class="nt-author__avatar"><?php echo get_avatar( $novatube_author_id, 64 ); ?></div>
								<div>
									<span class="nt-eyebrow"><?php esc_html_e( 'Written by', 'novatube' ); ?></span>
									<h2 class="nt-panel__title"><a href="<?php echo esc_url( get_author_posts_url( $novatube_author_id ) ); ?>"><?php echo esc_html( $novatube_author ); ?></a></h2>
									<p class="nt-page-desc"><?php echo esc_html( get_the_author_meta( 'description', $novatube_author_id ) ); ?></p>
								</div>
							</section>
						<?php endif; ?>

						<?php
						the_post_navigation(
							array(
								'prev_text' => '<span class="nt-eyebrow">' . esc_html__( 'Previous article', 'novatube' ) . '</span><span class="nt-section-title">%title</span>',
								'next_text' => '<span class="nt-eyebrow">' . esc_html__( 'Next article', 'novatube' ) . '</span><span class="nt-section-title">%title</span>',
								'class'     => 'nt-pagination',
							)
						);
						?>

						<?php if ( $novatube_related ) : ?>
							<section class="nt-video__related" aria-labelledby="related-posts-heading">
								<div class="nt-section-head">
									<div class="nt-section-head__main">
										<span class="nt-eyebrow"><?php novatube_the_icon( 'fire', 13 ); ?><?php esc_html_e( 'Keep reading', 'novatube' ); ?></span>
										<h2 class="nt-section-title" id="related-posts-heading"><?php esc_html_e( 'Related articles', 'novatube' ); ?></h2>
									</div>
								</div>

								<div class="nt-grid nt-grid--posts">
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
