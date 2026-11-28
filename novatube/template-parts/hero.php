<?php
/**
 * Featured spotlight with a lead video and two supporting picks.
 *
 * @package NovaTube
 */

$novatube_ids = novatube_get_featured_videos( 3 );

if ( empty( $novatube_ids ) ) {
	return;
}

$novatube_label = get_theme_mod( 'novatube_hero_label', __( 'Featured this week', 'novatube' ) );
$novatube_lead  = array_shift( $novatube_ids );
?>
<section class="nt-section nt-section--tight" aria-labelledby="nt-featured-heading">
	<div class="nt-container">
		<div class="nt-section-head">
			<div class="nt-section-head__main">
				<span class="nt-eyebrow"><?php novatube_the_icon( 'fire', 13 ); ?><?php echo esc_html( $novatube_label ? $novatube_label : __( 'Featured this week', 'novatube' ) ); ?></span>
				<h2 class="nt-section-title" id="nt-featured-heading"><?php esc_html_e( 'Handpicked for you', 'novatube' ); ?></h2>
			</div>
			<div class="nt-section-head__actions">
				<a class="nt-section-link" href="<?php echo esc_url( novatube_videos_url() ); ?>">
					<?php esc_html_e( 'View all videos', 'novatube' ); ?>
					<?php novatube_the_icon( 'arrow-right', 15 ); ?>
				</a>
			</div>
		</div>

		<div class="nt-spotlight">
			<?php
			$novatube_lead_terms = get_the_terms( $novatube_lead, 'video_category' );
			$novatube_lead_cat   = ( ! empty( $novatube_lead_terms ) && ! is_wp_error( $novatube_lead_terms ) ) ? $novatube_lead_terms[0] : null;
			$novatube_lead_views = novatube_get_views( $novatube_lead );
			?>
			<a class="nt-spotlight__lead" href="<?php echo esc_url( get_permalink( $novatube_lead ) ); ?>">
				<?php echo novatube_get_thumbnail( $novatube_lead, 'novatube-hero', array( 'alt' => get_the_title( $novatube_lead ), 'class' => 'nt-spotlight__media' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>
				<span class="nt-spotlight__shade"></span>
				<span class="nt-spotlight__play"><?php novatube_the_icon( 'play', 20 ); ?></span>
				<span class="nt-spotlight__badges">
					<?php if ( novatube_is_hd( $novatube_lead ) ) : ?>
						<span class="nt-badge nt-badge--quality"><?php esc_html_e( 'HD', 'novatube' ); ?></span>
					<?php endif; ?>
					<?php if ( novatube_get_duration( $novatube_lead ) ) : ?>
						<span class="nt-badge nt-badge--duration"><?php echo esc_html( novatube_get_duration( $novatube_lead ) ); ?></span>
					<?php endif; ?>
				</span>
				<span class="nt-spotlight__content">
					<span class="nt-spotlight__eyebrow"><?php novatube_the_icon( 'play', 12 ); ?><?php echo $novatube_lead_cat ? esc_html( $novatube_lead_cat->name ) : esc_html__( 'Featured video', 'novatube' ); ?></span>
					<span class="nt-spotlight__title"><?php echo esc_html( get_the_title( $novatube_lead ) ); ?></span>
					<span class="nt-spotlight__meta">
						<?php novatube_the_icon( 'eye', 13 ); ?><?php echo esc_html( novatube_format_count( $novatube_lead_views ) . ' ' . __( 'views', 'novatube' ) ); ?>
						<span class="nt-dot" aria-hidden="true"></span>
						<?php echo esc_html( novatube_relative_date( $novatube_lead ) ); ?>
					</span>
				</span>
			</a>

			<div class="nt-spotlight__side-stack">
				<?php foreach ( $novatube_ids as $novatube_id ) : ?>
					<?php
					$novatube_terms = get_the_terms( $novatube_id, 'video_category' );
					$novatube_cat   = ( ! empty( $novatube_terms ) && ! is_wp_error( $novatube_terms ) ) ? $novatube_terms[0] : null;
					?>
					<a class="nt-spotlight__side" href="<?php echo esc_url( get_permalink( $novatube_id ) ); ?>">
						<?php echo novatube_get_thumbnail( $novatube_id, 'novatube-card', array( 'alt' => get_the_title( $novatube_id ), 'class' => 'nt-spotlight__media' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>
						<span class="nt-spotlight__shade"></span>
						<span class="nt-spotlight__badges">
							<?php if ( novatube_get_duration( $novatube_id ) ) : ?>
								<span class="nt-badge nt-badge--duration nt-badge--sm"><?php echo esc_html( novatube_get_duration( $novatube_id ) ); ?></span>
							<?php endif; ?>
						</span>
						<span class="nt-spotlight__content">
							<span class="nt-spotlight__eyebrow"><?php echo $novatube_cat ? esc_html( $novatube_cat->name ) : esc_html__( 'Featured', 'novatube' ); ?></span>
							<span class="nt-spotlight__title"><?php echo esc_html( get_the_title( $novatube_id ) ); ?></span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
