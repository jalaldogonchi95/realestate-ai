<?php
namespace RealEstateAI\AI;
defined('ABSPATH')||exit;
final class ProviderFactory{
 public static function build(string $provider,array $config):AI_Provider_Interface{
  $d=self::definitions();if(empty($d[$provider]))throw new \InvalidArgumentException(__('Unknown AI provider.','realestate-ai'));
  $api=isset($config['api_key'])?trim((string)$config['api_key']):'';$model=!empty($config['model'])?trim((string)$config['model']):$d[$provider]['model'];
  return new OpenAICompatibleProvider($d[$provider]['label'],$d[$provider]['base_url'],$api,$model);
 }
 public static function definitions():array{return array(
  'deepseek'=>array('label'=>'DeepSeek','base_url'=>'https://api.deepseek.com/v1','model'=>'deepseek-chat'),
  'groq'=>array('label'=>'Groq','base_url'=>'https://api.groq.com/openai/v1','model'=>'openai/gpt-oss-120b'),
  'cerebras'=>array('label'=>'Cerebras','base_url'=>'https://api.cerebras.ai/v1','model'=>'llama3.1-70b'),
  'gemini'=>array('label'=>'Google Gemini','base_url'=>'https://generativelanguage.googleapis.com/v1beta/openai','model'=>'gemini-2.5-flash'),
  'mistral'=>array('label'=>'Mistral AI','base_url'=>'https://api.mistral.ai/v1','model'=>'mistral-small-latest'),
  'zai'=>array('label'=>'Z AI','base_url'=>'https://open.bigmodel.cn/api/paas/v4','model'=>'glm-4-flash'),
  'openrouter'=>array('label'=>'OpenRouter','base_url'=>'https://openrouter.ai/api/v1','model'=>'deepseek/deepseek-r1:free')
 );}
}