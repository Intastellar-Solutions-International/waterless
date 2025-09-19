<?php
/* Template Name: Instruction Page */
get_header();
?>

<main class="main-content ppad">
    <!-- Back link -->
    <section class="content">
        <a href="<?php echo get_permalink(get_page_by_path('instructions')); ?>">Tilbage</a>
    </section>

    <!-- Instruction Video -->
    <section class="content">
        <h1><?php the_title(); ?></h1>

        <?php
        // Optional: Make the Vimeo URL dynamic using custom field
        $vimeo_url = get_post_meta(get_the_ID(), 'instruction_vimeo_url', true);

        // Fallback if custom field is empty
        if (empty($vimeo_url)) {
            $vimeo_url = 'https://player.vimeo.com/video/1109661238?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479';
        }
        ?>

        <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe src="<?php echo esc_url($vimeo_url); ?>" frameborder="0" allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share" referrerpolicy="strict-origin-when-cross-origin" style="position:absolute;top:0;left:0;width:100%;height:100%;" title="<?php the_title(); ?>"></iframe>
        </div>
        <script src="https://player.vimeo.com/api/player.js"></script>
    </section>
</main>

<?php get_footer(); ?>