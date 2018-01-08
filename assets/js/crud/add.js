(function ($) {
    $(document).ready(function () {

        $('form[method=POST]').on('submit', function (e) {
            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                method: $form.attr('method'),
                data: $form.serialize(),
                success: function (data) {
                    console.log(data);
                    data = $.parseJSON(data);

                    if ("error" === data.type) {
                        display_many_fields_error(data.errors);
                    } else if ("success" === data.type) {
                        clear_form();
                        reload_bootgridtable();
                    }

                    notify(data.message, data.color, 9000);

                },
            });

            e.preventDefault();
        });

    });
})(jQuery);