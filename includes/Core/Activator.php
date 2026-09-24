<?php
namespace RealEstateAI\Core;
defined( 'ABSPATH' ) || exit;
final class Activator { public static function activate(): void { Roles::register(); PostTypes::register(); flush_rewrite_rules(); update_option( 'reai_version', REAI_VERSION ); } }
