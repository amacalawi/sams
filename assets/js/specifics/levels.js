var levelTable;
$(document).ready(function() {
    /*
    | ----------------------------------
    | # Listing
    | ----------------------------------
    */
    init_level_table();
    init_add_level_table();
    init_edit_level_contacts_table();

    /*
    | ----------------------------------
    | # Add New Level
    | ----------------------------------
    | # Validate | Submit
    */
    $('#add-new-level-btn').click(function () {
       init_add_level_table();
    });
    var $levelForm = $('#add-new-level-form').validate({
        rules: {
            levels_name: 'required',
            levels_code: 'required',
        },
        messages: {
            levels_name: {
                'required': "The Level Name field is required"
            },
            levels_code: {
                'required': "The Level Code field is required"
            },
        },
        errorElement: 'small',
        errorPlacement: function (error, element) {
            $(error).addClass('help-block');
            $(element).parents('.form-level-validation').addClass('has-warning').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.form-level-validation').addClass('has-warning');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.form-level-validation').removeClass('has-warning');
            $(element).parents('.form-level-validation').find('.help-block').remove();
        },
        submitHandler: function (form) {
            var add = {
                type: form.method,
                url: form.action,
                data: $(form).serialize() + "&levels_contacts=" + $('#contacts-table-command-add').bootgrid('getSelectedRows'),
                success: function (data) {
                    data = JSON.parse(data);
                    resetWarningMessages('.form-level-validation');
                    if( data.type !== 'success' )
                    {
                        var errors = data.message;

                        $.each(errors, function (k, v) {
                            $('#add-new-level-form').find('input[name='+k+'], select[name='+k+']').parents('.form-level-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            console.log(k,v);
                        });
                    }
                    else
                    {
                        // console.log(data);
                        notify(data.message, data.type, 9000);
                        $('#add-new-level-form')[0].reset();
                        $('#add-new-level-form [name=levels_name]').focus();
                        reload_level_table();
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
    | # Update Level
    | ----------------------------------
    */
    $('#edit-level-form').validate({
        rules: {
            levels_name: 'required',
            levels_code: 'required',
        },
        messages: {
            levels_name: {
                'required': "The Level Name field is required"
            },
            levels_code: {
                'required': "The Level Code field is required"
            },
        },
        errorElement: 'small',
        errorPlacement: function (error, element) {
            $(error).addClass('help-block');
            $(element).parents('.form-level-validation').addClass('has-warning').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.form-level-validation').addClass('has-warning');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.form-level-validation').removeClass('has-warning');
            $(element).parents('.form-level-validation').find('.help-block').remove();
        },
        submitHandler: function (form) {
            var levels_id = $(form).find('[name=levels_id]').val();
            if( levels_id === 'AJAX_CALL_ONLY' ) {
                swal("Error", "The Level's ID is invalid. Please reload the page and try again.", 'error');
                $('[name=levels_close]').click();
            } else {
                $.ajax({
                    type: 'POST',
                    url: $(form).attr('action') + '/' + levels_id,
                    data: $(form).serialize() + "&levels_contacts=" + $('#contacts-table-command-edit').bootgrid('getSelectedRows'),
                    success: function (data) {
                        data = JSON.parse(data);
                        // console.log(data);
                        resetWarningMessages('.form-level-validation');
                        if( data.type != 'success' )
                        {
                            var errors = data.message;
                            $.each(errors, function (k, v) {
                                $('#edit-level-form').find('input[name='+k+'], select[name='+k+']').parents('.form-level-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            });
                        }
                        else
                        {
                            $('#edit-level-form').find('button[name=levels_close]').delay(900).queue(function(next){ $(this).click(); next(); });
                            notify(data.message, data.type, 9000);
                            reload_level_table();
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
    $('body').on('click', '#delete-level-btn', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "The selected Levels will be deleted permanently from your record",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            closeOnConfirm: false,
        }, function(){
            $.ajax({
                type: 'POST',
                url: base_url('levels/delete'),
                data: {'levels_ids[]': $('#level-table-command').bootgrid('getSelectedRows')},
                success: function (data) {
                    var data = $.parseJSON(data);
                    reload_level_table();
                    swal("Deleted", data.message, data.type);
                    $('#delete-level-btn').removeClass('show');
                },
            });
        });

    });

    /*
    | ----------------------------------
    | # Level Code Suggestion
    | ----------------------------------
    */
    $('input[name=levels_name]').on('keyup', function () {
        $('input[name=levels_code]').val( slugify( $(this).val() ) ).parent().addClass('fg-toggled');
    });
});

function reload_level_table () {
    $('#level-table-command').bootgrid('reload');
    $('.contacts-table-command').bootgrid('reload');
}

function init_level_table()
{
    var selectedLevelRowCount = [];
    levelTable = $("#level-table-command").bootgrid({
        labels: {
            loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
            noResults: 'No Levels found',
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
                return  '<button role="button" class="wave-effect btn btn-icon command-edit"    data-row-id="' + row.levels_id + '"><span class="zmdi zmdi-edit"></span></button> ' +
                        '<button type="button" class="wave-effect btn btn-icon command-delete"  data-row-id="' + row.levels_id + '"><span class="zmdi zmdi-delete"></span></button> ';
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
        url: base_url('levels/listing'),
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
        selectedLevelRowCount.push(rows);
        if( selectedLevelRowCount.length > 1 )
        {
            $('#delete-level-btn').addClass('show');
        } else {
            $('#delete-level-btn').removeClass('show');
        }

        var _selectedRows = levelTable.bootgrid('getSelectedRows');

        if( _selectedRows.length > 1 )
        {
            $('#delete-level-btn').addClass('show');
        }

    }).on("deselected.rs.jquery.bootgrid", function(e, rows)
    {
        selectedLevelRowCount.splice(-1, 1);

        // console.log(selectedLevelRowCount);
        if( selectedLevelRowCount.length > 1 )
        {
            $('#delete-level-btn').addClass('show');
        } else {
            $('#delete-level-btn').removeClass('show');
        }

        if( _selectedRows.length < 1 )
        {
            $('#delete-level-btn').removeClass('show');
        }

    }).on("loaded.rs.jquery.bootgrid", function (e) {
        /*
        | -----------------------------------------------------------
        | # Edit
        | -----------------------------------------------------------
        */
        levelTable.find(".command-edit").on("click", function () {
            var levels_id = $(this).parents('tr').data('row-id');
            $.ajax({
                type: 'POST',
                url: base_url('levels/edit/' + levels_id),
                data: {levels_id: levels_id},
                success: function (data) {
                    var level = $.parseJSON(data);
                    $('#edit-level').modal("show");
                    var _form = $('#edit-level-form');

                    $.each(level, function (k, v) {
                        _form.find('[name=' + k + ']').val( v ).parent().addClass('fg-toggled');
                        $('select').trigger("chosen:updated");
                    });

                    init_edit_level_contacts_table();
                }
            });
        });

        /*
        | -----------------------------------------------------------
        | # Delete
        | -----------------------------------------------------------
        */
        levelTable.find(".command-delete").on("click", function (e) {
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.levels_name').text(),
                url  = base_url('levels/delete/') + '/' + id;
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: name + " will be deleted permanently from your levels",
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
                        reload_level_table();
                        swal("Deleted", data.message, data.type);
                    }
                });
            });
        });
    });
    var _selectedRows = $('#level-table-command').bootgrid('getSelectedRows');
}

function init_add_level_table () {
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

function init_edit_level_contacts_table()
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
                return  '<button type="button" data-toggle="tooltip" data-placement="top" title="Add to this Level" class="wave-effect btn btn-icon btn-xs command-add" data-row-id="' + row.contacts_id + '"><span class="zmdi zmdi-plus"></span></button> ' +
                        '<button type="button" data-toggle="tooltip" data-placement="top" title="Remove from this Level" class="wave-effect btn btn-icon btn-xs command-delete" data-row-id="' + row.contacts_id + '"><span class="zmdi zmdi-close"></span></button> ';
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
                value= $('#edit-level-form').find('[name=levels_id]').val();
            $(this).prop('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: url,
                data: { updating_level: 'contacts_level', value: value, action: 'add' },
                success: function (data) {
                    console.log(data);
                    var data = $.parseJSON(data);
                    reload_level_table();
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
                value= $('#edit-level-form').find('[name=levels_id]').val();
            $(this).prop('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: url,
                data: { updating_level: 'contacts_level', value: value, action: 'remove' },
                success: function (data) {
                    var data = $.parseJSON(data);
                    reload_level_table();
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
