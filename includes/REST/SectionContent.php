<?php

namespace TenUp\Curation\REST;
use TenUp\Curation\Sections\Sections;

class SectionContent extends Controller {

	public $rest_base = 'curation/section';

	function register_routes() {
		register_rest_route( $this->namespace, $this->rest_base, [
			[
				'methods' => \WP_REST_Server::EDITABLE,
				'callback' => [ $this, 'sync_section' ],
			],
		] );
	}

	function sync_section( \WP_REST_Request $request ) {
		$params = $request->get_json_params();
		$section = Sections::get_section( $params['section'] );
		if ( ! $section ) {
			return new \WP_Error( 'Invalid section requested.' );
		}
		return $section->save( $params['state'] );
	}

}