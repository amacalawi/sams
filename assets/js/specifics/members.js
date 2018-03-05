var memberTable;
var G_selectedRows = [];
$(document).ready(function(){



    // Student Number
    $('.input-selectize').each(function (e) {
        var $this = $(this);
        $this.selectize({
            // plugins: ['restore_on_backspace'],
            options: [],
            persist: false,
            maxItems: 1,
            create: function(input) {
                return {
                    value: input,
                    name: input
                };
            },
            valueField: 'name',
            labelField: 'name',
            searchField: ['name'],
            // render: {
            //     option: function (item, escape) {
            //         return escape(item.name);
            //     }
            // }
        });

        var $dis = $('[data-checker]');
        var $x = $('input[name=enrollment_status]:checked').val();
        if ($x == 'NEW') {
                $dis.hide();
        } else {
            $dis.show();
        }
        
        $('input[name=enrollment_status]').on('click', function (e) {
            if ($(this).val() == 'NEW') {
                $dis.hide();
            } else {
                $dis.show();
            }
        });

        $('[data-checker]').on('click', function () {
            var $this = $($(this).data('target'));
            notify("Checking, please wait...", 'info', 9000);
            var val = $this.val();
            var url = base_url('members/search/student?stud_no='+val);
            $.get(url, function (data) {
                console.log(data);
                if (data !== null || data !== 'null') {
                    $('#old_status').click();
                    pop_form('#add-new-member-form', data, false);
                } else {
                    pop_form('#add-new-member-form', {}, false);
                }
            });
        });
    });

    /*
    |---------------------------------------------
    | # Upload
    |---------------------------------------------
    */
    // Dropzone.options.myAwesomeDropzone = false;
    // Dropzone.autoDiscover = false;  

    // $('#dropzoneMember').dropzone({
    //     acceptedFiles: 'image/*',
    //     maxFiles: 1,
    //     init: function () {
    //         // this.on("processing", function(file) {
    //         //    this.options.url = base_url + 'job-element/upload-elements/'+ base_line;
    //         // }).on("queuecomplete", function (file, response) {
    //         //     $.job_element.reload();
    //         // });  
    //         this.on("error", function(file){if (!file.accepted) this.removeFile(file);});            
    //      }
    // });


    // Dropzone.autoDiscover = false;

    var myDropzone = window.Dropzone.options.dropzoneMember = {
        maxFiles: 1,
        // url: base_url("members/upload_photo/") + $('body #edit-member-form').find('input[type="text"][name="stud_no"]').val(),
        acceptedFiles: 'image/*',
        addRemoveLinks: true,
        init: function () {
            this.on("maxfilesexceeded", function(file){

            });
            this.on("addedfile", function (file) {
                // console.log('file added...');
            });

            this.on("success", function (file, response) {
                if( 'error' === response.type ) {
                    swal('Error', response.message, response.type);
                    this.removeFile(file);
                } else {
                    // var response = $.parseJSON(response);
                    // notify(response.message, response.type, 9000);
                    // this.removeFile(file);
                }
            });

            this.on("reset", function(file){ this.removeAllFiles(); });   
            var _this = this;

            $('.modal').on('hidden.bs.modal', function () {
                _this.removeAllFiles();
            });
        },
        error: function(file, response) {
            notify(response, 'danger', 0);
            this.removeFile(file);
        }
    };

    $('#edit-member').on('hidden.bs.modal', function () {
        // for (var i = 0; i < myDropzone.files.length; i ++) {
        //   myDropzone.removeFile(files[i]);
        // }
    })

    // $('#edit-member').on('shown.bs.modal', function () {
    //     $('#dropzoneMember').enable();
    // })



    /*
    | --------------------------------------------
    | # Listing
    | --------------------------------------------
    */
    init_table();
    /*
    | --------------------------------------------
    | # Add
    | --------------------------------------------
    | # Validate | Submit
    */
    $('#add-new-member-btn').on('click', function (e) {
        $('#add-new-member-form')[0].reset();
        pop_form('#add-new-member-form', {}, false);
        reload_selectpickers();
        $(document).find(".tag-select").val('').trigger("chosen:updated");
    });
    //     // $('#add-member').modal('hide');
    //     // $.post(base_url('members/check'), {"can":"members/add"}, function (data) {
    //     //     var data = $.parseJSON(data);
    //     //     if (data.type == "error") {
    //     //         swal(data.title, data.message, data.type);
    //     //         $('#add-member').modal('hide');
    //     //     } else {
    //     //         $('#add-member').modal('show');
    //     //         $('#add-new-member-form')[0].reset();
    //     //         reload_selectpickers();
    //     //         $('#add-new-member-form [name=firstname]').focus();
    //     //     }
    //     // });
    // })
    var $memberForm = $('#add-new-member-form').validate({
        rules: {
            // firstname: 'required',
            // lastname: 'required',
            // address_street: 'required',
            // address_brgy: 'required',
            // address_city: 'required',
            msisdn: 'required',
            // email: {
            //     'required': true,
            //     'email': true
            // }
        },
        messages: {
            // firstname: {
            //     'required': "The First Name field is required"
            // },
            // lastname: {
            //     'required': "The Last Name field is required"
            // },
            // address_street: {
            //     'required': "The Street field is required"
            // },
            // address_brgy: {
            //     'required': "The Subdivision/Brgy field is required"
            // },
            // address_city: {
            //     'required': "The Town / City field is required"
            // },
            msisdn: {
                'required': "The Mobile field is required"
            },
            // email: {
            //     'required': "The Email field is required",
            //     'email': "The Email field must contain a valid email address"
            // },
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
                        // var errors = data.message;
                        notify(data.message, data.type, 9000);

                        $('#add-new-member-form')[0].reset();
                        $('#add-new-member-form [name=firstname]').focus();
                        reload_table();
                        reload_selectpickers();
                        // $.each(errors, function (k, v) {
                        //     $('#add-new-member-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                        //     // console.log(k,v);
                        // });
                    }
                    else
                    {
                        notify(data.message, data.type, 9000);
                        $('#add-new-member-form')[0].reset();
                        $('#add-new-member-form [name=firstname]').focus();
                        reload_table();
                        reload_selectpickers();
                        // window.Dropzone.options.dropzoneMember.removeFile();
                        $(document).find('.dz-remove').click();
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
    })
    /*
    | ---------------------------------------
    | # Delete Many
    | ---------------------------------------
    */
    $('body').on('click', '#delete-member-btn', function (e) {
        e.preventDefault();
        // console.log(G_selectedRows);
        var url  =  base_url('members/remove');
        swal({
            title: "Are you sure?",
            text: "The selected Members will be removed from your record",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            closeOnConfirm: false,
        }, function(){
            $.ajax({
                type: 'POST',
                url: url,
                data: {'id[]': $("#member-table-command").bootgrid('getSelectedRows')},
                success: function (data) {
                    // console.log(data);
                    var data = $.parseJSON(data);
                    reload_table();
                    swal("Removed", data.member.message, data.member.type);
                    G_selectedRows = [];
                    $('#delete-member-btn').removeClass('show');
                },
            });
        });

    })
    /*
    | --------------------------------------------
    | # Update
    | --------------------------------------------
    */
    $('#edit-member-form').validate({
        rules: {
            // firstname: 'required',
            // lastname: 'required',
            // address_street: 'required',
            // address_brgy: 'required',
            // address_city: 'required',
            msisdn: 'required',
            // email: {
            //     'required': true,
            //     'email': true
            // }
        },
        messages: {
            // firstname: {
            //     'required': "The First Name field is required"
            // },
            // lastname: {
            //     'required': "The Last Name field is required"
            // },
            // address_street: {
            //     'required': "The Street field is required"
            // },
            // address_brgy: {
            //     'required': "The Subdivision/Brgy field is required"
            // },
            // address_city: {
            //     'required': "The Town / City field is required"
            // },
            msisdn: {
                'required': "The Mobile field is required"
            },
            // email: {
            //     'required': "The Email field is required",
            //     'email': "The Email field must contain a valid email address"
            // },
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
                        var data = $.parseJSON(data);
                        console.log(data);
                        resetWarningMessages('.form-group-validation');
                        if( data.type != 'success' ) {
                            var errors = data.message;
                            $.each(errors, function (k, v) {
                                $('#edit-member-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            });
                        } else {
                            $('#edit-member-form').find('button[name=close]').delay(900).queue(function(next){ $(this).click(); next(); });
                            notify(data.message, data.type, 9000);
                            reload_table();
                            $('#edit-member-form')[0].reset();
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
    $('#member-table-command').bootgrid('reload');
}
function init_table() {
    var trashCount = 0;
    var selectedRowCount = [];
    memberTable = $("#member-table-command").bootgrid({
        labels: {
            loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
            noResults: 'No Members found',
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
                return  '<button data-fab-edit role="button" class="wave-effect btn btn-icon command-edit"    data-row-id="' + row.id + '"><span class="zmdi zmdi-edit"></span></button> ' +
                        '<button data-fab-delete type="button" class="wave-effect btn btn-icon command-delete"  data-row-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></button> ';
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
        url: base_url('members/listing'),
        rowCount: [5, 10, 20, 30, 50, 100, 500, -1],
        keepSelection: true,

        selection: true,
        multiSelect: true,

        caseSensitive: false,
    }).on('appended.rs.jquery.bootgrid', function (e, arr) {
        // console.log(arr);
    }).on("selected.rs.jquery.bootgrid", function(e, rows)
    {
        selectedRowCount.push(rows);
        G_selectedRows.push(rows[0].id);
        // console.log(selectedRowCount);
        if( selectedRowCount.length >= 2 )
        {
            $('#delete-member-btn').addClass('show');
        } else {
            $('#delete-member-btn').removeClass('show');
        }
    }).on("deselected.rs.jquery.bootgrid", function(e, rows)
    {
        selectedRowCount.splice(-1, 1);
        G_selectedRows.splice(-1, 1);

        // console.log(selectedRowCount);
        if( selectedRowCount.length >= 2 )
        {
            $('#delete-member-btn').addClass('show');
        } else {
            $('#delete-member-btn').removeClass('show');
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
                    $('#delete-member-btn').addClass('show');
                } else {
                    $('#delete-member-btn').removeClass('show');
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
        memberTable.find(".command-edit").on("click", function (e) {
            var id = $(this).parents('tr').data('row-id'),
                url = base_url('members/edit/' + id);

            $.ajax({
                type: 'POST',
                url: url,
                data: {id: id},
                success: function (data) {
                    var member = $.parseJSON(data);
                    console.log(member);
                    if( undefined !== member.type && member.type == 'error' ) {
                        swal(member.title, member.message, member.type);
                    } else {
                        $('#edit-member').modal("show");
                        var _form = $('#edit-member-form');
                        _form[0].reset();
                        reload_selectpickers();
                        _form.find('[name=firstname]').focus();

                        $.each(member.member, function (k, v) {
                            _form.find('[name=' + k + ']').val( v ).parent().addClass('fg-toggled');
                            if( k == 'type' ) reload_selectpickers_key( k, v);
                            if( k == 'schedule_id' ) reload_selectpickers_key( k, v);
                            if( k == 'groups' ) reload_selectpickers_key( k+"[]", v);
                            if( k == 'level' ) reload_selectpickers_key( k, v);

                            if( k == 'stud_no') {
                                var urls = $('#dropzoneMember').attr('action', base_url('members/upload_photo/' + v));
                            }

                        });
                        
                        new_reload_selectpickers_key( "groups[]", member.groups);
                        
                    }
                }
            });
        });

        /*
        | -----------------------------------------------------------
        | # Delete
        | -----------------------------------------------------------
        */
        memberTable.find(".command-delete").on("click", function (e) {
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.fullname').text(),
                url  = base_url('members/remove/' + id);
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
                            swal(data.title, data.message, data.type);
                        } else {
                            reload_table();
                            swal("Removed", data.member.message, data.member.type);
                        }

                    }
                });
            });
        });

    });
}
