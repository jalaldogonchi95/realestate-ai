<?php
namespace RealEstateAI;

defined( 'ABSPATH' ) || exit;

final class Plugin {
	private static $instance = null;
	private $initialized = false;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {}

	public function init() {
		if ( $this->initialized ) {
			return;
		}
		$this->initialized = true;

		$this->register_components();
		load_plugin_textdomain(
			'realestate-ai',
			false,
			dirname( REAI_BASENAME ) . '/languages'
		);
	}

	private function register_components() {
		if ( class_exists( '\RealEstateAI\Core\PostTypes' ) ) {
			add_action( 'init', array( '\RealEstateAI\Core\PostTypes', 'register' ) );
		}
		if ( class_exists( '\RealEstateAI\Core\Roles' ) ) {
			add_action( 'init', array( '\RealEstateAI\Core\Roles', 'register' ) );
		}
		$components = array(
			'\RealEstateAI\Admin\Settings',
			'\RealEstateAI\Api\Rest',
			'\RealEstateAI\Api\Blocks',
			'\RealEstateAI\Frontend\Shortcodes',
			'\RealEstateAI\Search\PropertySearch',
			'\RealEstateAI\Subscriptions\SubscriptionManager',
			'\RealEstateAI\AI\AiManager',
			'\RealEstateAI\Gating\Gating',
		);
		foreach ( $components as $component ) {
			if ( class_exists( $component ) ) {
				$instance = new $component();
				if ( method_exists( $instance, 'register' ) ) {
					$instance->register();
				}
			}
		}
	}
}
