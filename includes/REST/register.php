<?php

namespace TenUp\Curation\REST;

add_action( 'rest_api_init', function () {

	require_once __DIR__ . '/Controller.php';

	require_once __DIR__ . '/FindContent.php';
	$fp = new FindContent();
	$fp->register_routes();

	require_once __DIR__ . '/SectionContent.php';
	$sp = new SectionContent();
	$sp->register_routes();

} );