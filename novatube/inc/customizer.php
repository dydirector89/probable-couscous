<?php
/**
 * Customizer settings.
 *
 * @package NovaTube
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize a checkbox.
 *
 * @param mixed $value Value.
 * @return bool
 */
function novatube_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Sanitize ad HTML.
 *
 * @param string $value Raw value.
 * @return string
 */
function novatube_sanitize_ad( $value ) {
	$allowed        = novatube_allowed_embed_html();
	$allowed['ins'] = array(
		'class'                      => true,
		'style'                      => true,
		'data-ad-client'             => true,
		'data-ad-slot'               => true,
		'data-ad-format'             => true,
		'data-full-width-responsive' => true,
	);

	return wp_kses( (string) $value, $allowed );
}

/**
 * Register Customizer options.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function novatube_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->add_panel(
		'novatube_options',
		array(
			'title'       => __( 'NovaTube Options', 'novatube' ),
			'description' => __( 'Branding, homepage sections, age gate, ads, and footer options.', 'novatube' ),
			'priority'    => 30,
		)
	);

	/* ---------------------------------------------------------------------
	 * Colors
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section(
		'novatube_colors',
		array(
			'title' => __( 'Brand Colors', 'novatube' ),
			'panel' => 'novatube_options',
		)
	);

	$wp_customize->add_setting(
		'novatube_accent_color',
		array(
			'default'           => '#ff7a45',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'novatube_accent_color',
			array(
				'label'       => __( 'Primary accent', 'novatube' ),
				'description' => __( 'Buttons, links, active states.', 'novatube' ),
				'section'     => 'novatube_colors',
			)
		)
	);

	$wp_customize->add_setting(
		'novatube_accent_color_2',
		array(
			'default'           => '#ff9468',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'novatube_accent_color_2',
			array(
				'label'       => __( 'Secondary accent', 'novatube' ),
				'description' => __( 'Used in gradients.', 'novatube' ),
				'section'     => 'novatube_colors',
			)
		)
	);

	/* ---------------------------------------------------------------------
	 * Homepage
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section(
		'novatube_homepage',
		array(
			'title' => __( 'Homepage', 'novatube' ),
			'panel' => 'novatube_options',
		)
	);

	$wp_customize->add_setting(
		'novatube_show_hero',
		array(
			'default'           => true,
			'sanitize_callback' => 'novatube_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'novatube_show_hero',
		array(
			'label'   => __( 'Show featured hero', 'novatube' ),
			'type'    => 'checkbox',
			'section' => 'novatube_homepage',
		)
	);

	$wp_customize->add_setting(
		'novatube_hero_label',
		array(
			'default'           => __( 'Featured this week', 'novatube' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'novatube_hero_label',
		array(
			'label'   => __( 'Hero eyebrow label', 'novatube' ),
			'type'    => 'text',
			'section' => 'novatube_homepage',
		)
	);

	$wp_customize->add_setting(
		'novatube_latest_title',
		array(
			'default'           => __( 'Latest videos', 'novatube' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'novatube_latest_title',
		array(
			'label'   => __( 'Latest videos heading', 'novatube' ),
			'type'    => 'text',
			'section' => 'novatube_homepage',
		)
	);

	$wp_customize->add_setting(
		'novatube_show_popular',
		array(
			'default'           => true,
			'sanitize_callback' => 'novatube_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'novatube_show_popular',
		array(
			'label'   => __( 'Show “Top rated” section', 'novatube' ),
			'type'    => 'checkbox',
			'section' => 'novatube_homepage',
		)
	);

	$wp_customize->add_setting(
		'novatube_show_chips',
		array(
			'default'           => true,
			'sanitize_callback' => 'novatube_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'novatube_show_chips',
		array(
			'label'   => __( 'Show category chips under the header', 'novatube' ),
			'type'    => 'checkbox',
			'section' => 'novatube_homepage',
		)
	);

	$wp_customize->add_setting(
		'novatube_videos_per_page',
		array(
			'default'           => 20,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'novatube_videos_per_page',
		array(
			'label'       => __( 'Videos per page', 'novatube' ),
			'type'        => 'number',
			'section'     => 'novatube_homepage',
			'input_attrs' => array(
				'min' => 4,
				'max' => 60,
			),
		)
	);

	/* ---------------------------------------------------------------------
	 * Age gate
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section(
		'novatube_age_gate',
		array(
			'title'       => __( 'Age Gate', 'novatube' ),
			'description' => __( 'Show an 18+ confirmation before visitors can browse.', 'novatube' ),
			'panel'       => 'novatube_options',
		)
	);

	$wp_customize->add_setting(
		'novatube_age_gate',
		array(
			'default'           => true,
			'sanitize_callback' => 'novatube_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'novatube_age_gate',
		array(
			'label'   => __( 'Enable age gate', 'novatube' ),
			'type'    => 'checkbox',
			'section' => 'novatube_age_gate',
		)
	);

	$wp_customize->add_setting(
		'novatube_age_title',
		array(
			'default'           => __( 'Are you 18 or older?', 'novatube' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'novatube_age_title',
		array(
			'label'   => __( 'Heading', 'novatube' ),
			'type'    => 'text',
			'section' => 'novatube_age_gate',
		)
	);

	$wp_customize->add_setting(
		'novatube_age_text',
		array(
			'default'           => __( 'This website contains adult material and is intended for adults only. By entering, you confirm that you are at least 18 years old and that viewing adult content is legal where you live.', 'novatube' ),
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'novatube_age_text',
		array(
			'label'   => __( 'Description', 'novatube' ),
			'type'    => 'textarea',
			'section' => 'novatube_age_gate',
		)
	);

	$wp_customize->add_setting(
		'novatube_age_enter',
		array(
			'default'           => __( 'I am 18 or older — Enter', 'novatube' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'novatube_age_enter',
		array(
			'label'   => __( 'Enter button label', 'novatube' ),
			'type'    => 'text',
			'section' => 'novatube_age_gate',
		)
	);

	$wp_customize->add_setting(
		'novatube_age_exit_url',
		array(
			'default'           => 'https://www.google.com/',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'novatube_age_exit_url',
		array(
			'label'   => __( 'Exit link URL', 'novatube' ),
			'type'    => 'url',
			'section' => 'novatube_age_gate',
		)
	);

	/* ---------------------------------------------------------------------
	 * Ads
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section(
		'novatube_ads',
		array(
			'title'       => __( 'Advertisement Slots', 'novatube' ),
			'description' => __( 'Paste ad HTML (images, iframes, or ad-network placeholders). Scripts are removed for security. Leave a slot empty to hide it.', 'novatube' ),
			'panel'       => 'novatube_options',
		)
	);

	foreach ( array(
		'sidebar' => __( 'Sidebar ad', 'novatube' ),
		'player'  => __( 'Below the video player', 'novatube' ),
		'home'    => __( 'Homepage, between video sections', 'novatube' ),
	) as $key => $label ) {
		$wp_customize->add_setting(
			'novatube_ad_' . $key,
			array(
				'default'           => '',
				'sanitize_callback' => 'novatube_sanitize_ad',
			)
		);

		$wp_customize->add_control(
			'novatube_ad_' . $key,
			array(
				'label'   => $label,
				'type'    => 'textarea',
				'section' => 'novatube_ads',
			)
		);
	}

	/* ---------------------------------------------------------------------
	 * Footer & social
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section(
		'novatube_footer',
		array(
			'title' => __( 'Footer & Social', 'novatube' ),
			'panel' => 'novatube_options',
		)
	);

	$wp_customize->add_setting(
		'novatube_footer_about',
		array(
			'default'           => __( 'A fast, modern video experience with new uploads every day. Curated categories, HD playback, and zero clutter.', 'novatube' ),
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'novatube_footer_about',
		array(
			'label'   => __( 'Footer about text', 'novatube' ),
			'type'    => 'textarea',
			'section' => 'novatube_footer',
		)
	);

	$wp_customize->add_setting(
		'novatube_footer_disclaimer',
		array(
			'default'           => __( 'All models were 18 years of age or older at the time of production. This website is intended for adults only. Please leave if you are under 18.', 'novatube' ),
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'novatube_footer_disclaimer',
		array(
			'label'   => __( 'Legal / disclaimer text', 'novatube' ),
			'type'    => 'textarea',
			'section' => 'novatube_footer',
		)
	);

	$wp_customize->add_setting(
		'novatube_copyright',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'novatube_copyright',
		array(
			'label'       => __( 'Copyright line', 'novatube' ),
			'description' => __( 'Leave empty to use “© {year} {site name}”.', 'novatube' ),
			'type'        => 'text',
			'section'     => 'novatube_footer',
		)
	);

	foreach ( array(
		'x'         => __( 'X (Twitter) URL', 'novatube' ),
		'instagram' => __( 'Instagram URL', 'novatube' ),
		'telegram'  => __( 'Telegram URL', 'novatube' ),
	) as $key => $label ) {
		$wp_customize->add_setting(
			'novatube_social_' . $key,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'novatube_social_' . $key,
			array(
				'label'   => $label,
				'type'    => 'url',
				'section' => 'novatube_footer',
			)
		);
	}
}
add_action( 'customize_register', 'novatube_customize_register' );
