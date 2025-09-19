<?php
function mytheme_enqueue_scripts()
{
    wp_enqueue_style('mytheme-style', get_stylesheet_uri());
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/css/reset.css');
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/css/style.css');
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/css/responsive.css');
    wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/map.js', [], false, true);
    wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/nav.js', [], false, true);
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_scripts');

register_nav_menus([
    'primary' => 'Main Menu',
]);


function mytheme_register_products() {
    register_post_type('product', [
        'labels' => [
            'name'          => 'Products',
            'singular_name' => 'Product',
        ],
        'public'      => true,
        'has_archive' => true,
        'supports'    => ['title', 'editor', 'thumbnail'],
        'menu_icon'   => 'dashicons-cart',
    ]);
}
add_action('init', 'mytheme_register_products');

// Register custom product meta fields
function mytheme_register_product_meta()
{
    $fields = [
        'dimension_height' => 'number',
        'dimension_width'  => 'number',
        'dimension_depth'  => 'number',
        'plumbing_no'      => 'string',
        'waterless_no'     => 'string',
        'cad_file'         => 'string',
        'zip_file'         => 'string',
        'drawing_file'     => 'string',
    ];

    foreach ($fields as $key => $type) {
        register_post_meta('product', $key, [
            'show_in_rest' => true,
            'single'       => true,
            'type'         => $type,
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            }
        ]);
    }
}
add_action('init', 'mytheme_register_product_meta');


// 2. Add meta box
function mytheme_add_product_meta_boxes()
{
    add_meta_box(
        'product_specs',
        'Product Specifications',
        'mytheme_render_product_meta_box',
        'product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'mytheme_add_product_meta_boxes');


// 3. Render meta box
function mytheme_render_product_meta_box($post)
{
    $fields = [
        'dimension_height' => 'Height (mm)',
        'dimension_width'  => 'Width (mm)',
        'dimension_depth'  => 'Depth (mm)',
        'plumbing_no'      => 'Plumbing no.',
        'waterless_no'     => 'Waterless no.',
        'cad_file'         => 'CAD File URL',
        'zip_file'         => 'Zip File URL',
        'drawing_file'     => 'Technical Drawing URL',
    ];

    foreach ($fields as $key => $label) {
        $value = get_post_meta($post->ID, $key, true);
        $type = strpos($key, '_file') !== false ? 'url' : 'text';
        echo "<p><label>{$label}: <input type='{$type}' name='{$key}' value='" . esc_attr($value) . "'></label></p>";
    }
}


// 4. Save meta box values
function mytheme_save_product_meta($post_id)
{
    $fields = [
        'dimension_height',
        'dimension_width',
        'dimension_depth',
        'plumbing_no',
        'waterless_no',
        'cad_file',
        'zip_file',
        'drawing_file'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = strpos($field, '_file') !== false ? esc_url_raw($_POST[$field]) : sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $field, $value);
        }
    }
}
add_action('save_post_product', 'mytheme_save_product_meta');
