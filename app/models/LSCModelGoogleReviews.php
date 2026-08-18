<?php
namespace lsc\blocks\app\models;

if( ! defined('ABSPATH')) {
  exit;
}

use lsc\blocks\app\abstracts\LSCAbstractModel;
use lsc\blocks\app\admin\LSCSettingsGoogleReviews;
use lsc\blocks\app\fixtures\LSCFixtureGoogleReviews;

class LSCModelGoogleReviews extends LSCAbstractModel {

  protected const CACHE_PREFIX = 'lsc_google_reviews_';
  protected const API_BASE     = 'https://places.googleapis.com/v1/';

  protected ?LSCFixtureGoogleReviews $fixture = null;

  /**
   * Search for a place by free-text (e.g. a business name), so an author
   * can find the correct place_id without knowing it upfront.
   *
   * @return array|\WP_Error
   */
  public function search_places( string $query ) {
    if ( $this->is_mock_mode() ) {
      return $this->fixture()->search_places( $query );
    }

    $api_key = LSCSettingsGoogleReviews::api_key();

    if ( empty($api_key) ) {
      return new \WP_Error('lsc_missing_api_key', __('No Google API key configured.', 'lsc-blocks'));
    }

    $response = wp_remote_post( static::API_BASE . 'places:searchText', [
      'headers' => [
        'Content-Type'     => 'application/json',
        'X-Goog-Api-Key'   => $api_key,
        'X-Goog-FieldMask' => 'places.id,places.displayName,places.formattedAddress',
      ],
      'body'    => wp_json_encode([ 'textQuery' => $query ]),
      'timeout' => 10,
    ]);

    if ( is_wp_error($response) ) {
      return $response;
    }

    $body = json_decode( wp_remote_retrieve_body($response), true );

    if ( empty($body['places']) ) {
      return [];
    }

    return array_map( fn( $place ) => [
      'id'      => $place['id'] ?? '',
      'name'    => $place['displayName']['text'] ?? '',
      'address' => $place['formattedAddress'] ?? '',
    ], $body['places'] );
  }

  /**
   * Fetch rating + reviews for a specific place, live from Google. Only
   * ever called by the cron job or the manual "refresh now" action -
   * render.php reads exclusively from the cache below, never this.
   *
   * @return array|\WP_Error
   */
  public function fetch_reviews( string $place_id ) {
    if ( $this->is_mock_mode() ) {
      return $this->fixture()->fetch_reviews( $place_id );
    }

    $api_key = LSCSettingsGoogleReviews::api_key();

    if ( empty($api_key) ) {
      return new \WP_Error('lsc_missing_api_key', __('No Google API key configured.', 'lsc-blocks'));
    }

    $response = wp_remote_get( static::API_BASE . 'places/' . $place_id, [
      'headers' => [
        'X-Goog-Api-Key'   => $api_key,
        'X-Goog-FieldMask' => 'id,displayName,rating,userRatingCount,reviews',
      ],
      'timeout' => 10,
    ]);

    if ( is_wp_error($response) ) {
      return $response;
    }

    $body = json_decode( wp_remote_retrieve_body($response), true );

    if ( empty($body['id']) ) {
      return new \WP_Error('lsc_invalid_place', __('Could not retrieve details for this place.', 'lsc-blocks'));
    }

    $reviews = array_map( fn( $review ) => [
      'author' => $review['authorAttribution']['displayName'] ?? '',
      'avatar' => $review['authorAttribution']['photoUri'] ?? '',
      'rating' => $review['rating'] ?? 0,
      'text'   => $review['text']['text'] ?? '',
      'time'   => $review['publishTime'] ?? '',
    ], $body['reviews'] ?? [] );

    return [
      'name'          => $body['displayName']['text'] ?? '',
      'rating'        => $body['rating'] ?? 0,
      'total_reviews' => $body['userRatingCount'] ?? 0,
      'reviews'       => $reviews,
      'fetched_at'    => time(),
    ];
  }

  protected function is_mock_mode(): bool {
    return apply_filters(
      'lsc_blocks_google_reviews_mock_mode',
      empty( LSCSettingsGoogleReviews::api_key() )
    );
  }

  protected function fixture(): LSCFixtureGoogleReviews {
    if ( is_null($this->fixture) ) {
      $this->fixture = new LSCFixtureGoogleReviews();
    }

    return $this->fixture;
  }

  public function get_cached( string $place_id ) {
    return get_transient( static::CACHE_PREFIX . $place_id );
  }

  /**
   * Cached with no expiry (0) - refreshing is entirely cron's job, not
   * the transient's. See the WP-Cron reliability note below: if cron is
   * ever delayed, stale-but-present data is better than reviews vanishing
   * site-wide the moment a TTL lapses.
   */
  public function cache( string $place_id, array $data ): void {
    set_transient( static::CACHE_PREFIX . $place_id, $data, 0 );
  }
}