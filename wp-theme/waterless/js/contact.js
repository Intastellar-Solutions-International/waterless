window.addEventListener('DOMContentLoaded', function () {
    const contactForm = document.querySelector('.contact-form');
    const contactFormAction = contactForm ? contactForm.getAttribute('action') : null;

    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(contactForm);
            const submitButton = contactForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;
            const formMessages = contactForm.querySelector('.form-messages');

            submitButton.disabled = true;
            submitButton.textContent = 'Sending...';


            fetch(contactFormAction, {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        formMessages.textContent = data.message || 'Your message has been sent successfully.';
                        formMessages.classList.add('success');
                        formMessages.classList.remove('error');
                        contactForm.reset();
                    } else {
                        formMessages.textContent = 'There was an error sending your message. Please try again later.';
                        formMessages.classList.add('error');
                        formMessages.classList.remove('success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    formMessages.textContent = 'There was an error sending your message. Please try again later.';
                    formMessages.classList.add('error');
                    formMessages.classList.remove('success');
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                });
        });
    }
});