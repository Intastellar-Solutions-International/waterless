<?php

/**
 * Front Page Template - dynamic and editable
 * Template Name: Front Page
 */
get_header();

// Determine the page object to pull meta from. Prefer the queried page when possible.
global $post;
if (isset($post) && $post instanceof WP_Post) {
    $page_id = $post->ID;
} else {
    $page_id = get_option('page_on_front');
}

// Helper: get meta or fallback
function wl_get_meta($page_id, $key, $fallback = '')
{
    $val = get_post_meta($page_id, $key, true);
    return $val !== '' && $val !== null ? $val : $fallback;
}

// Helper: resolve image URL from meta (either attachment ID or URL)
function wl_get_image_url($page_id, $key, $fallback)
{
    $val = get_post_meta($page_id, $key, true);
    if (empty($val)) {
        // try featured image
        $thumb = get_post_thumbnail_id($page_id);
        if ($thumb) return wp_get_attachment_image_url($thumb, 'full');
        return $fallback;
    }
    if (is_numeric($val)) {
        return wp_get_attachment_image_url((int)$val, 'full') ?: $fallback;
    }
    return esc_url($val);
}

// Helper: prefer Customizer value (theme_mod) over page meta
function wl_get_setting($page_id, $meta_key, $theme_mod_key, $fallback = '')
{
    $tm = get_theme_mod($theme_mod_key);
    if ($tm !== null && $tm !== '') {
        return $tm;
    }
    return wl_get_meta($page_id, $meta_key, $fallback);
}

// Helper: prefer Customizer image (theme_mod) over page meta
function wl_get_image_setting($page_id, $meta_key, $theme_mod_key, $fallback)
{
    $tm = get_theme_mod($theme_mod_key);
    if (! empty($tm)) {
        return esc_url($tm);
    }
    return wl_get_image_url($page_id, $meta_key, $fallback);
}

$hero_title = get_theme_mod('waterless_hero_title', get_the_title($page_id) ?: 'Ændring af vandforbrugsindustrien');
$hero_sub = get_theme_mod('waterless_hero_sub', 'Bæredygtige urinal-løsninger til dine bygninger og faciliteter.');
$hero_cta_text = get_theme_mod('waterless_hero_cta_text', 'Udforsk produkter');
$hero_cta_link = get_theme_mod('waterless_hero_cta_link', home_url('/products'));
$hero_image = get_theme_mod('waterless_hero_image', get_template_directory_uri() . '/assets/hero/Urinals1.png');

// Section content fields (with sensible defaults)
$sec1_image = get_theme_mod('waterless_sec1_image', get_template_directory_uri() . '/assets/products/urinal-eco-12.png');
$sec1_pre = get_theme_mod('waterless_sec1_pre', 'Hvad vi laver');
$sec1_heading = get_theme_mod('waterless_sec1_heading', 'Vandfri urinaler og bæredygtige løsninger');

$map_badge = get_theme_mod('waterless_map_badge', get_template_directory_uri() . '/assets/smvgrøn.png');
$map_heading = get_theme_mod('waterless_map_heading', 'Verden rundt siden 1997 - 72 lande og tæller');
$map_sub = get_theme_mod('waterless_map_sub', 'Virksomhed med stabil position på markedet');
$map_text = get_theme_mod('waterless_map_text', 'Vi er til stede på markedet siden 1997, og vi har 80% af det danske marked inden for vandfri urinaler.');
$map_link = get_theme_mod('waterless_map_link', home_url('/about.php'));

$full_image = get_theme_mod('waterless_full_image', get_template_directory_uri() . '/assets/hero/63f76bfa9ee8a4f89044ef031c41fa4c50977249.png');
$full_pre = get_theme_mod('waterless_full_pre', 'Tilpasset løsning til dig');
$full_heading = get_theme_mod('waterless_full_heading', 'Vandbesparelser');
$full_text = get_theme_mod('waterless_full_text', "Vil du vide, hvor meget du kan spare?
Indtast blot dine oplysninger i vores beregner og se besparelserne vokse!");

$install_heading = get_theme_mod('waterless_install_heading', 'Installation af vandfri urinaler');
$install_link = get_theme_mod('waterless_install_link', home_url('/'));
$install_image = get_theme_mod('waterless_install_image', get_template_directory_uri() . '/assets/Projekt bez nazwy (25) 1.jpg');

$testimonial_image = get_theme_mod('waterless_testimonial_image', 'https://waterless.dk/userfiles/image/Nytlayout/Outside_urinal.png');
$testimonial_heading = get_theme_mod('waterless_testimonial_heading', 'Vi har med succes installeret utallige urinaler på forskellige steder - og leverer en 100% lugtfri oplevelse, garanteret!');

?>

<article class="hero">
    <section class="hero-content">
        <section class="hero-text">
            <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
            <p><?php echo esc_html($hero_sub); ?></p>
            <a href="<?php echo esc_url($hero_cta_link); ?>" class="cta-button"><?php echo esc_html($hero_cta_text); ?></a>
        </section>
    </section>
    <img src="<?php echo esc_url($hero_image); ?>" alt="Hero Image" class="hero-image">
</article>

<main class="main-content">

    <section class="ppad content grid cols-2 content-center">
        <img src="<?php echo esc_url($sec1_image); ?>" alt="" class="product-image">
        <section>
            <p><?php echo esc_html($sec1_pre); ?></p>
            <h2><?php echo esc_html($sec1_heading); ?></h2>
        </section>
    </section>

    <section class="map-container">
        <img class="sigal" src="<?php echo esc_url($map_badge); ?>" alt="SMV Green Project">
        <div id="map"></div>
        <section>
            <h2><?php echo esc_html($map_heading); ?></h2>
            <h2><?php echo esc_html($map_sub); ?></h2>
            <p><?php echo nl2br(esc_html($map_text)); ?></p>
            <a href="<?php echo esc_url($map_link); ?>">Lær os at kende</a>
        </section>
    </section>

    <section class="full-width-container">
        <img class="full-width-image" src="<?php echo esc_url($full_image); ?>" alt="">
        <article class="full-width-article">
            <p class=""><?php echo esc_html($full_pre); ?></p>
            <h2 class=""><?php echo esc_html($full_heading); ?></h2>
            <p><?php echo nl2br(esc_html($full_text)); ?></p>
        </article>
    </section>

    <section class="content grid cols-2 ppad content-center">
        <section>
            <h2><?php echo esc_html($install_heading); ?></h2>
            <a href="<?php echo esc_url($install_link); ?>">Hvordan installeres?</a>
        </section>
        <img src="<?php echo esc_url($install_image); ?>" alt="">
    </section>

    <section class="content grid cols-2 ppad content-center">
        <img src="<?php echo esc_url($testimonial_image); ?>" alt="">
        <h2><?php echo esc_html($testimonial_heading); ?></h2>
    </section>

    <section class="content ppad content-center">

        <?php
        $post_id = get_the_ID(); // or a specific post ID
        $post = get_post($post_id);

        if (has_blocks($post->post_content)) {
            $blocks = parse_blocks($post->post_content);

            foreach ($blocks as $block) {
                echo render_block($block);
            }
        } else {
            // Fallback for classic editor content
            echo apply_filters('the_content', $post->post_content);
        }
        ?>
    </section>

</main>

<?php
get_footer();
?>