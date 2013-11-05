<?php
/**
 * @package Joomla
 * @subpackage mavikThumbnails
 * @copyright 2008 Vitaliy Marenkov
 * @author Vitaliy Marenkov <admin@mavik.com.ua>
 * Плагин заменяет изображения иконками со ссылкой на полную версию.
 */


/**
 * Декоратор для добавления к изображению всплыющего окна Slimbox
 * 
 */
class plgContentMavikThumbnailsDecoratorSlimbox extends plgContentMavikThumbnailsDecorator
{
	/**
	 * Добавление кода в заголовок страницы 
	 */
	function addHeader()
	{
		// Подключить библиотеку slimbox
		$document = &JFactory::getDocument();
		JHTML::_('behavior.mootools');
		if ($this->plugin->linkScripts) {
			$document->addScript(JURI::base().'plugins/content/mavikthumbnails/slimbox/js/slimbox.js');
			$document->addStyleSheet(JURI::base().'plugins/content/mavikthumbnails/slimbox/css/slimbox.css');
		}
	}
	
	/**
	 * Декорирование тега изображения
	 * @param $img string Тег изображения 
	 * @return string Декорированый тег изображения
	 */
	function decorate($img) {
		if ($this->plugin->img->isThumb) {
			$title = $this->plugin->img->getAttribute('title');
			if (empty($title) && $this->plugin->img->getAttribute('alt')) {
				$title = $this->plugin->img->getAttribute('alt');
			}
			$title = htmlspecialchars($title); 
			return '<a class="thumbnail" href="' . $this->plugin->originalSrc . '" rel="lightbox[' . @$this->plugin->article->id. ']" title="' . $title . '" target="_blank">' . $img . '</a>';
		} else {
			return $img;
		}
	}	
	
}
?>