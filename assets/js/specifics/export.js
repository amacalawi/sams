$(document).ready(function () {
   $('#export-btn').click(function (e) {
       e.preventDefault();

       $(this).prop('disabled', 'disabled');
       var start = $('[name=export_start]').val(), end = $('[name=export_end]').val();
       notify('<i class="zmdi zmdi-settings zmdi-hc-spin"></i>&nbsp;<span>Exporting Contacts from '+start+' - '+end+'...</span>', 'danger', 0, false);

   });
});