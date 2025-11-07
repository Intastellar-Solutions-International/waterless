jQuery(document).ready(function ($) {
    let mediaFrame;

    $(document).on('click', '.upload-menu-icon', function (e) {
        e.preventDefault();

        const button = $(this);
        const preview = button.siblings('.menu-item-icon-preview');
        const input = button.siblings('.menu-item-icon-id');
        const removeBtn = button.siblings('.remove-menu-icon');

        if (mediaFrame) {
            mediaFrame.open();
            return;
        }

        mediaFrame = wp.media({
            title: 'Select Menu Icon',
            button: { text: 'Use this icon' },
            library: { type: 'image' },
            multiple: false
        });

        mediaFrame.on('select', function () {
            const attachment = mediaFrame.state().get('selection').first().toJSON();
            preview.attr('src', attachment.url).show();
            input.val(attachment.id);
            removeBtn.show();
        });

        mediaFrame.open();
    });

    $(document).on('click', '.remove-menu-icon', function (e) {
        e.preventDefault();
        const button = $(this);
        const preview = button.siblings('.menu-item-icon-preview');
        const input = button.siblings('.menu-item-icon-id');

        preview.hide().attr('src', '');
        input.val('');
        button.hide();
    });
});
