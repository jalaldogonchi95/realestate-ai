<?php
namespace RealEstateAI\Ai;
defined( 'ABSPATH' ) || exit;
final class AiManager { public function register():void{} public function provider():AI_Provider_Interface{return new DeepSeek_Provider();} public function ask(int $uid,int $pid,string $q):array{return $this->provider()->chat('Only use supplied property data. Never invent facts.',wp_strip_all_tags($q),[]);} }
