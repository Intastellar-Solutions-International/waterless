<?php
$pageTitle = "Contact";
include_once 'components/head.php';
?>
<main class="main-content ppad">
    <section class="content">
        <h1>Contact Us</h1>
        <p>If you have any questions, feel free to reach out!</p>
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