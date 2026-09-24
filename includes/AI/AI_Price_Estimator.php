<?php
namespace RealEstateAI\AI;
defined( 'ABSPATH' ) || exit;
final class AI_Price_Estimator { public function __construct(private AI_Provider_Interface $provider){} public function estimate(array $property,array $comparables=[]):array{$r=$this->provider->chat('Return JSON with estimated_price, lower_bound, upper_bound, currency, confidence and rationale.','Estimate: '.wp_json_encode(['property'=>$property,'comparables'=>array_slice($comparables,0,20)],JSON_UNESCAPED_UNICODE),['model'=>get_option('re_pro_deepseek_model','deepseek-reasoner'),'temperature'=>0.1]);$d=json_decode(trim(str_replace(['```json','```'],'',$r['text'])),true);if(!is_array($d)){throw new \RuntimeException(__('AI returned invalid price data.','realestate-ai'));}return $d;} }
