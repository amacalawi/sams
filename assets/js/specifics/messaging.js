jQuery(document).ready(function ($) {
    $('#message-template-list .list-group-item button').on('click', function (e) {
        $('#new-sms-textarea').val($('#new-sms-textarea').val() + $(this).parent().find('span').text());
        $(this).parents('#send-templates-modal').find('.close').click();
        e.preventDefault();
    });
    $('#new-sms-textarea').on('inputchange', function (e) {
        console.log("asd");
    })
    $('#send-new-message-form').validate({
        ignore: [],
        rules: {
            'msisdn[]': 'required',
            body: 'required',
        },
        messages: {
            'msisdn[]': {
                'required': "The Phone Number field is required"
            },
            body: {
                'required': "Your Message field should not be empty"
            },
        },
        errorElement: 'small',
        errorPlacement: function (error, element) {
            $(error).addClass('help-block');
            $(element).parents('.form-group-validation').addClass('has-warning').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.form-group-validation').addClass('has-warning');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.form-group-validation').removeClass('has-warning');
            $(element).parents('.form-group-validation').find('.help-block').remove();
        },
        submitHandler: function (form) {
            $(form).find('#submit').html('Sending...');
            var add = {
                type: form.method,
                url: form.action,
                data: $(form).serialize(),
                success: function (data) {
                    console.log("PARSED JSON", data);
                    var data = JSON.parse(data);
                    resetWarningMessages('.form-group-validation');
                    if( data.type !== 'success' ) {
                        var errors = data.message;
                        $.each(errors, function (k, v) {
                            $('#send-new-message-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                        });
                    } else {
                        $(form).find('#submit').html('Send');
                        reset_selectize();
                        notify(data.message, data.type, 9000);
                        $('#send-new-message-form')[0].reset();
                        $('#send-new-message-form [name=msisdn]').focus();
                        $('input-selectize').selectize("clear");
                        reload_selectpickers();
                    }
                    console.log(data);
                },
            };
            $.ajax({
                type: add.type,
                url: add.url,
                data: add.data,
                success: add.success
            });
            // $(form).find('#submit').html('Send');
        }
    });
    $('.input-selectize').each(function (e) {
        var Ds = $(this);
        var Ds_selectize = $(this).selectize({
            plugins: ['remove_button', 'restore_on_backspace'],
            delimiter: $(this).data('selectize-delimiter') ? $(this).data('selectize-delimiter') : ',',
            options: [],
            persist: false,
            maxItems: null,
            create: function(input) {
                console.log(input);
                resetWarningMessages('.form-group-validation');
                if (null !== input.match(/\d/g) && input.match(/\d/g).length === 11) {
                    return {
                        value: input,
                        name: input,
                        msisdn: input
                    }
                }
                $('#send-new-message-form').find('#phone-field-container').addClass('has-warning').append('<small class="error help-block">Phone is not valid</small>');
                return false;
            },

            valueField: 'msisdn',
            labelField: 'name',
            searchField: ['name', 'msisdn'],

            render: {
                item: function(item, escape) {
                    console.log(item);
                    var caption = item.name ? item.name : item.msisdn;
                    return '<div><span class="name">' + escape(caption) + '</span></div>';
                },
                option: function(item, escape) {
                    console.log(item);
                    var label = item.name || item.msisdn;
                    var caption = item.name ? item.msisdn : null;
                    return '<div>' +
                        '<strong>' + escape(label) + '</strong>' +
                        (caption.length > 1 && caption ? '<div class="caption text-muted">' + escape(caption) + '</div>' : '') +
                    '</div>';
                }
            },
            focus: function (e) {
                console.log(e);
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                var attr = Ds.attr('data-selectize-ajax');
                var url = base_url('messaging/new');
                if (typeof attr !== typeof undefined && attr !== false) {
                    url = attr;
                }
                $.get(url, function (data) {
                    console.log($.parseJSON(data));
                    callback($.parseJSON(data));
                });
            },
        });
    });

    $('#send-later-btn-trigger').on('click', function (e) {
        e.preventDefault();
        var _msisdn = $('[name="msisdn[members][]"]').val(),
            _msisdn_g =  $('[name="msisdn[groups][]"] option:selected').text(),
            _body = $('[name="body"]').val(),

            _msisdn_input = $('[name="msisdn[]"]').val(),
            _body_input = $('[name="body"]').val();

        $('#send-later-modal #message-content').html(_body);
        $('#send-later-modal #send-to-content').html((_msisdn ? _msisdn : "") + ",<br/>" + (_msisdn_g?_msisdn_g:""));

    });
    $('#send-later-form').submit(function (e) {
        e.preventDefault();
        $(this).find('#submit').html('Sending...');
        $.post(base_url('messaging/bulk-send/later'), $(this).serialize() +"&"+ $('#send-new-message-form').serialize(), function (data) {
            var data = $.parseJSON(data);
            if (data.type == "success") {
                notify(data.message, data.type, 9000);
                $('#send-later-close-btn').click();
                $('#send-new-message-form')[0].reset();
                reset_selectize();
                $(this).find('#submit').html('Message Sent')
                $(this).find('#submit').html('Send');
            } else {
                var errors = data.message;
                $.each(errors, function (k, v) {
                    $('#send-later-form').find('input[name="'+k+'"], select[name="'+k+'"], textarea[name="'+k+'"]').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                    // console.log(k,v);
                    $('#send-new-message-form').find('input[name="'+k+'"], select[name="'+k+'"], textarea[name="'+k+'"]').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                });
                $(this).find('#submit').html('Send');
		$("#send-later-form").find('[name=submit]').html("Send");
            }
            console.log(data);
        });
    });
});

function reset_selectize() {
    $(document).find('.input-selectize .remove').click();
    var $select = $('#msisdn-input').selectize();
    var control = $select[0].selectize;
    control.clear();

    $select = $('#msisdn-group-input').selectize();
    control = $select[0].selectize;
    control.clear();
}
