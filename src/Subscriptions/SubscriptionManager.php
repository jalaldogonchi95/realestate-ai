<?php
namespace RealEstatePro\Subscriptions;
use RealEstatePro\Core\Roles;
defined('ABSPATH')||exit;
final class SubscriptionManager{
 public function register():void{
  foreach(['woocommerce_subscription_status_active','woocommerce_subscription_status_cancelled','woocommerce_subscription_status_expired','woocommerce_subscription_status_on-hold'] as $h)add_action($h,[$this,'sync']);
  add_action('woocommerce_checkout_order_processed',[$this,'sync_order']);
 }
 public function sync($s):void{
  if(!is_object($s)||!method_exists($s,'get_user_id'))return;$uid=(int)$s->get_user_id();$tier='free';$aud='customer';
  if(method_exists($s,'get_status')&&$s->get_status()==='active'){
   $n=strtolower(implode(' ',array_map(static fn($i)=>$i->get_name(),$s->get_items())));
   $aud=str_contains($n,'agent')?'agent':'customer';$tier=str_contains($n,'vip')?'vip':(str_contains($n,'pro')?'pro':(str_contains($n,'basic')?'basic':'free'));
  }
  Roles::set_tier($uid,$tier,$aud);
 }
 public function sync_order($order_id):void{}
}
