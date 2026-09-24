<?php
namespace RealEstatePro\Gating;
use RealEstatePro\Core\Roles;
defined('ABSPATH')||exit;
final class Gating{
 public function register():void{add_filter('the_content',[$this,'content'],20);add_filter('template_redirect',[$this,'protect_url']);}
 public function tier(int $uid=0):string{return Roles::tier($uid,'customer');}
 public function allowed(string $required,int $uid=0):bool{$r=['free'=>0,'pro'=>1,'vip'=>2];return ($r[$this->tier($uid)]??0)>=($r[$required]??0);}
 public function can_ai(int $pid,int $uid=0):bool{$level=sanitize_key((string)get_post_meta($pid,'visibility_level',true));return in_array($level,['pro','vip'],true)&&in_array($this->tier($uid),['pro','vip'],true);}
 public function content(string $content):string{
  if(!is_singular('property')||!in_the_loop())return $content;$need=sanitize_key((string)get_post_meta(get_the_ID(),'visibility_level',true))?:'free';
  if($this->allowed($need))return $content;
  return '<div class="re-pro-paywall"><div class="re-pro-blur">'.wp_kses_post(wp_trim_words($content,45)).'</div><div class="re-pro-upgrade"><strong>این محتوا برای اشتراک شما محدود است.</strong><a href="'.esc_url(wc_get_page_permalink('myaccount')).'">ارتقا به Pro/VIP</a></div></div>';
 }
 public function protect_url():void{
  if(!is_singular('property'))return;$id=get_the_ID();if(!$id||$this->allowed(sanitize_key((string)get_post_meta($id,'visibility_level',true))?:'free'))return;
  status_header(200);
 }
}
