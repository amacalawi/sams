var announcementTable, splashTable;
var G_selectedRows = [];

function fixed_url(str) {
  return encodeURIComponent(str).replace(/[!'()*]/g, function(c) {
    return '%' + c.charCodeAt(0).toString(16);
  });
}

function GetURLParameter(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}


jQuery(document).ready(function ($) {
    $('#generate-report').on('click', function (e) {
        e.preventDefault();

        var date_from = $("#date_from").val();
        var date_to = $("#date_to").val();
        var time_from = $("#time_from").val();
        var time_to = $("#time_to").val();
        var category = $("#category").val();
        var category_level = $("#category_level").val();
        var type = $("#type").val();
        var type_order = $("#type_order").val();

        if(date_from != "" && date_to != "" && category != "" && category_level != "" &&
           time_from != "" && time_to != "" && type != "" && type_order != ""){
            window.open(base_url('monitor/generate?date_from='+ fixed_url(date_from)+'&date_to='+fixed_url(date_to)+'&time_from='+fixed_url(time_from)+'&time_to='+fixed_url(time_to)+'&category='+fixed_url(category)+'&category_level='+fixed_url(category_level)+'&type='+fixed_url(type)+'&type_order='+fixed_url(type_order)));
        }
    });

    $('#download-csv').on('click', function (e) {
        e.preventDefault();

        var date_from = GetURLParameter('date_from');
        var date_to = GetURLParameter('date_to');
        var category = GetURLParameter('category');
        var category_level = GetURLParameter('category_level');
        var time_from = GetURLParameter('time_from');
        var time_to = GetURLParameter('time_to');
        var type = GetURLParameter('type');
        var type_order = GetURLParameter('type_order');

        document.location = base_url('monitor/fetch_csv?date_from=' + fixed_url(date_from) +'&date_to=' + fixed_url(date_to) +'&time_from=' + fixed_url(time_from) + '&time_to=' + fixed_url(time_to) + '&category=' + fixed_url(category) +'&category_level=' + fixed_url(category_level) + '&type=' + fixed_url(type) + '&type_order=' + fixed_url(type_order));
    });

    $('#category').on('change', function (e) {
        e.preventDefault();

        if($(this).val()=="Contact"){

            $.ajax({
                url: 'fetch_contact',
                type: "GET",
                beforeSend: function () {                

                },
                complete:function(){
                },
                success: function (data) {
                    $('#category_level option').remove();
                    $.each(data, function(i, item) {
                        $('#category_level').append('<option value="'+item.id+'">'+item.firstname+' '+item.lastname+'</option>');
                    });
                    $('#category_level').trigger("chosen:updated");
                },

                error: function( jqXhr ) {
                    if( jqXhr.status == 400 ) { //Validation error or other reason for Bad Request 400
                        var json = $.parseJSON( jqXhr.responseText );
                    }
                }

            });
        } else  if($(this).val()=="Group"){
            $.ajax({
                url: 'fetch_group',
                type: "GET",
                beforeSend: function () {                

                },
                complete:function(){
                },
                success: function (data) {
                    $('#category_level option').remove();
                    $.each(data, function(i, item) {
                        $('#category_level').append('<option value="'+item.groupid+'">'+item.groupname+'</option>');
                    });
                    $('#category_level').trigger("chosen:updated");
                },

                error: function( jqXhr ) {
                    if( jqXhr.status == 400 ) { //Validation error or other reason for Bad Request 400
                        var json = $.parseJSON( jqXhr.responseText );
                    }
                }
            });            
        } else if ($(this).val()=="Level") {
            $.ajax({
                url: 'fetch_level',
                type: "GET",
                beforeSend: function () {                

                },
                complete:function(){
                },
                success: function (data) {
                    $('#category_level option').remove();
                    $.each(data, function(i, item) {
                        $('#category_level').append('<option value="'+item.levelid+'">'+item.levelname+'</option>');
                    });
                    $('#category_level').trigger("chosen:updated");
                },

                error: function( jqXhr ) {
                    if( jqXhr.status == 400 ) { //Validation error or other reason for Bad Request 400
                        var json = $.parseJSON( jqXhr.responseText );
                    }
                }
            });
        } else {
            $('#category_level option').remove();
            $('#category_level').trigger("chosen:updated");
        }
    });

    init_table();


    var $userForm = $('#add-new-announcement-form').validate({
        rules: {
            announcement_name: 'required',
            announcement_text: 'required'
        },
        messages: {
            announcement_name: {
                'required': "The Announcement Name field is required"
            },
            announcement_text: {
                'required': "The Announcement Description field is required"
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
                    if( data.type !== 'success' )
                    {
                        var errors = data.message;

                        $.each(errors, function (k, v) {
                            $('#add-new-announcement-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            reload_selectpickers();
                        });
                    }
                    else
                    {   
                        $('#add-new-announcement-form').find('button[name=close]').delay(900).queue(function(next){ $(this).click(); next(); });
                        notify(data.message, data.type, 9000);
                        $('#add-new-announcement-form')[0].reset();
                        $('#add-new-announcement-form [name=announcement_name]').focus();
                        reload_announcement_table();
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



    $('#edit-announcement-form').validate({
        rules: {
            announcement_name: 'required',
            announcement_text: 'required'
        },
        messages: {
            announcement_name: {
                'required': "The Announcement Name field is required"
            },
            announcement_text: {
                'required': "The Announcement Description field is required"
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
            var announcement_id = $(form).find('[name=announcement_id]').val();
            if( announcement_id === 'AJAX_CALL_ONLY' ) {
                swal("Error", "The Announcment ID is invalid. Please reload the page and try again.", 'error');
                $('[name=close]').click();
            } else {
                $.ajax({
                    type: 'POST',
                    url: $(form).attr('action') + '/' + announcement_id,
                    data: $(form).serialize(),
                    success: function (data) {
                        data = JSON.parse(data);
                        console.log(data);
                        resetWarningMessages('.form-group-validation');
                        if( data.type != 'success' ) {
                            var errors = data.message;
                            $.each(errors, function(k, v) {
                                $('#edit-announcement-form').find('input[name='+k+'], select[name='+k+']').parents('.form-group-validation').addClass('has-warning').append('<small class="help-block">'+v+'</small>');
                            });
                        } else {
                            $('#edit-announcement-form').find('button[name=close]').delay(900).queue(function(next){ $(this).click(); next(); });
                            notify(data.message, data.type, 9000);
                            reload_announcement_table();
                            $('#edit-announcement-form')[0].reset();

                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr.status);
                        console.log(xhr.responseText);
                        console.log(thrownError);
                    }
                });
            }

        }
    });

    var files;    
    $('input[type=file]').on('change', prepareUpload);    
    function prepareUpload(event)
    {
      files = event.target.files;
    }

    $("#add-new-splash-form").submit(function( e ) {
        e.preventDefault();

        if($('input[type=file]').val()){

            var data = new FormData();
            $.each(files, function(key, value)
            {   
                data.append(key, value);
            });

            data.append('titles', $('#video_title').val());

            console.log(homebased + 'monitor/add_splash_source?titles=' + $('#video_title').val() + '&files');

            $.ajax({
                url: homebased + 'monitor/add_splash_source?titles=' + $('#video_title').val() + '&files',
                type: 'POST',
                data: data,
                cache: false,
                processData: false, // Don't process the files
                contentType: false,
                success: function(data, textStatus, jqXHR)
                {
                    if(typeof data.error === 'undefined')
                    {
                        console.log('SUCCESS: ' + data);

                        $('#add-new-splash-form').find('button[name=close]').delay(900).queue(function(next){ $(this).click(); next(); });
                        notify('The file has been successfully uploaded.', 'success', 3000);
                        $('#splash-table-command').bootgrid('reload');
                    }
                    else
                    {
                        console.log('ERRORS: ' + data.error);
                    }
                    //console.log("http://www.sams.dev/monitor/add_splash_source?files");
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    // Handle errors here
                    console.log('ERRORS: ' + textStatus);
                    // STOP LOADING SPINNER
                }
            });

            return false;
        }
    });


    $("#edit-splash-form").validate({
        rules: {
            video_titles: 'required',
            video_source: 'required'
        },
        messages: {
            video_titles: {
                'required': "The field is required"
            },
            video_source: {
                'required': "The field is required"
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


            if($('#edit-splash-form #splash-sources').val()){
                var data = new FormData();
                $.each(files, function(key, value)
                {   
                    data.append(key, value);
                });
                
                data.append('titles', $('#video_title').val());
                data.append('id', id);

                if( id === 'AJAX_CALL_ONLY' ) {
                    swal("Error", "The splash id is invalid. Please reload the page and try again.", 'error');
                    $('[name=close]').click();
                } else {
                    console.log(homebased + 'monitor/update_splash_source/?id=' + id + '&titles=' + $('#video_titles').val() + '&files');
                    $.ajax({
                        type: 'POST',
                        url: homebased + 'monitor/update_splash_source/?id=' + id + '&titles=' + $('#video_titles').val() + '&files',
                        type: 'POST',
                        data: data,
                        cache: false,
                        processData: false, // Don't process the files
                        contentType: false,
                        success: function(data, textStatus, jqXHR)
                        {
                            data = JSON.parse(data);
                            console.log(data);
                            resetWarningMessages('.form-group-validation');

                            if(typeof data.error === 'undefined')
                            {   
                                $('#edit-splash-form').find('button[name=close]').delay(900).queue(function(next){ $(this).click(); next(); });
                                $('#splash-table-command').bootgrid('reload');
                                notify('The file has been successfully uploaded.', 'success', 3000);

                                
                            } else {
                                var errors = data.message;
                                console.log('ERRORS: ' + data.error);
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            console.log(xhr.status);
                            console.log(xhr.responseText);
                            console.log(thrownError);
                        }
                    });
                }
            }
            else{
                return false;
            }

        }
    });
        
    
    var trashCount = 0;
    var selectedRowCountz = [];
    splashTable = $("#splash-table-command").bootgrid({
        labels: {
            loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
            noResults: 'No Splash Page found',
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
                        '<!--<button type="button" class="wave-effect btn btn-icon command-refresh" data-row-id="' + row.id + '"><span class="zmdi zmdi-search-for"></span></button>-->' +
                        '<button type="button" class="wave-effect btn btn-icon command-delete"  data-row-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></button> ' +
                        '<!--<button type="button" class="wave-effect btn btn-icon command-print"   data-row-id="' + row.id + '"><span class="zmdi zmdi-print"></span></button>-->';
            }
        },

        ajax: true,
        ajaxSettings: {
            method: "POST",
            cache: false
        },         
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(xhr.responseText);
            console.log(thrownError);
        },

        requestHandler: function (request)
        {
            console.log(request);
            return request;
        },
        responseHandler: function (response)
        {
            console.log(response);
            return response;
        },
        url: base_url('monitor/splash_listing'),
        rowCount: [5, 10, 20, 30, 50, 100],
        keepSelection: true,
        selection: true,
        multiSelect: true,
        caseSensitive: false,
    }).on('appended.rs.jquery.bootgrid', function (e, arr) {
      

    }).on("selected.rs.jquery.bootgrid", function(e, rows)
    {
       
    }).on("deselected.rs.jquery.bootgrid", function(e, rows)
    {
       
    }).on("loaded.rs.jquery.bootgrid", function (e) {
        reload_dom();
        $('.trash-count').text(trashCount);        

        splashTable.find(".command-edit").on("click", function () {
            var id = $(this).parents('tr').data('row-id'),
                url = base_url('monitor/edit_splash/' + id);

            console.log(url);
            $.ajax({
                type: 'POST',
                url: url,
                data: { id : id},
                success: function (data) {
                    var splash = $.parseJSON(data);
                    $('#edit-splash').modal("show");
                    var _form = $('#edit-splash-form');

                    $.each(splash, function (k, v) {

                        if(k == 'video_title')
                        {
                            _form.find('[name=' + k + 's]').val( v ).parent().addClass('fg-toggled');
                            reload_selectpickers_key( k, v);
                        }
                        else
                        {
                            _form.find('[name=' + k + ']').val( v ).parent().addClass('fg-toggled');
                            reload_selectpickers_key( k, v);
                        }
                    });
                }
            });
        });

        /*
        | -----------------------------------------------------------
        | # Delete
        | -----------------------------------------------------------
        */
        splashTable.find(".command-delete").on("click", function (e) {
            e.preventDefault();
            var row  = $(this).parents('tr').data('row-id'),
                id   = $(this).parents('tr').find('td:nth-child(3)').text(),
                url  = base_url('monitor/del_splash/' + row);
            
            swal({
                title: "Are you sure?",
                text: "The splash page with ID " + id + " will be deleted permanently from your database.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                closeOnConfirm: false
            }, function(){

                console.log(url);
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function (data) {
                        var data = $.parseJSON(data);
                        reload_splash_table();
                        swal("Deleted", data.message, data.type);
                    }
                });
            });
        });        
    });

})

function reload_splash_table()
{
    $('#splash-table-command').bootgrid('reload');
}

function reload_announcement_table()
{
    $('#announcement-table-command').bootgrid('reload');
}
function init_table() {
    var trashCount = 0;
    var selectedRowCount = [];
    announcementTable = $("#announcement-table-command").bootgrid({
        labels: {
            loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
            noResults: 'No Announcement found',
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
                return  '<button role="button" class="wave-effect btn btn-icon command-edit"    data-row-id="' + row.announcement_id + '"><span class="zmdi zmdi-edit"></span></button> ' +
                        '<!--<button type="button" class="wave-effect btn btn-icon command-refresh" data-row-id="' + row.announcement_id + '"><span class="zmdi zmdi-search-for"></span></button>-->' +
                        '<button type="button" class="wave-effect btn btn-icon command-delete"  data-row-id="' + row.announcement_id + '"><span class="zmdi zmdi-delete"></span></button> ' +
                        '<!--<button type="button" class="wave-effect btn btn-icon command-print"   data-row-id="' + row.announcement_id + '"><span class="zmdi zmdi-print"></span></button>-->';
            },
            announcement: function (column, row) {
                if(row.announcement_text.length > 175){
                    return row.announcement_text.substr(0, 175)+"...";
                } else {
                    return row.announcement_text.substr(0, 175);
                } 
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
            ///trashCount = response.trash.count;
            console.log(response);
            return response;
        },
        url: base_url('monitor/announcement_listing'),
        rowCount: [5, 10, 20, 30, 50, 100, -1],
        keepSelection: true,

        selection: true,
        multiSelect: true,

        caseSensitive: false,
    }).on('appended.rs.jquery.bootgrid', function (e, arr) {
         console.log(arr);
    }).on("selected.rs.jquery.bootgrid", function(e, rows)
    {
        selectedRowCount.push(rows);
        G_selectedRows.push(rows[0].announcement_id);
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
        announcementTable.find(".command-edit").on("click", function () {
            var id = $(this).parents('tr').data('row-id'),
                url = base_url('monitor/edit_announcement/' + id);

            $.ajax({
                type: 'POST',
                url: url,
                data: {announcement_id: id},
                success: function (data) {
                    var announcement = $.parseJSON(data);
                    $('#edit-announcement').modal("show");
                    var _form = $('#edit-announcement-form');

                    $.each(announcement, function (k, v) {
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
        announcementTable.find(".command-delete").on("click", function (e) {
            var id   = $(this).parents('tr').data('row-id')
                url  = base_url('monitor/del_announcement/' + id);
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: id + " will be deleted permanently from your users",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                closeOnConfirm: false
            }, function(){
                // on deleting button
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function (data) {
                        var data = $.parseJSON(data);
                        reload_announcement_table();
                        swal("Deleted", data.message, data.type);
                    }
                });
            });
        });

    });
}
