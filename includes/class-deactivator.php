<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
final class REAI_Deactivator { public static function deactivate(): void { \RealEstateAI\Core\Deactivator::deactivate(); } }
