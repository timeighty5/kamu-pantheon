<?php
/**
 * Customizer sections.
 *
 * @package KAMU
 */

/**
 * Register the section sections.
 *
 * @author WebDevStudios
 * @param object $wp_customize Instance of WP_Customize_Class.
 */
function wds_kamu_customize_sections( $wp_customize ) {

	// Register additional scripts section.
	$wp_customize->add_section(
		'wds_kamu_additional_scripts_section',
		[
			'title'    => esc_html__( 'Additional Scripts', 'kamu' ),
			'priority' => 10,
			'panel'    => 'site-options',
		]
	);

	// Register a social links section.
	$wp_customize->add_section(
		'wds_kamu_social_links_section',
		[
			'title'       => esc_html__( 'Social Media', 'kamu' ),
			'description' => esc_html__( 'Links here power the display_social_network_links() template tag.', 'kamu' ),
			'priority'    => 90,
			'panel'       => 'site-options',
		]
	);

	// Register a header section.
	$wp_customize->add_section(
		'wds_kamu_header_section',
		[
			'title'    => esc_html__( 'Header Customizations', 'kamu' ),
			'priority' => 90,
			'panel'    => 'site-options',
		]
	);

	// Register a footer section.
	$wp_customize->add_section(
		'wds_kamu_footer_section',
		[
			'title'    => esc_html__( 'Footer Customizations', 'kamu' ),
			'priority' => 90,
			'panel'    => 'site-options',
		]
	);
}
add_action( 'customize_register', 'wds_kamu_customize_sections' );
