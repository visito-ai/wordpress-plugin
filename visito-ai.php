<?php
/**
 * Plugin Name: Visito AI
 * Plugin URI: https://www.visitoai.com
 * Description: The AI platform that turns messages into revenue.
 * Version: 1.0.0
 * Author: Visito AI
 * Author URI: https://github.com/visito-ai/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */



if (!defined('ABSPATH')) exit;

// Register settings page
add_action('admin_menu', function () {
  add_options_page('Visito AI Settings', 'Visito AI', 'manage_options', 'visito-ai-settings', 'visito_ai_settings_page');
});

// Register setting (with sanitization callback)
add_action('admin_init', function () {
  register_setting('visito_ai_settings_group', 'visito_ai_api_key', [
      'sanitize_callback' => 'sanitize_text_field',
  ]);
});

function visito_ai_settings_page() {
    ?>
    <div class="wrap">
        <h1>Visito AI Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('visito_ai_settings_group');
            do_settings_sections('visito_ai_settings_group');
            $api_key = esc_attr(get_option('visito_ai_api_key'));
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">API Key</th>
                    <td><input type="text" name="visito_ai_api_key" value="<?php echo esc_js($api_key); ?>" size="50" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Inject script in frontend
add_action('wp_footer', function () {
    $api_key = get_option('visito_ai_api_key');
    if (!$api_key) return;
    ?>
    <script>
      (function () {
        const onLoad = function () {
          const script = document.createElement("script");
          script.src = "https://book.visitoai.com/embed.min.js";
          script.id = "visitowc";
          script.setAttribute("data-domain", "visitoai.com");
          script.setAttribute("data-apiKey", "<?php echo esc_js($api_key); ?>");
          document.body.appendChild(script);
        };
        if (document.readyState === "complete") {
          onLoad();
        } else {
          window.addEventListener("load", onLoad);
        }
      })();
    </script>
    <?php
});