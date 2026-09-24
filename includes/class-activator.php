<?php
defined( 'ABSPATH' ) || exit;

final class REAI_Activator {
	public static function activate() {
		update_option( 'reai_version', REAI_VERSION );
		flush_rewrite_rules();
	}
}
