<?php
namespace RealEstatePro\Core;
defined('ABSPATH')||exit;
final class Roles{
 public static function register():void{
  add_role('re_agent','Agent',['read'=>true,'upload_property'=>true,'edit_own_property'=>true,'view_agent_dashboard'=>true]);
  add_role('re_customer','Customer',['read'=>true,'view_customer_dashboard'=>true]);
  if($a=get_role('administrator'))foreach(['manage_re_pro_suite','approve_properties','manage_re_pro_subscriptions','view_re_pro_analytics'] as $c)$a->add_cap($c);
 }
 public static function tier(int $uid=0,string $audience='customer'):string{
  $u=get_user_by('id',$uid?:get_current_user_id());if(!$u)return 'free';
  if(user_can($u,'manage_options'))return 'vip';
  $key=$audience==='agent'?'re_pro_agent_tier':'re_pro_customer_tier';
  $tier=sanitize_key((string)get_user_meta($u->ID,$key,true));return in_array($tier,['free','basic','pro','vip'],true)?$tier:'free';
 }
 public static function set_tier(int $uid,string $tier,string $audience='customer'):void{update_user_meta($uid,$audience==='agent'?'re_pro_agent_tier':'re_pro_customer_tier',sanitize_key($tier));}
 public static function agent_limit(int $uid=0):int{return ['free'=>0,'basic'=>5,'pro'=>30,'vip'=>PHP_INT_MAX][self::tier($uid,'agent')]??0;}
}
