<?php
$pageTitle = "Contact";
include_once 'components/head.php';
?>
<main class="main-content ppad">
    <section class="content">
        <h1>Kontakt os</h1>
        <section class="grid cols-2">
            <section>
                <p>Hvis du har spørgsmål, er du velkommen til at kontakte os!</p>
                <form class="contact-form">
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
            </section>
            <section>
                <h2>Find os her</h2>
                <address>
                    Møllegade 23 <br>
                    6310 Broager <br>
                    Denmark
                </address>
            </section>
        </section>
    </section>
</main>
<?php
include_once 'components/footer.php';
?>