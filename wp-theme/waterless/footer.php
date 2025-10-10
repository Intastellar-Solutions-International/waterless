<footer class="footer">
    <section class="grid cols-2 footer-content ppad">
        <article>
            <?php
            if (has_custom_logo()) {
                the_custom_logo(); // dynamic logo
            } else { ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/logos/waterless-logo-2.jpg" alt="Waterless Scandinavia Logo" class="footer-logo">
            <?php } ?>

            <?php
            $default_tagline = 'Upgrade til bæredygtighed — spar vand, skær omkostningerne, og vær på forkant!';
            ?>
            <p><?php echo esc_html( get_theme_mod('footer_tagline', $default_tagline) ); ?></p>
        </article>

        <section class="grid cols-2">
            <article>
                <h2>Kontakt os</h2>
                <address>
                    <?php
                    // Output contact info, preserving line breaks
                    $default_contact = "Waterless Scandinavia ApS
Møllegade 23
6310 Broager
Denmark
Tel: +45 74 44 11 81
Email: info@waterless.dk";
                    echo nl2br( esc_html( get_theme_mod('footer_contact', $default_contact) ) );
                    ?>
                </address>
            </article>

            <article>
                <h2>Business</h2>
                <nav class="footer-nav">
                    <?php
                    if ( has_nav_menu( 'footer_menu' ) ) {
                        // Get the menu assigned to the "footer_menu" location and output links without <li> wrappers
                        $locations = get_nav_menu_locations();
                        $menu_items = [];
                        if ( isset( $locations['footer_menu'] ) ) {
                            $menu_items = wp_get_nav_menu_items( $locations['footer_menu'] );
                        }

                        if ( $menu_items && ! empty( $menu_items ) ) {
                            foreach ( $menu_items as $menu_item ) {
                                printf(
                                    '<a href="%s">%s</a>',
                                    esc_url( $menu_item->url ),
                                    esc_html( $menu_item->title )
                                );
                            }
                        }
                    } else { ?>
                        <a href="<?php echo esc_url( home_url( '/about' ) ); ?>">Om os</a>
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