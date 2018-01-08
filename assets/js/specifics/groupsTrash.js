jQuery(document).ready(function ($) {
    var groupsTrashTable = $('#trashed-group-table-command').bootgrid({
        labels: {
            loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
            noResults: 'No Groups found',
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
                return  '<button title="Restore" role="button" class="wave-effect btn btn-icon command-restore" data-row-id="' + row.id + '"><span class="zmdi zmdi-refresh"></span></button>';
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
            request.removedOnly = true;
            return request;
        },
        responseHandler: function (response)
        {
            // To accumulate custom parameter with the response object
            // response.customPost = 'anything';
            // response.current = 2;
            // console.log(response);
            return response;
        },
        url: base_url('groups/listing'),
        rowCount: [5, 10, 20, 30, 50, 100, -1],
        keepSelection: false,

        selection: false,
        // multiSelect: true,

        caseSensitive: false,
    }).on("loaded.rs.jquery.bootgrid", function (e) {

        groupsTrashTable.find('.command-restore').on('click', function (e) {
            var id   = $(this).parents('tr').data('row-id'),
                name = $(this).parents('tr').find('td.fullname').text(),
                url  = base_url('groups/restore/' + id);
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: url,
                success: function (data) {
                    var data = $.parseJSON(data);
                    console.log(data);
                    reload_trash_table();
                    notify(data.message, data.type, 9000);
                    // swal("Restored", data.group.message, data.group.type);
                }
            });
        })
    });
});

function reload_trash_table() {
    $('#trashed-group-table-command').bootgrid('reload');
}