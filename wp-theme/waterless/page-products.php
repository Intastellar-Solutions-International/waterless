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
        if (have_posts()) :
            $counter = 0;
            while (have_posts()) : the_post();
        ?>
                <article class="product-item">
                    <h2 class="product-title"><?php the_title(); ?></h2>

                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium', ['alt' => get_the_title()]); ?>
                    <?php else : ?>
                        <img src="<?php echo theme_get_default_product_image(); ?>" alt="<?php the_title(); ?>">
                    <?php endif; ?>

                    <a href="<?php the_permalink(); ?>" class="cta">
                        <?php echo esc_html(get_theme_mod('products_page_cta_text', 'Læs mere')); ?>
                    </a>
                </article>
        <?php
                $counter++;
                echo '<article></article>';
            endwhile;
        else :
            echo '<p>No products found.</p>';
        endif;
        ?>
    </section>
</main>

<?php get_footer(); ?>