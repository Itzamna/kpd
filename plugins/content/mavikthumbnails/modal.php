<?php
/**
 * @package Joomla
 * @subpackage mavikThumbnails
 * @copyright 2008 Vitaliy Marenkov
 * @author Vitaliy Marenkov <admin@mavik.com.ua>
 * Плагин заменяет изображения иконками со ссылкой на полную версию.
 */


/**
 * Декоратор для добавления к изображению стандартного модального окна
 * 
 */
class plgContentMavikThumbnailsDecoratorModal extends plgContentMavikThumbnailsDecorator
{
	/**
	 * Добавление кода в заголовок страницы 
	 */
	function addHeader()
	{
		// Подключить библиотеку модальных окон
		JHTML::_('behavior.modal');
	}
	
	/**
	 * Декорирование тега изображения
	 * @param $img string Тег изображения 
	 * @return string Декорированый тег изображения
	 */
	function decorate($img) {
		if ($this->plugin->img->isThumb) {
			return '<a class="modal thumbnail" href="'. $this->plugin->originalSrc .'" rel="{handler: \'image\', marginImage: {x: 50, y: 50}}">' . $img . '</a>';
		} else {
			return $img;
		}
	}	
	
}
?>