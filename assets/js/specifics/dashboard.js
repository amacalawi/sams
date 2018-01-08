jQuery(document).ready(function ($) {
    $(function(){ //DOM Ready
        var localData = JSON.parse(localStorage.getItem('gridsterPositions'));

        var gridster = $(".gridster .cards").gridster({
            widget_selector: ".card-item",
            widget_margins: [10, 10],
            widget_base_dimensions: [320, 250],
        }).data('gridster');
        console.log(gridster.serialize());

    });
})