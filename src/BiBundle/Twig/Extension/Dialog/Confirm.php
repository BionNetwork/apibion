<?php

namespace BiBundle\Twig\Extension\Dialog;

use BiBundle\Twig\Extension\Dialog\AbstractDialog;

class Confirm extends AbstractDialog
{

	/**
	 * Создает окно подтверждения
	 * @param string $parentId
	 * @param array $parameterArray
	 * @return string
	 */
	public function makeDialog($parentId, $parameterArray = [])
	{
		$unicId = $this->getParam($parameterArray, 'id', $this->getUnicId());
		$titleConfirm = $this->getParam($parameterArray, 'title', "Требуется подтверждение:");
		$messageConfirm = $this->getParam($parameterArray, 'message', "Вы уверены, что хотите совершить это действие?");
		$content = $this->getContent('confirm.html.twig');
		$renderedContent = $this->render($content, [
			'{{parentId}}' => $parentId,
			'{{id}}' => $unicId,
			'{{title}}' => $titleConfirm,
			'{{message}}' => $messageConfirm,
		]);
		return $renderedContent;
	}

	public function getWidgetName()
	{
		return "confirm";
	}
}
