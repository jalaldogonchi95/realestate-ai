<?php
/**
 * AI provider contract.
 *
 * @package RealEstatePro
 */
namespace RealEstatePro\Ai;

defined('ABSPATH') || exit;

/**
 * Contract for chat-completion providers.
 */
interface AI_Provider_Interface {
    /**
     * Send a chat completion request.
     *
     * @param string $system System instruction.
     * @param string $user User prompt.
     * @param array  $options Provider options.
     * @return array
     */
    public function chat(string $system, string $user, array $options = []): array;
}
