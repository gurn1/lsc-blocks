<?php
/**
 * Canned data used in place of a real Google Places API call, whenever
 * no API key is configured (or mock mode is deliberately forced via the
 * lsc_blocks_google_reviews_mock_mode filter).
 *
 * @since 1.2.0
 */

namespace lsc\blocks\app\fixtures;

if( ! defined('ABSPATH')) {
  exit;
}

class LSCFixtureGoogleReviews {

  protected array $search_results;
  protected array $reviews_data;

  public function __construct( ?array $search_results = null, ?array $reviews_data = null ) {
    $this->search_results = $search_results ?? $this->default_search_results();
    $this->reviews_data   = $reviews_data ?? $this->default_reviews_data();
  }

  public function search_places( string $query ): array {
    return array_map( function ( $result ) use ( $query ) {
      $result['name'] = $query . ' - ' . $result['name'];
      return $result;
    }, $this->search_results );
  }

  public function fetch_reviews( string $place_id ): array {
    return $this->reviews_data;
  }

  protected function default_search_results(): array {
    return [
      [ 'id' => 'mock_place_001', 'name' => 'Demo Result', 'address' => '123 Example Street, Sample Town' ],
    ];
  }

  protected function default_reviews_data(): array {
    return [
      'name'          => 'Demo Business',
      'rating'        => 4.6,
      'total_reviews' => 3,
      'reviews'       => [
        [ 'author' => 'Jordan P.', 'avatar' => '', 'rating' => 5, 'text' => 'Excellent service, would recommend to anyone in the area.', 'time' => '' ],
        [ 'author' => 'Alex M.',   'avatar' => '', 'rating' => 4, 'text' => 'Good experience overall, a little slow to respond initially.', 'time' => '' ],
        [ 'author' => 'Sam K.',    'avatar' => '', 'rating' => 5, 'text' => 'Really happy with the results, will be using again.', 'time' => '' ],
      ],
      'fetched_at' => time(),
    ];
  }
}