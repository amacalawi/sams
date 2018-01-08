$(document).on('ready', function() {
    window.Dropzone.options.dropzone = {
        maxFiles: 1,
        acceptedFiles: '.csv',
        addRemoveLinks: true,
        init: function () {
            this.on("maxfilesexceeded", function(file){

            });
            this.on("addedfile", function (file) {
                // console.log('file added...');
            });

            this.on("success", function (file, response) {
                if( 'error' === response.type )
                {
                    swal('Error', response.message, response.type);
                    this.removeFile(file);
                }
                else
                {
                    var response = $.parseJSON(response);
                    notify(response.message, response.type, 2000);
                    this.removeFile(file);

                    $('#contact-import-table-command').bootgrid({
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
                        url: base_url('contacts/listing'),
                        rowCount: [5, 10, 20, 30, 50, 100, -1],
                        // keepSelection: true,

                        // selection: true,
                        // multiSelect: true,

                        caseSensitive: false,
                    }).removeClass('hidden');
                    $('#contact-import-table-command').bootgrid('reload');
                    // console.log(response);
                }
            });

        },
        error: function(file, response) {
            notify(response, 'danger', 0);
            this.removeFile(file);
        }
    };
});