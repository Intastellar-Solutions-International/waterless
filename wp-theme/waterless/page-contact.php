<?php

/**
 * Template Name: Contact Page
 */
get_header();
?>

<main class="main-content ppad">
    <section class="content contact-page">

        <!-- Page Title -->
        <h1><?php echo esc_html(get_theme_mod('contact_page_title', 'Kontakt os')); ?></h1>
        <p><?php echo nl2br(esc_html(get_theme_mod('contact_page_intro', 'Har du spørgsmål eller ønsker du et tilbud? Kontakt os her.'))); ?></p>

        <!-- Contact Info Section -->
        <section class="grid cols-2 ppad">
            <div>
                <h2><?php echo esc_html(get_theme_mod('contact_page_info_title', 'Kontaktinformation')); ?></h2>
                <p><strong>Adresse:</strong><br><?php echo nl2br(esc_html(get_theme_mod('contact_page_address', 'Eksempelvej 12, 6400 Sønderborg'))); ?></p>
                <p><strong>Telefon:</strong><br><?php echo esc_html(get_theme_mod('contact_page_phone', '+45 12 34 56 78')); ?></p>
                <p><strong>E-mail:</strong><br><a href="mailto:<?php echo esc_attr(get_theme_mod('contact_page_email', 'info@waterless.dk')); ?>"><?php echo esc_html(get_theme_mod('contact_page_email', 'info@waterless.dk')); ?></a></p>
            </div>

            <!-- Contact Form -->
            <div>
                <h2><?php echo esc_html(get_theme_mod('contact_page_form_title', 'Send os en besked')); ?></h2>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="contact-form">
                    <div class="form-groups">
                        <fieldset class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" required>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="company">Company:</label>
                            <input type="text" id="company" name="company" required>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="message">Message:</label>
                            <textarea id="message" name="message" rows="8" required></textarea>
                        </fieldset>
                    </div>
                    <button type="submit" class="form-btn">Let's talk</button>
                </form>
            </div>
        </section>

        <!-- Map -->
        <?php if ($map_url = get_theme_mod('contact_page_map_embed')) : ?>
            <section class="map ppad">
                <iframe src="<?php echo esc_url($map_url); ?>" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </section>
        <?php endif; ?>

    </section>
</main>

<?php get_footer(); ?>