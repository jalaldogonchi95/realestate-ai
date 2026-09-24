=== RealEstate Pro Suite ===
Requires at least: 6.5
Requires PHP: 8.2
Stable tag: 1.0.0
License: GPLv2 or later
Text Domain: re-pro-suite

Subscription real-estate SaaS foundation for WordPress/WooCommerce.

== Installation ==
1. Install WordPress 6.5+, PHP 8.2+, MySQL 8+ and WooCommerce.
2. WooCommerce Subscriptions is recommended for recurring billing.
3. Upload the real-estate-pro-suite folder to wp-content/plugins/.
4. Activate the plugin and configure RealEstate Pro in wp-admin.
5. Create subscription products containing Pro or VIP in their names for the starter tier synchronizer.

== Shortcodes ==
[property-grid] [property-search] [property_details] [ai-assistant]

== Security ==
Property REST output is tier-filtered server-side. AI is unavailable to free customers and Pro is limited to 20 logged queries per month.

== Production integrations ==
Stripe/PayPal/Zarinpal/IDPay, Kavenegar/Ghasedak/Twilio, JWT, SSE, chat, radius search, saved-search alerts, analytics, Jalali UI, Elementor/WPBakery adapters, PHPUnit and Playwright should be integrated and tested before commercial launch.