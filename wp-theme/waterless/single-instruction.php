<?php get_header(); ?>
<main class="main-content ppad">
    <section class="content">
        <a href="<?php echo get_post_type_archive_link('instruction'); ?>">Tilbage</a>
    </section>

    <section class="content">
        <h1><?php the_title(); ?></h1>
        <?php
        $video_url = get_post_meta(get_the_ID(), 'instruction_video_url', true);
        $embed_url = $video_url ? waterless_get_embed_url($video_url) : null;
        ?>

        <?php if ($embed_url) : ?>
            <div style="padding:56.25% 0 0 0;position:relative;">
                <iframe
                    src="<?php echo esc_url($embed_url); ?>"
                    frameborder="0"
                    allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;"
                    title="<?php the_title_attribute(); ?>">
                </iframe>
            </div>
        <?php endif; ?>


        <div class="instruction-description" style="margin-top:20px;">
            <?php the_content(); ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>