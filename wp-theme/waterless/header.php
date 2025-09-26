<?php

$host = $_SERVER['HTTP_HOST'];
$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';

// Check if $host contains 'localhost'
if (strpos($host, 'localhost') > -1 || strpos($host, '127.0.0.1') > -1 || strpos($host, '0.0.0.0') > -1) {
    $baseUrl = '/';
} else {
    $baseUrl = 'https://inta.dev/waterless/';
}

$pageUrl =  $protocol . $host . $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>
</head>

<body>
    <header class="main-header">
        <section class="header-content">
            <?php
            if (has_custom_logo()) {
                the_custom_logo(); // Displays the uploaded logo
            } else {
                // Fallback: show site title if no logo is set
                echo '<h1>' . get_bloginfo('name') . '</h1>';
            }
            ?>
            <nav class="main-nav">
                <section class="menu">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'main_menu',
                        'container'      => false,    // no container element
                        'items_wrap'     => '%3$s',   // DO NOT output the outer <ul> wrapper (default is <ul>%3$s</ul>)
                        'depth'          => 2,
                        'walker'         => new Waterless_Walker_Nav_Menu(),
                        'fallback_cb'    => false,    // don't fall back to wp_page_menu()
                    ]);
                    ?>
                </section>

                <button class="hamburger-menu" id="hamburger-menu"></button>

                <!-- Language selector (keep static or hook into WPML/Polylang later) -->
                <?php
                // Define language data
                $languages = [
                    'da' => [
                        'link'  => get_theme_mod('waterless_lang_da_link', '/'),
                        'flag'  => get_theme_mod('waterless_lang_da_flag', get_template_directory_uri() . '/assets/icons/flag-denmark.jpg'),
                        'label' => 'Dansk'
                    ],
                    'en' => [
                        'link'  => get_theme_mod('waterless_lang_en_link', '/en'),
                        'flag'  => get_theme_mod('waterless_lang_en_flag', get_template_directory_uri() . '/assets/icons/uk.webp'),
                        'label' => 'English'
                    ],
                    'de' => [
                        'link'  => get_theme_mod('waterless_lang_de_link', '/de'),
                        'flag'  => get_theme_mod('waterless_lang_de_flag', get_template_directory_uri() . '/assets/icons/flag-germany.jpg'),
                        'label' => 'Deutsch'
                    ],
                ];

                // Detect language from URL path
                $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
                $parts = explode('/', $uri);
                $current_lang = $parts[0] ?? 'da';

                // Fallback to Danish if not recognized
                if (!array_key_exists($current_lang, $languages)) {
                    $current_lang = 'da';
                }

                // Current language data
                $current_flag  = $languages[$current_lang]['flag'];
                $current_label = $languages[$current_lang]['label'];
                ?>

                <section class="dropdown language-select-container">
                    <button class="language-selector dropdown-toggle">
                        <img class="language-icon" src="<?php echo esc_url($current_flag); ?>" alt="<?php echo esc_attr($current_label); ?>">
                    </button>
                    <section class="dropdown-menu language">
                        <article class="dropdown-content languages">
                            <?php foreach ($languages as $code => $lang) : ?>
                                <?php if ($code !== $current_lang) : ?>
                                    <a href="<?php echo esc_url($lang['link']); ?>" class="language-option">
                                        <img src="<?php echo esc_url($lang['flag']); ?>" alt="<?php echo esc_attr($lang['label']); ?>" class="language-icon">
                                        <?php echo esc_html($lang['label']); ?>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </article>
                    </section>
                </section>

                <!-- CTA -->
                <a href="/quote" class="cta-button --header">Få et tilbud</a>
            </nav>
        </section>
    </header>