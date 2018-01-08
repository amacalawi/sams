jQuery(document).ready(function ($) {
	init_bootgrid();
	
    var $updateForm = $('#edit-message-template-form').validate({
        rules: {
            name: 'required',
            code: 'required',
        },
        messages: {
            name: {
                'required': "The Name field is required"
            },
            code: {
                'required': "The Code field is required"
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
            var types_id = $(form).find('[name=id]').val();
            if( types_id === 'AJAX_CALL_ONLY' ) {
                swal("Error", "The Template's ID is invalid. Please reload the page and try again.", 'error');
                $('[name=close]').click();
            } else {
                $.ajax({
                    type: 'POST',
                    url: $(form).attr('action') + '/' + types_id,
                    data: $(form).serialize(),
                    success: function (data) {
                        data = JSON.parse(data);
                        // console.log(data);
                        resetWarningMessages('.form-group-validation');
                        if( data.type != 'success' )
                        {
                            var errors = data.message;
                            $.each(errors, function (k, v) {
                                $('#edit-message-template-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            });
                        }
                        else
                        {
                            $('#edit-message-template-form').find('button[name=close]').delay(900).queue(function(next){ $(this).click(); next(); });
                            notify(data.message, data.type, 9000);
                            reload_table();
                        }
                    },
                });
            }
        }
    });
    
    /*
    | ---------------------------------------
    | # Delete Many
    | ---------------------------------------
    */
    $('body').on('click', '#delete-message-template-btn', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "The selected Templates will be removed from your record",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Remove",
            closeOnConfirm: false,
        }, function(){
            $.ajax({
                type: 'POST',
                url: base_url('messaging/templates/remove'),
                data: {'ids[]': $('#preset-message-table-command').bootgrid('getSelectedRows')},
                success: function (data) {
                    var data = $.parseJSON(data);
                    reload_table();
                    swal("Removed", data.message, data.type);
                    $('#delete-message-template-btn').removeClass('show');
                },
            });
        });

    });

	/*
    | ----------------------------------
    | # Type Code Suggestion
    | ----------------------------------
    */
    $('input[name=name]').on('keyup', function () {
        $('input[name=code]').val( slugify( $(this).val() ) ).parent().addClass('fg-toggled');
    });

    $('#add-new-message-template-form').validate({
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
            $.ajax({
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
                            $('#add-new-message-template-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            // console.log(k,v);
                        });
                    }
                    else
                    {
                        notify(data.message, data.type, 9000);
                        $('#add-new-message-template-form')[0].reset();
                        $('#add-new-message-template-form [name=name]').focus();
                        reload_table();
                        reload_selectpickers();
                    }
                    console.log(data);
                },
                dataType: 'html',
            });
            return false;
        }
    });
});

function reload_table() {
	$('#message-templates-command').bootgrid('reload');
}

function init_bootgrid() {
	var trashCount = 0;
    msgTemplatesTable = $("#message-templates-command").bootgrid({
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
        url: base_url('messaging/templates/listing'),
        rowCount: [5, 10, 20, 30, 50, 100, 500, -1],
        keepSelection: true,

        selection: true,
        multiSelect: true,

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
            	if (msgTemplatesTablea.bootgrid('getSelectedRows') >= 2) {
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
        msgTemplatesTable.find(".command-edit").on("click", function (e) {
            var id = $(this).parents('tr').data('row-id'),
                url = base_url('messaging/templates/edit/' + id);

            $.ajax({
                type: 'POST',
                url: url,
                data: {id: id},
                success: function (data) {
                    var template = $.parseJSON(data);
                    if( undefined !== template.type && template.type == 'error' ) {
                        swal(template.title, template.message, template.type);
                    } else {
                        $('#edit-template').modal("show");
                        var _form = $('#edit-message-template-form');
                        _form[0].reset();
                        reload_selectpickers();
                        _form.find('[name=name]').focus();

                        $.each(template, function (k, v) {
                            _form.find('[name=' + k + ']').val( v ).parent().addClass('fg-toggled');
                            if ( k == 'name' ) reload_selectpickers_key( k, v);
                            if ( k == 'code' ) reload_selectpickers_key( k+"[]", v);
                        });
                    }
                }
            });
        });

        /*
        | -----------------------------------------------------------
        | # Delete
        | -----------------------------------------------------------
        */
        msgTemplatesTable.find(".command-delete").on("click", function (e) {
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.groups_name').text(),
                url  = base_url('messaging/templates/remove') + '/' + id;
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
