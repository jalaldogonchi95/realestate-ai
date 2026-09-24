<?php
namespace RealEstatePro\Core;
use RealEstatePro\Admin\Settings;use RealEstatePro\Api\Rest;use RealEstatePro\Api\Blocks;use RealEstatePro\Ai\AiManager;use RealEstatePro\Frontend\Shortcodes;use RealEstatePro\Gating\Gating;use RealEstatePro\Search\PropertySearch;use RealEstatePro\Subscriptions\SubscriptionManager;
defined('ABSPATH')||exit;
final class Plugin{
 private static bool $booted=false;
 public static function boot():void{
  if(self::$booted)return;self::$booted=true;
  add_action('init',[PostTypes::class,'register']);add_action('init',[Roles::class,'register']);
  (new Settings())->register();(new Rest())->register();(new Blocks())->register();(new Shortcodes())->register();(new PropertySearch())->register();(new SubscriptionManager())->register();(new AiManager())->register();(new Gating())->register();
  add_action('wp_enqueue_scripts',static function():void{wp_enqueue_style('re-pro-suite',RE_PRO_SUITE_URL.'assets/css/app.css',[],RE_PRO_SUITE_VERSION);wp_enqueue_script('re-pro-suite',RE_PRO_SUITE_URL.'assets/js/app.js',['jquery'],RE_PRO_SUITE_VERSION,true);wp_localize_script('re-pro-suite','rePro',['rest'=>esc_url_raw(rest_url('re-pro/v1/')),'nonce'=>wp_create_nonce('wp_rest'),'ajax'=>admin_url('admin-ajax.php'),'searchNonce'=>wp_create_nonce('re_pro_search')]);});
  load_plugin_textdomain('realestate-ai',false,dirname(plugin_basename(RE_PRO_SUITE_FILE)).'/languages');
 }
}
