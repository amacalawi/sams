var contactTable;
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
    var $contactForm = $('#add-new-contact-form').validate({
        rules: {
            contacts_firstname: 'required',
            contacts_lastname: 'required',
            contacts_street: 'required',
            contacts_brgy: 'required',
            contacts_city: 'required',
            contacts_mobile: 'required',
            contacts_email: {
                'required': true,
                'email': true
            }
        },
        messages: {
            contacts_firstname: {
                'required': "The First Name field is required"
            },
            contacts_lastname: {
                'required': "The Last Name field is required"
            },
            contacts_street: {
                'required': "The Street field is required"
            },
            contacts_brgy: {
                'required': "The Subdivision/Brgy field is required"
            },
            contacts_city: {
                'required': "The Town / City field is required"
            },
            contacts_mobile: {
                'required': "The Mobile field is required"
            },
            contacts_email: {
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
                            $('#add-new-contact-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            // console.log(k,v);
                        });
                    }
                    else
                    {
                        notify(data.message, data.type, 9000);
                        $('#add-new-contact-form')[0].reset();
                        $('#add-new-contact-form [name=contacts_firstname]').focus();
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
    $('body').on('click', '#delete-contact-btn', function (e) {
        e.preventDefault();
        // console.log(G_selectedRows);
        var url  =  base_url('contacts/remove');
        swal({
            title: "Are you sure?",
            text: "The selected Contacts will be removed from your record",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            closeOnConfirm: false,
        }, function(){
            $.ajax({
                type: 'POST',
                url: url,
                data: {'contacts_id[]': $("#contact-table-command").bootgrid('getSelectedRows')},
                success: function (data) {
                    // console.log(data);
                    var data = $.parseJSON(data);
                    reload_table();
                    swal("Removed", data.contact.message, data.contact.type);
                    G_selectedRows = [];
                    $('#delete-contact-btn').removeClass('show');
                },
            });
        });

    })
    /*
    | --------------------------------------------
    | # Update
    | --------------------------------------------
    */
    $('#edit-contact-form').validate({
        rules: {
            contacts_firstname: 'required',
            contacts_lastname: 'required',
            contacts_street: 'required',
            contacts_brgy: 'required',
            contacts_city: 'required',
            contacts_mobile: 'required',
            contacts_email: {
                'required': true,
                'email': true
            }
        },
        messages: {
            contacts_firstname: {
                'required': "The First Name field is required"
            },
            contacts_lastname: {
                'required': "The Last Name field is required"
            },
            contacts_street: {
                'required': "The Street field is required"
            },
            contacts_brgy: {
                'required': "The Subdivision/Brgy field is required"
            },
            contacts_city: {
                'required': "The Town / City field is required"
            },
            contacts_mobile: {
                'required': "The Mobile field is required"
            },
            contacts_email: {
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
            var contacts_id = $(form).find('[name=contacts_id]').val();
            if( contacts_id === 'AJAX_CALL_ONLY' ) {
                swal("Error", "The Contact's ID is invalid. Please reload the page and try again.", 'error');
                $('[name=contacts_close]').click();
            } else {
                $.ajax({
                    type: 'POST',
                    url: $(form).attr('action') + '/' + contacts_id,
                    data: $(form).serialize(),
                    success: function (data) {
                        data = JSON.parse(data);
                        console.log(data);
                        resetWarningMessages('.form-group-validation');
                        if( data.type != 'success' )
                        {
                            var errors = data.message;
                            $.each(errors, function (k, v) {
                                $('#edit-contact-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            });
                        }
                        else
                        {
                            $('#edit-contact-form').find('button[name=contacts_close]').delay(900).queue(function(next){ $(this).click(); next(); });
                            notify(data.message, data.type, 9000);
                            reload_table();
                            $('#edit-contact-form')[0].reset();
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
    $('#contact-table-command').bootgrid('reload');
}
function init_table() {
    var selectedRowCount = [];
    contactTable = $("#contact-table-command").bootgrid({
        labels: {
            loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
            noResults: 'No Contacts found',
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
                return  '<button role="button" class="wave-effect btn btn-icon command-edit"    data-row-id="' + row.contacts_id + '"><span class="zmdi zmdi-edit"></span></button> ' +
                        '<!--<button type="button" class="wave-effect btn btn-icon command-refresh" data-row-id="' + row.contacts_id + '"><span class="zmdi zmdi-search-for"></span></button>-->' +
                        '<button type="button" class="wave-effect btn btn-icon command-delete"  data-row-id="' + row.contacts_id + '"><span class="zmdi zmdi-delete"></span></button> ' +
                        '<!--<button type="button" class="wave-effect btn btn-icon command-print"   data-row-id="' + row.contacts_id + '"><span class="zmdi zmdi-print"></span></button>-->';
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
            console.log(response);
            return response;
        },
        url: base_url('contacts/listing'),
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
        G_selectedRows.push(rows[0].contacts_id);
        // console.log(selectedRowCount);
        if( selectedRowCount.length >= 2 )
        {
            $('#delete-contact-btn').addClass('show');
        } else {
            $('#delete-contact-btn').removeClass('show');
        }
    }).on("deselected.rs.jquery.bootgrid", function(e, rows)
    {
        selectedRowCount.splice(-1, 1);
        G_selectedRows.splice(-1, 1);

        // console.log(selectedRowCount);
        if( selectedRowCount.length >= 2 )
        {
            $('#delete-contact-btn').addClass('show');
        } else {
            $('#delete-contact-btn').removeClass('show');
        }

    }).on("loaded.rs.jquery.bootgrid", function (e) {
        reload_dom();
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
                    $('#delete-contact-btn').addClass('show');
                } else {
                    $('#delete-contact-btn').removeClass('show');
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
        contactTable.find(".command-edit").on("click", function () {
            var contacts_id = $(this).parents('tr').data('row-id'),
                url = base_url('contacts/edit/' + contacts_id);

            $.ajax({
                type: 'POST',
                url: url,
                data: {contacts_id: contacts_id},
                success: function (data) {
                    var contact = $.parseJSON(data);
                    $('#edit-contact').modal("show");
                    var _form = $('#edit-contact-form');

                    $.each(contact, function (k, v) {
                        _form.find('[name=' + k + ']').val( v ).parent().addClass('fg-toggled');
                        if( k == 'contacts_type' ) reload_selectpickers_key( k, v);
                        if( k == 'contacts_group' ) reload_selectpickers_key( k+"[]", v);
                        if( k == 'contacts_level' ) reload_selectpickers_key( k, v);
                    });
                }
            });
        });

        /*
        | -----------------------------------------------------------
        | # Delete
        | -----------------------------------------------------------
        */
        contactTable.find(".command-delete").on("click", function (e) {
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.contacts_firstname').text(),
                url  = base_url('contacts/remove/' + id);
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: name + " will be trashed.",
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
                        console.log(data);
                        reload_table();
                        swal("Removed", data.contact.message, data.contact.type);
                    }
                });
            });
        });

    });
}