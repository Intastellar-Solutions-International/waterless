<?php get_header(); ?>

<main class="main-content ppad">
    <section class="content">
        <h1>Our Products</h1>
    </section>

    <section class="content grid cols-5">
        <?php
        if (have_posts()) :
            $counter = 0; // Counter for spacing
            while (have_posts()) : the_post();
        ?>
                <article class="product-item">
                    <!-- Product title -->
                    <h2 class="product-title"><?php the_title(); ?></h2>

                    <!-- Product thumbnail -->
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium', ['alt' => get_the_title()]); ?>
                    <?php else : ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/products/default-product.png" alt="<?php the_title(); ?>">
                    <?php endif; ?>

                    <!-- CTA link -->
                    <a href="<?php the_permalink(); ?>" class="cta">Læs mere</a>
                </article>

        <?php
                $counter++;
                // Insert empty <article> for spacing after every product
                echo '<article></article>';
            endwhile;
        else :
            echo '<p>No products found.</p>';
        endif;
        ?>
    </section>
</main>

<?php get_footer(); ?>