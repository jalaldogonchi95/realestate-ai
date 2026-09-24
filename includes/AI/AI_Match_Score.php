<?php
namespace RealEstateAI\AI;
defined( 'ABSPATH' ) || exit;
final class AI_Match_Score { public function __construct(private AI_Provider_Interface $provider){} public function calculate(array $property,array $customer):int{$r=$this->provider->chat('Return JSON with score from 0 to 100. Use only supplied facts.',wp_json_encode(['property'=>$property,'customer_need'=>$customer],JSON_UNESCAPED_UNICODE),['model'=>get_option('re_pro_deepseek_model','deepseek-chat'),'temperature'=>0.1]);$d=json_decode(trim(str_replace(['```json','```'],'',$r['text'])),true);return max(0,min(100,absint($d['score']??0)));} }
