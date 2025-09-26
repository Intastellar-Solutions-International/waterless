<?php
/* Template Name: Instructions Page */
get_header();
?>

<main class="main-content ppad">
    <?php
    // Check if we are on a single instruction (via ?instruction=ID)
    $instruction_id = isset($_GET['instruction']) ? intval($_GET['instruction']) : null;

    if ($instruction_id) :
        $instruction = get_post($instruction_id);

        if ($instruction && $instruction->post_type === 'instruction') : ?>
            <!-- Single instruction view -->
            <section class="content">
                <a href="<?php echo get_permalink(get_the_ID()); ?>">← Tilbage</a>
                <h1><?php echo esc_html(get_the_title($instruction)); ?></h1>

                <div style="padding:56.25% 0 0 0;position:relative;">
                    <?php
                    $video_url = get_post_meta($instruction->ID, 'instruction_video_url', true);
                    if ($video_url) :
                        $embed_url = function_exists('waterless_get_embed_url') ? waterless_get_embed_url($video_url) : $video_url;
                    ?>
                        <iframe
                            src="<?php echo esc_url($embed_url); ?>"
                            frameborder="0"
                            allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share"
                            style="position:absolute;top:0;left:0;width:100%;height:100%;"
                            title="<?php echo esc_attr(get_the_title($instruction)); ?>">
                        </iframe>
                    <?php endif; ?>
                </div>

                <?php if (!empty($instruction->post_content)) : ?>
                    <div class="instruction-description" style="margin-top:20px;">
                        <?php echo wpautop($instruction->post_content); ?>
                    </div>
                <?php endif; ?>
            </section>

        <?php else : ?>
            <p><?php _e('Instruction not found.', 'waterless'); ?></p>
        <?php endif; ?>

    <?php else : ?>
        <!-- Overview grid -->
        <section class="content">
            <h1><?php the_title(); ?></h1>
        </section>

        <?php
        $instructions = new WP_Query([
            'post_type'      => 'instruction',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        if ($instructions->have_posts()) : ?>
            <section class="content grid cols-2">
                <?php while ($instructions->have_posts()) : $instructions->the_post();
                    $video_url = get_post_meta(get_the_ID(), 'instruction_video_url', true);
                    $thumb = '';

                    // Prefer featured image
                    if (has_post_thumbnail()) {
                        $thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    } elseif ($video_url && function_exists('waterless_get_video_thumbnail')) {
                        $thumb = waterless_get_video_thumbnail($video_url);
                    }
                ?>
                    <article class="product-item">
                        <h2 class="product-title"><?php the_title(); ?></h2>

                        <?php if ($thumb) : ?>
                            <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php endif; ?>

                        <a href="<?php the_permalink(); ?>" class="cta"><?php _e('Læs mere', 'waterless'); ?></a>
                    </article>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </section>
        <?php else : ?>
            <p><?php _e('No instructions have been added yet.', 'waterless'); ?></p>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>