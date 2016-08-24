function addTagForm($collectionHolder, $newLinkLi) {
	var prototype = $collectionHolder.data('prototype');
	var index = $collectionHolder.data('index');
	var newForm = prototype.replace(/__name__/g, index);
	$collectionHolder.data('index', index + 1);
	var $newFormLi = $('<div class="collection_widget_element"></div>').append(newForm);
	$newLinkLi.before($newFormLi);
	addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
	var $removeFormA = $('<button type="button" class="btn btn-default collection-delete-button"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>');

	var isHasError = $($tagFormLi).find('.has-error').length;
	if (isHasError !== 0) {
		$($removeFormA).css('margin-top', '-128px');
	}


	$tagFormLi.append($removeFormA);
	$removeFormA.on('click', function (e) {
		e.preventDefault();
		$tagFormLi.remove();
	});
}

var $collectionHolder;
var $addTagLink = $('<a href="#" class="btn btn-default btn-sm pull-right collection-add-button">Добавить</a>');
var $newLinkLi = $('<div class="collection_widget_buttons"></div>').append($addTagLink);

$(document).ready(function () {
	$collectionHolder = $('div.collection_widget_prototype');
	$collectionHolder.append($newLinkLi);
	$collectionHolder.data('index', $collectionHolder.find(':input').length);
	$('.collection-add-button').on('click', function (e) {
		e.preventDefault();
		var currentHolder = $(this).parent().parent();
		addTagForm(currentHolder, $(this).closest('.collection_widget_buttons'));
	});
	// Добавляем кнопку удаления
	$collectionHolder.find('.collection_widget_element').each(function () {
		addTagFormDeleteLink($(this));
	});
});
