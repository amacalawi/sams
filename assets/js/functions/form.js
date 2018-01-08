
function display_field_error(field, value, clear = true, formname = 'form', $callback = null) {
    $(formname).each(function () {
        var $dis = $(this);

        if ($callback != null) {
            $callback(field, error, formname);
        }

        if (clear) {
            $dis.find('input[name='+field+'], textarea[name='+field+'], select[name='+field+'], [name='+field+']:input')
                .parents('.form-group-validation')
                .removeClass('has-warning')
                .find('.help-block')
                .remove();
        }

        $dis.find('input[name='+field+'], textarea[name='+field+'], select[name='+field+'], [name='+field+']:input')
            .parents('.form-group-validation')
            .addClass('has-warning')
            .append('<small class="help-block">'+value+'</small>');
    });
}

function display_many_fields_error(fields, clear = true, formname = 'form') {
    if (clear) {
        $(formname).find('.help-block').parents('.form-group-validation').removeClass('has-warning');
        $(formname).find('.help-block').remove();
    }
    for (key in fields) {
        display_field_error(key, fields[key], clear, formname);
    }
}

function clear_form($form = $('form')) {
    $form[0].reset();
    $form.find('.help-block').parents('.form-group-validation').removeClass('has-warning');
    $form.find('.help-block').remove();
    $form.find(':input:first').focus();
}

function reload_bootgridtable(table = '[data-bootgrid]') {
    $(table).bootgrid('reload');
}

function get_formdata(url, method = 'POST', $callback = null) {
    $.ajax({
        url: url,
        method: method,
        success: function (data) {
            data = $.parseJSON(data);
            if ($callback != null) {
                $callback(data);
            }
        }
    })
}

function set_formdata(data, form = 'form', modal = 'false', id = 0, action = null, withId = true) {
    if (undefined !== data.type && data.type == 'error') {
        swal(data.title, data.message, data.type);
    }

    var $form = $(form);

    action = action ? action : $form.attr('action');
    if (withId) {
        $form.attr('action', action+"/"+id);
    } else {
        $form.attr('action', action);
    }
    $form[0].reset();

    $.each(data, function (k, v) {
        $form.find('[name=' + k + ']').val(v).parent().addClass('fg-toggled');
        reload_selectpickers();
    });

    $(modal).modal("show");
}

function pop_form(form, data, prompt, withId = false) {
    if (data == null) return;
    set_formdata($.parseJSON(data), form, false, false, null, withId);

    if (prompt) {
        notify("Done Populating the form", 'success', 9000);
    }
}
