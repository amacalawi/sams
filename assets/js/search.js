jQuery(document).ready(function ($) {

    $('[data-toggle=omni-search]').on('keyup', function (e) {
        var keyword = $(this).find('input[name=search]').val(),
            dropdown = $('<div class="dropdown" />');
            dropdown.append('<div class="dropdown-item">'+keyword+'</div>');

        $.ajax({
            method: "POST",
            url: $(this).find('form').attr('action') + '/' + keyword,
            success: function (data) {
                var data = $.parseJSON(data);
                console.log(data);
            }
        });

        console.log(keyword);
    });

});