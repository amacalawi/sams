(function ($) {
    $(document).ready(function () {
        var defaults = {
            labels: {
                loading: '<i class="zmdi zmdi-close zmdi-hc-spin"></i>',
                noResults: 'No Resource found',
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
                    return  '<button data-fab-restore title="Restore" role="button" class="wave-effect btn btn-icon command-restore" data-row-id="' + row.id + '"><span class="zmdi zmdi-refresh"></span></button>';
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
            url: '/',
            rowCount: [5, 10, 20, 30, 50, 100, 500, -1],
            keepSelection: true,

            selection: true,
            multiSelect: true,

            caseSensitive: false,
        };

        $("[data-trash-bootgrid]").each(function () {
            var $dis = $(this);
            var options = $.extend({}, defaults, $dis.data('bootgrid-options'));
            $dis.bootgrid(options)
                .on("selected.rs.jquery.bootgrid", function(e, rows) {
                    if ($dis.bootgrid("getSelectedRows").length >= 2) {
                        $('[data-fab-destroy]').addClass('show');
                    } else {
                        $('[data-fab-destroy]').removeClass('show');
                    }
                })
                .on("deselected.rs.jquery.bootgrid", function(e, rows) {
                    if ($dis.bootgrid("getSelectedRows").length >= 2) {
                        $('[data-fab-destroy]').addClass('show');
                    } else {
                        $('[data-fab-destroy]').removeClass('show');
                    }
                })
                .on("loaded.rs.jquery.bootgrid", function (e) {

                    $dis.find('[data-fab-restore]').on('click', function (e) {
                        var id = $(this).parents('tr').data('row-id'),
                        name = $(this).parents('tr').find('rd.name').text(),
                        url  = options.restore + "/" + id;
                        // get_formdata(options.edit+"/"+$(this).data('row-id'), 'POST', function (data) {
                        //     set_formdata(data, '[data-form-edit]', '[data-modal-edit]', $(this).data('row-id'));
                        // });
                        $.ajax({
                            type: 'POST',
                            url: url,
                            success: function (data) {
                                var data = $.parseJSON(data);
                                console.log(data);
                                $dis.bootgrid('reload');
                                notify(data.message, data.type, 9000);
                                // swal("Restored", data.member.message, data.member.type);
                            }
                        });
                        e.preventDefault();
                    });
                });
        });
    });
})(jQuery);