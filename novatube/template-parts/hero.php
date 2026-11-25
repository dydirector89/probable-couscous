<?php
/**
 * Hero section with featured videos.
 *
 * @package NovaTube
 */

$novatube_ids = novatube_get_featured_videos( 3 );

if ( empty( $novatube_ids ) ) {
	return;
}

$novatube_label = get_theme_mod( 'novatube_hero_label', __( 'Featured this week', 'novatube' ) );
$novatube_count = count( $novatube_ids );
?>
<section class="hero" aria-label="<?php esc_attr_e( 'Featured videos', 'novatube' ); ?>">
	<div class="container">
		<div class="section-head section-head--hero">
			<h2 class="eyebrow"><span class="eyebrow__dot" aria-hidden="true"></span><?php echo esc_html( $novatube_label ? $novatube_label : __( 'Featured this week', 'novatube' ) ); ?></h2>
			<a class="section-link" href="<?php echo esc_url( novatube_videos_url() ); ?>">
				<?php esc_html_e( 'View all videos', 'novatube' ); ?>
				<?php novatube_the_icon( 'arrow-right', 16 ); ?>
			</a>
		</div>

		<div class="hero-grid hero-grid--<?php echo esc_attr( (string) $novatube_count ); ?>">
			<?php foreach ( $novatube_ids as $novatube_index => $novatube_id ) : ?>
				<?php
				$novatube_terms    = get_the_terms( $novatube_id, 'video_category' );
				$novatube_term     = ( ! empty( $novatube_terms ) && ! is_wp_error( $novatube_terms ) ) ? $novatube_terms[0] : null;
				$novatube_duration = novatube_get_duration( $novatube_id );
				$novatube_permalink = get_permalink( $novatube_id );
				$novatube_title    = get_the_title( $novatube_id );
				?>
				<article class="hero-card<?php echo 0 === $novatube_index ? ' hero-card--lead' : ''; ?>">
					<a class="hero-card__link" href="<?php echo esc_url( $novatube_permalink ); ?>">
						<span class="hero-card__image">
							<?php
							echo novatube_get_thumbnail(
								$novatube_id,
								'novatube-hero',
								array(
									'alt'           => $novatube_title,
									'loading'       => 0 === $novatube_index ? 'eager' : 'lazy',
									'fetchpriority' => 0 === $novatube_index ? 'high' : 'auto',
									'sizes'         => 0 === $novatube_index ? '(max-width: 900px) 100vw, 66vw' : '(max-width: 900px) 50vw, 33vw',
								)
							); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper.
							?>
						</span>
						<span class="hero-card__shade"></span>
						<span class="hero-card__play"><?php novatube_the_icon( 'play', 30 ); ?></span>

						<span class="hero-card__badges">
							<?php if ( novatube_is_hd( $novatube_id ) ) : ?>
								<span class="badge badge--hd"><?php esc_html_e( 'HD', 'novatube' ); ?></span>
							<?php endif; ?>
							<?php if ( $novatube_duration ) : ?>
								<span class="badge badge--duration"><?php echo esc_html( $novatube_duration ); ?></span>
							<?php endif; ?>
						</span>

						<span class="hero-card__content">
							<?php if ( $novatube_term ) : ?>
								<span class="hero-card__eyebrow"><?php echo esc_html( $novatube_term->name ); ?></span>
							<?php endif; ?>
							<span class="hero-card__title"><?php echo esc_html( $novatube_title ); ?></span>
							<span class="hero-card__meta">
								<span class="meta-item"><?php novatube_the_icon( 'eye', 15 ); ?><?php echo esc_html( novatube_format_count( novatube_get_views( $novatube_id ) ) . ' ' . __( 'views', 'novatube' ) ); ?></span>
								<span class="meta-dot" aria-hidden="true"></span>
								<span class="meta-item"><?php echo esc_html( novatube_relative_date( $novatube_id ) ); ?></span>
							</span>
						</span>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
