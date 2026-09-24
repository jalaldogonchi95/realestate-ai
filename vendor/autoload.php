<?php
defined( 'ABSPATH' ) || exit;
spl_autoload_register( static function ( string $class ): void { $prefix = 'RealEstateAI\\'; if ( 0 !== strpos( $class, $prefix ) ) { return; } $relative = str_replace( '\\', '/', substr( $class, strlen( $prefix ) ) ); $file = dirname( __DIR__ ) . '/includes/' . $relative . '.php'; if ( is_readable( $file ) ) { require_once $file; } } );
