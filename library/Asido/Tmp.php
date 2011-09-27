<?php

/**
* Asido Temporary Object
*
* @package Asido
* @subpackage Asido.Core
*/
Class Asido_TMP {

	/**
	* Object for processing the source image
	* @var mixed
	* @access public
	*/
	var $source;

	/**
	* Source image filename
	* @var string
	* @access public
	*/
	var $source_filename;
	
	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
	
	/**
	* Object for processing the target image
	* @var mixed
	* @access public
	*/
	var $target;

	/**
	* Filename with which to save the processed file
	* @var string
	* @access public
	*/
	var $target_filename;

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

	/**
	* Image width
	* @var integer
	* @access public
	*/
	var $image_width;

	/**
	* Image height
	* @var integer
	* @access public
	*/
	var $image_height;

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
	
	/**
	* MIME-type with which to save the processed file
	* @var string
	* @access public
	*/
	var $save;

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
	
//--end-of-class--	
}
