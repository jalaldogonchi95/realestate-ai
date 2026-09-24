<?php
namespace RealEstateAI\Api;
defined( 'ABSPATH' ) || exit;
final class Rest { public function register():void{add_action('rest_api_init',function():void{register_rest_route('realestate-ai/v1','/property/(?P<id>\d+)', ['methods'=>'GET','permission_callback'=>'__return_true','callback'=>[$this,'property']]);});} public function property(\WP_REST_Request $r):\WP_REST_Response{$id=absint($r['id']);if('property'!==get_post_type($id)){return new \WP_REST_Response(['message'=>'Not found'],404);}return new \WP_REST_Response(['id'=>$id,'title'=>get_the_title($id),'price'=>get_post_meta($id,'price',true)],200);} }
