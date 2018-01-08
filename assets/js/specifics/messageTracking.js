jQuery(document).ready(function ($) {
    init_table();
    // var refresher = setInterval(function () {
    //     reload_tracking_table();
    // }, 30000);
    // setTimeout(function() {
    //     clearInterval(refresher);
    // }, 1800000);
});

function reload_tracking_table() {
    $("#messaging-tracking-table").bootgrid('reload', $("#messaging-tracking-table").bootgrid("getCurrentPage"));
}
function init_table () {
    /*
    | ------------------------------------
    | # Add
    | ------------------------------------
    */
    var currentPage = 1;
    $("#messaging-tracking-table").bootgrid({
        labels: {
            noResults: 'No Message found',
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
                return  '<button title="Send now" role="button" class="wave-effect btn btn-icon command-resend"    data-row-id="' + row.id + '"><span class="fa fa-paper-plane"></span></button> '
                        // '<button title="Cancel Sending" type="button" class="wave-effect btn btn-icon command-delete"  data-row-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></button> ';
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
            // request.customPost = 'anything';
            // console.log(request);
            // request.selectedRows = $('.contacts-table-command').bootgrid('getSelectedRows');
            // console.log('request');
            // console.log(request);
            return request;
        },
        responseHandler: function (response) {
            console.log(response);
            $('#table-track-text').find('#td-contacts span').text(response.contacts);
            $('#table-track-text').find('#td-pending span').text(response.pending);
            $('#table-track-text').find('#td-failed span').text(response.failed);
            $('#table-track-text').find('#td-success span').text(response.success);
            $('#table-track-text').find('#td-buffered span').text(response.buffered);
            // response.current = currentPage;
            return response;
        },
        url: base_url('messages/tracking-listing_grouped'),
        rowCount: [5, 10, 20, 30, 50, 100],
        keepSelection: true,

        selection: false,
        multiSelect: true,
        caseSensitive: false,
    }).on("loaded.rs.jquery.bootgrid", function (e) {
        reload_dom();
        currentPage = $("#messaging-tracking-table").bootgrid("getCurrentPage");

        $("#messaging-tracking-table .command-resend").on('click', function (e) {
            e.preventDefault();
            var id = $(this).parents('tr').data('row-id');

            swal({
                title: "Confirmation",
                html: true,
                text: "You are about to resend the pending messages. \n With Tracking ID:" +  id ,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Resend Now",
                closeOnConfirm: true
            }, function(){
                // on deleting button
                $.post(base_url('messaging/resend-message/' + id ), function (data) {
                    var data = $.parseJSON(data);
                    console.log(data);
                    notify(data.message, data.type, 9000);
                })
            });

        });

    });

    $("#send-tracking-btn").on('click', function (e) {
            e.preventDefault();

            swal({
                title: "Confirmation",
                html: true,
                text: "You are about to resend all pending messages.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Resend Now",
                closeOnConfirm: true
            }, function(){
                // on deleting button
                $.post(base_url('messaging/resend-all-message'), function (data) {
                    var data = $.parseJSON(data);
                    console.log(data);
                    notify(data.message, data.type, 9000);
                })
            });

        });
}
