$(document).ready(function () {
    $('#register-form').submit(function (event) {
        event.preventDefault();

        var form = $(this);
        var url = form.attr('action');
        var formData = form.serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#register-form').replaceWith('<div class="alert alert-success">' + response.message + '</div>');
                } else {
                    // Display error message
                    $('#message').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function () {
                $('#message').html('<div class="alert alert-danger">An error occurred.</div>');
            }
        });
    });
});