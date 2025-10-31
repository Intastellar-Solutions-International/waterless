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
    // Allow an optional third-party map script (configured in Customizer). If present, register and enqueue it
    $thirdparty_map = get_theme_mod('waterless_map_thirdparty_url');
    $map_deps = [];
    if (! empty($thirdparty_map)) {
        // Register third-party script from external URL. Do not version it (null) so caching is controlled externally.
        wp_register_script('waterless-map-thirdparty', esc_url_raw($thirdparty_map), [], null, true);
        wp_enqueue_script('waterless-map-thirdparty');
        $map_deps[] = 'waterless-map-thirdparty';
    }

    wp_enqueue_script(
        'waterless-contact',
        get_template_directory_uri() . '/js/contact.js',
        [],
        filemtime(get_template_directory() . '/js/contact.js'),
        true
    );

    // Lenis (smooth scrolling) and Leaflet (maps) via CDN
    // Load these only on pages where the front-page or map is present to avoid loading site-wide.
    $should_load_map_assets = is_front_page() || is_page('about') || is_page('why-sustainable');
    if ($should_load_map_assets) {
        // Lenis
        wp_register_script('lenis-cdn', 'https://cdn.jsdelivr.net/npm/lenis@1.3.1/dist/lenis.min.js', [], '1.3.1', true);
        wp_enqueue_script('lenis-cdn');

        // Leaflet CSS (CDN)
        wp_enqueue_style('leaflet-cdn-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4');

        // Leaflet JS (CDN)
        wp_register_script('leaflet-cdn', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], '1.9.4', true);
        wp_enqueue_script('leaflet-cdn');
        // Add crossorigin attribute (empty string as in upstream examples)
        if (function_exists('wp_script_add_data')) {
            wp_script_add_data('leaflet-cdn', 'crossorigin', '');
        }
    }

    wp_enqueue_script(
        'waterless-map',
        get_template_directory_uri() . '/js/map.js',
        $map_deps,
        filemtime(get_template_directory() . '/js/map.js'),
        true
    );

    // Localize map data (marker icon URL and any other map config)
    $map_icon = get_theme_mod('waterless_map_icon', get_template_directory_uri() . '/assets/map/icon.png');
    wp_localize_script('waterless-map', 'waterlessMap', [
        'markerIcon' => esc_url($map_icon),
    ]);

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

function waterless_customize_register_products_page($wp_customize)
{
    $wp_customize->add_section('products_page_section', array(
        'title'       => __('Products Page', 'waterless'),
        'priority'    => 30,
        'description' => __('Customize the Products Page content and layout.', 'waterless'),
    ));

    // Headline field
    $wp_customize->add_setting('products_page_headline', array(
        'default'           => __('Vores Produkter', 'waterless'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('products_page_headline', array(
        'label'       => __('Custom Headline', 'waterless'),
        'section'     => 'products_page_section',
        'type'        => 'text',
        'description' => __('Override the default page title.', 'waterless'),
    ));

    // CTA Button Text
    $wp_customize->add_setting('products_page_cta_text', array(
        'default'           => __('Læs mere', 'waterless'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('products_page_cta_text', array(
        'label'       => __('CTA Button Text', 'waterless'),
        'section'     => 'products_page_section',
        'type'        => 'text',
    ));

    // Grid Columns
    $wp_customize->add_setting('products_page_grid_columns', array(
        'default'           => 5,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('products_page_grid_columns', array(
        'label'       => __('Number of Columns', 'waterless'),
        'section'     => 'products_page_section',
        'type'        => 'number',
        'input_attrs' => array('min' => 1, 'max' => 6),
    ));

    // Default Image Upload
    $wp_customize->add_setting('products_page_default_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'products_page_default_image',
        array(
            'label'       => __('Default Product Image', 'waterless'),
            'section'     => 'products_page_section',
            'settings'    => 'products_page_default_image',
            'description' => __('Used when a product has no featured image.', 'waterless'),
        )
    ));
}
add_action('customize_register', 'waterless_customize_register_products_page');


// ============================
// Helper Function for Defaults
// ============================

function waterless_get_default_product_image()
{
    $default_image = get_theme_mod('products_page_default_image');
    if ($default_image) {
        return esc_url($default_image);
    }
    return get_template_directory_uri() . '/assets/products/default-product.png';
}


// ============================
// Product Compatibility Fields
// ============================

function waterless_add_product_compatibility_meta_boxes()
{
    add_meta_box(
        'product_compatibility_meta',
        __('Product Compatibility', 'waterless'),
        'waterless_render_product_compatibility_meta_box',
        'product',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'waterless_add_product_compatibility_meta_boxes');

function waterless_render_product_compatibility_meta_box($post)
{
    wp_nonce_field('save_product_compatibility_meta', 'product_compatibility_nonce');

    $selected_housings = get_post_meta($post->ID, '_compatible_housings', true);
    $selected_urinals  = get_post_meta($post->ID, '_compatible_urinals', true);

    $products = get_posts(array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'exclude'        => array($post->ID),
        'orderby'        => 'title',
        'order'          => 'ASC',
    ));

    echo '<p><strong>' . __('Compatible Housings', 'waterless') . '</strong></p>';
    echo '<select name="compatible_housings[]" multiple style="width:100%;height:100px;">';
    foreach ($products as $product) {
        $selected = (!empty($selected_housings) && in_array($product->ID, $selected_housings)) ? 'selected' : '';
        echo '<option value="' . esc_attr($product->ID) . '" ' . $selected . '>' . esc_html($product->post_title) . '</option>';
    }
    echo '</select>';

    echo '<p><strong>' . __('Compatible Urinals', 'waterless') . '</strong></p>';
    echo '<select name="compatible_urinals[]" multiple style="width:100%;height:100px;">';
    foreach ($products as $product) {
        $selected = (!empty($selected_urinals) && in_array($product->ID, $selected_urinals)) ? 'selected' : '';
        echo '<option value="' . esc_attr($product->ID) . '" ' . $selected . '>' . esc_html($product->post_title) . '</option>';
    }
    echo '</select>';
}

function waterless_save_product_compatibility_meta($post_id)
{
    if (!isset($_POST['product_compatibility_nonce']) || !wp_verify_nonce($_POST['product_compatibility_nonce'], 'save_product_compatibility_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $housings = isset($_POST['compatible_housings']) ? array_map('intval', $_POST['compatible_housings']) : array();
    $urinals  = isset($_POST['compatible_urinals']) ? array_map('intval', $_POST['compatible_urinals']) : array();

    update_post_meta($post_id, '_compatible_housings', $housings);
    update_post_meta($post_id, '_compatible_urinals', $urinals);
}
add_action('save_post_product', 'waterless_save_product_compatibility_meta');


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

// Add custom image field to menu items
add_filter('wp_setup_nav_menu_item', function ($menu_item) {
    $menu_item->thumbnail_id = get_post_meta($menu_item->ID, '_menu_item_thumbnail_id', true);
    return $menu_item;
});

add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_db_id) {
    if (isset($_POST['menu-item-thumbnail-id'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_menu_item_thumbnail_id', sanitize_text_field($_POST['menu-item-thumbnail-id'][$menu_item_db_id]));
    }
}, 10, 2);

add_action('wp_nav_menu_item_custom_fields', function ($item_id, $item) {
    $thumbnail_id = get_post_meta($item_id, '_menu_item_thumbnail_id', true);
    $image_url = $thumbnail_id ? wp_get_attachment_thumb_url($thumbnail_id) : '';
?>
    <p class="field-thumbnail description description-wide">
        <label for="edit-menu-item-thumbnail-<?php echo esc_attr($item_id); ?>">
            <?php _e('Thumbnail Image'); ?><br>
            <input type="hidden" class="menu-item-thumbnail-id" name="menu-item-thumbnail-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($thumbnail_id); ?>">
            <img class="menu-item-thumbnail-preview" src="<?php echo esc_url($image_url); ?>" style="max-width:60px;<?php echo empty($image_url) ? 'display:none;' : ''; ?>">
            <button class="button upload-menu-thumbnail"><?php _e('Select image'); ?></button>
            <button class="button remove-menu-thumbnail" style="<?php echo empty($image_url) ? 'display:none;' : ''; ?>"><?php _e('Remove'); ?></button>
        </label>
    </p>
<?php
}, 10, 2);

// Enqueue admin script for image upload
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'nav-menus.php') {
        wp_enqueue_media();
        wp_add_inline_script('jquery-core', "
            jQuery(document).ready(function($){
                $('.upload-menu-thumbnail').on('click', function(e){
                    e.preventDefault();
                    var button = $(this);
                    var input = button.closest('p').find('.menu-item-thumbnail-id');
                    var preview = button.closest('p').find('.menu-item-thumbnail-preview');
                    var removeBtn = button.closest('p').find('.remove-menu-thumbnail');
                    var frame = wp.media({title:'Select or Upload Image', button:{text:'Use this image'}, multiple:false});
                    frame.on('select', function(){
                        var attachment = frame.state().get('selection').first().toJSON();
                        input.val(attachment.id);
                        preview.attr('src', attachment.url).show();
                        removeBtn.show();
                    });
                    frame.open();
                });
                $('.remove-menu-thumbnail').on('click', function(e){
                    e.preventDefault();
                    var container = $(this).closest('p');
                    container.find('.menu-item-thumbnail-id').val('');
                    container.find('.menu-item-thumbnail-preview').hide();
                    $(this).hide();
                });
            });
        ");
    }
});

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

    $wp_customize->add_setting('waterless_sec1_description', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('waterless_sec1_description', [
        'label'    => __('Section 1 - Description', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_sec1_description',
        'type'     => 'textarea',
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

    // Map marker icon (uploadable via Customizer)
    $wp_customize->add_setting('waterless_map_icon', [
        'default'           => get_template_directory_uri() . '/assets/map/icon.png',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'waterless_map_icon_control', [
        'label'    => __('Map - Marker Icon', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_map_icon',
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

    $wp_customize->add_setting('waterless_install_heading_description', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ]);

    $wp_customize->add_control('waterless_install_heading_description_control', [
        'label'    => __('Install - Description', 'waterless'),
        'section'  => 'waterless_frontpage',
        'settings' => 'waterless_install_heading_description',
        'type'     => 'textarea',
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
    if (isset($wp_customize->selective_refresh)) {
        $sr = $wp_customize->selective_refresh;
        $sr->add_partial('waterless_hero_title', [
            'selector' => '.hero-title',
            'settings' => ['waterless_hero_title'],
            'render_callback' => function () {
                echo esc_html(get_theme_mod('waterless_hero_title'));
            }
        ]);
        $sr->add_partial('waterless_hero_sub', [
            'selector' => '.hero-text p',
            'settings' => ['waterless_hero_sub'],
            'render_callback' => function () {
                echo esc_html(get_theme_mod('waterless_hero_sub'));
            }
        ]);
        // Image partial for hero
        $sr->add_partial('waterless_hero_image', [
            'selector' => '.hero-image',
            'settings' => ['waterless_hero_image'],
            'render_callback' => function () {
                echo '<img class="hero-image" src="' . esc_url(get_theme_mod('waterless_hero_image')) . '" alt="">';
            }
        ]);

        $sr->add_partial('waterless_sec1_heading', [
            'selector' => '.ppad.content.grid.cols-2.content-center section h2',
            'settings' => ['waterless_sec1_heading'],
            'render_callback' => function () {
                echo esc_html(get_theme_mod('waterless_sec1_heading'));
            }
        ]);
        $sr->add_partial('waterless_sec1_image', [
            'selector' => '.ppad .product-image',
            'settings' => ['waterless_sec1_image'],
            'render_callback' => function () {
                echo '<img class="product-image" src="' . esc_url(get_theme_mod('waterless_sec1_image')) . '" alt="">';
            }
        ]);

        $sr->add_partial('waterless_map_text', [
            'selector' => '.map-container section p',
            'settings' => ['waterless_map_text'],
            'render_callback' => function () {
                echo nl2br(esc_html(get_theme_mod('waterless_map_text')));
            }
        ]);
        $sr->add_partial('waterless_map_badge', [
            'selector' => '.map-container .sigal',
            'settings' => ['waterless_map_badge'],
            'render_callback' => function () {
                echo '<img class="sigal" src="' . esc_url(get_theme_mod('waterless_map_badge')) . '" alt="">';
            }
        ]);

        $sr->add_partial('waterless_full_text', [
            'selector' => '.full-width-article',
            'settings' => ['waterless_full_text', 'waterless_full_heading', 'waterless_full_pre'],
            'render_callback' => function () {
                echo '<p>' . esc_html(get_theme_mod('waterless_full_pre')) . '</p>';
                echo '<h2>' . esc_html(get_theme_mod('waterless_full_heading')) . '</h2>';
                echo '<p>' . nl2br(esc_html(get_theme_mod('waterless_full_text'))) . '</p>';
            }
        ]);
        $sr->add_partial('waterless_full_image', [
            'selector' => '.full-width-image',
            'settings' => ['waterless_full_image'],
            'render_callback' => function () {
                echo '<img class="full-width-image" src="' . esc_url(get_theme_mod('waterless_full_image')) . '" alt="">';
            }
        ]);

        $sr->add_partial('waterless_testimonial_heading', [
            'selector' => '.content.grid.cols-2.ppad.content-center:last-of-type h2',
            'settings' => ['waterless_testimonial_heading'],
            'render_callback' => function () {
                echo esc_html(get_theme_mod('waterless_testimonial_heading'));
            }
        ]);
        $sr->add_partial('waterless_testimonial_image', [
            'selector' => '.content.grid.cols-2.ppad.content-center:last-of-type img',
            'settings' => ['waterless_testimonial_image'],
            'render_callback' => function () {
                echo '<img src="' . esc_url(get_theme_mod('waterless_testimonial_image')) . '" alt="">';
            }
        ]);
    }

    // === About & Why-Sustainable Customizer controls ===

    $wp_customize->add_section('waterless_sustainable', [
        'title'    => __('Why Sustainable', 'waterless'),
        'priority' => 41,
    ]);

    $wp_customize->add_setting('waterless_sustainable_heading', [
        'default'           => 'Waterless Scandinavia – Leading the Way in Sustainable Restroom Solutions',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('waterless_sustainable_heading_control', [
        'label'    => __('Why - Heading', 'waterless'),
        'section'  => 'waterless_sustainable',
        'settings' => 'waterless_sustainable_heading',
        'type'     => 'text',
    ]);

    $wp_customize->add_setting('waterless_sustainable_text', [
        'default'           => 'At Waterless Scandinavia, we believe that sustainability starts with smarter choices.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('waterless_sustainable_text_control', [
        'label'    => __('Why - Text', 'waterless'),
        'section'  => 'waterless_sustainable',
        'settings' => 'waterless_sustainable_text',
        'type'     => 'textarea',
    ]);

    // Map third-party script URL
    $wp_customize->add_section('waterless_map_scripts', [
        'title'    => __('Map Scripts', 'waterless'),
        'priority' => 42,
    ]);
    $wp_customize->add_setting('waterless_map_thirdparty_url', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('waterless_map_thirdparty_url_control', [
        'label'    => __('Third-party Map Script URL (e.g. CDN)', 'waterless'),
        'section'  => 'waterless_map_scripts',
        'settings' => 'waterless_map_thirdparty_url',
        'type'     => 'url',
    ]);

    // Selective refresh partials for About & Why pages
    if (isset($wp_customize->selective_refresh)) {
        $sr->add_partial('waterless_about_intro', [
            'selector' => '.content article.content section.grid.cols-2 div p',
            'settings' => ['waterless_about_intro'],
            'render_callback' => function () {
                echo nl2br(esc_html(get_theme_mod('waterless_about_intro')));
            }
        ]);

        $sr->add_partial('waterless_about_image', [
            'selector' => 'section.grid.cols-2 img',
            'settings' => ['waterless_about_image'],
            'render_callback' => function () {
                echo '<img src="' . esc_url(get_theme_mod('waterless_about_image')) . '" alt="">';
            }
        ]);

        $sr->add_partial('waterless_sustainable_heading', [
            'selector' => '.paint-bg.info-container h1',
            'settings' => ['waterless_sustainable_heading'],
            'render_callback' => function () {
                echo esc_html(get_theme_mod('waterless_sustainable_heading'));
            }
        ]);
        $sr->add_partial('waterless_sustainable_text', [
            'selector' => '.paint-bg.info-container p',
            'settings' => ['waterless_sustainable_text'],
            'render_callback' => function () {
                echo nl2br(esc_html(get_theme_mod('waterless_sustainable_text')));
            }
        ]);
    }
}
add_action('customize_register', 'waterless_customize_register');

/**
 * When Customizer is saved, persist the front-page theme_mod values into the front page post meta.
 * This keeps the page editable in the Page editor and prevents values from appearing to 'disappear'
 * when other parts of the site read post meta instead of theme_mods.
 */
function waterless_sync_customizer_to_page_meta()
{
    // Only run in admin on customize save
    if (! is_admin()) return;

    // Require capability
    if (! current_user_can('edit_pages')) return;

    $front_id = (int) get_option('page_on_front');
    if ($front_id <= 0) return;

    // Map of theme_mod => post_meta_key
    $map = [
        'waterless_hero_title' => 'hero_title',
        'waterless_hero_sub' => 'hero_subtitle',
        'waterless_hero_cta_text' => 'hero_cta_text',
        'waterless_hero_cta_link' => 'hero_cta_link',
        'waterless_hero_image' => 'hero_image',

        'waterless_sec1_pre' => 'sec1_pre',
        'waterless_sec1_heading' => 'sec1_heading',
        'waterless_sec1_image' => 'sec1_image',

        'waterless_map_heading' => 'map_heading',
        'waterless_map_sub' => 'map_sub',
        'waterless_map_text' => 'map_text',
        'waterless_map_badge' => 'map_badge',

        'waterless_full_pre' => 'full_pre',
        'waterless_full_heading' => 'full_heading',
        'waterless_full_text' => 'full_text',
        'waterless_full_image' => 'full_image',

        'waterless_install_heading' => 'install_heading',
        'waterless_install_image' => 'install_image',

        'waterless_testimonial_heading' => 'testimonial_heading',
        'waterless_testimonial_image' => 'testimonial_image',
    ];

    foreach ($map as $tm => $meta_key) {
        $val = get_theme_mod($tm);
        if ($val !== null) {
            // Save string values as-is; image URLs are URLs
            update_post_meta($front_id, $meta_key, $val);
        }
    }

    // Note: We intentionally do not sync the third-party map script URL into page meta.
}
add_action('customize_save_after', 'waterless_sync_customizer_to_page_meta');

// Register Instructions Custom Post Type
function waterless_register_instructions_cpt()
{
    $labels = array(
        'name'                  => _x('Instruction Video', 'Post type general name', 'waterless'),
        'singular_name'         => _x('Instruction', 'Post type singular name', 'waterless'),
        'menu_name'             => _x('Instruction Video', 'Admin Menu text', 'waterless'),
        'name_admin_bar'        => _x('Instruction', 'Add New on Toolbar', 'waterless'),
        'add_new'               => __('Add New', 'waterless'),
        'add_new_item'          => __('Add New Video', 'waterless'),
        'new_item'              => __('New Instruction Video', 'waterless'),
        'edit_item'             => __('Edit Instruction Video', 'waterless'),
        'view_item'             => __('View Instruction Video', 'waterless'),
        'all_items'             => __('All Instruction Video', 'waterless'),
        'search_items'          => __('Search Instruction Video', 'waterless'),
        'not_found'             => __('No instruction videos found.', 'waterless'),
        'not_found_in_trash'    => __('No instruction videos found in Trash.', 'waterless'),
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

// Meta box content
/* function waterless_instruction_video_callback($post) {
    wp_nonce_field('waterless_save_instruction_video', 'waterless_instruction_video_nonce');

    $value = get_post_meta($post->ID, 'instruction_video_url', true);

    echo '<label for="instruction_video_url">Video URL (YouTube or Vimeo):</label><br>';
    echo '<input type="url" id="instruction_video_url" name="instruction_video_url" ';
    echo 'value="' . esc_attr($value) . '" style="width:100%;max-width:600px;">';
} */

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
add_action('init', function () {
    if (function_exists('register_block_pattern_category')) {
        register_block_pattern_category(
            'waterless',
            ['label' => __('Waterless Patterns', 'waterless')]
        );
    }
});

// ============================
// Products Page Customizer
// ============================

function theme_customize_register_products_page($wp_customize)
{

    // Add a new section for the Products Page
    $wp_customize->add_section('products_page_section', array(
        'title'       => __('Products Page', 'waterless'),
        'priority'    => 30,
        'description' => __('Customize the Products Page content and layout.', 'waterless'),
    ));

    // Headline field
    $wp_customize->add_setting('products_page_headline', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('products_page_headline', array(
        'label'       => __('Custom Headline', 'waterless'),
        'section'     => 'products_page_section',
        'type'        => 'text',
        'description' => __('Override the default page title.', 'waterless'),
    ));

    // CTA Button Text
    $wp_customize->add_setting('products_page_cta_text', array(
        'default'           => __('Læs mere', 'waterless'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('products_page_cta_text', array(
        'label'       => __('CTA Button Text', 'waterless'),
        'section'     => 'products_page_section',
        'type'        => 'text',
    ));

    // Grid Columns
    $wp_customize->add_setting('products_page_grid_columns', array(
        'default'           => 5,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('products_page_grid_columns', array(
        'label'       => __('Number of Columns', 'waterless'),
        'section'     => 'products_page_section',
        'type'        => 'number',
        'input_attrs' => array('min' => 1, 'max' => 6),
    ));

    // Default Image Upload
    $wp_customize->add_setting('products_page_default_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'products_page_default_image',
        array(
            'label'       => __('Default Product Image', 'waterless'),
            'section'     => 'products_page_section',
            'settings'    => 'products_page_default_image',
            'description' => __('Used when a product has no featured image.', 'waterless'),
        )
    ));
}

add_action('customize_register', 'theme_customize_register_products_page');

/**
 * Use the About template for translated About pages.
 */
function waterless_customize_about_page($wp_customize)
{
    // Panel
    $wp_customize->add_section('about_page_section', [
        'title' => __('About Page Content', 'waterless'),
        'priority' => 30,
    ]);

    // Headline + intro
    $wp_customize->add_setting('about_page_title', ['default' => 'Waterless Scandinavia – Pionerer inden for vandfri innovation']);
    $wp_customize->add_control('about_page_title', [
        'label' => __('Main Title', 'waterless'),
        'section' => 'about_page_section',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('about_page_intro');
    $wp_customize->add_control('about_page_intro', [
        'label' => __('Intro Text', 'waterless'),
        'section' => 'about_page_section',
        'type' => 'textarea',
    ]);

    // Images
    $wp_customize->add_setting('about_page_image_1');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'about_page_image_1', [
        'label' => __('First Image', 'waterless'),
        'section' => 'about_page_section',
    ]));

    $wp_customize->add_setting('about_page_image_2');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'about_page_image_2', [
        'label' => __('Second Image', 'waterless'),
        'section' => 'about_page_section',
    ]));

    // Second section
    $wp_customize->add_setting('about_page_second_title');
    $wp_customize->add_control('about_page_second_title', [
        'label' => __('Second Section Title', 'waterless'),
        'section' => 'about_page_section',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('about_page_second_text');
    $wp_customize->add_control('about_page_second_text', [
        'label' => __('Second Section Text', 'waterless'),
        'section' => 'about_page_section',
        'type' => 'textarea',
    ]);

    // --- TEAM SECTION ---
    $wp_customize->add_setting('about_page_team_title', ['default' => 'Meet our team members!']);
    $wp_customize->add_control('about_page_team_title', [
        'label' => __('Team Section Title', 'waterless'),
        'section' => 'about_page_section',
        'type' => 'text',
    ]);

    for ($i = 1; $i <= 2; $i++) {
        $wp_customize->add_setting("about_page_team{$i}_name");
        $wp_customize->add_control("about_page_team{$i}_name", [
            'label' => __("Team Member {$i} Name", 'waterless'),
            'section' => 'about_page_section',
            'type' => 'text',
        ]);

        $wp_customize->add_setting("about_page_team{$i}_region");
        $wp_customize->add_control("about_page_team{$i}_region", [
            'label' => __("Team Member {$i} Region", 'waterless'),
            'section' => 'about_page_section',
            'type' => 'text',
        ]);

        $wp_customize->add_setting("about_page_team{$i}_email");
        $wp_customize->add_control("about_page_team{$i}_email", [
            'label' => __("Team Member {$i} Email", 'waterless'),
            'section' => 'about_page_section',
            'type' => 'email',
        ]);

        $wp_customize->add_setting("about_page_team{$i}_phone");
        $wp_customize->add_control("about_page_team{$i}_phone", [
            'label' => __("Team Member {$i} Phone", 'waterless'),
            'section' => 'about_page_section',
            'type' => 'text',
        ]);

        $wp_customize->add_setting("about_page_team{$i}_link");
        $wp_customize->add_control("about_page_team{$i}_link", [
            'label' => __("Team Member {$i} Meeting Link", 'waterless'),
            'section' => 'about_page_section',
            'type' => 'url',
        ]);

        $wp_customize->add_setting("about_page_team{$i}_image");
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "about_page_team{$i}_image", [
            'label' => __("Team Member {$i} Image", 'waterless'),
            'section' => 'about_page_section',
        ]));
    }
}
add_action('customize_register', 'waterless_customize_about_page');


add_filter('template_include', function ($template) {
    if (is_page()) {
        global $post;
        $slug = get_post_field('post_name', $post);
        $title = strtolower(trim(get_the_title($post)));

        // All localized About slugs/titles you want to match
        $about_variants = ['about', 'om-os', 'om', 'a-propos', 'uber-uns'];

        if (in_array($slug, $about_variants) || in_array($title, $about_variants)) {
            // Check if a custom page-about template exists
            $custom_template = locate_template('templates/page-about.html');
            if ($custom_template) {
                return $custom_template;
            }

            // Fallback to PHP template if not block-based
            $php_template = locate_template('page-about.php');
            if ($php_template) {
                return $php_template;
            }
        }

        // All localized About slugs/titles you want to match
        $about_variants = ['til-din-virksomhed', 'business'];

        if (in_array($slug, $about_variants) || in_array($title, $about_variants)) {
            // Check if a custom page-about template exists
            // Fallback to PHP template if not block-based
            $php_template = locate_template('page-business.php');
            if ($php_template) {
                return $php_template;
            }
        }
    }
    return $template;
});

function waterless_customize_business_page($wp_customize)
{
    // Section
    $wp_customize->add_section('business_page_section', [
        'title' => __('Business Page Content', 'waterless'),
        'priority' => 31,
    ]);

    // Page title
    $wp_customize->add_setting('business_page_title', ['default' => 'Til din virksomhed']);
    $wp_customize->add_control('business_page_title', [
        'label' => __('Page Title', 'waterless'),
        'section' => 'business_page_section',
        'type' => 'text',
    ]);

    // Page intro
    $wp_customize->add_setting('business_page_intro', [
        'default' => 'Her kan du finde informationer om vores produkter og hvordan de kan hjælpe din virksomhed med at spare vand og penge.',
    ]);
    $wp_customize->add_control('business_page_intro', [
        'label' => __('Intro Text', 'waterless'),
        'section' => 'business_page_section',
        'type' => 'textarea',
    ]);

    // Product items (loop)
    for ($i = 1; $i <= 8; $i++) {
        $wp_customize->add_setting("business_page_item{$i}_title", [
            'default' => "Business item {$i}",
            'transport' => 'refresh',
        ]);
        $wp_customize->add_control("business_page_item{$i}_title", [
            'label' => __("Product {$i} Title", 'waterless'),
            'section' => 'business_page_section',
            'type' => 'text',
            'transport' => 'refresh',
        ]);

        $wp_customize->add_setting("business_page_item{$i}_image");
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "business_page_item{$i}_image", [
            'label' => __("Product {$i} Image", 'waterless'),
            'section' => 'business_page_section',
            'transport' => 'refresh',
        ]));

        $wp_customize->add_setting("business_page_item{$i}_link", [
            'default' => '#',
        ]);
        $wp_customize->add_control("business_page_item{$i}_link", [
            'label' => __("Product {$i} Link", 'waterless'),
            'section' => 'business_page_section',
            'type' => 'url',
            'transport' => 'refresh',
        ]);
    }
}
add_action('customize_register', 'waterless_customize_business_page');

function waterless_customize_contact_page($wp_customize)
{
    $wp_customize->add_section('contact_page_section', [
        'title'       => __('Contact Page', 'waterless'),
        'priority'    => 35,
        'description' => __('Edit the content of the Contact page', 'waterless'),
    ]);

    // Title & Intro
    $wp_customize->add_setting('contact_page_title', ['default' => 'Kontakt os']);
    $wp_customize->add_control('contact_page_title', [
        'label' => __('Page Title', 'waterless'),
        'section' => 'contact_page_section',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('contact_page_intro', ['default' => 'Har du spørgsmål eller ønsker du et tilbud? Kontakt os her.']);
    $wp_customize->add_control('contact_page_intro', [
        'label' => __('Intro Text', 'waterless'),
        'section' => 'contact_page_section',
        'type' => 'textarea',
    ]);

    // Contact Info
    $wp_customize->add_setting('contact_page_info_title', ['default' => 'Kontaktinformation']);
    $wp_customize->add_control('contact_page_info_title', [
        'label' => __('Info Section Title', 'waterless'),
        'section' => 'contact_page_section',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('contact_page_address', ['default' => 'Eksempelvej 12, 6400 Sønderborg']);
    $wp_customize->add_control('contact_page_address', [
        'label' => __('Address', 'waterless'),
        'section' => 'contact_page_section',
        'type' => 'textarea',
    ]);

    $wp_customize->add_setting('contact_page_phone', ['default' => '+45 12 34 56 78']);
    $wp_customize->add_control('contact_page_phone', [
        'label' => __('Phone', 'waterless'),
        'section' => 'contact_page_section',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('contact_page_email', ['default' => 'info@waterless.dk']);
    $wp_customize->add_control('contact_page_email', [
        'label' => __('Email', 'waterless'),
        'section' => 'contact_page_section',
        'type' => 'text',
    ]);

    // Contact Form
    $wp_customize->add_setting('contact_page_form_title', ['default' => 'Send os en besked']);
    $wp_customize->add_control('contact_page_form_title', [
        'label' => __('Form Title', 'waterless'),
        'section' => 'contact_page_section',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('contact_page_form_shortcode', ['default' => '[contact-form-7 id="123" title="Contact form"]']);
    $wp_customize->add_control('contact_page_form_shortcode', [
        'label' => __('Form Shortcode', 'waterless'),
        'section' => 'contact_page_section',
        'type' => 'text',
    ]);

    // Map Embed
    $wp_customize->add_setting('contact_page_map_embed', ['default' => '']);
    $wp_customize->add_control('contact_page_map_embed', [
        'label' => __('Google Maps Embed URL', 'waterless'),
        'section' => 'contact_page_section',
        'type' => 'url',
    ]);
}
add_action('customize_register', 'waterless_customize_contact_page');


add_action('wp_ajax_waterless_send_contact_form', 'waterless_send_contact_form');
add_action('wp_ajax_nopriv_waterless_send_contact_form', 'waterless_send_contact_form');

function waterless_send_contact_form()
{
    // Sanitize form fields
    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json(['success' => false, 'message' => 'Udfyld venligst alle felter.']);
    }

    // Compose email
    $to = get_option('admin_email');
    $subject = "Ny kontaktformular fra $name";
    $body = "Navn: $name\nEmail: $email\n\nBesked:\n$message";
    $headers = ["From: $name <$email>", "Reply-To: $email"];

    // Send mail
    $sent = wp_mail($to, $subject, $body, $headers);

    if ($sent) {
        wp_send_json(['success' => true, 'message' => 'Tak for din besked! Vi vender tilbage hurtigst muligt.']);
    } else {
        wp_send_json(['success' => false, 'message' => 'Der opstod en fejl ved afsendelse. Prøv igen senere.']);
    }
}

// ============================
// Helper Function for Defaults
// ============================

function theme_get_default_product_image()
{
    $default_image = get_theme_mod('products_page_default_image');
    if ($default_image) {
        return esc_url($default_image);
    }
    return get_template_directory_uri() . '/assets/products/default-product.png';
}
