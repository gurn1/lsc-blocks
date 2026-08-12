<?php
/**
 * Controller for Post feedback
 * 
 * @since 1.0.0
 */

namespace lsc\blocks\app\controllers;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

use lsc\blocks\app\abstracts\LSCAbstractController;
use lsc\blocks\app\models\LSCModelPostFeedback;

class LSCControllerPostFeedback extends LSCAbstractController {

  public static function identifier(): string {
    return 'postfeedback-widget';
  }

  protected static function model_class(): string {
    return LSCModelPostFeedback::class;
  }
  
  public function register(): void {}

  public static function route(): ?array {
    return [
      [
        'endpoint' => 'feedback/(?P<post_id>\d+)',
        'handlers' => [
          [ 'methods' => 'GET', 'callback' => 'response' ],
          [ 'methods' => 'POST', 'callback' => 'store', 'args' => [
              'required'          => true,
              'type'              => 'string',
              'enum'              => ['yes', 'no'],
              'sanitize_callback' => 'sanitize_text_field'
            ],
          ],
        ],
      ]
    ];
  }

  public function request( \WP_REST_Request $request): array {
    return [ 'post_id' => (int) $request->get_param('post_id') ];
  }

  public function response( \WP_REST_Request $request ): \WP_REST_Response {
    $args = $this->request( $request );
    
    return rest_ensure_request( $this->model->get( $args['post_id'] ) );
  }

  public function count( int $post_id ): array {
    return $this->model->get( $post_id );
  }

  public function store( \WP_REST_Request $request ): \WP_REST_Response {
    $post_id  = (int) $request->get_param('post_id');
    $vote     = $request->get_param('vote');  

    if( ! get_post($post_id ) ) {
      return new \WP_REST_Response( [ 'message' => __('Post not found', 'lsc-blocks') ], 404 );
    }

    return rest_ensure_response( $this->model->increment( $post_id, $vote ) );
  }

}