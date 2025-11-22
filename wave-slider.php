<?php
/*
Plugin Name: Wave Animations Image Slider
Description: Wave-style slider with image upload, thumbnails, reorder, remove + shortcode [wave_slider]
Version: 3.0
Author:WP Design Lab
*/

if (!defined('ABSPATH')) exit;

/*
|--------------------------------------------------------------------------
| ADMIN MENU
|--------------------------------------------------------------------------
*/
add_action('admin_menu', function() {
    add_menu_page(
        'Wave Slider',
        'Wave Slider',
        'manage_options',
        'wave-slider',
        'wave_slider_admin_page',
        'dashicons-images-alt2',
        90
    );
});

/*
|--------------------------------------------------------------------------
| ADMIN SCRIPTS + STYLES
|--------------------------------------------------------------------------
*/
add_action('admin_enqueue_scripts', function($hook){
    if ($hook === 'toplevel_page_wave-slider') {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-sortable');

        wp_enqueue_script(
            'wave-slider-admin-js',
            plugins_url('assets/admin.js', __FILE__),
            ['jquery', 'jquery-ui-sortable'],
            time(),
            true
        );

        wp_enqueue_style(
            'wave-slider-admin-css',
            plugins_url('admin.css', __FILE__),
            [],
            time()
        );
    }
});

/*
|--------------------------------------------------------------------------
| SAVE SETTINGS
|--------------------------------------------------------------------------
*/
add_action('admin_init', function() {
    register_setting('wave_slider_settings', 'wave_slider_images');
});

/*
|--------------------------------------------------------------------------
| ADMIN PAGE
|--------------------------------------------------------------------------
*/
function wave_slider_admin_page() {
    $images = get_option('wave_slider_images', []);
?>
<div class="wrap">
    <h1>Wave Slider Settings</h1>

    <form method="post" action="options.php">
        <?php settings_fields('wave_slider_settings'); ?>

        <button type="button" class="button button-primary wave-add-image">+ Add Image</button>

        <ul id="wave-slider-list">
            <?php if (!empty($images)) : foreach ($images as $img) : ?>
                <li class="wave-item">
                    <div class="thumb" style="background-image:url('<?php echo esc_url($img); ?>');"></div>
                    <input type="hidden" name="wave_slider_images[]" value="<?php echo esc_attr($img); ?>" />
                    <button class="button wave-remove">Remove</button>
                </li>
            <?php endforeach; endif; ?>
        </ul>

        <?php submit_button('Save Slider'); ?>
    </form>
</div>
<?php }

/*
|--------------------------------------------------------------------------
| FRONTEND SHORTCODE
|--------------------------------------------------------------------------
*/
add_shortcode('wave_slider', function() {

    $images = get_option('wave_slider_images', []);
    if (empty($images)) return '';

    $html = '<div class="wave-slider"><div class="our-wrapper"><div class="items">';

    foreach($images as $img) {
        $html .= '<div class="item" tabindex="0" style="background-image:url('.esc_url($img).')"></div>';
    }

    $html .= '</div></div></div>';
    return $html;
});

/*
|--------------------------------------------------------------------------
| FRONTEND CSS
|--------------------------------------------------------------------------
*/
add_action('wp_enqueue_scripts', function(){
    wp_enqueue_style('wave-slider-style', plugins_url('style.css', __FILE__));
});
