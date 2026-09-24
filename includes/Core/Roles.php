<?php
namespace RealEstateAI\Core;
defined( 'ABSPATH' ) || exit;
final class Roles { public static function register(): void { add_role( 're_agent', 'Agent', [ 'read' => true ] ); add_role( 're_customer', 'Customer', [ 'read' => true ] ); } public static function tier( int $uid = 0, string $audience = 'customer' ): string { $user = get_user_by( 'id', $uid ?: get_current_user_id() ); if ( ! $user ) { return 'free'; } if ( user_can( $user, 'manage_options' ) ) { return 'vip'; } return 'free'; } }
