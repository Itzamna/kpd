<?php
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.plugin.plugin' );

class plgContentInsertlightImage extends JPlugin {
	function plgContentInsertlightImage(&$subject, $params) {
		parent::__construct ( $subject, $params );
	}
	
	function onPrepareContent(&$article, &$params, $limitstart) {
		global $mainframe;
		
		if (! defined ( 'JT_LIGHTBOX_INSERT' )) {
			define ( 'JT_LIGHTBOX_INSERT', 1 );
			JHTML::_ ( 'behavior.modal' );
		}
		
		$regex = '/{resize\s*.*?}/i';
		
		$folder 		= $this->params->get ( 'folder' );
		$width 			= $this->params->get ( 'width' );
		$height 		= $this->params->get ( 'height' );
		$thumbfolder 	= $this->params->get ( 'thumbfolder' );
		$thumbprefix 	= $this->params->get ( 'thumbprefix' );
		
		if ($this->params->get ( 'enabled' ) == 0) {
			$article->text = preg_replace ( $regex, '', $article->text );
			return true;
		}
		if (! JFolder::exists ( JPATH_ROOT . DS . $folder . DS . 'thumbnails' )) {
			$article->text = preg_replace ( $regex, '', $article->text );
			return true;
		}
		
		preg_match_all ( $regex, $article->text, $matches );
		
		jimport ( 'joomla.filesystem.file' );

			require_once (JPATH_ROOT . DS . 'plugins' . DS . 'content' . DS . 'insertlightimage' . DS . 'jtimage.php');
			$imager = new JTImage ( );
		
		if (count ( $matches [0] ) > 0) {
			foreach ( $matches [0] as $match ) {
				$match_a = str_replace ( '{', '', $match );
				$match_a = str_replace ( '}', '', $match_a );
				$replacer = explode ( ':', $match_a );
				
				switch ($replacer [1]) {
					case 'normal' :
						$imagefile = $folder . '/' . $replacer [2];
						$thumbfile = $folder . '/'.$thumbfolder.'/' . $thumbprefix . $replacer [2];
						$scriptwidth = isset($replacer[3]) ? $replacer[3] : false;
						$scriptheight = isset($replacer[4]) ? $replacer[4] : false;
						break;
					case 'other' :
						$imagefile = $replacer[2] . '/' . $replacer [3];
						$thumbfile = $replacer[2] . '/'.$thumbfolder.'/' . $thumbprefix . $replacer [3];
						$scriptwidth = isset($replacer[3]) ? $replacer[4] : false;
						$scriptheight = isset($replacer[4]) ? $replacer[5] : false;
						break;
					case 'separate' :
						$imagefile = $replacer[3];
						$thumbfile = $replacer[2];
						$scriptwidth = isset($replacer[4]) ? $replacer[4] : false;
						$scriptheight = isset($replacer[5]) ? $replacer[5] : false;
						break;
					default :
						$article->text = str_replace ( $match, '', $article->text );
						break;
				}
				
				$imagepath = JPATH_ROOT . DS . str_replace('/', DS , $imagefile);
				$thumbpath = JPATH_ROOT . DS . str_replace('/', DS , $thumbfile);
				
				$err_level = error_reporting();
				error_reporting(0);
				$imager->load ( $imagepath );
				$imagewidth = $imager->getWidth ();
				$imageheight = $imager->getHeight ();
				
				if (($scriptwidth != false) && ($scriptheight != false )) {

					$dimension = min($scriptwidth,$scriptheight);
					
					$xratio = $imagewidth / $dimension;
					$yratio = $imageheight / $dimension;
				
					$ratio = max($xratio, $yratio);
				
					$thiswidth = ceil($imagewidth / $ratio);
					$thisheight = ceil($imageheight / $ratio);
				} else {
					$thiswidth = $imagewidth;
					$thisheight = $imageheight;		
				}
				error_reporting($err_level);
				if (JFile::exists ( $thumbpath )) {
					if ($this->params->get ( 'mode' ) == 0) {
						$article->text = str_replace ( $match, '<img src="' . $thumbfile . '" />', $article->text );
					} else {
						if (JFile::exists ( $imagepath )) {
							$article->text = str_replace ( $match, '<a class="modal"  href="' . JURI::root () . $imagefile . '" rel="{handler: \'iframe\', size: {x: ' . $thiswidth . ', y: ' . $thisheight . '}}"><img src="'. JURI::root () . $thumbfile . '" /></a>', $article->text );
						} else {
							$article->text = str_replace ( $match, '<img src="'. JURI::root () . $thumbfile . '" />', $article->text );
						}
					}
				
				} else {
					if (JFile::exists ($imagepath )) {
						$article->text = str_replace ( $match, '<a class="modal"  href="' . JURI::root () . $imagefile . '" rel="{handler: \'iframe\', size: {x: ' . $thiswidth . ', y: ' . $thisheight . '}}"><img src="plugins/content/insertlightimage/default.jpg" /></a>', $article->text );
					} else {
						$article->text = str_replace ( $match, '', $article->text );
					}
				}
			}
		}
	}

}


