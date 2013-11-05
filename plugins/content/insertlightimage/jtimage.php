<?php
defined('_VALID_MOS') or defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 * JTImage
 *
 * @package JoomlaTune.Framework
 * @subpackage Image
 * @author Dmitry M. Litvinov
 * @copyright 2008 by Dmitry M. Litvinov (http://joomlatune.ru)
 * @version $Id$
 * @access public
 */


class JTImage
{
	var $image = null;
	var $imageName = null;
	var $imageType = null;
	var $contentType = null;
	
	/**
	 * This method is created by the object of this class, or returns reference on already existing
	 *
	 * @param
	 * @return JTImage $instance
	 */
	function &getInstance()
	{
		static $instance;
		if (!is_object($instance)) {
			$instance = new JTImage();
		}
		return $instance;
	}
	
	/**
	 * This method is returned by an image
	 *
	 * @param
	 * @return JTImage $image
	 */
	function createImage( $width = 0, $height = 0 )
	{
		//SWFSprite //TODO : написать функцию создания изображения с заданным размером и заданного
	//формата
	}

	
	/**
	 * This method is returned by an image
	 *
	 * @param
	 * @return JTImage $image
	 */
	function getImage()
	{
		return $this->image;
	}
	
	/**
	 * This method set image resource to intrnal image
	 *
	 * @param
	 */
	
	function setImage($image){
		$this->image = $image;
	}
	
	/**
	 * This method returns the width of image
	 *
	 * @param void
	 * @return int
	 */
	function getWidth()
	{
		return imagesx($this->image);
	}
	
	/**
	 * This method returns the height of image
	 *
	 * @param void
	 * @return int
	 */
	function getHeight()
	{
		return imagesy($this->image);
	}
	
	/**
	 * This method is returned by the type of image, being on the passed way
	 *
	 * @param string $fileName
	 * @return
	 */
	function getType( $fileName )
	{
		$reference = array('jpg' => 'jpeg', 'jpeg' => 'jpeg', 'gif' => 'gif', 'png' => 'png');
		$extension = pathinfo($fileName);
		$extension = strtolower(@$extension['extension']);
		if ((!$extension) || (!isset($reference[$extension]))) {
			return false;
		}
		;
		return $reference[$extension];
	}
	
	/**
	 * JTImage::getConentType()
	 *
	 * @param
	 * @return string $ctype
	 */
	function getConentType()
	{
		switch ($this->imageType) {
			case "gif":
				$ctype = 'image/gif';
				break;
			case "png":
				$ctype = 'image/png';
				break;
			case "jpeg":
			default:
				$ctype = 'image/jpeg';
				break;
		}
		return $ctype;
	}
	
	/**
	 * This method is loaded by an image from a file, being on a transferrable way, in the variable of class
	 *
	 * @param string $fileName
	 * @return boolean
	 */
	function load( $fileName )
	{
		$this->imageType = $this->getType($fileName);
		$this->contentType = $this->getConentType($this->imageType);
		$action = 'imagecreatefrom' . $this->imageType;
		if (function_exists($action)) {
			//echo $fileName;
			$this->imageName = $fileName;
			$this->image = $action($this->imageName);
			return true;
		}
		return false;
	}
	
	/**
	 * This method is saved by an image, being in the field of class, in a file with the indicated name.
	 * The type of the created file will coincide with the type of file from which a load of image was in the field class
	 *
	 * @param string $fileName
	 * @return void
	 */
	function save( $fileName = '' )
	{
		if (isset($this->imageType) && isset($this->image)) {
			$action = 'image' . $this->imageType;
			if ($fileName != '') {
				$action($this->image, $fileName);
			} else {
				$action($this->image, $this->imageName);
			}
		}
	}
	
	/**
	 * This method is shown by an image being in the field of class
	 *
	 * @return void
	 */
	function display()
	{
		$action = 'imagecreatefrom' . $this->imageType;
		if (function_exists($action)) {
			$image = $action($this->image);
		}
		header("Content-type: " . $this->contentType);
		switch ($this->imageType) {
			case "gif":
				imagegif($this->image, '');
				break;
			case "png":
				imagepng($this->image, '', 75, 0);
				break;
			case "jpeg":
			default:
				imagejpeg($this->image, '', 75);
				break;
		}
		imagedestroy($image);
		die();
	}
	
	/**
	 * This method applies to the image, to contained in the field of class, a filter
	 * is characterized transferrable a method by a parameter
	 *
	 * @param mixed $filter
	 * @return boolean
	 */
	function filter( $filter )
	{
		if (is_a($filter, 'JTImageFilter')) {
			$filter->apply($this->image);
		}
	}
	
	/**
	 * This method is changed by the size of image, being in the field of class, to the sizes, indicated as transferrable parameters
	 *
	 * @param integer $width
	 * @param integer $height
	 * @return void
	 */
	function resize( $width = 0, $height = 0, $dimension = 0 )
	{
		if ($this->image != null) {
			$image_width = imagesx($this->image);
			$image_height = imagesy($this->image);
			
			if (($image_width > $dimension) || ($image_height > $dimension)) {
				$xratio = $image_width / $dimension;
				$yratio = $image_height / $dimension;
				
				$ratio = max($xratio, $yratio);
				
				$thumbnail_width = ceil($image_width / $ratio);
				$thumbnail_height = ceil($image_height / $ratio);
				
				$thumbnail = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
				@imagecolortransparent($thumbnail, 0);
				@imagealphablending($thumbnail, false);
				
				if (function_exists("imagecopyresampled")) {
					if (!@imagecopyresampled($thumbnail, $this->image, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $image_width, $image_height)) {
						imagecopyresized($thumbnail, $this->image, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $image_width, $image_height);
					}
				} else {
					imagecopyresized($thumbnail, $this->image, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $image_width, $image_height);
				}
			} else {
				$thumbnail = imagecreatetruecolor($image_width, $image_height);
				@imagecolortransparent($thumbnail, 0);
				@imagealphablending($thumbnail, false);
				@imagecopy($thumbnail, $this->image, 0, 0, 0, 0, $image_width, $image_height);
			}
			$this->image = $thumbnail;
		} else {
			echo "File was not load. Resize file after loading";
		}
	}
	
	/**
	 * This method cuts out from an image, contained in the field of class, part of the set width and height since the set co-ordinates
	 *  and writes down the got image in the field of class
	 *
	 * @param integer $width
	 * @param integer $height
	 * @param integer $x
	 * @param integer $y
	 * @return boolean
	 */
	function clip( $width, $height, $x = 0, $y = 0 )
	{
		if ($this->image != null) {
			$clip = imagecreatetruecolor($width, $height);
			@imagecolortransparent($clip, 0);
			@imagealphablending($clip, false);
			imagecopymerge($clip, $this->image, 0, 0, $x, $y, $width, $height, 100);
			$this->image = $clip;
			return true;
		} else {
			echo "File was not load. Clip file afte loading";
			return false;
		}
	}
	
	
	function toAspect( $width, $height )
	{
		if ($this->image != null) {
			$image_width = imagesx($this->image);
			$image_height = imagesy($this->image);
			
			$ratio = $width / $height;
			$image_ratio = $image_width / $image_height;
			
			$semiresult = $ratio / $image_ratio;
			//echo $ratio.' -- '.$semiresult . '<br />';
			if ($ratio < 1) {
				if ($semiresult < 1) {
					$result_height = $image_height;
					$result_width = $image_height * $width / $height;
				} else {
					$result_height = $image_height;
					$result_width = $image_height / $height * $width;
				}
			} else {
				if ($semiresult < 1) {
				$result_height = $image_height;
				$result_width = ($image_height / $height) * $width;
				} else {
					$result_width = $image_width;
					$result_height = ($image_width / $width) * $height;
				}
			}
			
			$this->clip($result_width,$result_height,0,0);
			
			return true;
		
		} else {
			echo "File was not load. Aspected file after loading";
			return false;
		}
	}
}

?>