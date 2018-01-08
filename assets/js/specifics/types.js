var typeTable;
$(document).ready(function() {
    /*
    | ----------------------------------
    | # Listing
    | ----------------------------------
    */
    init_type_table();
    init_add_type_table();
    init_edit_type_contacts_table();

    /*
    | ----------------------------------
    | # Add New Type
    | ----------------------------------
    | # Validate | Submit
    */
    $('#add-new-type-btn').on('click', function () {
       init_add_type_table();
       $('#add-new-type-form')[0].reset();
       $('#add-new-type-form [name=types_name]').focus();
    });
    var $typeForm = $('#add-new-type-form').validate({
        rules: {
            types_name: 'required',
            types_code: 'required',
        },
        messages: {
            types_name: {
                'required': "The Type Name field is required"
            },
            types_code: {
                'required': "The Type Code field is required"
            },
        },
        errorElement: 'small',
        errorPlacement: function (error, element) {
            $(error).addClass('help-block');
            $(element).parents('.form-type-validation').addClass('has-warning').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.form-type-validation').addClass('has-warning');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.form-type-validation').removeClass('has-warning');
            $(element).parents('.form-type-validation').find('.help-block').remove();
        },
        submitHandler: function (form) {
            var add = {
                type: form.method,
                url: form.action,
                data: $(form).serialize() + "&types_contacts=" + $('#contacts-table-command-add').bootgrid('getSelectedRows'),
                success: function (data) {
                    data = JSON.parse(data);
                    resetWarningMessages('.form-type-validation');
                    if( data.type !== 'success' ) {
                        var errors = data.message;

                        $.each(errors, function (k, v) {
                            $('#add-new-type-form').find('input[name='+k+'], select[name='+k+']').parents('.form-type-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            console.log(k,v);
                        });
                    } else {
                        // console.log(data);
                        notify(data.message, data.type, 9000);
                        $('#add-new-type-form')[0].reset();
                        $('#add-new-type-form [name=types_name]').focus();
                        reload_type_table();
                    }
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
    | # Update Type
    | ----------------------------------
    */
    $('#edit-type-form').validate({
        rules: {
            types_name: 'required',
            types_code: 'required',
        },
        messages: {
            types_name: {
                'required': "The Type Name field is required"
            },
            types_code: {
                'required': "The Type Code field is required"
            },
        },
        errorElement: 'small',
        errorPlacement: function (error, element) {
            $(error).addClass('help-block');
            $(element).parents('.form-type-validation').addClass('has-warning').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.form-type-validation').addClass('has-warning');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.form-type-validation').removeClass('has-warning');
            $(element).parents('.form-type-validation').find('.help-block').remove();
        },
        submitHandler: function (form) {
            var types_id = $(form).find('[name=types_id]').val();
            if( types_id === 'AJAX_CALL_ONLY' ) {
                swal("Error", "The Type's ID is invalid. Please reload the page and try again.", 'error');
                $('[name=types_close]').click();
            } else {
                $.ajax({
                    type: 'POST',
                    url: $(form).attr('action') + '/' + types_id,
                    data: $(form).serialize() + "&types_contacts=" + $('#contacts-table-command-edit').bootgrid('getSelectedRows'),
                    success: function (data) {
                        data = JSON.parse(data);
                        // console.log(data);
                        resetWarningMessages('.form-type-validation');
                        if( data.type != 'success' )
                        {
                            var errors = data.message;
                            $.each(errors, function (k, v) {
                                $('#edit-type-form').find('input[name='+k+'], select[name='+k+']').parents('.form-type-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            });
                        }
                        else
                        {
                            $('#edit-type-form').find('button[name=types_close]').delay(900).queue(function(next){ $(this).click(); next(); });
                            notify(data.message, data.type, 9000);
                            reload_type_table();
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
    $('body').on('click', '#delete-type-btn', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "The selected Types will be deleted permanently from your record",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            closeOnConfirm: false,
        }, function(){
            $.ajax({
                type: 'POST',
                url: base_url('types/delete'),
                data: {'types_ids[]': $('#type-table-command').bootgrid('getSelectedRows')},
                success: function (data) {
                    var data = $.parseJSON(data);
                    reload_type_table();
                    swal("Deleted", data.message, data.type);
                    $('#delete-type-btn').removeClass('show');
                },
            });
        });

    });

    /*
    | ----------------------------------
    | # Type Code Suggestion
    | ----------------------------------
    */
    $('input[name=types_name]').on('keyup', function () {
        $('input[name=types_code]').val( slugify( $(this).val() ) ).parent().addClass('fg-toggled');
    });
});

function reload_type_table () {
    $('#type-table-command').bootgrid('reload');
    $('.contacts-table-command').bootgrid('reload');
}

function init_type_table()
{
    var selectedTypeRowCount = [];
    typeTable = $("#type-table-command").bootgrid({
        labels: {
            loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
            noResults: 'No Types found',
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
        url: base_url('types/listing'),
        rowCount: [5, 10, 20, 30, 50, 100, -1],
        keepSelection: true,

        selection: true,
        multiSelect: true,
        // rowSelect: true,
        caseSensitive: false,
    }).on('appended.rs.jquery.bootgrid', function (e, arr) {
        // console.log(arr);
    }).on("selected.rs.jquery.bootgrid", function(e, rows)
    {
        // console.log(rows);
        selectedTypeRowCount.push(rows);
        if( selectedTypeRowCount.length > 1 )
        {
            $('#delete-type-btn').addClass('show');
        } else {
            $('#delete-type-btn').removeClass('show');
        }

        var _selectedRows = typeTable.bootgrid('getSelectedRows');

        if( _selectedRows.length > 1 )
        {
            $('#delete-type-btn').addClass('show');
        }

    }).on("deselected.rs.jquery.bootgrid", function(e, rows)
    {
        selectedTypeRowCount.splice(-1, 1);

        // console.log(selectedTypeRowCount);
        if( selectedTypeRowCount.length > 1 )
        {
            $('#delete-type-btn').addClass('show');
        } else {
            $('#delete-type-btn').removeClass('show');
        }

        if( _selectedRows.length < 1 )
        {
            $('#delete-type-btn').removeClass('show');
        }

    }).on("loaded.rs.jquery.bootgrid", function (e) {
        /*
        | -----------------------------------------------------------
        | # Edit
        | -----------------------------------------------------------
        */
        typeTable.find(".command-edit").on("click", function () {
            var types_id = $(this).parents('tr').data('row-id');
            $.ajax({
                type: 'POST',
                url: base_url('types/edit/' + types_id),
                data: {types_id: types_id},
                success: function (data) {
                    var type = $.parseJSON(data);
                    $('#edit-type').modal("show");
                    var _form = $('#edit-type-form');

                    $.each(type, function (k, v) {
                        _form.find('[name=' + k + ']').val( v ).parent().addClass('fg-toggled');
                        $('select').trigger("chosen:updated");
                    });

                    init_edit_type_contacts_table();
                }
            });
        });

        /*
        | -----------------------------------------------------------
        | # Delete
        | -----------------------------------------------------------
        */
        typeTable.find(".command-delete").on("click", function (e) {
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.types_name').text(),
                url  = base_url('types/delete/') + '/' + id;
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: name + " will be deleted permanently from your types",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                closeOnConfirm: false
            }, function(){
                // on deleting button
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    success: function (data) {
                        var data = $.parseJSON(data);
                        reload_type_table();
                        swal("Deleted", data.message, data.type);
                    }
                });
            });
        });
    });
    var _selectedRows = $('#type-table-command').bootgrid('getSelectedRows');
}

function init_add_type_table () {
    /*
    | ------------------------------------
    | # Add
    | ------------------------------------
    */
    $("#contacts-table-command-add").bootgrid({
        labels: {
            noResults: 'No Members found',
        },
        css: {
            icon: 'zmdi icon',
            iconColumns: 'zmdi-view-module',
            iconDown: 'zmdi-caret-down',
            iconRefresh: 'zmdi-refresh',
            iconUp: 'zmdi-caret-up',
        },

        ajax: true,
        ajaxSettings: {
            method: "POST",
            cache: true,
        },
        requestHandler: function (request)
        {
            // To accumulate custom parameter with the request object
            // request.customPost = 'anything';
            // console.log(request);
            // request.selectedRows = $('.contacts-table-command').bootgrid('getSelectedRows');
            // console.log('request');
            // console.log(request);
            return request;
        },
        responseHandler: function (response) {
            console.log(response);
            return response;
        },
        url: base_url('members/listing'),
        rowCount: [5, 10, 20, 30, 50, 100, -1],
        keepSelection: false,

        selection: true,
        multiSelect: true,
        caseSensitive: false,
    }).on("loaded.rs.jquery.bootgrid", function (e) {
        reload_dom();
    });
}

function init_edit_type_contacts_table()
{
    /*
    | ------------------------------------
    | # Edit
    | ------------------------------------
    */
    var contactsTableCommandEdit = $("#contacts-table-command-edit").bootgrid({
        labels: {
            noResults: 'No Members found',
        },
        css: {
            icon: 'zmdi icon',
            iconColumns: 'zmdi-view-module',
            iconDown: 'zmdi-expand-more',
            iconRefresh: 'zmdi-refresh',
            iconUp: 'zmdi-expand-less',
        },
        formatters: {
            commands: function (column, row) {
                return  '<button type="button" data-toggle="tooltip" data-placement="top" title="Add to this Type" class="wave-effect btn btn-icon btn-xs command-add" data-row-id="' + row.contacts_id + '"><span class="zmdi zmdi-plus"></span></button> ' +
                        '<button type="button" data-toggle="tooltip" data-placement="top" title="Remove from this Type" class="wave-effect btn btn-icon btn-xs command-delete" data-row-id="' + row.contacts_id + '"><span class="zmdi zmdi-close"></span></button> ';
            }
        },

        ajax: true,
        ajaxSettings: {
            method: "POST",
            cache: true,
        },
        requestHandler: function (request)
        {
            // To accumulate custom parameter with the request object
            return request;
        },
        url: base_url( 'members/listing' ),
        rowCount: [5, 10, 20, 30, 50, 100, -1],

        caseSensitive: false,
    }).on("loaded.rs.jquery.bootgrid", function (e) {
        reload_dom();
        /*
        | -----------------------------------------------------------
        | # Add To List
        | -----------------------------------------------------------
        */
        $("#contacts-table-command-edit").find(".command-add").on('click', function (e) {
            e.preventDefault();
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.contacts_name').text(),
                url  = base_url('members/update/' + id),
                value= $('#edit-type-form').find('[name=types_id]').val();
            $(this).prop('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: url,
                data: { updating_type: 'contacts_type', value: value, action: 'add' },
                success: function (data) {
                    console.log(data);
                    var data = $.parseJSON(data);
                    reload_type_table();
                    if( 'error' == data.type )
                    {
                        swal('Error', data.message, data.type);
                    }
                    else
                    {
                        notify(data.message, data.type);
                    }
                },
                done: function (data) {
                    $(this).prop('disabled', '');
                }
            });
            return false;
        });

        /*
        | -----------------------------------------------------------
        | # Delete From List
        | -----------------------------------------------------------
        */
        $("#contacts-table-command-edit").find(".command-delete").on("click", function (e) {
            e.preventDefault();
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.contacts_name').text(),
                url  = base_url('members/update/' + id),
                value= $('#edit-type-form').find('[name=types_id]').val();
            $(this).prop('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: url,
                data: { updating_type: 'contacts_type', value: value, action: 'remove' },
                success: function (data) {
                    var data = $.parseJSON(data);
                    reload_type_table();
                    if( 'error' == data.type )
                    {
                        swal('Error', data.message, data.type);
                    }
                    else
                    {
                        notify(data.message, data.type);
                    }
                },
                done: function (data) {
                    $(this).prop('disabled', '');
                }
            });
        });
    });
}
