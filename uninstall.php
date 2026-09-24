<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }
global $wpdb;
foreach ( [ 'reai_ai_usage_log', 'reai_property_views', 'reai_saved_searches' ] as $table ) { $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . $table ); }
delete_option( 're_pro_deepseek_key' );
delete_option( 're_pro_deepseek_model' );
