<?php
/* Template Name: Subpage Pattern */
get_header();
?>

<main>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article class="relative">
                <section class="paint-bg info-container">
                    <h1><?php the_title(); ?></h1>
                    <div class="page-intro">
                        <?php the_content(); ?>
                    </div>
                </section>

                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('full', ['class' => 'section-bg', 'alt' => get_the_title()]); ?>
                <?php endif; ?>
            </article>

            <?php
            // Optional flexible fields (ACF) or Gutenberg blocks
            if (have_rows('subpage_sections')) :
                while (have_rows('subpage_sections')) : the_row(); ?>
                    <article class="relative">
                        <section class="paint-bg info-container <?php the_sub_field('alignment'); ?>">
                            <h2><?php the_sub_field('heading'); ?></h2>
                            <p><?php the_sub_field('text'); ?></p>
                        </section>

                        <?php $image = get_sub_field('background_image');
                        if ($image) : ?>
                            <img class="section-bg" src="<?php echo esc_url($image['url']); ?>"
                                alt="<?php echo esc_attr($image['alt']); ?>">
                        <?php endif; ?>
                    </article>
            <?php endwhile;
            endif;
            ?>

    <?php endwhile;
    endif; ?>
</main>

<?php get_footer(); ?>