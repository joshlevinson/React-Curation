<?php

namespace TenUp\Curation\REST;

abstract class Controller extends \WP_REST_Controller {

	public $namespace;

	function __construct() {
		$this->namespace = \TenUp\Curation\get_namespace();
	}

	function is_numeric( $prop ) {
		return is_numeric( $prop );
	}

}