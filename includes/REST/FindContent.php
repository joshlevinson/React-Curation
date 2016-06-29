<?php

namespace TenUp\Curation\REST;
use TenUp\Curation\Sections\Sections;

class FindContent extends Controller {

	public $rest_base = 'curation/content';

	function register_routes() {
		register_rest_route( $this->namespace, $this->rest_base, [
			[
				'methods' => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'find_content' ],
			],
		] );
	}

	function find_content( \WP_REST_Request $request ) {
		$section = $request->get_param( 'section' );
		$section = json_decode( stripslashes( $section ), true );
		$section = Sections::get_section( $section );
		if ( ! $section ) {
			return new \WP_Error( 'Invalid section requested.' );
		}
		return $section->find_content( sanitize_text_field( $request->get_param( 'search' ) ) );
	}

}