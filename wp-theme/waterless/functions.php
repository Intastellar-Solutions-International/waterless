<?php

function waterless_enqueue_scripts()
{
    wp_register_style("waterless-style", get_template_directory_uri() . '/css/reset.css');
    wp_register_style("waterless-style", get_template_directory_uri() . '/css/style.css');
    wp_register_style("waterless-style", get_template_directory_uri() . '/css/responsive.css');

    wp_register_script("waterless-js", get_template_directory_uri() . '/js/map.js');
    wp_register_script("waterless-js", get_template_directory_uri() . '/js/nav.js');

    wp_enqueue_style('waterless-style', get_stylesheet_uri());
    wp_enqueue_style('waterless-style', get_template_directory_uri() . '/css/reset.css');
    wp_enqueue_style('waterless-style', get_template_directory_uri() . '/css/style.css');
    wp_enqueue_style('waterless-style', get_template_directory_uri() . '/css/responsive.css');
    wp_enqueue_script('waterless-js', get_template_directory_uri() . '/js/map.js', [], false, true);
    wp_enqueue_script('waterless-js', get_template_directory_uri() . '/js/nav.js', [], false, true);
}
add_action('wp_enqueue_scripts', 'waterless_enqueue_scripts');

register_nav_menus([
    'primary' => 'Main Menu',
]);


function waterless_register_products()
{
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
add_action('init', 'waterless_register_products');

// Register waterless product meta fields
function waterless_register_product_meta()
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
add_action('init', 'waterless_register_product_meta');


// 2. Add meta box
function waterless_add_product_meta_boxes()
{
    add_meta_box(
        'product_specs',
        'Product Specifications',
        'waterless_render_product_meta_box',
        'product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'waterless_add_product_meta_boxes');


// 3. Render meta box
function waterless_render_product_meta_box($post)
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
function waterless_save_product_meta($post_id)
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
add_action('save_post_product', 'waterless_save_product_meta');

// Home page
// Register native meta fields for the homepage
function waterless_register_homepage_fields()
{
    $fields = [
        // Hero Section
        'hero_title',
        'hero_subtitle',
        'hero_cta',
        'hero_image',

        // Intro Section
        'intro_small_text',
        'intro_heading',
        'intro_image',

        // Map Section
        'map_badge',
        'map_heading1',
        'map_heading2',
        'map_text',
        'map_cta',

        // Full-width Section
        'fullwidth_image',
        'fullwidth_small_text',
        'fullwidth_heading',
        'fullwidth_text1',
        'fullwidth_text2',

        // Installation Section
        'installation_heading',
        'installation_cta_text',
        'installation_cta_link',
        'installation_image',

        // Success Section
        'success_image',
        'success_heading'
    ];

    foreach ($fields as $field) {
        register_post_meta('page', $field, [
            'show_in_rest' => true,  // Gutenberg and REST API support
            'single'       => true,
            'type'         => 'string',
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            }
        ]);
    }
}
add_action('init', 'waterless_register_homepage_fields');

// Add meta box to edit homepage fields
function waterless_add_homepage_meta_boxes()
{
    add_meta_box(
        'homepage_fields',
        'Homepage Content',
        'waterless_render_homepage_meta_box',
        'page', // Only for Pages
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'waterless_add_homepage_meta_boxes');

// Render the fields
function waterless_render_homepage_meta_box($post)
{
    $fields = [
        // Hero
        'hero_title' => 'Hero Title',
        'hero_subtitle' => 'Hero Subtitle',
        'hero_cta' => 'Hero CTA Text',
        'hero_image' => 'Hero Image URL',

        // Intro
        'intro_small_text' => 'Intro Small Text',
        'intro_heading' => 'Intro Heading',
        'intro_image' => 'Intro Image URL',

        // Map
        'map_badge' => 'Map Badge Image URL',
        'map_heading1' => 'Map Heading 1',
        'map_heading2' => 'Map Heading 2',
        'map_text' => 'Map Text',
        'map_cta' => 'Map CTA Text',

        // Full-width
        'fullwidth_image' => 'Full-width Image URL',
        'fullwidth_small_text' => 'Full-width Small Text',
        'fullwidth_heading' => 'Full-width Heading',
        'fullwidth_text1' => 'Full-width Text 1',
        'fullwidth_text2' => 'Full-width Text 2',

        // Installation
        'installation_heading' => 'Installation Heading',
        'installation_cta_text' => 'Installation CTA Text',
        'installation_cta_link' => 'Installation CTA Link',
        'installation_image' => 'Installation Image URL',

        // Success
        'success_image' => 'Success Section Image URL',
        'success_heading' => 'Success Section Heading'
    ];

    foreach ($fields as $key => $label) {
        $value = get_post_meta($post->ID, $key, true);
        $type = strpos($key, 'image') !== false || strpos($key, 'link') !== false ? 'url' : 'text';
        echo "<p><label>{$label}: <input type='{$type}' name='{$key}' value='" . esc_attr($value) . "' style='width:100%;'></label></p>";
    }
}

function waterless_save_homepage_meta($post_id)
{
    // Prevent autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [
        'hero_title',
        'hero_subtitle',
        'hero_cta',
        'hero_image',
        'intro_small_text',
        'intro_heading',
        'intro_image',
        'map_badge',
        'map_heading1',
        'map_heading2',
        'map_text',
        'map_cta',
        'fullwidth_image',
        'fullwidth_small_text',
        'fullwidth_heading',
        'fullwidth_text1',
        'fullwidth_text2',
        'installation_heading',
        'installation_cta_text',
        'installation_cta_link',
        'installation_image',
        'success_image',
        'success_heading'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = (strpos($field, 'image') !== false || strpos($field, 'link') !== false)
                ? esc_url_raw($_POST[$field])
                : sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $field, $value);
        }
    }
}
add_action('save_post_page', 'waterless_save_homepage_meta');

// Register Vimeo URL meta field
// Register native meta field to store multiple instruction videos
function waterless_register_instruction_videos_meta()
{
    register_post_meta('page', 'instruction_videos', [
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string', // Will store JSON array
        'auth_callback' => function () {
            return current_user_can('edit_posts');
        }
    ]);
}
add_action('init', 'waterless_register_instruction_videos_meta');

// Add meta box for multiple instruction videos
function waterless_add_instruction_videos_meta_box()
{
    add_meta_box(
        'instruction_videos_box',
        'Instruction Videos',
        'waterless_render_instruction_videos_meta_box',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'waterless_add_instruction_videos_meta_box');

function waterless_render_instruction_videos_meta_box($post)
{
    $videos_json = get_post_meta($post->ID, 'instruction_videos', true);
    $videos = $videos_json ? json_decode($videos_json, true) : [];

    echo '<div id="instruction-videos-container">';

    if ($videos) {
        foreach ($videos as $index => $video) {
            echo '<div class="instruction-video-item" style="margin-bottom:10px;">';
            echo '<label>Video Title: <input type="text" name="instruction_videos[' . $index . '][title]" value="' . esc_attr($video['title']) . '" style="width:40%;"></label> ';
            echo '<label>Vimeo URL: <input type="url" name="instruction_videos[' . $index . '][url]" value="' . esc_attr($video['url']) . '" style="width:45%;"></label> ';
            echo '<button type="button" class="remove-video button">Remove</button>';
            echo '</div>';
        }
    }

    echo '</div>';
    echo '<p><button type="button" id="add-instruction-video" class="button">Add Video</button></p>';

    // Inline JS for adding/removing video fields
?>
    <script>
        (function($) {
            let container = $('#instruction-videos-container');
            let index = container.children().length;

            $('#add-instruction-video').on('click', function(e) {
                e.preventDefault();
                let html = `
                    <div class="instruction-video-item" style="margin-bottom:10px;">
                        <label>Video Title: <input type="text" name="instruction_videos[` + index + `][title]" style="width:40%;"></label>
                        <label>Vimeo URL: <input type="url" name="instruction_videos[` + index + `][url]" style="width:45%;"></label>
                        <button type="button" class="remove-video button">Remove</button>
                    </div>
                `;
                container.append(html);
                index++;
            });

            // Remove a video field
            $(document).on('click', '.remove-video', function(e) {
                e.preventDefault();
                $(this).parent('.instruction-video-item').remove();
            });
        })(jQuery);
    </script>
<?php
}



function waterless_save_instruction_videos_meta($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['instruction_videos'])) {
        $videos = array_map(function ($video) {
            return [
                'title' => sanitize_text_field($video['title']),
                'url' => esc_url_raw($video['url'])
            ];
        }, $_POST['instruction_videos']);

        update_post_meta($post_id, 'instruction_videos', wp_json_encode($videos));
    }
}
add_action('save_post_page', 'waterless_save_instruction_videos_meta');
