$(document).ready(function(){
    var filterCounter = $(".filter-item").length;
    $(".filter-delete").click(function(){
        filter.deleteItem(this);
    });
    // add new filter to card
    $(".filter-add").click(function(){
        var selectedFilter = $("#cardFilters option:selected");
        var label = selectedFilter.text();
        if ('' == label) {
            var elem = $('<div class="alert alert-error">не выбран фильтр</div>');
            $(".selected-filters").append(elem);
            elem.fadeOut(2000);
            return;
        }
        var html = '<div class="filter-item">' +
            '<span class="label label-primary">' + label + '</span>';
        var checkboxes = [];
        filterCounter += 1;
        $("#charts input:checked").each(function(){
            html += '<span class="label label-info chart-item">' + $(this).parent().next("input").val() + '</span>';
            checkboxes.push({'id': $(this).val(), 'name': $(this).parent().next("input").val()});
        });
        html += '<div class="btn-group" role="group" aria-label="...">' +
                    '<button type="button" class="btn btn-default filter-delete" onclick="filter.deleteItem(this)">Delete</button>' +
                '</div>' +
                '<input type="hidden" name="card[data][filters][' + filterCounter + '][id]" value="' + selectedFilter.val() + '"/>' +
                '<input type="hidden" name="card[data][filters][' + filterCounter + '][name]" value="' + label + '"/>';
        $(checkboxes).each(function(i){
            html += '<input type="hidden" name="card[data][filters][' + filterCounter + '][charts]['+i+'][id]" value="' + checkboxes[i]['id'] + '"/>';
            html += '<input type="hidden" name="card[data][filters][' + filterCounter + '][charts]['+i+'][name]" value="' + checkboxes[i]['name'] + '"/>';
        });
        html += '</div>';
        $(".selected-filters").append(html);
    });
    $("#cardFilters").change(function(){
        $("#charts input:checked").attr("checked", false);
    });
});

var filter = {
    deleteItem: function(elem) {
        $(elem).parents(".filter-item").remove();
    }
};