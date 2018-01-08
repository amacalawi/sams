jQuery(document).ready(function ($) {
    $('.input-selectize').each(function (e) {
        var Ds = $(this);
        var Ds_selectize = $(this).selectize({
            plugins: ['remove_button', 'restore_on_backspace'],
            delimiter: $(this).data('selectize-delimiter') ? $(this).data('selectize-delimiter') : ',',
            options: [],
            persist: false,
            maxItems: null,
            // create: function(input) {
            //     console.log(input);
            //     // $('form').find('#phone-field-container').addClass('has-warning').append('<small class="error help-block">Phone is not valid</small>');
            //     return false;
            // },

            valueField: 'name',
            labelField: 'code',
            searchField: ['name', 'code'],

            render: {
                item: function(item, escape) {
                    console.log(item);
                    var caption = item.name ? item.name : item.code;
                    return '<div><span class="name">' + escape(caption) + '</span></div>';
                },
                option: function(item, escape) {
                    console.log(item);
                    var label = item.name || item.code;
                    var caption = item.name ? item.code : null;
                    return '<div>' +
                        '<strong>' + escape(label) + '</strong>' +
                        (caption.length > 1 && caption ? '<div class="caption text-muted">' + escape(caption) + '</div>' : '') +
                    '</div>';
                }
            },
            focus: function (e) {
                console.log(e);
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                var attr = Ds.attr('data-selectize-ajax');
                var url = base_url('schedules/new');
                if (typeof attr !== typeof undefined && attr !== false) {
                    url = attr;
                }
                $.get(url, function (data) {
                    console.log($.parseJSON(data));
                    callback($.parseJSON(data));
                });
            },
        });
    });

    /*
    | ---------------------------------------
    | # Delete Many
    | ---------------------------------------
    */
    $('body').on('click', '#delete-schedule-btn', function (e) {
        e.preventDefault();
        // console.log(G_selectedRows);
        var url  =  base_url('schedule/remove');
        swal({
            title: "Are you sure?",
            text: "The selected Schedule will be removed from your record",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            closeOnConfirm: false,
        }, function(){
            $.ajax({
                type: 'POST',
                url: url,
                data: {'id[]': $("#schedule-table-command").bootgrid('getSelectedRows')},
                success: function (data) {
                    // console.log(data);
                    var data = $.parseJSON(data);
                    reload_table();
                    swal(data.title, data.message, data.type);
                    G_selectedRows = [];
                    $('#delete-schedule-btn').removeClass('show');
                },
            });
        });

    })

    /*
    | --------------------------------------------
    | # Update
    | --------------------------------------------
    */
    $('#edit-schedule-form').validate({
        rules: {
            name: 'required',
        },
        messages: {
            name: {
                'required': "The Name field is required"
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
                swal("Error", "The Schedule's ID is invalid. Please reload the page and try again.", 'error');
                $('[name=close]').click();
            } else {
                $.ajax({
                    type: 'POST',
                    url: $(form).attr('action') + '/' + id,
                    data: $(form).serialize(),
                    success: function (data) {
                        var data = $.parseJSON(data);
                        console.log(data);
                        resetWarningMessages('.form-group-validation');
                        if( data.type != 'success' ) {
                            var errors = data.message;
                            $.each(errors, function (k, v) {
                                $('#edit-schedule-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            });
                        } else {
                            $('#edit-schedule-form').find('button[name=close]').delay(900).queue(function(next){ $(this).click(); next(); });
                            notify(data.message, data.type, 9000);
                            reload_table();
                            $('#edit-schedule-form')[0].reset();
                            reload_selectpickers();
                        }
                    },
                });
            }

        }
    });

	var schedulesTable = $('#schedule-table-command').bootgrid({
		labels: {
            loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
            noResults: 'No Schedules found',
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
        url: base_url('schedules/listing'),
        rowCount: [5, 10, 20, 30, 50, 100, 500, -1],
        keepSelection: true,
        // selection: true,
        // multiSelect: true,
	}).on("loaded.rs.jquery.bootgrid", function (e) {
        $('.trash-count').text(trashCount);
        /*
        | -----------------------------------------------------------
        | # Edit
        | -----------------------------------------------------------
        */
        schedulesTable.find(".command-edit").on("click", function (e) {
            var id = $(this).parents('tr').data('row-id'),
                url = base_url('schedules/edit/' + id);

            $.ajax({
                type: 'POST',
                url: url,
                data: {id: id},
                success: function (data) {
                    var schedule = $.parseJSON(data);
                    if( undefined !== schedule.type && schedule.type == 'error' ) {
                        swal(schedule.title, schedule.message, schedule.type);
                    } else {
                        $('#edit-schedule').modal("show");
                        var _form = $('#edit-schedule-form');
                        _form[0].reset();
                        reload_selectpickers();
                        _form.find('[name=name]').focus();

                        $.each(schedule, function (k, v) {
				console.log(k,v);
                            _form.find('[name=' + k + ']').val( v ).parent().addClass('fg-toggled');
                            // reload_selectpickers(k,v);//_form.find('select[name='+k+']').val(v);
                        });
			reload_selectpickers();
                    }
                }
            });
        });
        /*
        | -----------------------------------------------------------
        | # Delete
        | -----------------------------------------------------------
        */
        schedulesTable.find(".command-delete").on("click", function (e) {
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.name').text(),
                url  = base_url('schedules/remove/' + id);
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

                        if( undefined !== data.type && data.type == 'error' ) {
                            swal("Success", data.message, data.type);
                        } else {
                            reload_table();
                            swal("Removed", data.message, data.type);
                        }

                    }
                });
            });
        });
    });

    $('input[name=name]').on('keyup', function () {
        $('input[name=code]').val( slugify( $(this).val() ) ).parent().addClass('fg-toggled');
    });
});

function reload_table() {
    $('#schedule-table-command').bootgrid('reload');
}
