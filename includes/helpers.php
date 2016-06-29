<?php

namespace TenUp\Curation;
use TenUp\Curation\Sections\Sections;
use TenUp\Curation\Sections\Section;

/**
 * Determines if a post exists
 * @uses get_post_status
 *
 * @param $id int
 *
 * @return bool
 */
function post_exists_and_is_published( $id ) {
	return is_string( get_post_status( $id ) );
}

/**
 * @param $section_id
 *
 * @return Section
 */
function get_section( $section_id ) {

	$section = Sections::get_section_by_id( $section_id );
	if ( ! $section ) {
		$section = new Section( $section_id );
	}

	return $section;
}

function get_namespace() {
	return 'tenup/curation/v1';
}