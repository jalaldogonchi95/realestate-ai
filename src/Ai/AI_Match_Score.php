<?php
namespace RealEstatePro\Ai;
defined('ABSPATH') || exit;

final class AI_Match_Score {
    private AI_Provider_Interface $provider;
    public function __construct(AI_Provider_Interface $provider) { $this->provider = $provider; }

    public function calculate(array $property, array $customer): array {
        $payload = wp_json_encode([
            'property' => $this->clean($property),
            'customer_need' => $this->clean($customer),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $result = $this->provider->chat(
            'You are a real-estate matching engine. Use only supplied facts. Return valid JSON with score from 0 to 100, reasons as an array, and missing_requirements as an array.',
            'Calculate the match: ' . $payload,
            [
                'model' => get_option('re_pro_deepseek_model', 'deepseek-chat'),
                'temperature' => 0.1,
                'max_tokens' => 900,
            ]
        );

        $data = $this->decode($result['text']);
        $score = isset($data['score']) ? $data['score'] : 0;
        $data['score'] = max(0, min(100, absint($score)));
        $reasons = isset($data['reasons']) ? $data['reasons'] : [];
        $missing = isset($data['missing_requirements']) ? $data['missing_requirements'] : [];
        $data['reasons'] = array_values(array_filter(array_map('sanitize_text_field', (array) $reasons)));
        $data['missing_requirements'] = array_values(array_filter(array_map('sanitize_text_field', (array) $missing)));
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
            throw new \RuntimeException(__('AI returned invalid match data.', 'realestate-ai'));
        }
        return $data;
    }
}
