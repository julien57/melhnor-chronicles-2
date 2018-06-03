
$(function() {

    $('#ajax-contact').submit(function(e) {
        e.preventDefault();

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