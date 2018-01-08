jQuery(document).ready(function ($) {
    init_keys();
    init_table();
    add_resource();
    update_resource();
});

function init_keys() {
    $('button.keys-add-stud_no').on('click', function (e) {
        $('#add-new-preset-message-form [name=name]').val($('#add-new-preset-message-form [name=name]').val() + "<STUD_NO>");
        $('#edit-preset-message-form [name=name]').val($('#edit-preset-message-form [name=name]').val() + "<STUD_NO>");
        // e.preventDefault();
    });
    $('button.keys-add-stud_name').on('click', function (e) {
        $('#add-new-preset-message-form [name=name]').val($('#add-new-preset-message-form [name=name]').val() + "<STUD_NAME>");
        $('#edit-preset-message-form [name=name]').val($('#edit-preset-message-form [name=name]').val() + "<STUD_NAME>");
        // e.preventDefault();
    });
    $('button.keys-add-date').on('click', function (e) {
        $('#add-new-preset-message-form [name=name]').val($('#add-new-preset-message-form [name=name]').val() + "<DATE>");
        $('#edit-preset-message-form [name=name]').val($('#edit-preset-message-form [name=name]').val() + "<DATE>");
        // e.preventDefault();
    });
    $('button.keys-add-time').on('click', function (e) {
        $('#add-new-preset-message-form [name=name]').val($('#add-new-preset-message-form [name=name]').val() + "<TIME>");
        $('#edit-preset-message-form [name=name]').val($('#edit-preset-message-form [name=name]').val() + "<TIME>");
        // e.preventDefault();
    });
}

function add_resource() {
    var $addForm = $('#add-new-preset-message-form').validate({
        rules: {
            name: 'required',
            code: 'required',
        },
        messages: {
            name: {
                'required': "The Message is required"
            },
            code: {
                'required': "The code is required"
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
            var add = {
                type: form.method,
                url: form.action,
                data: $(form).serialize(),
                success: function (data) {
                    var data = $.parseJSON(data);
                    console.log(data);
                    resetWarningMessages('.form-group-validation');
                    if( data.type !== 'success' )
                    {
                        var errors = data.message;

                        $.each(errors, function (k, v) {
                            $('#add-new-preset-message-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            // console.log(k,v);
                        });
                    }
                    else
                    {
                        notify(data.message, data.type, 9000);
                        $('#add-new-preset-message-form')[0].reset();
                        $('#add-new-preset-message-form [name=name]').focus();
                        reload_table();
                        reload_selectpickers();
                    }
                    console.log(data);
                },
                dataType: 'html',
            };
            $.ajax({
                type: add.type,
                url: add.url,
                data: add.data,
                success: add.success,
            });
        }
    });
}

function reload_table() {
    $("#preset-message-table-command").bootgrid('reload');
}

function init_table() {
    var trashCount = 0;
    var presetMsgTable = $("#preset-message-table-command").bootgrid({
        labels: {
            loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
            noResults: 'No Templates found',
        },
        css: {
            icon: 'zmdi icon',
            iconColumns: 'zmdi-view-module',
            iconDown: 'zmdi-caret-down',
            iconRefresh: 'zmdi-refresh',
            iconUp: 'zmdi-caret-up',
        },
        formatters: {
            commands: function (column, row) {
                return  '<button role="button" class="wave-effect btn btn-icon command-edit"    data-row-id="' + row.id + '"><span class="zmdi zmdi-edit"></span></button> ' +
                        '<button type="button" class="wave-effect btn btn-icon command-delete"  data-row-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></button> ';
            }
        },

        ajax: true,
        ajaxSettings: {
            method: "POST",
            cache: false
        },
        requestHandler: function (request)
        {
            // To accumulate custom parameter with the request object
            // request.customPost = 'anything';
            // request.current = 2;
            console.log(request);
            return request;
        },
        responseHandler: function (response)
        {
            // To accumulate custom parameter with the response object
            // response.customPost = 'anything';
            // response.current = 2;
            trashCount = response.trash.count;
            console.log(response);
            return response;
        },
        url: base_url('schedules/preset-messages/listing'),
        rowCount: [5, 10, 20, 30, 50, 100, 500, -1],
        keepSelection: true,

        // selection: true,
        // multiSelect: true,

        caseSensitive: false,
    }).on("loaded.rs.jquery.bootgrid", function (e) {
        reload_dom();
        $('.trash-count').text(trashCount);
        /*
        | ---------------------------------
        | # Checkbox
        | ---------------------------------
        */
        $('.select-box[value="all"]').click(function(){
            var select_all = $('.select-box[value="all"]:checked').length;
            if (select_all > 0) {
                if (presetMsgTablea.bootgrid('getSelectedRows') >= 2) {
                    $('#delete-template-btn').addClass('show');
                } else {
                    $('#delete-template-btn').removeClass('show');
                }
            }
        });

        /*
        | -----------------------------------------------------------
        | # Edit
        | -----------------------------------------------------------
        */
        presetMsgTable.find(".command-edit").on("click", function (e) {
            var id = $(this).parents('tr').data('row-id'),
                url = base_url('schedules/preset-messages/edit/' + id);
                // console.log(url);
            $.ajax({
                type: 'POST',
                url: url,
                data: {id: id},
                success: function (data) {
                    var presetMessage = $.parseJSON(data);
                    console.log(data);
                    if( undefined !== presetMessage.type && presetMessage.type == 'error' ) {
                        swal(presetMessage.title, presetMessage.message, presetMessage.type);
                    } else {
                        $('#edit-preset-message').modal("show");
                        var _form = $('#edit-preset-message-form');
                        // _form[0].reset();

                        $.each(presetMessage, function (k, v) {
                            _form.find('[name=' + k + ']').val( v ).parent().addClass('fg-toggled');
                            // if ( k == 'name' ) reload_selectpickers_key( k, v);
                        });
                        reload_selectpickers();
                        _form.find('[name=name]').focus();
                    }
                }
            });
        });

        /*
        | -----------------------------------------------------------
        | # Delete
        | -----------------------------------------------------------
        */
        presetMsgTable.find(".command-delete").on("click", function (e) {
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.groups_name').text(),
                url  = base_url('schedules/preset-messages/remove') + '/' + id;
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: name + " will be trashed",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Remove",
                closeOnConfirm: false
            }, function(){
                // on deleting button
                $.ajax({
                    type: 'POST',
                    url: url,
                    success: function (data) {
                        var data = $.parseJSON(data);
                        reload_table();
                        swal(data.title, data.message, data.type);
                    }
                });
            });
        });

    });
}

function update_resource() {
    $('#edit-preset-message-form').validate({
        rules: {
            name: 'required',
        },
        messages: {
            name: {
                required: "The First Name field is required"
            }
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
            var id = $(form).find('[name=id]').val();
            if( id === 'AJAX_CALL_ONLY' ) {
                swal("Error", "The Member's ID is invalid. Please reload the page and try again.", 'error');
                $('[name=close]').click();
            } else {
                $.ajax({
                    type: 'POST',
                    url: $(form).attr('action') + '/' + id,
                    data: $(form).serialize(),
                    success: function (data) {
                        var data = JSON.parse(data);
                        console.log(data);
                        resetWarningMessages('.form-group-validation');
                        if( data.type != 'success' ) {
                            var errors = data.message;
                            $.each(errors, function (k, v) {
                                $('#edit-preset-message-form').find('input[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            });
                        } else {
                            $('#edit-preset-message-form').find('button[name=close]').delay(900).queue(function(next){ $(this).click(); next(); });
                            notify(data.message, data.type, 9000);
                            reload_table();
                            $('#edit-preset-message-form')[0].reset();
                            // reload_selectpickers();
                        }
                    },
                });
            }

        }
    });
}