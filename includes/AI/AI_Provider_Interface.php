<?php
namespace RealEstateAI\Ai;
defined( 'ABSPATH' ) || exit;
interface AI_Provider_Interface { public function chat( string $system, string $user, array $options = [] ): array; }
