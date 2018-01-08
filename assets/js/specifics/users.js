var userTable;
var G_selectedRows = [];
$(document).ready(function(){
    /*
    | --------------------------------------------
    | # Listing
    | --------------------------------------------
    */
    init_table();

    /*
    | ---------------------------------------
    | # Global Buttons | New | Delete
    | ---------------------------------------
    | #
    | #
    */


    /*
    | --------------------------------------------
    | # Add
    | --------------------------------------------
    | # Validate | Submit
    */
    var $userForm = $('#add-new-user-form').validate({
       rules: {
            privilege: 'required',
            privilege_level: 'required',
            username: 'required',
            password: 'required',
            email: {
                'required': true,
                'email': true,
            },
        },
        messages: {
            privilege: {
                'required': "The Privilege field is required"
            },
            privilege_level: {
                'required': "The Privilege Level field is required"
            },
            username: {
                'required': "The Username is required"
            },
            password: {
                'required': "The Password field is required"
            },
            email: {
                'required': "The Email field is required",
                'email': "The Email field must contain a valid email address"
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
                    data = JSON.parse(data);
                    resetWarningMessages('.form-group-validation');
                    if( data.type !== 'success' )
                    {
                        var errors = data.message;

                        $.each(errors, function (k, v) {
                            $('#add-new-user-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            reload_selectpickers();
                        });
                    }
                    else
                    {
                        notify(data.message, data.type, 9000);
                        $('#add-new-user-form')[0].reset();
                        $('#add-new-user-form [name=username]').focus();
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
    })
    /*
    | ---------------------------------------
    | # Delete Many
    | ---------------------------------------
    */
    $('body').on('click', '#delete-user-btn', function (e) {
        e.preventDefault();
        // console.log(G_selectedRows);
        var url  =  base_url('users/delete');
        swal({
            title: "Are you sure?",
            text: "The selected Users will be deleted permanently from your record",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            closeOnConfirm: false,
        }, function(){
            $.ajax({
                type: 'POST',
                url: url,
                data: {'users_id[]': G_selectedRows},
                success: function (data) {
                    // console.log(data);
                    var data = $.parseJSON(data);
                    reload_table();
                    swal("Deleted", data.message, data.type);
                    G_selectedRows = [];
                    $('#delete-user-btn').removeClass('show');
                },
            });
        });

    })
    /*
    | --------------------------------------------
    | # Update
    | --------------------------------------------
    */
    $('#edit-user-form').validate({
        rules: {
            username: 'required',
            privilege: 'required',
            privilege_level: 'required',
            email: {
                'required': true,
                'email': true
            }
        },
        messages: {
            username: {
                'required': "The Username field is required"
            },
            privilege: {
                'required': "The Privilege field is required"
            },
            privilege_level: {
                'required': "The Privilege Level field is required"
            },
            email: {
                'required': "The Email field is required",
                'email': "The Email field must contain a valid email address"
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
            var users_id = $(form).find('[name=id]').val();
            if( users_id === 'AJAX_CALL_ONLY' ) {
                swal("Error", "The User's ID is invalid. Please reload the page and try again.", 'error');
                $('[name=close]').click();
            } else {
                $.ajax({
                    type: 'POST',
                    url: $(form).attr('action') + '/' + users_id,
                    data: $(form).serialize(),
                    success: function (data) {
                        data = JSON.parse(data);
                        console.log(data);
                        resetWarningMessages('.form-group-validation');
                        if( data.type != 'success' ) {
                            var errors = data.message;
                            $.each(errors, function(k, v) {
                                $('#edit-user-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            });
                        } else {
                            $('#edit-user-form').find('button[name=close]').delay(900).queue(function(next){ $(this).click(); next(); });
                            notify(data.message, data.type, 9000);
                            reload_table();
                            $('#edit-user-form')[0].reset();
                            reload_selectpickers();
                        }
                    },
                });
            }

        }
    });

});


function reload_table()
{
    $('#user-table-command').bootgrid('reload');
}
function init_table() {
    var trashCount = 0;
    var selectedRowCount = [];
    userTable = $("#user-table-command").bootgrid({
        labels: {
            loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
            noResults: 'No Users found',
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
                return  '<button role="button" class="wave-effect btn btn-icon command-edit"    data-row-id="' + row.users_id + '"><span class="zmdi zmdi-edit"></span></button> ' +
                        '<!--<button type="button" class="wave-effect btn btn-icon command-refresh" data-row-id="' + row.users_id + '"><span class="zmdi zmdi-search-for"></span></button>-->' +
                        '<button type="button" class="wave-effect btn btn-icon command-delete"  data-row-id="' + row.users_id + '"><span class="zmdi zmdi-delete"></span></button> ' +
                        '<!--<button type="button" class="wave-effect btn btn-icon command-print"   data-row-id="' + row.users_id + '"><span class="zmdi zmdi-print"></span></button>-->';
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
        url: base_url('users/listing'),
        rowCount: [5, 10, 20, 30, 50, 100, -1],
        keepSelection: true,

        selection: true,
        multiSelect: true,

        caseSensitive: false,
    }).on('appended.rs.jquery.bootgrid', function (e, arr) {
        // console.log(arr);
    }).on("selected.rs.jquery.bootgrid", function(e, rows)
    {
        selectedRowCount.push(rows);
        G_selectedRows.push(rows[0].users_id);
        // console.log(selectedRowCount);
        if( selectedRowCount.length >= 2 )
        {
            $('#delete-user-btn').addClass('show');
        } else {
            $('#delete-user-btn').removeClass('show');
        }
    }).on("deselected.rs.jquery.bootgrid", function(e, rows)
    {
        selectedRowCount.splice(-1, 1);
        G_selectedRows.splice(-1, 1);

        // console.log(selectedRowCount);
        if( selectedRowCount.length >= 2 )
        {
            $('#delete-user-btn').addClass('show');
        } else {
            $('#delete-user-btn').removeClass('show');
        }

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
            if( select_all > 0 )
            {
                selectedRowCount.push(1);
                if( selectedRowCount.length >= 2 )
                {
                    $('#delete-user-btn').addClass('show');
                } else {
                    $('#delete-user-btn').removeClass('show');
                }
            } else {
                selectedRowCount.splice(-1, selectedRowCount.length-1);
            }
        });
        /*
        | -----------------------------------------------------------
        | # Edit
        | -----------------------------------------------------------
        */
        userTable.find(".command-edit").on("click", function () {
            var users_id = $(this).parents('tr').data('row-id'),
                url = base_url('users/edit/' + users_id);

            $.ajax({
                type: 'POST',
                url: url,
                data: {users_id: users_id},
                success: function (data) {
                    var user = $.parseJSON(data);
                    $('#edit-user').modal("show");
                    var _form = $('#edit-user-form');

                    $.each(user, function (k, v) {
                        _form.find('[name=' + k + ']').val( v ).parent().addClass('fg-toggled');
                        reload_selectpickers_key( k, v);
                    });
                }
            });
        });

        /*
        | -----------------------------------------------------------
        | # Delete
        | -----------------------------------------------------------
        */
        userTable.find(".command-delete").on("click", function (e) {
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.users_firstname').text(),
                url  = base_url('users/delete/' + id);
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: name + " will be deleted permanently from your users",
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
                        reload_table();
                        swal("Deleted", data.message, data.type);
                    }
                });
            });
        });

    });
}