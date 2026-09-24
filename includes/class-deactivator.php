<?php
defined( 'ABSPATH' ) || exit;

final class REAI_Deactivator {
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
