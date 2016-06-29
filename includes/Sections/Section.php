<?php

namespace TenUp\Curation\Sections;

trait SectionTraits {

	protected $title = 'Section';
	protected $id = 'section';
	protected $default_num_content = 3;
	public $num_content;
	protected $content_ids;
	protected $exclude_content_already_used = [ ];

	function get_title() {
		return $this->title;
	}

	function get_id() {
		return $this->id;
	}

	function get_content_ids() {
		return $this->get( 'content' ) ?? [ ];
	}

	function get( $prop = false ) {
		$state = $this->get_state();
		if ( $prop ) {
			return $state[ $prop ] ?? null;
		}

		return $state;
	}

	function get_for_js() {
		return [
			'title'       => $this->get_title(),
			'id'          => $this->get_id(),
			'state'       => [
				'content' => $this->format_content( $this->get_curated_content() ),
			],
			'num_content' => absint( $this->num_content ),
		];
	}

	/**
	 * @param array $exclude
	 *
	 * @return int[] Array of content IDs
	 */
	/**
	 * @param array $exclude
	 *
	 * @return int[] Array of content IDs
	 */
	function get_content( $exclude = [] ) {
		$content_ids = $this->get_curated_content();

		if ( count( $content_ids ) >= $this->num_content ) {
			return $content_ids;
		}

		$diff = $this->num_content - count( $content_ids );

		$exclude = array_merge( $exclude, $content_ids, Sections::get_used_content() );

		$content_ids = $this->backfill( $content_ids, $diff, $exclude );

		Sections::mark_used_content( $content_ids );

		return $content_ids;
	}

	function backfill( $content_ids, $num_to_backfill, $exclude = [] ) {

		$backfill = $this->get_backfill_content( ...func_get_args() );

		if ( $backfill ) {
			$content_ids = array_merge( $content_ids, $backfill );
		}

		return array_map( 'absint', $content_ids );

	}

}

/**
 * Class Section
 * @package TenUp\Curation
 *
 */
class Section {

	use SectionTraits;

	function __construct( $id, $num_content = null, $title = '' ) {
		$this->id          = $id;
		$this->num_content = $num_content ?? $this->default_num_content;
		$this->title       = $title;
	}

	function find_content( $search = '' ) {
		add_filter( 'posts_where', [ $this, 'search_by_title' ], 1, 2 );

		$args = [ ];
		if ( $search ) {
			$args['search'] = sanitize_text_field( $search );
		}
		$query = new \WP_Query( wp_parse_args( $this->get_query_args(), $args ) );

		remove_filter( 'posts_where', [ $this, 'search_by_title' ], 1 );

		if ( $query->have_posts() ) {
			return $this->format_content( $query->posts );
		}

		return [ ];
	}

	function search_by_title( $where, &$query ) {
		global $wpdb;
		if ( $title = $query->get( 'search' ) ) {
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $title ) ) . '%\'';
		}

		return $where;
	}

	function get_curated_content() {
		$content_ids = array_map( 'absint', $this->get_content_ids() ?: [ ] );

		return array_unique( array_filter( $content_ids, function ( $content_id ) {
			return \TenUp\Curation\post_exists_and_is_published( $content_id );
		} ) );
	}

	function save( $state ) {
		return $this->save_state(
			[
				'content' => array_map( 'absint', wp_list_pluck( $state['content'], 'id' ) ),
			]
		);
	}

	function save_state( $state ) {
		return update_option( $this->get_id(), $state );
	}

	function get_state() {
		return get_option( $this->get_id(), [ ] );
	}

	function format_content( $content = [ ] ) {
		return array_map( function ( $content_id ) {
			$content = get_post( $content_id );

			return [
				'id'    => $content->ID,
				'title' => html_entity_decode( get_the_title( $content ) ),
			];
		}, $content );
	}

	function get_query_args( $backfill = false, $num_to_backfill = false ) {
		return [
			'posts_per_page' => '20',
		];
	}

	function get_backfill_content( $content_ids, $num_to_backfill, $exclude ) {

		$args  = [
			'post__not_in'   => array_map( 'absint', $exclude ),
			'posts_per_page' => $num_to_backfill,
			'no_found_rows'  => true,
			'fields'         => 'ids',
			'post_status'    => 'publish',
		];

		$query = new \WP_Query( wp_parse_args( $args, $this->get_query_args( $backfill = true, $num_to_backfill ) ) );
		if ( $query->have_posts() ) {
			return $query->posts;
		}

		return [];

	}

}
