<?php
defined( 'ABSPATH' ) || exit;

final class REAI_Activator {
	public static function activate() {
		if ( class_exists( '\RealEstateAI\Core\Roles' ) ) {
			\RealEstateAI\Core\Roles::register();
		}
		if ( class_exists( '\RealEstateAI\Core\PostTypes' ) ) {
			\RealEstateAI\Core\PostTypes::register();
		}
		flush_rewrite_rules();
		update_option( 'reai_version', REAI_VERSION );
	}
}
