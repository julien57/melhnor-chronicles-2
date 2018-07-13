class Contact {

    addMessageEmail(e) {
        const email = e.target.value;
        let mailLength = 'Erroné';
        let color = 'red';

        if (Contact.verifyEmail(email)) {
            mailLength = 'Mail OK !';
            color = 'green';
        }
        document.getElementById('mail-control').textContent = mailLength;
        document.getElementById('mail-control').style.color = color;
    }

    VerifyInputs(e) {
        if (document.getElementById('contact_name') === '' || document.getElementById('contact_mail') === '' ||
            document.getElementById('contact_subject') === '' || document.getElementById('contact_body') === '') {

            document.getElementById('error_input').textContent = 'Veuillez remplir tous les champs.';
            document.getElementById('error_input').style.color = 'red';
        } else {
            Contact.submitAjaxForm(e);
        }
    }

    static submitAjaxForm(e) {
        const $form = $(e.currentTarget);

        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize(),
            success: function(data) {
                $form[0].reset();
                $('#form-messages').text(data.successMessage);
                $('#form-messages').addClass('alert-success');
            },
            error: function(data) {
                $('#form-messages').text(data.errorMessage);
                $('#form-messages').addClass('alert-danger');
            }
        });
    }

    static verifyEmail(email) {
        const regex = /.+@.+\..+/;
        return regex.test(email);
    }
}

const contact = new Contact();

document.getElementById('contact_mail').addEventListener('input', (e) => {
    contact.addMessageEmail(e);
});

document.getElementById('contact_mail').addEventListener('blur', () => {
    document.getElementById('mail-control').textContent = '';
});

$('#ajax-contact').submit(function(e) {
    e.preventDefault();
    contact.VerifyInputs(e);
});
