<?php

/**
 * Title: Front Page Layout
 * Slug: waterless/front-page
 * Categories: waterless
 * Block Types: core/post-content
 * Inserter: true
 */
?>

<!-- wp:cover {"url":"<?php echo esc_url(get_template_directory_uri() . '/assets/hero/Urinals1.png'); ?>","dimRatio":0,"className":"hero"} -->
<div class="wp-block-cover hero">
    <span aria-hidden="true" class="wp-block-cover__background has-background-dim-0"></span>
    <img class="wp-block-cover__image-background hero-image" alt="Hero Image" src="<?php echo esc_url(get_template_directory_uri() . '/assets/hero/Urinals1.png'); ?>" data-object-fit="cover" />
    <div class="wp-block-cover__inner-container">
        <div class="wp-block-group hero-content">
            <div class="wp-block-group hero-text">
                <h1 class="hero-title">Ændring af vandforbrugsindustrien</h1>
                <p>Bæredygtige urinal-løsninger til dine bygninger og faciliteter.</p>
                <div class="wp-block-button cta-button"><a class="wp-block-button__link wp-element-button" href="/products">Udforsk produkter</a></div>
            </div>
        </div>
    </div>
</div>
<!-- /wp:cover -->

<!-- wp:columns {"className":"ppad content grid cols-2 content-center"} -->
<div class="wp-block-columns ppad content grid cols-2 content-center">
    <div class="wp-block-column">
        <figure class="wp-block-image">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/products/urinal-eco-12.png'); ?>" alt="Waterless Urinal" class="product-image" />
        </figure>
    </div>
    <div class="wp-block-column">
        <p>Hvad vi laver</p>
        <h2>Vandfri urinaler og bæredygtige løsninger</h2>
    </div>
</div>
<!-- /wp:columns -->

<!-- wp:group {"className":"map-container"} -->
<div class="wp-block-group map-container">
    <figure class="wp-block-image sigal"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/smvgrøn.png'); ?>" alt="SMV Green Project" /></figure>
    <div id="map"></div>
    <div class="wp-block-group">
        <h2>Verden rundt siden 1997 - 72 lande og tæller</h2>
        <h2>Virksomhed med stabil position på markedet</h2>
        <p>Vi er til stede på markedet siden 1997, og vi har 80% af det danske marked inden for vandfri urinaler.</p>
        <div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/about.php">Lær os at kende</a></div>
    </div>
</div>
<!-- /wp:group -->

<!-- wp:cover {"url":"<?php echo esc_url(get_template_directory_uri() . '/assets/hero/63f76bfa9ee8a4f89044ef031c41fa4c50977249.png'); ?>","dimRatio":0,"className":"full-width-container"} -->
<div class="wp-block-cover full-width-container">
    <span aria-hidden="true" class="wp-block-cover__background has-background-dim-0"></span>
    <img class="wp-block-cover__image-background full-width-image" alt="" src="<?php echo esc_url(get_template_directory_uri() . '/assets/hero/63f76bfa9ee8a4f89044ef031c41fa4c50977249.png'); ?>" data-object-fit="cover" />
    <div class="wp-block-cover__inner-container">
        <div class="wp-block-group full-width-article">
            <p>Tilpasset løsning til dig</p>
            <h2>Vandbesparelser</h2>
            <p>Vil du vide, hvor meget du kan spare?</p>
            <p>Indtast blot dine oplysninger i vores beregner <br>og se besparelserne vokse!</p>
        </div>
    </div>
</div>
<!-- /wp:cover -->

<!-- wp:columns {"className":"content grid cols-2 ppad content-center"} -->
<div class="wp-block-columns content grid cols-2 ppad content-center">
    <div class="wp-block-column">
        <h2>Installation af vandfri urinaler</h2>
        <p><a href="#">Hvordan installeres?</a></p>
    </div>
    <div class="wp-block-column">
        <figure class="wp-block-image">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/Projekt bez nazwy (25) 1.jpg'); ?>" alt="Installation" />
        </figure>
    </div>
</div>
<!-- /wp:columns -->

<!-- wp:columns {"className":"content grid cols-2 ppad content-center"} -->
<div class="wp-block-columns content grid cols-2 ppad content-center">
    <div class="wp-block-column">
        <figure class="wp-block-image"><img src="https://waterless.dk/userfiles/image/Nytlayout/Outside_urinal.png" alt="Outside Urinal" /></figure>
    </div>
    <div class="wp-block-column">
        <h2>Vi har med succes installeret utallige urinaler på forskellige steder - og leverer en 100% lugtfri oplevelse, garanteret!</h2>
    </div>
</div>
<!-- /wp:columns -->