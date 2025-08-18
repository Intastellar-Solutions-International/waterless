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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Waterless Scandinavia ApS - Sustainable urinal solutions for your buildings and facilities.">
    <meta name="keywords" content="Waterless, Scandinavia, ApS, Sustainable, Urinal, Solutions">
    <base href="<?php echo $baseUrl; ?>">
    <meta name="og:image" content="https://waterless.dk/assets/images/og-image.jpg">
    <meta name="og:title" content="<?php echo $pageTitle; ?> | Waterless Scandinavia ApS">
    <meta name="og:description" content="Waterless Scandinavia ApS - Sustainable urinal solutions for your buildings and facilities.">
    <meta name="og:url" content="<?php echo $pageUrl; ?>">
    <meta name="og:type" content="website">
    <meta name="og:site_name" content="Waterless Scandinavia ApS">
    <meta name="og:locale" content="da_DK">
    <title><?php echo $pageTitle; ?> | Waterless Scandinavia ApS</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/icons/fav/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/icons/fav/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/icons/fav/favicon-16x16.png">
    <link rel="stylesheet" href="css/leaflet/leaflet.css"
        crossorigin="" />
    <link href="css/lenis/lenis.min.css
    " rel="stylesheet">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        crossorigin=""></script>
    <script src="js/nav.js" defer></script>
    <script src="js/map.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/lenis@1.3.1/dist/lenis.min.js"></script>
</head>

<body>
    <header class="main-header">
        <section class="header-content">
            <a href="<?php echo $baseUrl; ?>" class="logo-container">
                <img class="logo" src="assets/logos/waterless-logo-2.jpg" alt="Waterless Logo">
            </a>
            <nav class="main-nav">
                <!-- Navigation items can be added here -->
                <section class="menu">
                    <section class="dropdown">
                        <button class="dropdown-toggle nav-elements">Produkter</button>
                        <section class="dropdown-menu">
                            <article class="dropdown-content grid cols-3">
                                <section class="dropdown-content-item">
                                    <p>Desinfektion Dispenser & tilbehør</p>
                                </section>
                                <section class="dropdown-content-item">
                                    <p>Waterless Urinaler & tilbehør</p>
                                </section>
                                <section class="dropdown-content-item">
                                    <p>Rengøringsartikler til urinaler m.m.</p>
                                </section>
                            </article>
                        </section>
                    </section>
                    <a href="instructions" class="nav-elements">Instruktioner</a>
                    <a href="business" class="nav-elements">Til din virksomhed</a>
                    <a href="about" class="nav-elements">Om os</a>
                    <a href="contact" class="nav-elements">Kontakt</a>
                </section>

                <!-- Language selector -->
                <section class="dropdown">
                    <button class="language-selector dropdown-toggle">
                        <img class="language-icon" src="assets/icons/flag-denmark.jpg" alt="Language Icon">
                    </button>
                    <section class="dropdown-menu">
                        <article class="dropdown-content languages">
                            <a href="en" class="language-option">
                                <img src="assets/icons/uk.webp" alt="English Flag" class="language-icon">
                                English
                            </a>
                            <a href="de" class="language-option">
                                <img src="assets/icons/flag-germany.jpg" alt="German Flag" class="language-icon">
                                Deutsch
                            </a>
                        </article>
                    </section>
                </section>
                <!-- CTA -->
                <a href="quote" class="cta-button --header">Make an enquiry</a>
            </nav>
        </section>
    </header>