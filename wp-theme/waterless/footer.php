<footer class="footer">
    <section class="grid cols-2 footer-content ppad">
        <article>
            <?php
            if (has_custom_logo()) {
                the_custom_logo(); // dynamic logo
            } else { ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/logos/waterless-logo-2.jpg" alt="Waterless Scandinavia Logo" class="footer-logo">
            <?php } ?>

            <p><?php echo esc_html(get_theme_mod('footer_tagline')); ?></p>
        </article>

        <section class="grid cols-2">
            <article>
                <h2>Kontakt os</h2>
                <address>
                    <?php
                    // Output contact info, preserving line breaks
                    echo nl2br(esc_html(get_theme_mod('footer_contact')));
                    ?>
                </address>
            </article>

            <article>
                <h2>Business</h2>
                <nav class="footer-nav">
                    <?php
                    if (has_nav_menu('footer_menu')) {
                        wp_nav_menu([
                            'theme_location' => 'footer_menu',
                            'container' => false,
                            'menu_class' => '',
                            'items_wrap' => '%3$s', // output only <li> items
                        ]);
                    } else { ?>
                        <a href="<?php echo esc_url(home_url('/about')); ?>">Om os</a>
                        <a href="#">Karriere</a>
                        <a href="#">Partnere</a>
                        <a href="#">Privatlivspolitik</a>
                    <?php } ?>
                </nav>
            </article>
        </section>
    </section>

    <div class="copy">
        <p>&copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
        <p>Udviklet af <a href="https://www.intastellarsolutions.com" target="_blank" rel="noopener">Intastellar Solutions, International</a></p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>

</html>