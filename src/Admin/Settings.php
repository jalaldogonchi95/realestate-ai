<?php
/**
 * Plugin settings.
 *
 * @package RealEstatePro
 */
namespace RealEstatePro\Admin;

defined('ABSPATH') || exit;

final class Settings {
    public function register(): void {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function menu(): void {
        add_menu_page(
            __('RealEstate Pro Suite', 'realestate-ai'),
            __('RealEstate Pro', 'realestate-ai'),
            'manage_re_pro_suite',
            're-pro-suite',
            [$this, 'page'],
            'dashicons-building',
            26
        );
    }

    public function register_settings(): void {
        register_setting('re_pro_suite', 're_pro_ai_provider', [
            'sanitize_callback' => function ($value) {
                $allowed = ['deepseek', 'openai', 'anthropic'];
                return in_array($value, $allowed, true) ? $value : 'deepseek';
            },
        ]);

        register_setting('re_pro_suite', 're_pro_deepseek_key', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('re_pro_suite', 're_pro_deepseek_model', [
            'sanitize_callback' => function ($value) {
                $allowed = ['deepseek-chat', 'deepseek-reasoner'];
                return in_array($value, $allowed, true) ? $value : 'deepseek-chat';
            },
        ]);
        register_setting('re_pro_suite', 're_pro_openai_key', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('re_pro_suite', 're_pro_anthropic_key', ['sanitize_callback' => 'sanitize_text_field']);
    }

    public function page(): void {
        if (! current_user_can('manage_re_pro_suite')) {
            wp_die(esc_html__('You do not have permission to access this page.', 'realestate-ai'));
        }

        $provider = get_option('re_pro_ai_provider', 'deepseek');
        $model = get_option('re_pro_deepseek_model', 'deepseek-chat');
        ?>
        <div class="wrap" dir="rtl">
            <h1><?php echo esc_html__('RealEstate Pro Suite — AI', 'realestate-ai'); ?></h1>
            <p><?php echo esc_html__('در این مرحله اتصال DeepSeek و قابلیت‌های AI آگهی، Match Score و تخمین قیمت فعال شده است.', 'realestate-ai'); ?></p>

            <form method="post" action="options.php">
                <?php settings_fields('re_pro_suite'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="re_pro_ai_provider"><?php echo esc_html__('AI Provider', 'realestate-ai'); ?></label></th>
                        <td>
                            <select id="re_pro_ai_provider" name="re_pro_ai_provider">
                                <option value="deepseek" <?php selected($provider, 'deepseek'); ?>>DeepSeek</option>
                                <option value="openai" <?php selected($provider, 'openai'); ?>>OpenAI</option>
                                <option value="anthropic" <?php selected($provider, 'anthropic'); ?>>Anthropic</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="re_pro_deepseek_model"><?php echo esc_html__('DeepSeek Model', 'realestate-ai'); ?></label></th>
                        <td>
                            <select id="re_pro_deepseek_model" name="re_pro_deepseek_model">
                                <option value="deepseek-chat" <?php selected($model, 'deepseek-chat'); ?>>deepseek-chat</option>
                                <option value="deepseek-reasoner" <?php selected($model, 'deepseek-reasoner'); ?>>deepseek-reasoner</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="re_pro_deepseek_key"><?php echo esc_html__('DeepSeek API Key', 'realestate-ai'); ?></label></th>
                        <td>
                            <input id="re_pro_deepseek_key" class="regular-text" type="password" name="re_pro_deepseek_key" value="<?php echo esc_attr(get_option('re_pro_deepseek_key', '')); ?>" autocomplete="new-password">
                            <p class="description"><?php echo esc_html__('برای امنیت بیشتر می‌توانید RE_PRO_DEEPSEEK_API_KEY را در wp-config.php تعریف کنید؛ این مقدار بر تنظیمات دیتابیس اولویت دارد.', 'realestate-ai'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="re_pro_openai_key"><?php echo esc_html__('OpenAI API Key', 'realestate-ai'); ?></label></th>
                        <td><input id="re_pro_openai_key" class="regular-text" type="password" name="re_pro_openai_key" value="<?php echo esc_attr(get_option('re_pro_openai_key', '')); ?>" autocomplete="new-password"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="re_pro_anthropic_key"><?php echo esc_html__('Anthropic API Key', 'realestate-ai'); ?></label></th>
                        <td><input id="re_pro_anthropic_key" class="regular-text" type="password" name="re_pro_anthropic_key" value="<?php echo esc_attr(get_option('re_pro_anthropic_key', '')); ?>" autocomplete="new-password"></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
