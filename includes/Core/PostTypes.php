<?php
namespace RealEstateAI\Core;
defined( 'ABSPATH' ) || exit;
final class PostTypes { public static function register(): void { register_post_type( 'property', [ 'labels' => [ 'name' => 'Properties', 'singular_name' => 'Property' ], 'public' => true, 'has_archive' => true, 'rewrite' => [ 'slug' => 'properties' ], 'supports' => [ 'title', 'editor', 'thumbnail', 'author' ], 'show_in_rest' => true ] ); } }
