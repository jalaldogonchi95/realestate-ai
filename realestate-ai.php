<?php
/**
 * Plugin Name: RealEstate AI
 * Plugin URI: https://github.com/jalaldogonchi95/realestate-ai
 * Description: پلتفرم هوشمند املاک و مستغلات با هوش مصنوعی DeepSeek، پیامک IPPanel، درگاه‌های پرداخت و داشبوردهای Agent/Customer/Admin
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Jalal Dogonchi
 * License: GPL-2.0-or-later
 * Text Domain: realestate-ai
 * Domain Path: /languages
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'REAI_VERSION', '1.0.0' );
define( 'REAI_FILE', __FILE__ );
define( 'REAI_PATH', plugin_dir_path( __FILE__ ) );
define( 'REAI_URL', plugin_dir_url( __FILE__ ) );
define( 'REAI_BASENAME', plugin_basename( __FILE__ ) );

require_once REAI_PATH . 'includes/class-activator.php';
require_once REAI_PATH . 'includes/class-deactivator.php';

if ( file_exists( REAI_PATH . 'vendor/autoload.php' ) ) {
	require_once REAI_PATH . 'vendor/autoload.php';
} else {
	spl_autoload_register(
		static function ( $class ) {
			$prefix   = 'RealEstateAI\\';
			$base_dir = REAI_PATH . 'includes/';
			$length   = strlen( $prefix );
			if ( 0 !== strncmp( $prefix, $class, $length ) ) {
				return;
			}
			$relative = substr( $class, $length );
			$file     = $base_dir . str_replace( '\\', '/', $relative ) . '.php';
			if ( file_exists( $file ) ) {
				require_once $file;
			}
		}
	);
}

register_activation_hook( __FILE__, array( 'REAI_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'REAI_Deactivator', 'deactivate' ) );

add_action(
	'plugins_loaded',
	static function () {
		\RealEstateAI\Plugin::instance()->init();
	}
);
