<?php
namespace RealEstatePro\Ai;
defined('ABSPATH') || exit;

final class AI_Price_Estimator {
    private AI_Provider_Interface $provider;
    public function __construct(AI_Provider_Interface $provider) { $this->provider = $provider; }

    public function estimate(array $property, array $comparables = []): array {
        $payload = wp_json_encode([
            'property' => $this->clean($property),
            'comparables' => array_map([$this, 'clean'], array_slice($comparables, 0, 20)),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $result = $this->provider->chat(
            'You are a real-estate price analysis assistant. Use only supplied data. Return valid JSON with estimated_price, lower_bound, upper_bound, currency, confidence from 0 to 100, and rationale. Never present an estimate as guaranteed.',
            'Estimate from this JSON: ' . $payload,
            [
                'model' => get_option('re_pro_deepseek_model', 'deepseek-reasoner'),
                'temperature' => 0.1,
                'max_tokens' => 1100,
            ]
        );

        $data = $this->decode($result['text']);
        foreach (['estimated_price','lower_bound','upper_bound'] as $key) {
            if (isset($data[$key])) {
                $data[$key] = max(0, (float) $data[$key]);
            }
        }
        $confidence = isset($data['confidence']) ? $data['confidence'] : 0;
        $data['confidence'] = max(0, min(100, absint($confidence)));
        $data['currency'] = sanitize_text_field(isset($data['currency']) ? $data['currency'] : 'Toman');
        $data['rationale'] = sanitize_textarea_field(isset($data['rationale']) ? $data['rationale'] : '');
        return $data;
    }

    private function clean(array $data): array {
        $clean = [];
        foreach ($data as $key => $value) {
            $clean[sanitize_key($key)] = is_array($value) ? array_map('sanitize_text_field', $value) : sanitize_text_field((string) $value);
        }
        return $clean;
    }

    private function decode(string $text): array {
        $text = trim(str_replace(['```json', '```'], '', $text));
        $data = json_decode($text, true);
        if (!is_array($data)) {
            throw new \RuntimeException(__('AI returned invalid price data.', 'realestate-ai'));
        }
        return $data;
    }
}
