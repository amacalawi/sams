jQuery(document).ready(function (e) {
    /*
    |-------------------------------
    | # Table Init
    |-------------------------------
    */
    init_privileges_levels_table();

    /*
    |-------------------------------
    | # Add Privileges Levels
    |-------------------------------
    */
   $('#add-new-privileges-level-btn').click(function (e) {
       $('#add-new-privileges-level-form')[0].reset();
       reload_selectpickers();
   })
    $('#add-new-privileges-level-form').validate({
        rules: {
            name: 'required',
            code: 'required',
            'modules[]': 'required',
        },
        messages: {
            name: {
                'required': "The Privileges Level Name field is required"
            },
            code: {
                'required': "The Privileges Level Code field is required"
            },
            'modules[]': {
                'required': "The Modules need to be specified"
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
            var add = {
                type: form.method,
                url: form.action,
                data: $(form).serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    resetWarningMessages('.form-group-validation');
                    console.log(data);
                    if( data.type !== 'success' )
                    {
                        var errors = data.message;

                        $.each(errors, function (k, v) {
                            $('#add-new-privileges-level-form').find('input[name="'+k+'"], select[name="'+k+'"]').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            // console.log(k,v);
                        });
                    }
                    else
                    {
                        // console.log(data);
                        notify(data.message, data.type, 9000);
                        $('#add-new-privileges-level-form')[0].reset();
                        $('#add-new-privileges-level-form [name=name]').focus();
                        reload_selectpickers();
                        reload_privileges_levels_table();
                    }
                },
                // dataType: 'html',
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
    | --------------------------------------------
    | # Update
    | --------------------------------------------
    */
    $('#edit-privileges-level-form').validate({
        rules: {
            name: 'required',
            code: 'required',
            modules: 'required',
        },
        messages: {
            name: {
                'required': "The First Name field is required"
            },
            code: {
                'required': "The Last Name field is required"
            },
            modules: {
                'required': "The Subdivision/Brgy field is required"
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
            var id = $(form).find('[name=id]').val();
            if( id === 'AJAX_CALL_ONLY' ) {
                swal("Error", "The Privileges Level's ID is invalid. Please reload the page and try again.", 'error');
                $('[name=close]').click();
            } else {
                $.ajax({
                    type: 'POST',
                    url: $(form).attr('action') + '/' + id,
                    data: $(form).serialize(),
                    success: function (data) {
                        data = JSON.parse(data);
                        console.log(data);
                        resetWarningMessages('.form-group-validation');
                        if( data.type != 'success' ) {
                            var errors = data.message;
                            $.each(errors, function (k, v) {
                                $('#edit-privileges-level-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            });
                        } else {
                            $('#edit-privileges-level-form').find('button[name=close]').delay(900).queue(function(next){ $(this).click(); next(); });
                            notify(data.message, data.type, 9000);
                            reload_privileges_levels_table();
                            $('#edit-privileges-level-form')[0].reset();
                            reload_selectpickers();
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
    $('body').on('click', '#delete-privileges-level-btn', function (e) {
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
                url: base_url('privileges-levels/remove'),
                data: {'id[]': $('#privileges-levels-table').bootgrid('getSelectedRows')},
                success: function (data) {
                    var data = $.parseJSON(data);
                    reload_privileges_levels_table();
                    swal("Deleted", data.message, data.type);
                    $('#delete-privileges-level-btn').removeClass('show');
                },
            });
        });

    });

    /*
    | -------------------------------------
    | # Privileges Levels Code Suggestion
    | -------------------------------------
    */
    $('input[name=name]').on('keyup', function () {
        $('input[name=code]').val( slugify( $(this).val() ) ).parent().addClass('fg-toggled');
    });
});

function reload_privileges_levels_table() {
    jQuery('#privileges-levels-table').bootgrid('reload');
}
function init_privileges_levels_table() {
    var trashCount = 0;
    var privilegesLevelTable =jQuery('#privileges-levels-table').bootgrid({
        labels: {
            loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
            noResults: 'No Privileges Levels found',
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
            console.log(response);
            return response;
        },
        url: base_url('privileges-levels/listing'),
        rowCount: [5, 10, 20, 30, 50, 100, -1],
        keepSelection: true,

        selection: true,
        multiSelect: true,
        // rowSelect: true,
        caseSensitive: false,
    }).on("selected.rs.jquery.bootgrid", function(e, rows) {
        // console.log(rows);
        console.log(privilegesLevelTable.bootgrid('getSelectedRows').length);
        if ( privilegesLevelTable.bootgrid('getSelectedRows').length > 1 ) {
            $('#delete-privileges-level-btn').addClass('show');
        } else {
            $('#delete-privileges-level-btn').removeClass('show');
        }
    }).on("deselected.rs.jquery.bootgrid", function(e, rows) {
        // console.log(rows);
        if ( privilegesLevelTable.bootgrid('getSelectedRows').length > 1 ) {
            $('#delete-privileges-level-btn').addClass('show');
        } else {
            $('#delete-privileges-level-btn').removeClass('show');
        }
    }).on("loaded.rs.jquery.bootgrid", function (e) {
        reload_dom();
        $('.trash-count').text(trashCount);

        /*
        | -----------------------------------------------------------
        | # Edit
        | -----------------------------------------------------------
        */
        privilegesLevelTable.find(".command-edit").on("click", function () {
            var id = $(this).parents('tr').data('row-id');
            $.ajax({
                type: 'POST',
                url: base_url('privileges-levels/edit/' + id),
                data: {id: id},
                success: function (data) {
                    var privilege = $.parseJSON(data);
                    $('#edit-privileges-level').modal("show");
                    var _form = $('#edit-privileges-level-form');

                    $.each(privilege, function (k, v) {
                        _form.find('[name="' + k + '"]').val( v ).parent().addClass('fg-toggled');
                        reload_selectpickers_key(k, v);
                        console.log(k, v);
                        $('select').trigger("chosen:updated");
                        if( k == "modules" ) reload_selectpickers_key(k+"[]", v);
                    });
                }
            });
        });

        /*
        | -----------------------------------------------------------
        | # Delete
        | -----------------------------------------------------------
        */
        privilegesLevelTable.find(".command-delete").on("click", function (e) {
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.name').text(),
                url  = base_url('privileges-levels/remove') + '/' + id;
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: name + " will be deleted permanently from your Privileges Levels",
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
                        reload_privileges_levels_table();
                        swal(data.title, data.message, data.type);
                    }
                });
            });
        });

    });
}