jQuery(document).ready(function ($) {
    $('#register-form').submit(function (e) {
        $.ajax({
            method: "POST",
            url: base_url('auth/register'),
            data: $('#register-form').serialize(),
            success: function (data) {
                var data = $.parseJSON(data);
                resetWarningMessages('.form-group-validation');
                if( data.type !== 'success' ) {
                    var errors = data.message;
                    $.each(errors, function (k, v) {
                        $('#register-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                        console.log(k,v);
                    });
                } else {
                    // console.log(data);
                    notify(data.message, data.type, 9000);

                    $('#register-form')[0].reset();
                    $('#register-form [name=username]').focus();
                }
                console.log(data);
            }
        });
        e.preventDefault();
    });
})