var privilegeTable;
$(document).ready(function() {
    /*
    | ----------------------------------
    | # Listing
    | ----------------------------------
    */
    init_privilege_table();
    init_add_privilege_table();
    init_edit_privilege_contacts_table();

    /*
    | ----------------------------------
    | # Add New Privilege
    | ----------------------------------
    | # Validate | Submit
    */
    $('#add-new-privilege-btn').click(function () {
       init_add_privilege_table();
    });
    var $privilegeForm = $('#add-new-privilege-form').validate({
        rules: {
            name: 'required',
            code: 'required',
        },
        messages: {
            name: {
                'required': "The Privilege Name field is required"
            },
            code: {
                'required': "The Privilege Code field is required"
            },
        },
        errorElement: 'small',
        errorPlacement: function (error, element) {
            $(error).addClass('help-block');
            $(element).parents('.form-privilege-validation').addClass('has-warning').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.form-privilege-validation').addClass('has-warning');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.form-privilege-validation').removeClass('has-warning');
            $(element).parents('.form-privilege-validation').find('.help-block').remove();
        },
        submitHandler: function (form) {
            var add = {
                type: form.method,
                url: form.action,
                data: $(form).serialize() + "&privileges_contacts=" + $('#contacts-table-command-add').bootgrid('getSelectedRows'),
                success: function (data) {
                    data = JSON.parse(data);
                    resetWarningMessages('.form-privilege-validation');
                    if( data.type !== 'success' )
                    {
                        var errors = data.message;

                        $.each(errors, function (k, v) {
                            $('#add-new-privilege-form').find('input[name='+k+'], select[name='+k+']').parents('.form-privilege-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            console.log(k,v);
                        });
                    }
                    else
                    {
                        // console.log(data);
                        notify(data.message, data.type, 9000);
                        $('#add-new-privilege-form')[0].reset();
                        $('#add-new-privilege-form [name=name]').focus();
                        reload_privilege_table();
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
    | # Update Privilege
    | ----------------------------------
    */
    $('#edit-privilege-form').validate({
        rules: {
            name: 'required',
            code: 'required',
        },
        messages: {
            name: {
                'required': "The Privilege Name field is required"
            },
            code: {
                'required': "The Privilege Code field is required"
            },
        },
        errorElement: 'small',
        errorPlacement: function (error, element) {
            $(error).addClass('help-block');
            $(element).parents('.form-privilege-validation').addClass('has-warning').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.form-privilege-validation').addClass('has-warning');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.form-privilege-validation').removeClass('has-warning');
            $(element).parents('.form-privilege-validation').find('.help-block').remove();
        },
        submitHandler: function (form) {
            var privileges_id = $(form).find('[name=id]').val();
            if( privileges_id === 'AJAX_CALL_ONLY' ) {
                swal("Error", "The Privilege's ID is invalid. Please reload the page and try again.", 'error');
                $('[name=close]').click();
            } else {
                $.ajax({
                    type: 'POST',
                    url: $(form).attr('action') + '/' + privileges_id,
                    data: $(form).serialize(),
                    success: function (data) {
                        data = JSON.parse(data);
                        // console.log(data);
                        resetWarningMessages('.form-privilege-validation');
                        if( data.type != 'success' )
                        {
                            var errors = data.message;
                            $.each(errors, function (k, v) {
                                $('#edit-privilege-form').find('input[name='+k+'], select[name='+k+']').parents('.form-privilege-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            });
                        }
                        else
                        {
                            $('#edit-privilege-form').find('button[name=close]').delay(900).queue(function(next){ $(this).click(); next(); });
                            notify(data.message, data.type, 9000);
                            reload_privilege_table();
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
    $('body').on('click', '#delete-privilege-btn', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "The selected Privileges will be deleted permanently from your record",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            closeOnConfirm: false,
        }, function(){
            $.ajax({
                type: 'POST',
                url: base_url('privileges/remove'),
                data: {'privileges_ids[]': $('#privilege-table-command').bootgrid('getSelectedRows')},
                success: function (data) {
                    var data = $.parseJSON(data);
                    reload_privilege_table();
                    swal("Deleted", data.message, data.type);
                    $('#delete-privilege-btn').removeClass('show');
                },
            });
        });

    });

    /*
    | ----------------------------------
    | # Privilege Code Suggestion
    | ----------------------------------
    */
    $('input[name=name]').on('keyup', function () {
        $('input[name=code]').val( slugify( $(this).val() ) ).parent().addClass('fg-toggled');
    });
});

function reload_privilege_table () {
    $('#privilege-table-command').bootgrid('reload');
    $('.contacts-table-command').bootgrid('reload');
}

function init_privilege_table()
{
    var trashCount = 0;
    var selectedPrivilegeRowCount = [];
    privilegeTable = $("#privilege-table-command").bootgrid({
        labels: {
            loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
            noResults: 'No Privileges found',
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
                return  '<button role="button" class="wave-effect btn btn-icon command-edit"    data-row-id="' + row.privileges_id + '"><span class="zmdi zmdi-edit"></span></button> ' +
                        '<button type="button" class="wave-effect btn btn-icon command-delete"  data-row-id="' + row.privileges_id + '"><span class="zmdi zmdi-delete"></span></button> ';
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
            console.log(response);
            return response;
        },
        url: base_url('privileges/listing'),
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
        selectedPrivilegeRowCount.push(rows);
        if( selectedPrivilegeRowCount.length > 1 )
        {
            $('#delete-privilege-btn').addClass('show');
        } else {
            $('#delete-privilege-btn').removeClass('show');
        }

        var _selectedRows = privilegeTable.bootgrid('getSelectedRows');

        if( _selectedRows.length > 1 )
        {
            $('#delete-privilege-btn').addClass('show');
        }

    }).on("deselected.rs.jquery.bootgrid", function(e, rows)
    {
        selectedPrivilegeRowCount.splice(-1, 1);

        // console.log(selectedPrivilegeRowCount);
        if( selectedPrivilegeRowCount.length > 1 )
        {
            $('#delete-privilege-btn').addClass('show');
        } else {
            $('#delete-privilege-btn').removeClass('show');
        }

        if( _selectedRows.length < 1 )
        {
            $('#delete-privilege-btn').removeClass('show');
        }

    }).on("loaded.rs.jquery.bootgrid", function (e) {
        $('.trash-count').text(trashCount);
        /*
        | -----------------------------------------------------------
        | # Edit
        | -----------------------------------------------------------
        */
        privilegeTable.find(".command-edit").on("click", function () {
            var privileges_id = $(this).parents('tr').data('row-id');
            $.ajax({
                type: 'POST',
                url: base_url('privileges/edit/' + privileges_id),
                data: {privileges_id: privileges_id},
                success: function (data) {
                    var privilege = $.parseJSON(data);
                    console.log(privilege);
                    $('#edit-privilege').modal("show");
                    var _form = $('#edit-privilege-form');

                    $.each(privilege, function (k, v) {
                        _form.find('[name=' + k + ']').val( v ).parent().addClass('fg-toggled');
                        $('select').trigger("chosen:updated");
                    });

                    init_edit_privilege_contacts_table();
                }
            });
        });

        /*
        | -----------------------------------------------------------
        | # Delete
        | -----------------------------------------------------------
        */
        privilegeTable.find(".command-delete").on("click", function (e) {
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.privileges_name').text(),
                url  = base_url('privileges/remove') + '/' + id;
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: name + " will be deleted permanently from your privileges",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                closeOnConfirm: false
            }, function(){
                // on deleting button
                $.ajax({
                    type: 'POST',
                    url: url,
                    success: function (data) {
                        var data = $.parseJSON(data);
                        reload_privilege_table();
                        swal(data.title, data.message, data.type);
                    }
                });
            });
        });
    });
    var _selectedRows = $('#privilege-table-command').bootgrid('getSelectedRows');
}

function init_add_privilege_table () {
    /*
    | ------------------------------------
    | # Add
    | ------------------------------------
    */
    $("#contacts-table-command-add").bootgrid({
        labels: {
            noResults: 'No Contacts found',
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
        url: base_url('contacts/listing'),
        rowCount: [5, 10, 20, 30, 50, 100, -1],
        keepSelection: false,

        selection: true,
        multiSelect: true,
        caseSensitive: false,
    }).on("loaded.rs.jquery.bootgrid", function (e) {
        reload_dom();
    });
}

function init_edit_privilege_contacts_table()
{
    /*
    | ------------------------------------
    | # Edit
    | ------------------------------------
    */
    var contactsTableCommandEdit = $("#contacts-table-command-edit").bootgrid({
        labels: {
            noResults: 'No Contacts found',
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
                return  '<button type="button" data-toggle="tooltip" data-placement="top" title="Add to this Privilege" class="wave-effect btn btn-icon btn-xs command-add" data-row-id="' + row.contacts_id + '"><span class="zmdi zmdi-plus"></span></button> ' +
                        '<button type="button" data-toggle="tooltip" data-placement="top" title="Remove from this Privilege" class="wave-effect btn btn-icon btn-xs command-delete" data-row-id="' + row.contacts_id + '"><span class="zmdi zmdi-close"></span></button> ';
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
        url: base_url( 'contacts/listing' ),
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
                url  = base_url('contacts/update/' + id),
                value= $('#edit-privilege-form').find('[name=id]').val();
            $(this).prop('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: url,
                data: { updating_privilege: 'contacts_privilege', value: value, action: 'add' },
                success: function (data) {
                    console.log(data);
                    var data = $.parseJSON(data);
                    reload_privilege_table();
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
                url  = base_url('contacts/update/' + id),
                value= $('#edit-privilege-form').find('[name=id]').val();
            $(this).prop('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: url,
                data: { updating_privilege: 'contacts_privilege', value: value, action: 'remove' },
                success: function (data) {
                    var data = $.parseJSON(data);
                    reload_privilege_table();
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