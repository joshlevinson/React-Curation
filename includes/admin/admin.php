<?php

namespace TenUp\Curation;

use \TenUp\Curation\Sections\Sections;

add_action( 'admin_menu', __NAMESPACE__ . '\register_curation_page' );

/**
 * Register a custom menu page.
 */
function register_curation_page() {
	$sections = Sections::get_sections();
	add_menu_page(
		esc_html__( 'Curation', 'st_core' ),
		'Curation',
		'edit_posts',
		'curation',
		function () use ( $sections ) {
			display_curation_admin( $sections );
		},
		'dashicons-list-view',
		6
	);
}

function display_curation_admin( $sections ) {
	include_once 'curation-template.php';
	wp_register_script( 'TenUpCuration', trailingslashit( TENUP_CURATION_ASSET_URL ) . '/js/bundle.js', [ ], TENUP_CURATION_VERSION, true );
	wp_localize_script( 'TenUpCuration', 'TenUpCuration', [
		'sections' => Sections::get_sections_for_js( $sections ),
		'nonce'    => wp_create_nonce( 'wp_rest' ),
		'base_url' => site_url(
			trailingslashit( rest_get_url_prefix() ) . trailingslashit( get_namespace() )
		),
	] );
	wp_enqueue_script( 'TenUpCuration' );
	wp_enqueue_style( 'TenUpCuration', trailingslashit( TENUP_CURATION_ASSET_URL ) . 'css/curation.css' );
}