
$(function() {

    $('#ajax-contact').submit(function(e) {
        e.preventDefault();

        const $form = $(e.currentTarget);

        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize(),
            success: function(data) {
                $form[0].reset();
                $('#form-messages').text(data.successMessage);
            },
            error: function(data) {
                $('#form-messages').text(data.errorMessage);
            }
        });
    });
});