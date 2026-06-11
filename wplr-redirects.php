<?php
/**
 * Plugin Name: WP Link Redirect Track
 * Description: Creates trackable redirect pages that fire GA4 events before redirecting.
 * Version: 0.0.1
 * Author: @my-code-library
 */

if (!defined('ABSPATH')) exit;

/**
 * Shortcode: [pj_redirect url="https://example.com" label="My Redirect"]
 *
 * Usage:
 * Create a page → add shortcode → GA4 event fires → user is redirected.
 */
function pj_redirect_shortcode($atts) {
    $atts = shortcode_atts(array(
        'url'   => '',
        'label' => 'Redirect Click'
    ), $atts);

    if (empty($atts['url'])) {
        return '<p>No redirect URL provided.</p>';
    }

    ob_start();
    ?>
    <script>
        // Fire GA4 event
        if (typeof gtag === 'function') {
            gtag('event', 'outbound_click', {
                event_category: 'Redirect',
                event_label: '<?php echo esc_js($atts['label']); ?>',
                destination: '<?php echo esc_js($atts['url']); ?>'
            });
        }

        // Redirect after 150ms
        setTimeout(function() {
            window.location.href = "<?php echo esc_url($atts['url']); ?>";
        }, 150);
    </script>

    <p>Redirecting…</p>
    <?php
    return ob_get_clean();
}
add_shortcode('pj_redirect', 'pj_redirect_shortcode');
