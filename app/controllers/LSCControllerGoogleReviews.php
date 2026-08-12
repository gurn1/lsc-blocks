<?php
namespace lsc\blocks\app\controllers;

if( ! defined('ABSPATH')) {
  exit;
}

use lsc\blocks\app\abstracts\LSCAbstractController;
use lsc\blocks\app\models\LSCModelGoogleReviews;

class LSCControllerGoogleReviews extends LSCAbstractController {

  public static function identifier(): string {
    return 'google-reviews';
  }

  protected static function model_class(): string {
    return LSCModelGoogleReviews::class;
  }

  /**
   * No post type or taxonomy, but this is where the custom cron
   * intervals get registered - "weekly"/"monthly" don't exist in WP
   * core, only hourly/twicedaily/daily do.
   */
  public function register(): void {
    add_filter('cron_schedules', [$this, 'register_cron_intervals']);
    add_action('lsc_refresh_google_reviews', [$this, 'handle_scheduled_refresh']);
    add_action('lsc_refresh_google_reviews_once', [$this, 'handle_scheduled_refresh']);
  }

  public function register_cron_intervals( array $schedules ): array {
    $schedules['lsc_weekly'] = [
      'interval' => WEEK_IN_SECONDS,
      'display'  => __('Once Weekly', 'lsc-blocks'),
    ];
    $schedules['lsc_monthly'] = [
      'interval' => 30 * DAY_IN_SECONDS,
      'display'  => __('Once Monthly (~30 days)', 'lsc-blocks'),
    ];
    return $schedules;
  }

  public static function route(): ?array {
    return [
      [
        'endpoint'       => 'google-reviews/search',
        'methods'        => 'POST',
        'callback'       => 'search',
        'requires_admin' => true,
        'args' => [
          'query' => [
            'required'          => true,
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
          ],
        ],
      ],
      [
        'endpoint'       => 'google-reviews/refresh/(?P<place_id>[\w-]+)',
        'methods'        => 'POST',
        'callback'       => 'refresh',
        'requires_admin' => true,
      ],
    ];
  }

  public function search( \WP_REST_Request $request ): \WP_REST_Response {
    $results = $this->model->search_places( $request->get_param('query') );

    if ( is_wp_error($results) ) {
      return new \WP_REST_Response( [ 'message' => $results->get_error_message() ], 400 );
    }

    return rest_ensure_response( $results );
  }

  public function refresh( \WP_REST_Request $request ): \WP_REST_Response {
    $place_id = $request->get_param('place_id');
    $data     = $this->model->fetch_reviews( $place_id );

    if ( is_wp_error($data) ) {
      return new \WP_REST_Response( [ 'message' => $data->get_error_message() ], 400 );
    }

    $this->model->cache( $place_id, $data );

    return rest_ensure_response( $data );
  }

  /**
   * Public accessor for render.php
   */
  public function reviews_for( string $place_id ): ?array {
    $cached = $this->model->get_cached( $place_id );
    return $cached ?: null;
  }

  public function ensure_scheduled( string $place_id, string $interval ): void {
    $schedule_slug = match ($interval) {
      'weekly'  => 'lsc_weekly',
      'monthly' => 'lsc_monthly',
      default   => 'daily',
    };

    $scheduled_event = wp_get_scheduled_event( 'lsc_refresh_google_reviews', [ $place_id ] );

    if ( $scheduled_event && $scheduled_event->schedule !== $schedule_slug ) {
      wp_unschedule_event( $scheduled_event->timestamp, 'lsc_refresh_google_reviews', [ $place_id ] );
      $scheduled_event = false;
    }

    if ( ! $scheduled_event ) {
      wp_schedule_event( time(), $schedule_slug, 'lsc_refresh_google_reviews', [ $place_id ] );
    }

    if ( ! $this->model->get_cached( $place_id ) && ! wp_next_scheduled( 'lsc_refresh_google_reviews_once', [ $place_id ] ) ) {
      wp_schedule_single_event( time(), 'lsc_refresh_google_reviews_once', [ $place_id ] );
    }
  }

  public function handle_scheduled_refresh( string $place_id ): void {
    $data = $this->model->fetch_reviews( $place_id );

    if ( ! is_wp_error($data) ) {
      $this->model->cache( $place_id, $data );
    }
  }
}