$(document).ready(function(){
    var filterCounter = $(".filter-item").length,
        argumentCounter = $(".argument-item").length;
    $(".filter-delete").click(function(){
        elem.deleteFilter(this);
    });
    $(".argument-delete").click(function(){
        elem.deleteArgument(this);
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
            '<span class="label label-primary">' + $("#cardFilters-title").val() + ' ( ' + label + ' ) </span>';
        var checkboxes = [];
        filterCounter += 1;
        $("#charts input:checked").each(function(){
            html += '<span class="label label-info chart-item">' + $(this).parent().next("input").val() + '</span>';
            checkboxes.push({'id': $(this).val(), 'name': $(this).parent().next("input").val()});
        });
        html += '<div class="btn-group" role="group" aria-label="...">' +
                    '<button type="button" class="btn btn-default filter-delete" onclick="elem.deleteFilter(this)">Delete</button>' +
                '</div>' +
                '<input type="hidden" name="card[data][filters][' + filterCounter + '][id]" value="' + selectedFilter.val() + '"/>' +
                '<input type="hidden" name="card[data][filters][' + filterCounter + '][title]" value="' + $("#cardFilters-title").val() + '"/>' +
                '<input type="hidden" name="card[data][filters][' + filterCounter + '][name]" value="' + label + '"/>' +
                '<input type="hidden" name="card[data][filters][' + filterCounter + '][type]" value="' + selectedFilter.data("type") + '"/>';
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
    // add new argument to card
    $(".argument-add").click(function(){
        var dataType = $("#cardArgumentsType option:selected");
        var dimension = $("#cardArgumentsDimension option:selected");
        var name = $("#cardArguments-name").val(),
            code = $("#cardArguments-code").val(),
            desc = $("#cardArguments-description").val();
        if ('' == name) {
            var elem = $('<div class="alert alert-error">не выбран фильтр</div>');
            $(".selected-arguments").append(elem);
            elem.fadeOut(2000);
            return;
        }
        var html = '<div class="argument-item">' +
            '<span class="label label-primary">' + name + '</span>';
        argumentCounter += 1;
        html += '<div class="btn-group" role="group" aria-label="...">' +
            '<button type="button" class="btn btn-default argument-delete" onclick="elem.deleteArgument(this)">Delete</button>' +
            '</div>' +
            '<input type="hidden" name="card[argument][' + argumentCounter + '][id]" value=""/>' +
            '<input type="hidden" name="card[argument][' + argumentCounter + '][name]" value="' + name + '"/>' +
            '<input type="hidden" name="card[argument][' + argumentCounter + '][code]" value="' + code + '"/>' +
            '<input type="hidden" name="card[argument][' + argumentCounter + '][description]" value="' + desc + '"/>' +
            '<input type="hidden" name="card[argument][' + argumentCounter + '][datatype]" value="' + dataType.val() + '"/>' +
            '<input type="hidden" name="card[argument][' + argumentCounter + '][dimension]" value="' + dimension.val() + '"/>';
        html += '</div>';
        $(".selected-arguments").append(html);
    })
});

var elem = {
    deleteFilter: function(elem) {
        $(elem).parents(".filter-item").remove();
    },
    deleteArgument: function(elem) {
        $(elem).parents(".argument-item").remove()
    }
};