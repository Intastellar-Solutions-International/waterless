<?php
/* Template Name: Instructions Page */
get_header();
?>

<main class="main-content ppad">
    <section class="content">
        <a href="<?php echo get_permalink(get_page_by_path('instructions')); ?>">Tilbage</a>
    </section>

    <?php
    $videos_json = get_post_meta(get_the_ID(), 'instruction_videos', true);
    $videos = $videos_json ? json_decode($videos_json, true) : [];

    if ($videos) :
        foreach ($videos as $video) : ?>
            <section class="content">
                <h1><?php echo esc_html($video['title']); ?></h1>
                <div style="padding:56.25% 0 0 0;position:relative;">
                    <iframe src="<?php echo esc_url($video['url']); ?>" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" style="position:absolute;top:0;left:0;width:100%;height:100%;" title="<?php echo esc_attr($video['title']); ?>"></iframe>
                </div>
            </section>
        <?php endforeach;
    else : ?>
        <p>No instruction videos added yet.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>