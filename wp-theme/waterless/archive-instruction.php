<?php get_header(); ?>
<main class="main-content ppad">
    <section class="content">
        <h1><?php _e('Instruktioner', 'waterless'); ?></h1>
    </section>
    <section class="content grid cols-2">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article class="product-item">
                    <h2 class="product-title"><?php the_title(); ?></h2>
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium', ['class' => 'instruction-thumb']); ?>
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>" class="cta"><?php _e('Læs mere', 'waterless'); ?></a>
                </article>
        <?php endwhile;
        endif; ?>
    </section>
</main>
<?php get_footer(); ?>