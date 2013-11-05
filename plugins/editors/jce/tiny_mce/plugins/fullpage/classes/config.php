<?php
/**
* @version		$Id: config.php 48 2009-05-27 10:46:36Z happynoodleboy $
* @package      JCE
* @copyright    Copyright (C) 2005 - 2009 Ryan Demmer. All rights reserved.
* @author		Ryan Demmer
* @license      GNU/GPL
* JCE is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/
class FullpageConfig
{
	function getConfig(&$vars)
	{
		// Get JContentEditor instance
		$jce 	=& JContentEditor::getInstance();
		$params = $jce->getPluginParams('fullpage');
		
		$doctypes = array(
			'XHTML 1.0 Transitional' 	=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
			'XHTML 1.0 Frameset' 		=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
			'XHTML 1.0 Strict' 			=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
			'XHTML 1.1' 				=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
			'HTML 4.01 Transitional' 	=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">',
			'HTML 4.01 Strict' 			=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
			'HTML 4.01 Frameset' 		=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">',
			'HTML 5' 					=> '<!DOCTYPE HTML>'
		);
		
		$doctype 		= $jce->getParam($params, 'fullpage_default_doctype', 'HTML 4.01 Transitional', 'HTML 4.01 Transitional');
		$default_fonts 	= 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,times new roman,times,serif;Tahoma=tahoma,arial,helvetica,sans-serif;Times New Roman=times new roman,times,serif;Verdana=verdana,arial,helvetica,sans-serif;Impact=impact;WingDings=wingdings';
		$fonts 			= $jce->getEditorFonts($jce->getEditorParam('editor_theme_advanced_fonts_add', ''), $jce->getEditorParam('editor_theme_advanced_fonts_remove', ''));
		
		$vars['fullpage_fonts'] 				= $fonts == $default_fonts ? '' : $fonts;
		$vars['fullpage_fontsizes'] 			= $jce->getParam($params, 'editor_theme_advanced_font_sizes', '8pt,10pt,12pt,14pt,18pt,24pt,36pt', '10px,11px,12px,13px,14px,15px,16px');		
		
		if ($doctype) {
			$vars['fullpage_default_doctype'] 	= '"' . addslashes($doctypes[$doctype]) . '"';
		}

		$vars['fullpage_hide_in_source_view']	= $jce->getParam($params, 'fullpage_hide_in_source_view', 0, 0);
		$vars['fullpage_default_encoding']		= $params->get('fullpage_default_encoding');
		$vars['fullpage_default_xml_pi']		= $jce->getParam($params, 'fullpage_default_xml_pi', 0, 0);
		$vars['fullpage_default_font_family'] 	= $params->get('fullpage_default_font_family');
		$vars['fullpage_default_title']			= $jce->getParam($params, 'fullpage_default_title', 'Untitled Document', 'Untitled Document');
		$vars['fullpage_default_font_size']		= $params->get('fullpage_default_font_size');
		$vars['fullpage_default_text_color']	= $params->get('fullpage_default_text_color');
	}
}
?>