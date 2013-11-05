<?php
/**
 * @version		$Id: packer.php 107 2011-02-26 16:32:11Z happy_noodle_boy $
 * @package		JCE
 * @copyright	Copyright (C) 2009 Ryan Demmer. All rights reserved.
 * @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
 * @license		GNU/GPL
 * This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

defined('_JEXEC') or die('RESTRICTED');

class JCEPacker extends JObject
{
    var $_base		= '';
	
	var $files 		= array();
    
    var $start 		= '';
    
    var $end 		= '';
    
    /**
     * Constructor activating the default information of the class
     *
     * @access	protected
     */
    function __construct($config = array()){
    	$this->setProperties($config);
    }
    
    function setFiles($files = array())
    {
    	$this->files = $files;
    }
    
    function getFiles()
    {
    	return $this->files;
    }
    
	function setContentStart($start = '')
    {
    	$this->start = $start;
    }
    
    function getContentStart()
    {
    	return $this->start;
    }
    
	function setContentEnd($end = '')
    {
    	$this->end = $end;
    }
    
    function getContentEnd()
    {
    	return $this->end;
    }
    
	function pack()
	{
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		
		$gzip = false;
		
		// Headers
		header("Content-type: text/javascript; charset: UTF-8");
		header("Cache-Control: must-revalidate");
		header("Vary: Accept-Encoding");  // Handle proxies
		header("Expires: " . gmdate("D, d M Y H:i:s", time() + 3600 * 24 * 7) . " GMT");
		
		$files = $this->getFiles();
		
		// Check if it supports gzip
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']))
			$encodings = explode(',', strtolower(preg_replace("/\s+/", "", $_SERVER['HTTP_ACCEPT_ENCODING'])));
	
		if ((in_array('gzip', $encodings) || in_array('x-gzip', $encodings) || isset($_SERVER['---------------'])) && function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
			$enc = in_array('x-gzip', $encodings) ? "x-gzip" : "gzip";
			$gzip = true;
		} else {
			// flag $gzip false regardless of parameter
			$gzip = false;
		}
		
		$content = $this->getContentStart();
		
		foreach ($files as $file) {
			$text = $this->getText($file);			
			$content .= $text;
		}
		
		$content .= $this->getContentEnd();

		// Generate GZIP'd content
		if ($gzip) {
			header("Content-Encoding: " . $enc);
			$data = gzencode($content, 9, FORCE_GZIP);

			// Stream to client
			die($data);
		} else {
			// Stream uncompressed content
			die($content);
		}
	}
	
	function getText($file)
	{		
		if ($file && JFile::exists($file)) {				
			return @JFile::read($file);
		}
		
		return '';
	}
}
?>