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

                }
            });

        },
        error: function(file, response) {
            notify(response, 'danger', 0);
            this.removeFile(file);
        }
    };
});