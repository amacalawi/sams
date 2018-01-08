jQuery(document).ready(function (e) {
    /*
    |-------------------------------
    | # Table Init
    |-------------------------------
    */
    init_modules_table();

    /*
    |------------------------------
    | # Add New Module
    |------------------------------
    */
    $('#add-new-module-btn').on('click', function (e) {
        $('#add-new-module-form')[0].reset();
        reload_selectpickers();
        $('#add-new-module-form [name=name]').focus();
    })
    var $moduleForm = $('#add-new-module-form').validate({
        rules: {
            name: 'required',
            slug: 'required',
        },
        messages: {
            name: {
                'required': "The Name field is required"
            },
            slug: {
                'required': "The slug field is required"
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
                    resetWarningMessages('.form-group-validation');
                    if( data.type !== 'success' )
                    {
			console.log("ERROR", data);
                        var errors = data.message;
			if (typeof errors == "string") {
			    swal("Error", data.message, data.type);
			} else {
                            $.each(errors, function (k, v) {
                                $('#add-new-module-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                                // console.log(k,v);
                            });
 			}
                    }
                    else
                    {
                        notify(data.message, data.type, 9000);
                        $('#add-new-module-form')[0].reset();
                        $('#add-new-module-form [name=name]').focus();
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
                success: add.success
            });
        }
    });
    /*
    | ----------------------------------
    | # Update Module
    | ----------------------------------
    */
    $('#edit-module-form').validate({
        rules: {
            name: 'required',
            slug: 'required',
        },
        messages: {
            name: {
                'required': "The Module Name field is required"
            },
            slug: {
                'required': "The Module Slug field is required"
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
            var modules_id = $(form).find('[name=id]').val();
            if( modules_id === 'AJAX_CALL_ONLY' ) {
                swal("Error", "The Module's ID is invalid. Please reload the page and try again.", 'error');
                $('[name=close]').click();
            } else {
                $.ajax({
                    type: 'POST',
                    url: $(form).attr('action') + '/' + modules_id,
                    data: $(form).serialize(),
                    success: function (data) {
                        var data = $.parseJSON(data);
                        console.log(data);
                        resetWarningMessages('.form-group-validation');
                        if( data.type != 'success' )
                        {
                            var errors = data.message;
                            $.each(errors, function (k, v) {
                                $('#edit-module-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            });
                        }
                        else
                        {
                            $('#edit-module-form').find('button[name=close]').delay(900).queue(function(next){ $(this).click(); next(); });
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
    $('body').on('click', '#delete-module-btn', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "The selected Modules will be removed from your record",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            closeOnConfirm: false,
        }, function(){
            $.ajax({
                type: 'POST',
                url: base_url('modules/remove'),
                data: {'id[]': $('#modules-table').bootgrid('getSelectedRows')},
                success: function (data) {
                    var data = $.parseJSON(data);
                    console.log(data);
                    reload_table();
                    swal(data.title, data.message, data.type);
                    $('#delete-module-btn').removeClass('show');
                },
            });
        });

    });
});

function reload_table() {
    jQuery('#modules-table').bootgrid('reload');
}
function init_modules_table() {
    var trashCount = 0;
    var modulesTable = jQuery('#modules-table').bootgrid({
        labels: {
            loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
            noResults: 'No Modules found',
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
                return  '<button role="button" class="wave-effect btn btn-icon command-edit"    data-row-id="' + row.types_id + '"><span class="zmdi zmdi-edit"></span></button> ' +
                        '<button type="button" class="wave-effect btn btn-icon command-delete"  data-row-id="' + row.types_id + '"><span class="zmdi zmdi-delete"></span></button> ';
            },
        },

        ajax: true,
        ajaxSettings: {
            method: "POST",
            cache: false,
        },
        requestHandler: function (request)
        {
            // To accumulate custom parameter with the request object
            // console.log(request);
            return request;
        },
        responseHandler: function (response)
        {
            // To accumulate custom parameter with the response object
            // response.customPost = 'anything';
            // response.current = 2;
            trashCount = response.trash.count;
            // console.log(response);
            return response;
        },
        url: base_url('modules/listing'),
        rowCount: [5, 10, 20, 30, 50, 100, -1],
        keepSelection: true,

        selection: true,
        multiSelect: true,
        // rowSelect: true,
        caseSensitive: false,
    }).on("selected.rs.jquery.bootgrid", function(e, rows)
    {

        if( modulesTable.bootgrid("getSelectedRows").length > 1 ) {
            $('#delete-module-btn').addClass('show');
        } else {
            $('#delete-module-btn').removeClass('show');
        }

    }).on("deselected.rs.jquery.bootgrid", function(e, rows)
    {
        if( modulesTable.bootgrid("getSelectedRows").length > 1 ) {
            $('#delete-module-btn').addClass('show');
        } else {
            $('#delete-module-btn').removeClass('show');
        }

    }).on("loaded.rs.jquery.bootgrid", function (e) {
        reload_dom();
        $('.trash-count').text(trashCount);
        /*
        |---------------------------
        | # Edit a Module
        |---------------------------
        */
        modulesTable.find('.command-edit').on('click', function (e) {
            var id = $(this).parents('tr').data('row-id'),
                url = base_url('modules/edit/' + id);

            $.ajax({
                type: 'POST',
                url: url,
                data: {id: id},
                success: function (data) {
                    var module = $.parseJSON(data);
                    $('#edit-module').modal("show");
                    var _form = $('#edit-module-form');
                    _form[0].reset();
                    reload_selectpickers();
                    _form.find('[name=name]').focus();

                    $.each(module, function (k, v) {
                        _form.find('[name=' + k + ']').val( v ).parent().addClass('fg-toggled');
                    });
                }
            });
        });

        /*
        | -----------------------------------------------------------
        | # Delete
        | -----------------------------------------------------------
        */
        modulesTable.find(".command-delete").on("click", function (e) {
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.name').text(),
                url  = base_url('modules/remove/' + id);
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: name + " will be trashed.",
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
                        console.log(data);
                        reload_table();
                        swal("Removed", data.message, data.type);
                    }
                });
            });
        });

    });
}
