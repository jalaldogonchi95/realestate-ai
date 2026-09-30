<?php
namespace RealEstateAI\Admin;
defined('ABSPATH')||exit;
use RealEstateAI\AI\ProviderFactory;
final class Settings{
 public function register():void{add_action('admin_menu',array($this,'menu'));add_action('admin_init',array($this,'save'));}
 public function menu():void{add_menu_page('RealEstate AI','RealEstate AI','manage_options','reai-general',array($this,'page'),'dashicons-admin-home',56);}
 public function save():void{
  if(!isset($_POST['reai_ai_save'])||!current_user_can('manage_options'))return;check_admin_referer('reai_ai_settings');
  $d=ProviderFactory::definitions();$p=array();
  foreach($d as $k=>$v)$p[$k]=array('api_key'=>isset($_POST['api_key'][$k])?sanitize_text_field(wp_unslash($_POST['api_key'][$k])):'','model'=>isset($_POST['model'][$k])?sanitize_text_field(wp_unslash($_POST['model'][$k])):$v['model']);
  $primary=isset($_POST['primary'])?sanitize_key(wp_unslash($_POST['primary'])):'deepseek';if(!isset($d[$primary]))$primary='deepseek';
  $fallbacks=isset($_POST['fallbacks'])?array_map('sanitize_key',(array)wp_unslash($_POST['fallbacks'])):array();
  update_option('reai_ai_settings',array('primary'=>$primary,'fallbacks'=>$fallbacks,'providers'=>$p),false);add_settings_error('reai_ai','saved',__('AI settings saved.','realestate-ai'),'updated');
 }
 public function page():void{
  $s=get_option('reai_ai_settings',array());$p=$s['providers']??array();$primary=$s['primary']??'deepseek';$fallbacks=(array)($s['fallbacks']??array('groq','gemini'));$d=ProviderFactory::definitions();settings_errors('reai_ai');?>
  <div class="wrap" dir="rtl"><h1>تنظیمات هوش مصنوعی RealEstate AI</h1><p>Provider اصلی و پشتیبان را انتخاب کنید. هنگام خطای Provider اصلی، سیستم به ترتیب سراغ پشتیبان‌ها می‌رود.</p>
  <form method="post"><?php wp_nonce_field('reai_ai_settings');?><input type="hidden" name="reai_ai_save" value="1">
  <table class="widefat striped" style="max-width:1100px"><thead><tr><th>Provider</th><th>API Key</th><th>Model</th><th>اصلی</th><th>پشتیبان</th></tr></thead><tbody>
  <?php foreach($d as $k=>$v):?><tr><td><strong><?php echo esc_html($v['label']);?></strong></td><td><input type="password" name="api_key[<?php echo esc_attr($k);?>]" value="<?php echo esc_attr($p[$k]['api_key']??'');?>" class="regular-text" autocomplete="new-password"></td><td><input type="text" name="model[<?php echo esc_attr($k);?>]" value="<?php echo esc_attr($p[$k]['model']??$v['model']);?>" class="regular-text"></td><td><input type="radio" name="primary" value="<?php echo esc_attr($k);?>" <?php checked($primary,$k);?>></td><td><input type="checkbox" name="fallbacks[]" value="<?php echo esc_attr($k);?>" <?php checked(in_array($k,$fallbacks,true));?>></td></tr><?php endforeach;?>
  </tbody></table><?php submit_button('ذخیره تنظیمات');?></form><p class="description">شرایط استفاده تجاری و محدودیت‌های هر سرویس را قبل از استفاده در سایت بررسی کنید.</p></div><?php
 }
}