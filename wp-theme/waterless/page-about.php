<?php

/**
 * Template Name: About Page
 */
get_header();
?>

<main class="main-content ppad">
    <article class="content">
        <!-- First section -->
        <section class="grid cols-2">
            <div>
                <h1><?php echo esc_html(get_theme_mod('about_page_title', 'Waterless Scandinavia – Pionerer inden for vandfri innovation')); ?></h1>
                <p><?php echo nl2br(esc_html(get_theme_mod('about_page_intro', 'Vi var det første firma i Danmark til at introducere vandløse urinaler, og i dag forbliver vi en af de globale ledere på området.'))); ?></p>
            </div>
            <img src="<?php echo esc_url(get_theme_mod('about_page_image_1', get_template_directory_uri() . '/assets/founders/jim.jpg')); ?>" alt="Waterless Scandinavia" />
        </section>

        <!-- Second section -->
        <section class="grid cols-2 ppad">
            <img src="<?php echo esc_url(get_theme_mod('about_page_image_2', get_template_directory_uri() . '/assets/founders/team.jpg')); ?>" alt="Waterless Scandinavia" />
            <div>
                <h2><?php echo esc_html(get_theme_mod('about_page_second_title', 'Tillid: Nøglen til Waterless og det danske samfund')); ?></h2>
                <p><?php echo nl2br(esc_html(get_theme_mod('about_page_second_text', 'Hos Waterless er tillid fundamentet for vores succes. Det gør det muligt for vores team at arbejde problemfrit, innovere og levere bæredygtige vandbesparende løsninger.'))); ?></p>
            </div>
        </section>

        <!-- Meet our team -->
        <section class="ppad">
            <h2><?php echo esc_html(get_theme_mod('about_page_team_title', 'Meet our team members!')); ?></h2>

            <div class="grid cols-2">
                <!-- Team member 1 -->
                <article class="team-member">
                    <h3><?php echo esc_html(get_theme_mod('about_page_team1_name', 'Jim Skov')); ?></h3>
                    <img src="<?php echo esc_url(get_theme_mod('about_page_team1_image', get_template_directory_uri() . '/assets/founders/jim-skov.jpg')); ?>" alt="<?php echo esc_attr(get_theme_mod('about_page_team1_name', 'Jim Skov')); ?>">
                    <p><?php echo esc_html(get_theme_mod('about_page_team1_region', 'Fyn, Sjælland, Norge og Sverige')); ?></p>
                    <p>Mail: <a href="mailto:<?php echo antispambot(get_theme_mod('about_page_team1_email', 'js@waterless.dk')); ?>"><?php echo antispambot(get_theme_mod('about_page_team1_email', 'js@waterless.dk')); ?></a></p>
                    <p>Tel.: <?php echo esc_html(get_theme_mod('about_page_team1_phone', '+45 30 95 05 59')); ?></p>
                    <a class="cta" href="<?php echo esc_url(get_theme_mod('about_page_team1_link', 'https://meetings-eu1.hubspot.com/jim-skov')); ?>" target="_blank" rel="noopener noreferrer">Få et møde</a>
                </article>

                <!-- Team member 2 -->
                <article class="team-member">
                    <h3><?php echo esc_html(get_theme_mod('about_page_team2_name', 'Paúl Knudsen')); ?></h3>
                    <img src="<?php echo esc_url(get_theme_mod('about_page_team2_image', get_template_directory_uri() . '/assets/founders/paul-knudsen.jpg')); ?>" alt="<?php echo esc_attr(get_theme_mod('about_page_team2_name', 'Paúl Knudsen')); ?>">
                    <p><?php echo esc_html(get_theme_mod('about_page_team2_region', 'Jylland & Nordtyskland')); ?></p>
                    <p>Mail: <a href="mailto:<?php echo antispambot(get_theme_mod('about_page_team2_email', 'pk@waterless.dk')); ?>"><?php echo antispambot(get_theme_mod('about_page_team2_email', 'pk@waterless.dk')); ?></a></p>
                    <p>Tel.: <?php echo esc_html(get_theme_mod('about_page_team2_phone', '+45 20 14 50 84')); ?></p>
                    <a class="cta" href="<?php echo esc_url(get_theme_mod('about_page_team2_link', 'https://meetings-eu1.hubspot.com/paul-yde')); ?>" target="_blank" rel="noopener noreferrer">Få et møde</a>
                </article>
            </div>
        </section>
    </article>
</main>

<?php get_footer(); ?>