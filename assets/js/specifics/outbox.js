jQuery(document).ready(function ($) {
    /*
    |---------------
    | # Outbox Table
    |---------------
    */
    init_outbox_table();
    var refresher = setInterval(function () {
        reload_outbox_table();
    }, 10000);
    setTimeout(function() {
        clearInterval(refresher);
    }, 1800000);
});

function reload_outbox_table()
{
    console.log("refresh");
    var sample = $('#outbox-table').bootgrid('reload', $('#outbox-table').bootgrid('getCurrentPage'));

}
function init_outbox_table() {


    // var trashCount = 0;
    // contactTable = $("#outbox-table").bootgrid({
    //     labels: {
    //         loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
    //         noResults: 'No Sent Messages',
    //     },
    //     css: {
    //         icon: 'zmdi icon',
    //         iconColumns: 'zmdi-view-module',
    //         iconDown: 'zmdi-caret-down',
    //         iconRefresh: 'zmdi-refresh',
    //         iconUp: 'zmdi-caret-up',
    //     },
    //     // formatters: {
    //     //     commands: function (column, row) {
    //     //         return  '<button role="button" class="wave-effect btn btn-icon command-edit"    data-row-id="' + row.id + '"><span class="zmdi zmdi-edit"></span></button> ' +
    //     //                 '<button type="button" class="wave-effect btn btn-icon command-delete"  data-row-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></button> ';
    //     //     }
    //     // },

    //     ajax: true,
    //     ajaxSettings: {
    //         method: "POST",
    //         cache: false
    //     },
    //     requestHandler: function (request)
    //     {
    //         // To accumulate custom parameter with the request object
    //         // request.customPost = 'anything';
    //         // request.current = 2;
    //         // console.log(request);
    //         return request;
    //     },
    //     responseHandler: function (response)
    //     {
    //         // To accumulate custom parameter with the response object
    //         // response.customPost = 'anything';
    //         // response.current = 2;
    //         trashCount = response.trash.count;
    //         return response;
    //     },
    //     url: base_url('messaging/listing'),
    //     rowCount: [5, 10, 20, 30, 50, 100, 500, -1],
    //     // keepSelection: true,

    //     // selection: true,
    //     // multiSelect: true,

    //     caseSensitive: false,
    // }).on("loaded.rs.jquery.bootgrid", function (e) {
    //     reload_dom();
    //     $('.trash-count').text(trashCount);
    //     contactTable.find('td.status:contains("success")').parent().addClass('success');
    //     contactTable.find('td.status:contains("pending")').parent().addClass('warning');
    // });


    /*
    | ---------------------------------
    | # department table initialized
    | ---------------------------------
    */
    contactTable = $("#outbox-table").bootgrid({
        css: {
            icon: 'zmdi icon',
            iconColumns: 'zmdi-view-module',
            iconDown: 'zmdi-chevron-down',
            iconRefresh: 'zmdi-refresh',
            iconUp: 'zmdi-chevron-up'
        },
        formatters: {
            "commands": function(column, row) {
                return  "<button title=\"edit this\" type=\"button\" class=\"btn btn-icon command-edit waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-edit\"></span></button> "+
                        "<button title=\"remove this\" type=\"button\" class=\"btn btn-icon command-delete waves-effect waves-circle\" data-row-id=\"" + row.id + "\"><span class=\"zmdi zmdi-delete\"></span></button>";
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
        url: base_url('messaging/listing'),
        keepSelection: true,
        selection: true,
        multiSelect: true,
        caseSensitive: false,
        rowCount: [5, 10, 20, 30, 50, 100, 500, -1],
    }).on("selected.rs.jquery.bootgrid", function(e, rows)
    {
        
    }).on("deselected.rs.jquery.bootgrid", function(e, rows)
    {

    }).on("loaded.rs.jquery.bootgrid", function (e, rows) {
         reload_dom();
        contactTable.find('td.status:contains("success")').parent().addClass('success');
        contactTable.find('td.status:contains("pending")').parent().addClass('warning');
    }); 
}
