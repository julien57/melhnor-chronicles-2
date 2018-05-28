$(function() {
    var form = $('#ajax-contact');
    var formMessages = $('#form-messages');

    $(form).submit(function(event) {
        event.preventDefault();

        var formData = $(form).serialize();
        console.log(formData);
        $.ajax({
            type: 'POST',
            url: $(form).attr('action'),
            data: formData
        }).done(function(response) {
            $(formMessages).removeClass('error');
            $(formMessages).addClass('success');

            $(formMessages).text(response);

            $('#contact_name').val('');
            $('#contact_mail').val('');
            $('#contact_subject').val('');
            $('#contact_body').val('');
        }).fail(function(data) {
            $(formMessages).removeClass('success');
            $(formMessages).addClass('error');

            if (data.responseText !== '') {
                $(formMessages).text(data.responseText);
            } else {
                $(formMessages).text('Oops! An error occured and your message could not be sent.');
            }
        });
    })
});
