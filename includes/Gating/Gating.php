<?php
namespace RealEstateAI\Gating;
defined( 'ABSPATH' ) || exit;
final class Gating { public function register():void{} public function tier(int $uid=0):string{return 'free';} public function can_ai(int $pid,int $uid=0):bool{return false;} }
