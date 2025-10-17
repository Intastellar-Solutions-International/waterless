<?php

/**
 * Template Name: About Page
 */
include_once 'components/head.php';
?>

<main class="main-content ppad">
    <article class="content">
        <!-- First section -->
        <section class="grid cols-2">
            <div>
                <h1><?php echo esc_html(get_theme_mod('about_page_title')); ?></h1>
                <p><?php echo nl2br(esc_html(get_theme_mod('about_page_intro'))); ?></p>
            </div>
            <img src="<?php echo esc_url(get_theme_mod('about_page_image_1')); ?>" alt="Waterless Scandinavia" />
        </section>

        <!-- Second section -->
        <section class="grid cols-2 ppad">
            <img src="<?php echo esc_url(get_theme_mod('about_page_image_2')); ?>" alt="Waterless Scandinavia" />
            <div>
                <h2><?php echo esc_html(get_theme_mod('about_page_second_title')); ?></h2>
                <p><?php echo nl2br(esc_html(get_theme_mod('about_page_second_text'))); ?></p>
            </div>
        </section>
    </article>
</main>

<?php include_once 'components/footer.php'; ?>