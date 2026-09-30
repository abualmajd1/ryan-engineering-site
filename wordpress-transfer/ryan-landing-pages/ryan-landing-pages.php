<?php
/**
 * Plugin Name: Ryan Engineering Landing Pages
 * Description: صفحات هبوط مستقلة لمكتب ريان مع تحميل الأصول والتتبع بشكل منفصل عن الصفحة الرئيسية.
 * Version: 1.0.0
 * Author: Ryan Engineering
 */
if (!defined('ABSPATH')) exit;

define('RYAN_LP_VERSION', '1.0.0');
define('RYAN_LP_DIR', plugin_dir_path(__FILE__));
define('RYAN_LP_URL', plugin_dir_url(__FILE__));

function ryan_lp_slugs() {
    return array('surveying-riyadh', 'engineering-consulting-riyadh', 'building-permits-riyadh', 'sukuk');
}
function ryan_lp_is_page() {
    return is_page(ryan_lp_slugs());
}
function ryan_lp_asset($file) {
    $matches = glob(RYAN_LP_DIR . 'assets/' . $file);
    return !empty($matches) ? RYAN_LP_URL . 'assets/' . basename($matches[0]) : '';
}
function ryan_lp_enqueue() {
    if (!ryan_lp_is_page()) return;
    global $wp_styles, $wp_scripts;
    // The React bundle is the complete page shell; prevent theme front-end scripts/styles from altering it.
    if ($wp_scripts && !empty($wp_scripts->queue)) {
        foreach ((array) $wp_scripts->queue as $handle) wp_dequeue_script($handle);
    }
    if ($wp_styles && !empty($wp_styles->queue)) {
        foreach ((array) $wp_styles->queue as $handle) wp_dequeue_style($handle);
    }
    $css = ryan_lp_asset('index-*.css');
    $js = ryan_lp_asset('index-*.js');
    if ($css) wp_enqueue_style('ryan-lp-css', $css, array(), RYAN_LP_VERSION);
    if ($js) {
        wp_enqueue_script('ryan-lp-js', $js, array(), RYAN_LP_VERSION, true);
        wp_add_inline_script('ryan-lp-js', 'window.RYAN_ASSET_BASE = ' . wp_json_encode(RYAN_LP_URL) . ';', 'before');
    }
}
add_action('wp_enqueue_scripts', 'ryan_lp_enqueue', 999);
function ryan_lp_module_script($tag, $handle, $src) {
    return $handle === 'ryan-lp-js' ? '<script type="module" src="' . esc_url($src) . '"></script>' : $tag;
}
add_filter('script_loader_tag', 'ryan_lp_module_script', 10, 3);

function ryan_lp_tracking() {
    if (!ryan_lp_is_page()) return;
    $gtm = trim((string) get_option('ryan_lp_gtm_id', ''));
    $ga4 = trim((string) get_option('ryan_lp_ga4_id', ''));
    if ($gtm !== '') {
        echo "<script>window.dataLayer=window.dataLayer||[];window.dataLayer.push({'gtm.start':new Date().getTime(),event:'gtm.js'});</script>";
        echo '<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!=="dataLayer"?"&l="+l:"";j.async=true;j.src="https://www.googletagmanager.com/gtm.js?id="+i+dl;f.parentNode.insertBefore(j,f);})(window,document,"script","dataLayer",' . wp_json_encode($gtm) . ');</script>';
    } elseif ($ga4 !== '') {
        echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . esc_attr($ga4) . '"></script>';
        echo '<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config",' . wp_json_encode($ga4) . ');</script>';
    }
}
add_action('wp_head', 'ryan_lp_tracking', 1);
function ryan_lp_body_class($classes) { if (ryan_lp_is_page()) $classes[] = 'ryan-landing-page-template'; return $classes; }
add_filter('body_class', 'ryan_lp_body_class');
function ryan_lp_template($template) {
    if (ryan_lp_is_page()) return RYAN_LP_DIR . 'templates/landing.php';
    return $template;
}
add_filter('template_include', 'ryan_lp_template', 99);

function ryan_lp_admin_menu() { add_options_page('Ryan Landing Pages', 'Ryan Landing Pages', 'manage_options', 'ryan-landing-pages', 'ryan_lp_settings_page'); }
add_action('admin_menu', 'ryan_lp_admin_menu');
function ryan_lp_register_settings() {
    register_setting('ryan_lp_settings', 'ryan_lp_gtm_id', array('sanitize_callback' => 'sanitize_text_field'));
    register_setting('ryan_lp_settings', 'ryan_lp_ga4_id', array('sanitize_callback' => 'sanitize_text_field'));
}
add_action('admin_init', 'ryan_lp_register_settings');
function ryan_lp_settings_page() { if (!current_user_can('manage_options')) return; ?>
<div class="wrap" dir="rtl"><h1>Ryan Landing Pages</h1><p>أنشئ صفحات WordPress بالمسارات المحددة أدناه. لا تضع أي محتوى داخل الصفحة؛ الإضافة تعرض التصميم تلقائياً.</p><form method="post" action="options.php"><?php settings_fields('ryan_lp_settings'); ?><table class="form-table"><tr><th>معرّف Google Tag Manager</th><td><input class="regular-text" name="ryan_lp_gtm_id" value="<?php echo esc_attr(get_option('ryan_lp_gtm_id', '')); ?>" placeholder="GTM-XXXXXXX" /></td></tr><tr><th>معرّف GA4 البديل</th><td><input class="regular-text" name="ryan_lp_ga4_id" value="<?php echo esc_attr(get_option('ryan_lp_ga4_id', '')); ?>" placeholder="G-XXXXXXXXXX" /><p class="description">إذا أضفت GTM، سيُستخدم GTM فقط ويجب إضافة GA4 من داخل الحاوية.</p></td></tr></table><?php submit_button('حفظ الإعدادات'); ?></form><h2>المسارات المطلوبة</h2><ol><li>/lp/surveying-riyadh/</li><li>/lp/engineering-consulting-riyadh/</li><li>/lp/building-permits-riyadh/</li><li>/lp/sukuk/</li></ol></div>
<?php }
