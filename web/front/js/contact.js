
$(function() {

    $('#ajax-contact').submit(function(e) {
       
        var name = $('#contact_name').val();
        var mail = $('#contact_mail').val();
        var subject = $('#contact_subject').val();
        var body = $('#contact_body').val();
        var info_champs = "Merci de remplir le champs manquant.";

        var $this = $(this);

        var formData = {};
        var $form = $(e.currentTarget);
        $.each($form.serializeArray(), function(key, fieldData) {
            formData[fieldData.name] = fieldData.value
        });

        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: JSON.stringify(formData),
            success: function() {
                console.log(this.data);
                console.log(this.type);
                console.log(this.url);
            }
        });
    });
});