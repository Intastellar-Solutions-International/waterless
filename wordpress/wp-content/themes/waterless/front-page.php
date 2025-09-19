<?php get_header(); ?>

<?php
// Get the homepage ID
$page_id = get_option('page_on_front');
?>

<!-- Hero Section -->
<article class="hero">
    <section class="hero-content">
        <section class="hero-text">
            <h1 class="hero-title"><?php echo esc_html(get_post_meta($page_id, 'hero_title', true)); ?></h1>
            <p><?php echo esc_html(get_post_meta($page_id, 'hero_subtitle', true)); ?></p>
            <a href="<?php echo get_permalink(get_page_by_path('products')); ?>" class="cta-button">
                <?php echo esc_html(get_post_meta($page_id, 'hero_cta', true)); ?>
            </a>
        </section>
    </section>
    <img src="<?php echo esc_url(get_post_meta($page_id, 'hero_image', true)); ?>" alt="Hero Image" class="hero-image">
</article>

<main class="main-content">
    <!-- Intro Section -->
    <section class="ppad content grid cols-2 content-center">
        <img src="<?php echo esc_url(get_post_meta($page_id, 'intro_image', true)); ?>" alt="Waterless Urinal" class="product-image">
        <section>
            <p><?php echo esc_html(get_post_meta($page_id, 'intro_small_text', true)); ?></p>
            <h2><?php echo esc_html(get_post_meta($page_id, 'intro_heading', true)); ?></h2>
        </section>
    </section>

    <!-- Map Section -->
    <section class="map-container">
        <img class="sigal" src="<?php echo esc_url(get_post_meta($page_id, 'map_badge', true)); ?>" alt="SMV Green Project">
        <div id="map"></div>
        <section>
            <h2><?php echo esc_html(get_post_meta($page_id, 'map_heading1', true)); ?></h2>
            <h2><?php echo esc_html(get_post_meta($page_id, 'map_heading2', true)); ?></h2>
            <p><?php echo esc_html(get_post_meta($page_id, 'map_text', true)); ?></p>
            <a href="<?php echo get_permalink(get_page_by_path('about')); ?>">
                <?php echo esc_html(get_post_meta($page_id, 'map_cta', true)); ?>
            </a>
        </section>
    </section>

    <!-- Full-width Section -->
    <section class="full-width-container">
        <img class="full-width-image" src="<?php echo esc_url(get_post_meta($page_id, 'fullwidth_image', true)); ?>" alt="">
        <article class="full-width-article">
            <p><?php echo esc_html(get_post_meta($page_id, 'fullwidth_small_text', true)); ?></p>
            <h2><?php echo esc_html(get_post_meta($page_id, 'fullwidth_heading', true)); ?></h2>
            <p><?php echo esc_html(get_post_meta($page_id, 'fullwidth_text1', true)); ?></p>
            <p><?php echo esc_html(get_post_meta($page_id, 'fullwidth_text2', true)); ?></p>
        </article>
    </section>

    <!-- Installation Section -->
    <section class="content grid cols-2 ppad content-center">
        <section>
            <h2><?php echo esc_html(get_post_meta($page_id, 'installation_heading', true)); ?></h2>
            <a href="<?php echo esc_url(get_post_meta($page_id, 'installation_cta_link', true)); ?>">
                <?php echo esc_html(get_post_meta($page_id, 'installation_cta_text', true)); ?>
            </a>
        </section>
        <img src="<?php echo esc_url(get_post_meta($page_id, 'installation_image', true)); ?>" alt="">
    </section>

    <!-- Success Section -->
    <section class="content grid cols-2 ppad content-center">
        <img src="<?php echo esc_url(get_post_meta($page_id, 'success_image', true)); ?>" alt="">
        <h2><?php echo esc_html(get_post_meta($page_id, 'success_heading', true)); ?></h2>
    </section>
</main>

<?php get_footer(); ?>