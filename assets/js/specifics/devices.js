$(document).ready(function(){
    $('input[name=name]').on('keyup', function () {
        $('input[name=code]').val( slugify( $(this).val() ) ).parent().addClass('fg-toggled');
    });
});