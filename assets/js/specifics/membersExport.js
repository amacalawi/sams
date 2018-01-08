$.validator.setDefaults({ ignore: ":hidden:not(.tag-select)" })
// add the rule here
$.validator.addMethod("valueNotEquals", function(value, element, arg){
    return arg != value;
}, "Please select an item");

$(document).ready(function () {

    $('[name=export_start]').datetimepicker({
        format: 'MMMM DD, YYYY',
    });
    $('[name=export_end]').datetimepicker({
        format: 'MMMM DD, YYYY',
        useCurrent: false
    });
    $("[name=export_start]").on("dp.change", function (e) {
        $('[name=export_end]').data("DateTimePicker").minDate(e.date);
    });
    $("[name=export_end]").on("dp.change", function (e) {
        $('[name=export_start]').data("DateTimePicker").maxDate(e.date);
    });


    $('#export-members-form').validate({
        rules: {
            export_start: 'required',
            export_end: 'required',
            export_format: { valueNotEquals: "" },
            export_level: { valueNotEquals: "" },
        },
        messages: {
            export_format: { valueNotEquals: "Please select a Format" },
            export_level: { valueNotEquals: "Please select a Level" },
        },
        errorElement: 'small',
        errorPlacement: function (error, element) {
            $(error).addClass('help-block');
            $(element).parents('.form-group-validation').addClass('has-warning').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.form-group-validation').addClass('has-warning');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.form-group-validation').removeClass('has-warning');
            $(element).parents('.form-group-validation').find('.help-block').remove();
        },
    });
    $('#export-btn').click(function (e) {
        e.preventDefault();

        if(!$(this).parents('form').valid()) { return false; }
        else {
            // var start = $('[name=export_start]').val(), end = $('[name=export_end]').val();
            // notify('<i class="zmdi zmdi-settings zmdi-hc-spin"></i>&nbsp;<span>Exporting Members from '+start+' - '+end+'...</span>', 'danger', 0, false);
            $('#export-members-form').submit();
            // $.ajax({
            //     type: 'POST',
            //     url: $('#export-members-form').attr('action'),
            //     data: $('#export-members-form').serialize(),
            //     success: function (data) {
            //         var data = $.parseJSON(data);
            //         notify(data.message, data.type, 0);
            //         console.log(data);
            //     }
            // })
        }

        // $(this).prop('disabled', 'disabled');

    });
});