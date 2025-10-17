<?php
/*
Template Name: Business Page
*/
get_header();
echo get_theme_mod("business_page_item1_title");
?>

<main class="main-content ppad">
    <section class="content">
        <h1><?php echo esc_html(get_theme_mod('business_page_title', 'Til din virksomhed')); ?></h1>
        <p><?php echo nl2br(esc_html(get_theme_mod('business_page_intro', 'Her kan du finde informationer om vores produkter og hvordan de kan hjælpe din virksomhed med at spare vand og penge.'))); ?></p>
    </section>

    <section class="content grid cols-5">
        <?php
        $counter = 1;
        while (get_theme_mod("business_page_item{$counter}_title")) : the_post();
        ?>
            <article class="product-item">
                <h2 class="product-title"><?php echo esc_html(get_theme_mod("business_page_item{$counter}_title")); ?></h2>
                <img src="<?php echo esc_url(get_theme_mod("business_page_item{$counter}_image", theme_get_default_product_image())); ?>" alt="<?php echo esc_html(get_theme_mod("business_page_item{$counter}_title")); ?>">

                <a href="<?php echo esc_url(get_theme_mod("business_page_item{$counter}_link")); ?>" class="cta">
                    <?php echo esc_html(get_theme_mod('products_page_cta_text', 'Læs mere')); ?>
                </a>
            </article>
        <?php
            $counter++;
            echo '<article></article>';
        endwhile;
        ?>
    </section>
</main>

<?php get_footer(); ?>