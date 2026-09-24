<?php
namespace RealEstateAI\Ai;
defined( 'ABSPATH' ) || exit;
final class AI_Listing_Enhancer { public function __construct(private AI_Provider_Interface $provider){} public function enhance(array $listing):array{$r=$this->provider->chat('You are a Persian real-estate copywriter. Use only supplied facts. Return JSON.','Improve this listing: '.wp_json_encode($listing,JSON_UNESCAPED_UNICODE),['model'=>get_option('re_pro_deepseek_model','deepseek-chat'),'temperature'=>0.4]);$d=json_decode(trim(str_replace(['```json','```'],'',$r['text'])),true);if(!is_array($d)){throw new \RuntimeException(__('AI returned invalid JSON.','realestate-ai'));}return $d;} }
