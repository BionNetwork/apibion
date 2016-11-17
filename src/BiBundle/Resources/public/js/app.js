$(document).ready(function(){
    $(".filter-delete").click(function(){
        filter.deleteItem(this);
    });
    // add new filter to card
    $(".filter-add").click(function(){
        var label = $("#cardFilters option:selected").text();
        if ('' == label) {
            var elem = $('<div class="alert alert-error">не выбран фильтр</div>');
            $(".selected-filters").append(elem);
            elem.fadeOut(2000);
            return;
        }
        var html = '<div class="filter-item">' +
            '<span class="label label-primary">' + label + '</span>';
        $("#charts input:checked").each(function(){
            html += '<span class="label label-info chart-item">' + $(this).parent().next("input").val() + '</span>'
        });
        html += '<div class="btn-group" role="group" aria-label="...">' +
                    '<button type="button" class="btn btn-default filter-delete" onclick="filter.deleteItem(this)">Delete</button>' +
                '</div>' +
            '</div>';
        $(".selected-filters").append(html);
    });
    $("#cardFilters").change(function(){
        $("#charts input:checked").attr("checked", false);
    })
});

var filter = {
    deleteItem: function(elem) {
        $(elem).parents(".filter-item").remove();
    }
};