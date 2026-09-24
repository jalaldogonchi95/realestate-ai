<?php
/**
 * DeepSeek provider.
 *
 * @package RealEstatePro
 */
namespace RealEstatePro\Ai;

defined('ABSPATH') || exit;

/**
 * DeepSeek API provider using the OpenAI-compatible chat format.
 */
final class DeepSeek_Provider implements AI_Provider_Interface {
    private const BASE_URL = 'https://api.deepseek.com/v1/chat/completions';
    private const DEFAULT_MODEL = 'deepseek-chat';
    private const DEFAULT_TIMEOUT = 30;
    private const MAX_RETRIES = 3;

    /**
     * Send a completion request to DeepSeek.
     *
     * @param string $system System instruction.
     * @param string $user User prompt.
     * @param array  $options Request options.
     * @return array
     */
    public function chat(string $system, string $user, array $options = []): array {
        $api_key = $this->get_api_key();

        if ('' === $api_key) {
            throw new \RuntimeException(__('DeepSeek API key is not configured.', 'realestate-ai'));
        }

        $model = ! empty($options['model']) ? sanitize_text_field($options['model']) : self::DEFAULT_MODEL;
        $timeout = ! empty($options['timeout']) ? max(5, min(120, absint($options['timeout']))) : self::DEFAULT_TIMEOUT;
        $temperature = isset($options['temperature']) ? (float) $options['temperature'] : 0.2;
        $temperature = max(0, min(2, $temperature));

        $body = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $user],
            ],
            'temperature' => $temperature,
        ];

        if (! empty($options['max_tokens'])) {
            $body['max_tokens'] = max(1, min(8192, absint($options['max_tokens'])));
        }

        $last_error = __('DeepSeek request failed.', 'realestate-ai');

        for ($attempt = 1; $attempt <= self::MAX_RETRIES; $attempt++) {
            $response = wp_remote_post(
                self::BASE_URL,
                [
                    'timeout' => $timeout,
                    'redirection' => 2,
                    'headers' => [
                        'Authorization' => 'Bearer ' . $api_key,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'body' => wp_json_encode($body),
                ]
            );

            if (is_wp_error($response)) {
                $last_error = $response->get_error_message();
            } else {
                $status = (int) wp_remote_retrieve_response_code($response);
                $data = json_decode(wp_remote_retrieve_body($response), true);

                if ($status >= 200 && $status < 300 && is_array($data)) {
                    $text = $data['choices'][0]['message']['content'] ?? '';

                    if (is_string($text) && '' !== $text) {
                        return [
                            'text' => $text,
                            'usage' => is_array($data['usage'] ?? null) ? $data['usage'] : [],
                            'raw' => $data,
                        ];
                    }

                    $last_error = __('DeepSeek returned an empty response.', 'realestate-ai');
                } else {
                    $message = $data['error']['message'] ?? '';
                    $last_error = $message ? sanitize_text_field($message) : sprintf(
                        __('DeepSeek HTTP error: %d.', 'realestate-ai'),
                        $status
                    );

                    if (429 === $status || $status >= 500) {
                        $retry_after = (int) wp_remote_retrieve_header($response, 'retry-after');
                        $delay = $retry_after > 0 ? min($retry_after, 10) : min(2 ** ($attempt - 1), 8);
                        sleep($delay);
                        continue;
                    }

                    throw new \RuntimeException($last_error, $status);
                }
            }

            if ($attempt < self::MAX_RETRIES) {
                sleep(min(2 ** ($attempt - 1), 8));
            }
        }

        throw new \RuntimeException($last_error);
    }

    /**
     * Read the API key. wp-config constant takes precedence.
     *
     * @return string
     */
    private function get_api_key(): string {
        if (defined('RE_PRO_DEEPSEEK_API_KEY') && RE_PRO_DEEPSEEK_API_KEY) {
            return (string) RE_PRO_DEEPSEEK_API_KEY;
        }

        return (string) get_option('re_pro_deepseek_key', '');
    }
}
