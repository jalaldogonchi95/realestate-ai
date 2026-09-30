<?php
namespace RealEstateAI\AI;
defined('ABSPATH')||exit;
final class AiManager{
 public function register():void{}
 public function provider():AI_Provider_Interface{
  $s=get_option('reai_ai_settings',array());$p=$s['providers']??array();$primary=!empty($s['primary'])?sanitize_key($s['primary']):'deepseek';
  foreach(array_unique(array_merge(array($primary),array_map('sanitize_key',(array)($s['fallbacks']??array('groq','gemini'))))) as $name){try{return ProviderFactory::build($name,is_array($p[$name]??null)?$p[$name]:array());}catch(\Throwable $e){continue;}}
  throw new \RuntimeException(__('No configured AI provider is available.','realestate-ai'));
 }
 public function ask(int $uid,int $pid,string $q):array{
  $s=get_option('reai_ai_settings',array());$p=$s['providers']??array();$order=array_unique(array_merge(array(!empty($s['primary'])?$s['primary']:'deepseek'),array_map('sanitize_key',(array)($s['fallbacks']??array('groq','gemini')))));$last=null;
  foreach($order as $name){try{$r=ProviderFactory::build(sanitize_key($name),is_array($p[$name]??null)?$p[$name]:array())->chat('Only use supplied property data. Never invent facts.',wp_strip_all_tags($q),array());update_option('reai_ai_last_provider',sanitize_key($name),false);return $r;}catch(\Throwable $e){$last=$e;}}
  throw new \RuntimeException($last?$last->getMessage():__('AI request failed.','realestate-ai'));
 }
}