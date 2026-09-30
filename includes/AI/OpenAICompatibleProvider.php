<?php
namespace RealEstateAI\AI;
defined('ABSPATH')||exit;
final class OpenAICompatibleProvider implements AI_Provider_Interface{
 private $name;private $base_url;private $api_key;private $default_model;
 public function __construct(string $name,string $base_url,string $api_key,string $default_model){$this->name=$name;$this->base_url=rtrim($base_url,'/');$this->api_key=$api_key;$this->default_model=$default_model;}
 public function chat(string $system,string $user,array $options=array()):array{
  if(''===$this->api_key)throw new \RuntimeException(sprintf(__('%s API key is not configured.','realestate-ai'),$this->name));
  $model=!empty($options['model'])?sanitize_text_field($options['model']):$this->default_model;
  $body=array('model'=>$model,'messages'=>array(array('role'=>'system','content'=>$system),array('role'=>'user','content'=>$user)),'temperature'=>isset($options['temperature'])?(float)$options['temperature']:0.2);
  $r=wp_remote_post($this->base_url.'/chat/completions',array('timeout'=>35,'headers'=>array('Authorization'=>'Bearer '.$this->api_key,'Content-Type'=>'application/json','Accept'=>'application/json'),'body'=>wp_json_encode($body)));
  if(is_wp_error($r))throw new \RuntimeException($r->get_error_message());
  $status=(int)wp_remote_retrieve_response_code($r);$data=json_decode(wp_remote_retrieve_body($r),true);
  if($status<200||$status>=300||!is_array($data)){ $msg=is_array($data)&&!empty($data['error']['message'])?sanitize_text_field($data['error']['message']):sprintf(__('%s returned HTTP %d.','realestate-ai'),$this->name,$status);throw new \RuntimeException($msg,$status);}
  return array('text'=>(string)($data['choices'][0]['message']['content']??''),'usage'=>$data['usage']??array(),'provider'=>$this->name,'model'=>$model);
 }
}