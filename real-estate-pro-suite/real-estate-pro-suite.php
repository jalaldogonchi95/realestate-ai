<?php
/**
 * Plugin Name: RealEstate Pro Suite
 * Description: Subscription-based real estate SaaS platform for WordPress and WooCommerce.
 * Version: 1.0.0
 * Author: RealEstate Pro Suite
 * Text Domain: realestate-ai
 * Domain Path: /languages
 * Requires at least: 6.5
 * Requires PHP: 8.2
 */
defined('ABSPATH') || exit;
define('RE_PRO_SUITE_VERSION','1.0.0');
define('RE_PRO_SUITE_FILE',__FILE__);
define('RE_PRO_SUITE_DIR',plugin_dir_path(__FILE__));
define('RE_PRO_SUITE_URL',plugin_dir_url(__FILE__));
require_once RE_PRO_SUITE_DIR.'src/Support/Autoloader.php';
RealEstatePro\Support\Autoloader::register(RE_PRO_SUITE_DIR.'src');
register_activation_hook(__FILE__,['RealEstatePro\\Core\\Activator','activate']);
register_deactivation_hook(__FILE__,['RealEstatePro\\Core\\Deactivator','deactivate']);
add_action('plugins_loaded',static function():void{RealEstatePro\\Core\\Plugin::boot();});
