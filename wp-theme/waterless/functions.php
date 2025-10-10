<?php
// Load custom nav walker
require_once get_template_directory() . '/inc/class-waterless-walker.php';
// Load helper functions
require_once get_template_directory() . '/inc/helpers.php';


function waterless_enqueue_scripts()
{
    // Styles
    wp_enqueue_style(
        'waterless-reset',
        get_template_directory_uri() . '/css/reset.css',
        [],
        filemtime(get_template_directory() . '/css/reset.css')
    );

    wp_enqueue_style(
        'waterless-main',
        get_template_directory_uri() . '/css/style.css',
        ['waterless-reset'],
        filemtime(get_template_directory() . '/css/style.css')
    );

    wp_enqueue_style(
        'waterless-responsive',
        get_template_directory_uri() . '/css/responsive.css',
        ['waterless-main'],
        filemtime(get_template_directory() . '/css/responsive.css')
    );

    // Scripts
    wp_enqueue_script(
        'waterless-map',
        get_template_directory_uri() . '/js/map.js',
        [],
        filemtime(get_template_directory() . '/js/map.js'),
        true
    );

    wp_enqueue_script(
        'waterless-nav',
        get_template_directory_uri() . '/js/nav.js',
        [],
        filemtime(get_template_directory() . '/js/nav.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'waterless_enqueue_scripts');


function waterless_register_products()
{
    $labels = [
        'name'                  => _x('Products', 'Post type general name', 'waterless'),
        'singular_name'         => _x('Product', 'Post type singular name', 'waterless'),
        'menu_name'             => _x('Products', 'Admin Menu text', 'waterless'),
        'name_admin_bar'        => _x('Product', 'Add New on Toolbar', 'waterless'),
        'add_new'               => __('Add Product', 'waterless'),
        'add_new_item'          => __('Add New Product', 'waterless'),
        'new_item'              => __('New Product', 'waterless'),
        'edit_item'             => __('Edit Product', 'waterless'),
        'view_item'             => __('View Product', 'waterless'),
        'all_items'             => __('All Products', 'waterless'),
        'search_items'          => __('Search Products', 'waterless'),
        'parent_item_colon'     => __('Parent Products:', 'waterless'),
        'not_found'             => __('No products found.', 'waterless'),
        'not_found_in_trash'    => __('No products found in Trash.', 'waterless'),
        'featured_image'        => _x('Product Image', 'Overrides the “Featured Image” phrase', 'waterless'),
        'set_featured_image'    => _x('Set product image', 'waterless'),
        'remove_featured_image' => _x('Remove product image', 'waterless'),
        'use_featured_image'    => _x('Use as product image', 'waterless'),
        'archives'              => _x('Product archives', 'waterless'),
        'insert_into_item'      => _x('Insert into product', 'waterless'),
        'uploaded_to_this_item' => _x('Uploaded to this product', 'waterless'),
        'filter_items_list'     => _x('Filter products list', 'waterless'),
        'items_list_navigation' => _x('Products list navigation', 'waterless'),
        'items_list'            => _x('Products list', 'waterless'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'product'],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-cart',
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'       => true,
    ];

    register_post_type('product', $args);

    // ✅ Product Categories (hierarchical, like blog categories)
    $taxonomy_labels = [
        'name'              => _x('Product Categories', 'taxonomy general name', 'waterless'),
        'singular_name'     => _x('Product Category', 'taxonomy singular name', 'waterless'),
        'search_items'      => __('Search Product Categories', 'waterless'),
        'all_items'         => __('All Product Categories', 'waterless'),
        'parent_item'       => __('Parent Category', 'waterless'),
        'parent_item_colon' => __('Parent Category:', 'waterless'),
        'edit_item'         => __('Edit Category', 'waterless'),
        'update_item'       => __('Update Category', 'waterless'),
        'add_new_item'      => __('Add New Category', 'waterless'),
        'new_item_name'     => __('New Category Name', 'waterless'),
        'menu_name'         => __('Categories', 'waterless'),
    ];

    $taxonomy_args = [
        'hierarchical'      => true,
        'labels'            => $taxonomy_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'product-category'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('product_category', ['product'], $taxonomy_args);

    // ✅ Product Tags (non-hierarchical, like blog tags)
    $tag_labels = [
        'name'                       => _x('Product Tags', 'taxonomy general name', 'waterless'),
        'singular_name'              => _x('Product Tag', 'taxonomy singular name', 'waterless'),
        'search_items'               => __('Search Product Tags', 'waterless'),
        'popular_items'              => __('Popular Tags', 'waterless'),
        'all_items'                  => __('All Product Tags', 'waterless'),
        'edit_item'                  => __('Edit Tag', 'waterless'),
        'update_item'                => __('Update Tag', 'waterless'),
        'add_new_item'               => __('Add New Tag', 'waterless'),
        'new_item_name'              => __('New Tag Name', 'waterless'),
        'separate_items_with_commas' => __('Separate tags with commas', 'waterless'),
        'add_or_remove_items'        => __('Add or remove tags', 'waterless'),
        'choose_from_most_used'      => __('Choose from the most used tags', 'waterless'),
        'not_found'                  => __('No tags found.', 'waterless'),
        'menu_name'                  => __('Tags', 'waterless'),
    ];

    $tag_args = [
        'hierarchical'          => false,
        'labels'                => $tag_labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => ['slug' => 'product-tag'],
        'show_in_rest'          => true,
    ];

    register_taxonomy('product_tag', ['product'], $tag_args);
}
add_action('init', 'waterless_register_products');


// Register waterless product meta fields
function waterless_register_product_meta()
{
    $fields = [
        'dimension_height' => 'number',
        'dimension_width'  => 'number',
        'dimension_depth'  => 'number',
        'product_color'    => 'string',
        'material'         => 'string',
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
        'color'            => 'Color',
        'material'         => 'Material',
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
        'color',
        'material',
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
// Add meta box
function waterless_add_instruction_meta_box()
{
    add_meta_box(
        'instruction_videos_box',
        __('Instruction Videos', 'waterless'),
        'waterless_render_instruction_meta_box',
        'page',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'waterless_add_instruction_meta_box');

// Render fields
function waterless_render_instruction_meta_box($post)
{
    $instructions_json = get_post_meta($post->ID, 'instruction_videos', true);
    $instructions = $instructions_json ? json_decode($instructions_json, true) : [];
?>
    <div id="instruction-videos-wrapper">
        <?php foreach ($instructions as $index => $instruction) : ?>
            <div class="instruction-item" style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                <p><label>Title:<br>
                        <input type="text" name="instructions[<?php echo $index; ?>][title]" value="<?php echo esc_attr($instruction['title']); ?>" style="width:100%;">
                    </label></p>
                <p><label>Video URL:<br>
                        <input type="text" name="instructions[<?php echo $index; ?>][url]" value="<?php echo esc_url($instruction['url']); ?>" style="width:100%;">
                    </label></p>
                <p><label>Image URL:<br>
                        <input type="text" name="instructions[<?php echo $index; ?>][image]" value="<?php echo esc_url($instruction['image']); ?>" style="width:100%;">
                    </label></p>
                <p><label>Description:<br>
                        <textarea name="instructions[<?php echo $index; ?>][description]" style="width:100%; height:80px;"><?php echo esc_textarea($instruction['description'] ?? ''); ?></textarea>
                    </label></p>
            </div>
        <?php endforeach; ?>
    </div>

    <p><button type="button" class="button" id="add-instruction">Add Instruction</button></p>

    <script>
        document.getElementById('add-instruction').addEventListener('click', function() {
            const wrapper = document.getElementById('instruction-videos-wrapper');
            const index = wrapper.children.length;
            const html = `
            <div class="instruction-item" style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                <p><label>Title:<br>
                    <input type="text" name="instructions[${index}][title]" style="width:100%;">
                </label></p>
                <p><label>Video URL:<br>
                    <input type="text" name="instructions[${index}][url]" style="width:100%;">
                </label></p>
                <p><label>Image URL:<br>
                    <input type="text" name="instructions[${index}][image]" style="width:100%;">
                </label></p>
                <p><label>Description:<br>
                    <textarea name="instructions[${index}][description]" style="width:100%; height:80px;"></textarea>
                </label></p>
            </div>`;
            wrapper.insertAdjacentHTML('beforeend', html);
        });
    </script>
<?php
}

// Save meta
function waterless_save_instruction_meta($post_id)
{
    if (isset($_POST['instructions'])) {
        $instructions = array_map(function ($item) {
            return [
                'title' => sanitize_text_field($item['title']),
                'url' => esc_url_raw($item['url']),
                'image' => esc_url_raw($item['image']),
                'description' => sanitize_textarea_field($item['description']),
            ];
        }, $_POST['instructions']);
        update_post_meta($post_id, 'instruction_videos', wp_json_encode($instructions));
    }
}
add_action('save_post', 'waterless_save_instruction_meta');


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

function waterless_theme_setup()
{
    // Enable support for Custom Logo
    add_theme_support('custom-logo', [
        'height'      => 80,   // Recommended logo height
        'width'       => 200,  // Recommended logo width
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => ['site-title', 'site-description'], // Optional
    ]);

    // Add support for wide/full width blocks
    add_theme_support('align-wide');

    // Add support for featured images, title tags, etc.
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'waterless_theme_setup');

function waterless_editor_styles()
{
    add_theme_support('editor-styles');
    add_editor_style('css/editor.css');
}
add_action('after_setup_theme', 'waterless_editor_styles');


// Register footer menu
function waterless_register_menus()
{
    register_nav_menus([
        'main_menu' => __('Main Menu', 'waterless'),
        'footer_menu' => __('Footer Menu', 'waterless'),
    ]);
}
add_action('after_setup_theme', 'waterless_register_menus');


function waterless_customize_register($wp_customize)
{
    // Panel for Language Selector
    $wp_customize->add_section('waterless_language_selector', [
        'title'    => __('Language Selector', 'waterless'),
        'priority' => 40,
    ]);

    // Danish (default)
    $wp_customize->add_setting('waterless_lang_da_link', ['default' => '/']);
    $wp_customize->add_control('waterless_lang_da_link', [
        'label'   => __('Danish Link', 'waterless'),
        'section' => 'waterless_language_selector',
        'type'    => 'url',
    ]);
    $wp_customize->add_setting('waterless_lang_da_flag', ['default' => get_template_directory_uri() . '/assets/icons/flag-denmark.jpg']);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'waterless_lang_da_flag', [
        'label'   => __('Danish Flag', 'waterless'),
        'section' => 'waterless_language_selector',
    ]));

    // English
    $wp_customize->add_setting('waterless_lang_en_link', ['default' => '/en']);
    $wp_customize->add_control('waterless_lang_en_link', [
        'label'   => __('English Link', 'waterless'),
        'section' => 'waterless_language_selector',
        'type'    => 'url',
    ]);
    $wp_customize->add_setting('waterless_lang_en_flag', ['default' => get_template_directory_uri() . '/assets/icons/uk.webp']);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'waterless_lang_en_flag', [
        'label'   => __('English Flag', 'waterless'),
        'section' => 'waterless_language_selector',
    ]));

    // German
    $wp_customize->add_setting('waterless_lang_de_link', ['default' => '/de']);
    $wp_customize->add_control('waterless_lang_de_link', [
        'label'   => __('German Link', 'waterless'),
        'section' => 'waterless_language_selector',
        'type'    => 'url',
    ]);
    $wp_customize->add_setting('waterless_lang_de_flag', ['default' => get_template_directory_uri() . '/assets/icons/flag-germany.jpg']);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'waterless_lang_de_flag', [
        'label'   => __('German Flag', 'waterless'),
        'section' => 'waterless_language_selector',
    ]));

    // Footer tagline
    $wp_customize->add_section('footer_section', [
        'title'    => __('Footer Settings', 'waterless'),
        'priority' => 120,
    ]);

    $wp_customize->add_setting('footer_tagline', [
        'default'           => 'Upgrade til bæredygtighed — spar vand, skær omkostningerne, og vær på forkant!',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('footer_tagline_control', [
        'label'    => __('Footer Tagline', 'waterless'),
        'section'  => 'footer_section',
        'settings' => 'footer_tagline',
        'type'     => 'textarea',
    ]);

    // Footer contact info
    $wp_customize->add_setting('footer_contact', [
        'default'           => "Waterless Scandinavia ApS\nMøllegade 23\n6310 Broager\nDenmark\nTel: +45 74 44 11 81\nEmail: info@waterless.dk",
        'sanitize_callback' => 'wp_kses_post',
    ]);

    $wp_customize->add_control('footer_contact_control', [
        'label'    => __('Footer Contact Info', 'waterless'),
        'section'  => 'footer_section',
        'settings' => 'footer_contact',
        'type'     => 'textarea',
    ]);

    // Front Page - Hero section controls
    $wp_customize->add_section('waterless_frontpage', [
        'title'    => __('Front Page - Hero', 'waterless'),
        'priority' => 30,
    ]);

    $wp_customize->add_setting('waterless_hero_title', [
        'default'           => 'Ændring af vandforbrugsindustrien',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('waterless_hero_title_control', [
        'label'    => __('Hero Title', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_hero_title',
        'type'     => 'text',
    ]);

    $wp_customize->add_setting('waterless_hero_sub', [
        'default'           => 'Bæredygtige urinal-løsninger til dine bygninger og faciliteter.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('waterless_hero_sub_control', [
        'label'    => __('Hero Subtitle', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_hero_sub',
        'type'     => 'textarea',
    ]);

    $wp_customize->add_setting('waterless_hero_cta_text', [
        'default'           => 'Udforsk produkter',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('waterless_hero_cta_text_control', [
        'label'    => __('Hero CTA Text', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_hero_cta_text',
        'type'     => 'text',
    ]);

    $wp_customize->add_setting('waterless_hero_cta_link', [
        'default'           => home_url('/products'),
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('waterless_hero_cta_link_control', [
        'label'    => __('Hero CTA Link', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_hero_cta_link',
        'type'     => 'url',
    ]);

    $wp_customize->add_setting('waterless_hero_image', [
        'default'           => get_template_directory_uri() . '/assets/hero/Urinals1.png',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'waterless_hero_image_control', [
        'label'    => __('Hero Image', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_hero_image',
    ]));

    // Additional front page sections: Section 1 (intro block)
    $wp_customize->add_setting('waterless_sec1_pre', [
        'default'           => 'Hvad vi laver',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('waterless_sec1_pre_control', [
        'label'    => __('Section 1 - Pre heading', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_sec1_pre',
        'type'     => 'text',
    ]);

    $wp_customize->add_setting('waterless_sec1_heading', [
        'default'           => 'Vandfri urinaler og bæredygtige løsninger',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('waterless_sec1_heading_control', [
        'label'    => __('Section 1 - Heading', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_sec1_heading',
        'type'     => 'text',
    ]);

    $wp_customize->add_setting('waterless_sec1_image', [
        'default'           => get_template_directory_uri() . '/assets/products/urinal-eco-12.png',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'waterless_sec1_image_control', [
        'label'    => __('Section 1 Image', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_sec1_image',
    ]));

    // Map section
    $wp_customize->add_setting('waterless_map_heading', [
        'default'           => 'Verden rundt siden 1997 - 72 lande og tæller',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('waterless_map_heading_control', [
        'label'    => __('Map - Heading', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_map_heading',
        'type'     => 'text',
    ]);

    $wp_customize->add_setting('waterless_map_sub', [
        'default'           => 'Virksomhed med stabil position på markedet',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('waterless_map_sub_control', [
        'label'    => __('Map - Subheading', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_map_sub',
        'type'     => 'text',
    ]);

    $wp_customize->add_setting('waterless_map_text', [
        'default'           => "Vi er til stede på markedet siden 1997, og vi har 80% af det danske marked inden for vandfri urinaler.",
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('waterless_map_text_control', [
        'label'    => __('Map - Text', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_map_text',
        'type'     => 'textarea',
    ]);

    $wp_customize->add_setting('waterless_map_badge', [
        'default'           => get_template_directory_uri() . '/assets/smvgrøn.png',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'waterless_map_badge_control', [
        'label'    => __('Map - Badge Image', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_map_badge',
    ]));

    // Full width section
    $wp_customize->add_setting('waterless_full_pre', [
        'default'           => 'Tilpasset løsning til dig',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('waterless_full_pre_control', [
        'label'    => __('Full - Pre heading', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_full_pre',
        'type'     => 'text',
    ]);

    $wp_customize->add_setting('waterless_full_heading', [
        'default'           => 'Vandbesparelser',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('waterless_full_heading_control', [
        'label'    => __('Full - Heading', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_full_heading',
        'type'     => 'text',
    ]);

    $wp_customize->add_setting('waterless_full_text', [
        'default'           => 'Vil du vide, hvor meget du kan spare?\nIndtast blot dine oplysninger i vores beregner og se besparelserne vokse!',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('waterless_full_text_control', [
        'label'    => __('Full - Text', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_full_text',
        'type'     => 'textarea',
    ]);

    $wp_customize->add_setting('waterless_full_image', [
        'default'           => get_template_directory_uri() . '/assets/hero/63f76bfa9ee8a4f89044ef031c41fa4c50977249.png',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'waterless_full_image_control', [
        'label'    => __('Full - Background Image', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_full_image',
    ]));

    // Install section
    $wp_customize->add_setting('waterless_install_heading', [
        'default'           => 'Installation af vandfri urinaler',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('waterless_install_heading_control', [
        'label'    => __('Install - Heading', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_install_heading',
        'type'     => 'text',
    ]);

    $wp_customize->add_setting('waterless_install_image', [
        'default'           => get_template_directory_uri() . '/assets/Projekt bez nazwy (25) 1.jpg',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'waterless_install_image_control', [
        'label'    => __('Install - Image', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_install_image',
    ]));

    // Testimonial / final block
    $wp_customize->add_setting('waterless_testimonial_heading', [
        'default'           => 'Vi har med succes installeret utallige urinaler på forskellige steder - og leverer en 100% lugtfri oplevelse, garanteret!',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('waterless_testimonial_heading_control', [
        'label'    => __('Testimonial - Heading', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_testimonial_heading',
        'type'     => 'text',
    ]);

    $wp_customize->add_setting('waterless_testimonial_image', [
        'default'           => 'https://waterless.dk/userfiles/image/Nytlayout/Outside_urinal.png',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'waterless_testimonial_image_control', [
        'label'    => __('Testimonial - Image', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_testimonial_image',
    ]));

    // Register selective refresh partials where available to enable live-preview without full refresh
    if ( isset( $wp_customize->selective_refresh ) ) {
        $sr = $wp_customize->selective_refresh;
        $sr->add_partial( 'waterless_hero_title', [
            'selector' => '.hero-title',
            'settings' => ['waterless_hero_title'],
            'render_callback' => function() { echo esc_html( get_theme_mod('waterless_hero_title') ); }
        ]);
        $sr->add_partial( 'waterless_hero_sub', [
            'selector' => '.hero-text p',
            'settings' => ['waterless_hero_sub'],
            'render_callback' => function() { echo esc_html( get_theme_mod('waterless_hero_sub') ); }
        ]);
        // Image partial for hero
        $sr->add_partial( 'waterless_hero_image', [
            'selector' => '.hero-image',
            'settings' => ['waterless_hero_image'],
            'render_callback' => function() { echo '<img class="hero-image" src="' . esc_url( get_theme_mod('waterless_hero_image') ) . '" alt="">'; }
        ]);

        $sr->add_partial( 'waterless_sec1_heading', [
            'selector' => '.ppad.content.grid.cols-2.content-center section h2',
            'settings' => ['waterless_sec1_heading'],
            'render_callback' => function() { echo esc_html( get_theme_mod('waterless_sec1_heading') ); }
        ]);
        $sr->add_partial( 'waterless_sec1_image', [
            'selector' => '.ppad .product-image',
            'settings' => ['waterless_sec1_image'],
            'render_callback' => function() { echo '<img class="product-image" src="' . esc_url( get_theme_mod('waterless_sec1_image') ) . '" alt="">'; }
        ]);

        $sr->add_partial( 'waterless_map_text', [
            'selector' => '.map-container section p',
            'settings' => ['waterless_map_text'],
            'render_callback' => function() { echo nl2br( esc_html( get_theme_mod('waterless_map_text') ) ); }
        ]);
        $sr->add_partial( 'waterless_map_badge', [
            'selector' => '.map-container .sigal',
            'settings' => ['waterless_map_badge'],
            'render_callback' => function() { echo '<img class="sigal" src="' . esc_url( get_theme_mod('waterless_map_badge') ) . '" alt="">'; }
        ]);

        $sr->add_partial( 'waterless_full_text', [
            'selector' => '.full-width-article',
            'settings' => ['waterless_full_text','waterless_full_heading','waterless_full_pre'],
            'render_callback' => function() {
                echo '<p>' . esc_html( get_theme_mod('waterless_full_pre') ) . '</p>';
                echo '<h2>' . esc_html( get_theme_mod('waterless_full_heading') ) . '</h2>';
                echo '<p>' . nl2br( esc_html( get_theme_mod('waterless_full_text') ) ) . '</p>';
            }
        ]);
        $sr->add_partial( 'waterless_full_image', [
            'selector' => '.full-width-image',
            'settings' => ['waterless_full_image'],
            'render_callback' => function() { echo '<img class="full-width-image" src="' . esc_url( get_theme_mod('waterless_full_image') ) . '" alt="">'; }
        ]);

        $sr->add_partial( 'waterless_testimonial_heading', [
            'selector' => '.content.grid.cols-2.ppad.content-center:last-of-type h2',
            'settings' => ['waterless_testimonial_heading'],
            'render_callback' => function() { echo esc_html( get_theme_mod('waterless_testimonial_heading') ); }
        ]);
        $sr->add_partial( 'waterless_testimonial_image', [
            'selector' => '.content.grid.cols-2.ppad.content-center:last-of-type img',
            'settings' => ['waterless_testimonial_image'],
            'render_callback' => function() { echo '<img src="' . esc_url( get_theme_mod('waterless_testimonial_image') ) . '" alt="">'; }
        ]);
    }
}
add_action('customize_register', 'waterless_customize_register');

// Register Instructions Custom Post Type
function waterless_register_instructions_cpt()
{
    $labels = array(
        'name'                  => _x('Instructions', 'Post type general name', 'waterless'),
        'singular_name'         => _x('Instruction', 'Post type singular name', 'waterless'),
        'menu_name'             => _x('Instructions', 'Admin Menu text', 'waterless'),
        'name_admin_bar'        => _x('Instruction', 'Add New on Toolbar', 'waterless'),
        'add_new'               => __('Add New', 'waterless'),
        'add_new_item'          => __('Add New Instruction', 'waterless'),
        'new_item'              => __('New Instruction', 'waterless'),
        'edit_item'             => __('Edit Instruction', 'waterless'),
        'view_item'             => __('View Instruction', 'waterless'),
        'all_items'             => __('All Instructions', 'waterless'),
        'search_items'          => __('Search Instructions', 'waterless'),
        'not_found'             => __('No instructions found.', 'waterless'),
        'not_found_in_trash'    => __('No instructions found in Trash.', 'waterless'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-welcome-learn-more',
        'rewrite'            => array('slug' => 'instructions'),
        'supports'           => array('title', 'editor', 'thumbnail'), // WP editor for description
        'has_archive'        => true,
    );

    register_post_type('instruction', $args);
}
add_action('init', 'waterless_register_instructions_cpt');

// Add video URL meta box
function waterless_add_instruction_video_metabox()
{
    add_meta_box(
        'instruction_video_url',
        __('Instruction Video', 'waterless'),
        'waterless_render_instruction_video_metabox',
        'instruction',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'waterless_add_instruction_video_metabox');

function waterless_render_instruction_video_metabox($post)
{
    $video_url = get_post_meta($post->ID, '_instruction_video_url', true);
?>
    <p><label for="instruction_video_url"><?php _e('Video URL (Vimeo/YouTube)', 'waterless'); ?></label></p>
    <input type="text" id="instruction_video_url" name="instruction_video_url"
        value="<?php echo esc_attr($video_url); ?>" style="width:100%;">
<?php
}

function waterless_save_instruction_video_url($post_id)
{
    if (isset($_POST['instruction_video_url'])) {
        update_post_meta($post_id, '_instruction_video_url', esc_url_raw($_POST['instruction_video_url']));
    }
}
add_action('save_post_instruction', 'waterless_save_instruction_video_url');

/**
 * Extracts a YouTube or Vimeo video ID and returns the correct embed URL
 */
// Register meta box for Instruction video URL
function waterless_add_instruction_metabox() {
    add_meta_box(
        'waterless_instruction_video',
        'Instruction Video',
        'waterless_instruction_video_callback',
        'instruction',   // <- post type
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'waterless_add_instruction_metabox');

// Meta box content
function waterless_instruction_video_callback($post) {
    wp_nonce_field('waterless_save_instruction_video', 'waterless_instruction_video_nonce');

    $value = get_post_meta($post->ID, 'instruction_video_url', true);

    echo '<label for="instruction_video_url">Video URL (YouTube or Vimeo):</label><br>';
    echo '<input type="url" id="instruction_video_url" name="instruction_video_url" ';
    echo 'value="' . esc_attr($value) . '" style="width:100%;max-width:600px;">';
}

function waterless_save_instruction_video($post_id)
{
    // Check nonce
    if (
        !isset($_POST['waterless_instruction_video_nonce']) ||
        !wp_verify_nonce($_POST['waterless_instruction_video_nonce'], 'waterless_save_instruction_video')
    ) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (isset($_POST['post_type']) && $_POST['post_type'] === 'instruction') {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save
    if (isset($_POST['instruction_video_url'])) {
        update_post_meta($post_id, 'instruction_video_url', esc_url_raw($_POST['instruction_video_url']));
    }
}

function waterless_get_video_thumbnail($url)
{
    // YouTube
    if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([^\?&]+)/', $url, $matches)) {
        return 'https://img.youtube.com/vi/' . esc_attr($matches[1]) . '/hqdefault.jpg';
    }

    // Vimeo → requires API call
    if (preg_match('/vimeo\.com\/(?:video\/)?([0-9]+)/', $url, $matches)) {
        $video_id = $matches[1];
        $response = wp_remote_get("https://vimeo.com/api/v2/video/$video_id.json");

        if (is_array($response) && !is_wp_error($response)) {
            $body = json_decode(wp_remote_retrieve_body($response), true);
            if (!empty($body[0]['thumbnail_large'])) {
                return esc_url($body[0]['thumbnail_large']);
            }
        }
    }

    return ''; // fallback empty
}

add_action('save_post', 'waterless_save_instruction_video');

/**
 * Register custom block patterns for Waterless
 */
// Register custom block pattern category for Waterless
add_action( 'init', function() {
    if ( function_exists( 'register_block_pattern_category' ) ) {
        register_block_pattern_category(
            'waterless',
            [ 'label' => __( 'Waterless Patterns', 'waterless' ) ]
        );
    }
});
