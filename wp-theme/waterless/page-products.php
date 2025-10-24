<?php
/*
Template Name: Products
*/
get_header();
?>

<main class="main-content ppad">
    <section class="content">
        <h1>
            <?php
            $custom_headline = get_theme_mod('products_page_headline');
            echo $custom_headline ? esc_html($custom_headline) : get_the_title();
            ?>
        </h1>
    </section>

    <section class="content grid cols-<?php echo absint(get_theme_mod('products_page_grid_columns', 5)); ?>">
        <?php
        // Custom query for all products (from all categories)
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'tax_query'      => [
                [
                    'taxonomy' => 'product-category',
                    'field'    => 'slug',
                    'terms'    => get_terms([
                        'taxonomy' => 'product-category',
                        'fields'   => 'slugs',
                        'hide_empty' => false,
                    ]),
                ],
            ],
        ];



        $products = get_posts(array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ));

        if ($products) :
            foreach ($products as $product) :
                // You need to set up post data for template tags to work
                setup_postdata($product);
        ?>

                <article class="product-item">
                    <h2 class="product-title"><?php echo esc_html(get_the_title($product)); ?></h2>

                    <?php if (has_post_thumbnail($product->ID)) : ?>
                        <?php echo get_the_post_thumbnail($product->ID, 'medium', ['alt' => get_the_title($product)]); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url(theme_get_default_product_image()); ?>" alt="<?php echo esc_attr(get_the_title($product)); ?>">
                    <?php endif; ?>

                    <a href="<?php echo esc_url(get_permalink($product->ID)); ?>" class="cta">
                        <?php echo esc_html(get_theme_mod('products_page_cta_text', 'Læs mere')); ?>
                    </a>
                </article>

        <?php
            endforeach;
            wp_reset_postdata();
        else :
            echo '<p>Ingen produkter fundet.</p>';
        endif;
        ?>
    </section>
</main>

<?php get_footer(); ?>