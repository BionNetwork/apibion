<?php

namespace BiBundle\Twig\Extension\Dialog;

use Twig_Extension;

abstract class AbstractDialog extends Twig_Extension implements IDialog
{

	/**
	 * Возвращает уникальный идентификатор
	 * @return string
	 */
	protected function getUnicId()
	{
		return $this->getName() . '_' . md5(uniqid(rand(), true));
	}

	/**
	 * Возвращает контент из шаблона
	 * @param string $templateFileName Имя файла шаблона
	 * @return string
	 * @throws \Exception
	 */
	protected function getContent($templateFileName)
	{
		$path = realpath(__DIR__ . DIRECTORY_SEPARATOR . 'Template' . DIRECTORY_SEPARATOR . $templateFileName);
		if (!$path) {
			$exeptionMessage = "File " . $templateFileName . " not found!";
			throw new \Exception($exeptionMessage);
		}
		return file_get_contents($path);
	}

	/**
	 * Выполняет подстановку переменных в шаблон
	 * @param string $content
	 * @param array $parameterArray
	 * @return string
	 */
	protected function render($content, $parameterArray = [])
	{
		$resultContent = $content;
		foreach ($parameterArray as $key => $value) {
			$resultContent = str_replace($key, $value, $resultContent);
		}
		return $resultContent;
	}

	/**
	 * Возвращает значение из массива по ключу, если оно есть
	 * И альтернативное значение, если его нет
	 *
	 * @param array $parameterArray
	 * @param string $parameterKey
	 * @param string $valueIsEmpty
	 *
	 * @return string
	 */
	protected function getParam($parameterArray, $parameterKey, $valueIsEmpty)
	{
		if (empty($parameterArray[$parameterKey])) {
			$result = $valueIsEmpty;
		} else {
			$result = $parameterArray[$parameterKey];
		}
		return $result;
	}

	public function getFunctions()
	{
		parent::getFunctions();
		return [
			//$this->getWidgetName() => new \Twig_Function_Method($this, 'makeDialog', ['is_safe' => ['html']]),
		];
	}

	public function getName()
	{
		return 'dialog_' . $this->getWidgetName();
	}
}
