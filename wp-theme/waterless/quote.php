<?php
$pageTitle = "Get a Quote";
include_once 'components/head.php';
?>
<main class="main-content ppad">
    <section class="content">
        <h1>Get a Quote</h1>
        <p>Please fill out the form below to receive a quote.</p>
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
</main>
<?php
include_once 'components/footer.php';
?>