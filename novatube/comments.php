<?php
/**
 * Comments template.
 *
 * @package NovaTube
 */

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="nt-comments">
	<div class="nt-section-head">
		<div class="nt-section-head__main">
			<span class="nt-eyebrow"><?php novatube_the_icon( 'mail', 13 ); ?><?php esc_html_e( 'Discussion', 'novatube' ); ?></span>
			<h2 class="nt-section-title">
				<?php
				$novatube_comment_count = get_comments_number();

				if ( '1' === (string) $novatube_comment_count ) {
					esc_html_e( 'One comment', 'novatube' );
				} else {
					/* translators: %s: comment count. */
					printf( esc_html( _n( '%s comment', '%s comments', $novatube_comment_count, 'novatube' ) ), esc_html( number_format_i18n( $novatube_comment_count ) ) );
				}
				?>
			</h2>
		</div>
	</div>

	<?php if ( have_comments() ) : ?>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 32,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => novatube_icon( 'chevron-left', 15 ) . esc_html__( 'Older comments', 'novatube' ),
				'next_text' => esc_html__( 'Newer comments', 'novatube' ) . novatube_icon( 'chevron-right', 15 ),
			)
		);
		?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="nt-page-desc"><?php esc_html_e( 'Comments are closed.', 'novatube' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply_before'   => '<h3 id="reply-title" class="nt-panel__title comment-reply-title">',
			'title_reply_after'    => '</h3>',
			'class_submit'         => 'nt-btn nt-btn--primary',
			'label_submit'         => __( 'Post comment', 'novatube' ),
			'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Your email address will not be published. Required fields are marked *', 'novatube' ) . '</p>',
		)
	);
	?>
</section>
