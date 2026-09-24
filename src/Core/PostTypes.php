<?php
namespace RealEstatePro\Core;
defined('ABSPATH')||exit;
final class PostTypes{
 public static function register():void{
  register_post_type('property',['labels'=>['name'=>'Properties','singular_name'=>'Property','add_new'=>'Add Property'],'public'=>true,'has_archive'=>true,'rewrite'=>['slug'=>'properties'],'supports'=>['title','editor','thumbnail','author'],'show_in_rest'=>true,'map_meta_cap'=>true]);
  foreach(['property_type','property_status','city','neighborhood','amenities'] as $tax)register_taxonomy($tax,'property',['label'=>ucwords(str_replace('_',' ',$tax)),'public'=>true,'hierarchical'=>true,'show_in_rest'=>true]);
  foreach(['price','area_sqm','bedrooms','bathrooms','floor','year_built','address_full','lat','lng','gallery_ids','video_url','virtual_tour_url','agent_id','visibility_level','featured','over_quota'] as $m){
   register_post_meta('property',$m,['single'=>true,'show_in_rest'=>true,'auth_callback'=>static fn()=>current_user_can('edit_posts'),'sanitize_callback'=>static fn($v)=>is_array($v)?array_map('absint',$v):sanitize_text_field($v)]);
  }
  register_post_meta('property','agent_id',['single'=>true,'type'=>'integer','show_in_rest'=>true,'sanitize_callback'=>'absint']);
  add_action('save_post_property',[self::class,'enforce_owner'],10,3);
 }
 public static function enforce_owner(int $post_id,\WP_Post $post,bool $update):void{
  if(wp_is_post_revision($post_id)||defined('DOING_AUTOSAVE'))return;
  if(current_user_can('manage_options'))return;
  if(in_array('re_agent',(array)wp_get_current_user()->roles,true)&&!get_post_meta($post_id,'agent_id',true))update_post_meta($post_id,'agent_id',get_current_user_id());
 }
}
