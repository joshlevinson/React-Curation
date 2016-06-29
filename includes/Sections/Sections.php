<?php

namespace TenUp\Curation\Sections;

class Sections {
	/**
	 * @var $registry Section[]
	 */
	protected static $registry = [ ];

	protected static $exclude_content_already_used = [];

	/**
	 * @param $section Section
	 */
	public static function add( $section ) {
		self::$registry[ $section->get_id() ] = $section;
	}

	public static function mark_used_content( $content_ids ) {
		self::$exclude_content_already_used = array_merge( self::$exclude_content_already_used, $content_ids );
	}

	public static function get_used_content() {
		return self::$exclude_content_already_used;
	}

	public static function get_sections() {
		return self::$registry;
	}

	public static function get_sections_for_js( $sections = [] ) {
		$sections = $sections ?? self::$registry;

		return array_values( array_map( function ( $section ) {
			/**
			 * @var $section Section
			 */
			return $section->get_for_js();
		}, $sections ) );
	}

	public static function get_section( $section_params ) {
		return self::$registry[ $section_params['id'] ] ?? apply_filters( __NAMESPACE__ . '\get_section', false, $section_params );
	}

	public static function get_section_by_id( $section_id ) {
		return self::$registry[ $section_id ] ?: false;
	}

}