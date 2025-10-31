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
        // Get current taxonomy slug (if any)
        $term_slug = get_query_var('product-category');

        // Base args
        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ];

        // If a taxonomy slug is present, restrict the query
        if (!empty($term_slug)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product-category',
                    'field'    => 'slug',
                    'terms'    => $term_slug,
                ],
            ];
        }

        $products = new WP_Query($args);

        if ($products->have_posts()) :
            while ($products->have_posts()) :
                $products->the_post();
        ?>
                <article class="product-item">
                    <h2 class="product-title"><?php the_title(); ?></h2>

                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium', ['alt' => get_the_title()]); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url(theme_get_default_product_image()); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                    <?php endif; ?>

                    <a href="<?php the_permalink(); ?>" class="cta">
                        <?php echo esc_html(get_theme_mod('products_page_cta_text', 'Læs mere')); ?>
                    </a>
                </article>
        <?php
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>Ingen produkter fundet.</p>';
        endif;
        ?>
    </section>
</main>

<?php get_footer(); ?>