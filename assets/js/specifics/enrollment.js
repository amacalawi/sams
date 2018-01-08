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
                    return  '<button data-fab-edit role="button" class="wave-effect btn btn-icon command-edit" data-row-id="' + row.member_id + '"><span class="zmdi zmdi-edit"></span></button> ' +
                            '<button data-fab-delete type="button" class="wave-effect btn btn-icon command-delete" data-row-id="' + row.id + '"><span class="zmdi zmdi-delete"></span></button> ';
                }
            },

            ajax: true,
            ajaxSettings: {
                method: "POST",
                cache: false
            },
            url: '/',
            rowCount: [5, 10, 20, 30, 50, 100, 500, -1],
            keepSelection: true,

            selection: true,
            multiSelect: true,

            caseSensitive: false,
        };

        $("[data-bootgrid]").each(function () {
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
                    $dis.find('[data-fab-edit]').on('click', function () {
                        var $this = $(this);
                        var mid = $(this).parents('tr').find('td.member_id').text();
                        // alert(mid);
                        var id = mid != "" ? mid : $(this).data('row-id');
                        get_formdata(options.edit+"/"+id, 'POST', function (data) {
                            set_formdata(data, '[data-form-edit]', '[data-modal-edit]', $this.data('row-id'), $('[data-form-edit]').data('form-edit'));
                        });
                    });

                    $dis.find('[data-fab-delete]').on('click', function (e) {
                        var id   = $(this).parents('tr').data('row-id'),
                        name = $(this).parents('tr').find('td.name').text(),
                        mid = $(this).parents('tr').find('td.member_id').text(),
                        url  = options.remove + "/" + (mid ? mid : id);
                        // get_formdata(options.edit+"/"+$(this).data('row-id'), 'POST', function (data) {
                        //     set_formdata(data, '[data-form-edit]', '[data-modal-edit]', $(this).data('row-id'));
                        // });
                        var _text = ("permanent_delete" in options) ? name + " will be permanently deleted." : name + " will be trashed.";
                        swal({
                            title: "Are you sure?",
                            text: _text,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Remove",
                            closeOnConfirm: false
                        }, function(){
                            // on removing resource
                            $.ajax({
                                type: 'POST',
                                url: url,
                                success: function (data) {
                                    var data = $.parseJSON(data);

                                    if( undefined !== data.type && data.type == 'error' ) {
                                        swal(data.title, data.message, data.type);
                                    } else {
                                        $dis.bootgrid('reload');
                                        // reload_table();
                                        swal("Removed", data.member.message, data.member.type);
                                    }

                                }
                            });
                        });
                        e.preventDefault();
                    });
                });
        });
    });
})(jQuery);