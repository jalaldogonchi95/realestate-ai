<?php
namespace RealEstatePro\Api;
use RealEstatePro\Ai\AiManager;use RealEstatePro\Gating\Gating;
defined('ABSPATH')||exit;
final class Rest{
 public function register():void{add_action('rest_api_init',function():void{
  register_rest_route('re-pro/v1','/property/(?P<id>\d+)',['methods'=>'GET','permission_callback'=>'__return_true','callback'=>[$this,'property']]);
  register_rest_route('re-pro/v1','/property/(?P<id>\d+)/ai',['methods'=>'POST','permission_callback'=>fn()=>is_user_logged_in(),'callback'=>[$this,'ai']]);
 });}
 public function property(\WP_REST_Request $r):\WP_REST_Response{$id=(int)$r['id'];if(get_post_type($id)!=='property')return new \WP_REST_Response(['message'=>'Not found'],404);$tier=(new Gating())->tier();$o=['id'=>$id,'title'=>get_the_title($id),'city'=>wp_get_post_terms($id,'city',['fields'=>'names']),'price'=>get_post_meta($id,'price',true),'thumbnail'=>get_the_post_thumbnail_url($id,'medium')];if(in_array($tier,['pro','vip'],true))foreach(['address_full','gallery_ids','video_url','virtual_tour_url','bedrooms','bathrooms','floor','year_built'] as $f)$o[$f]=get_post_meta($id,$f,true);return new \WP_REST_Response($o,200);}
 public function ai(\WP_REST_Request $r):\WP_REST_Response{try{return new \WP_REST_Response((new AiManager())->ask(get_current_user_id(),(int)$r['id'],sanitize_textarea_field((string)$r->get_param('question'))),200);}catch(\Throwable $e){return new \WP_REST_Response(['message'=>$e->getMessage()],403);}}
}
