<?php
namespace RealEstateAI\Frontend;
defined( 'ABSPATH' ) || exit;
final class Shortcodes { public function register():void{add_shortcode('realestate_ai_listings',[$this,'listings']);add_shortcode('realestate_ai_search',[$this,'search']);} public function listings():string{return '<div class="reai-listings"></div>';} public function search():string{return '<form class="reai-search"><input name="q"><button>جستجو</button></form>';}}
