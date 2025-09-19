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
            <a href="<?php echo $baseUrl; ?>" class="logo-container">
                <img class="logo" src="assets/logos/waterless-logo-2.jpg" alt="Waterless Logo">
            </a>
            <?php wp_nav_menu(['theme_location' => 'primary']); ?>
        </section>
    </header>