<?php get_header(); ?>

<main class="main-content">
    <?php
    // The loop: show Gutenberg content of the "Home" page
    if (have_posts()) :
        while (have_posts()) : the_post();
            the_content(); // outputs blocks from the editor
        endwhile;
    endif;
    ?>
</main>

<?php get_footer(); ?>