<?php
namespace RealEstatePro\Ai;
defined('ABSPATH') || exit;

final class AI_Listing_Enhancer {
    private AI_Provider_Interface $provider;
    public function __construct(AI_Provider_Interface $provider) { $this->provider = $provider; }

    public function enhance(array $listing): array {
        $payload = wp_json_encode($this->clean($listing), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $system = 'You are a Persian real-estate copywriter and SEO specialist. Use only facts in JSON. Never invent facts. Return valid JSON with title, description, seo_title, seo_description and tags.';
        $result = $this->provider->chat($system, 'Improve this listing: ' . $payload, [
            'model' => get_option('re_pro_deepseek_model', 'deepseek-chat'),
            'temperature' => 0.4,
            'max_tokens' => 1400,
        ]);
        return $this->decode($result['text']);
    }

    private function clean(array $data): array {
        $allowed = ['title','description','property_type','property_status','city','neighborhood','price','area_sqm','bedrooms','bathrooms','floor','year_built','amenities'];
        $clean = [];
        foreach ($allowed as $key) {
            if (isset($data[$key])) {
                $clean[$key] = is_array($data[$key]) ? array_map('sanitize_text_field', $data[$key]) : sanitize_textarea_field((string) $data[$key]);
            }
        }
        return $clean;
    }

    private function decode(string $text): array {
        $text = trim(str_replace(['```json', '```'], '', $text));
        $data = json_decode($text, true);
        if (!is_array($data)) {
            throw new \RuntimeException(__('AI returned invalid JSON.', 'realestate-ai'));
        }
        return $data;
    }
}
