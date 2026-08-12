<?php
/**
 * Controller for projects
 *
 * @since 1.0.0
 */

namespace lsc\blocks\app\controllers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use lsc\blocks\app\abstracts\LSCAbstractController;
use lsc\blocks\app\models\LSCModelProjects;

class LSCControllerProjects extends LSCAbstractController {

	public static function identifier(): string {
		return 'projects';
	}

	protected static function model_class(): string {
		return LSCModelProjects::class;
	}

	public function register(): void {
		$this->register_post_type();
	}

	/**
	 * Register post types
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {

		$post_type_name = $this->model::$post_type_name;
		$taxonomies     = $this->model->taxonomies();

		register_post_type( $post_type_name, $this->model::post_type_args() );

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				register_taxonomy(
					$taxonomy['taxonomy'],
					$taxonomy['object_type'] ?? [ $post_type_name ],
					$taxonomy['args'] ?? []
				);
			}
		}

		if ( get_option( 'lsc_plugin_activation' ) ) {
			flush_rewrite_rules();
			delete_option( 'lsc_plugin_activation' );
		}
	}

	public static function route(): ?array {
		return [
			[ 'endpoint' => 'projects-grid', 'methods'  => 'GET' ]
		];
	}

	/**
	 * Request
	 *
	 * @since 1.0.0
	 */
	public function request( \WP_REST_Request $request ): array {
		$category = sanitize_text_field( $request->get_param( 'category' ) );
		$taxonomy = sanitize_text_field( $request->get_param( 'taxonomy' ) );

		if ( ! $category || ! $taxonomy ) {
			return [];
		}

		// Resolve slug to term_id first — term_id lookups use an indexed
		// column, whereas querying tax_query directly by 'field' => 'slug'
		// forces an unindexed string join on wp_terms for every request.
		$cache_key = 'lsc_term_id_' . $taxonomy . '_' . $category;
		$term_id   = wp_cache_get( $cache_key, 'lsc_projects' );

		if ( false === $term_id ) {
			$term = get_term_by( 'slug', $category, $taxonomy );

			if ( ! $term || is_wp_error( $term ) ) {
				wp_cache_set( $cache_key, 0, 'lsc_projects', HOUR_IN_SECONDS );
				return [];
			}

			$term_id = $term->term_id;
			wp_cache_set( $cache_key, $term_id, 'lsc_projects', HOUR_IN_SECONDS );
		}

		if ( ! $term_id ) {
			return [];
		}

    return [
      // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Query optimized: term_id lookup + object cache above.
      'tax_query' => [
        [
          'taxonomy'         => $taxonomy,
          'field'            => 'term_id',
          'terms'            => $term_id,
          'include_children' => false,
        ],
      ],
    ];
	}

	/**
	 * Handle response
	 *
	 * @since 1.0.0
	 */
	public function response( \WP_REST_Request $data ): \WP_REST_Response {
		$args = $this->request( $data );

		if ( empty( $args ) ) {
			return rest_ensure_response( [ 'html' => '' ] );
		}

		// Cache the rendered grid HTML per query so identical filter
		// requests skip both the tax lookup and the post query entirely.
		$cache_key = 'lsc_grid_' . md5( wp_json_encode( $args ) );
		$html      = wp_cache_get( $cache_key, 'lsc_projects' );

		if ( false === $html ) {
			ob_start();
			$this->grid_template( $args );
			$html = ob_get_clean();

			wp_cache_set( $cache_key, $html, 'lsc_projects', HOUR_IN_SECONDS );
		}

		return rest_ensure_response( [ 'html' => $html ] );
	}

	/**
	 * Get filtering template
	 *
	 * @since 1.0.0
	 * @return string|false
	 */
	public function filtering_template( $taxonomy = 'service_area', $args = [] ) {
		$terms = $this->model->get_taxonomy_terms( $taxonomy, $args );

		if ( empty( $terms ) && ! is_array( $terms ) ) {
			return false;
		}
		require LSC_VIEWS . 'partials/filtering.php';
	}

	/**
	 * Get the grid template
	 *
	 * @since 1.0.0
	 * @return string|false
	 */
	public function grid_template( $params = [], $taxonomy = 'service_area' ) {
		$items = $this->model->get_items( $params );

		if ( empty( $items ) || ! is_array( $items ) ) {
			echo esc_html__( 'No Items Found', 'lsc-blocks' );
			return false;
		}

		require LSC_VIEWS . 'partials/grid.php';
	}
}