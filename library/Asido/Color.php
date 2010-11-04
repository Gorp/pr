<?php
/**
* Asido Color
*
* This class stores common color-related routines
*
* @package Asido
* @subpackage Asido.Core
*/
Class Asido_Color {

	/**
	* Red Channel
	* @var integer
	* @access private
	*/
	var $_red = 0;
	
	/**
	* Green Channel
	* @var integer
	* @access private
	*/
	var $_green = 0;
	
	/**
	* Blue Channel
	* @var integer
	* @access private
	*/
	var $_blue = 0;

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

	/**
	* Set a new color
	*
	* @param integer $red	the value has to be from 0 to 255
	* @param integer $green	the value has to be from 0 to 255
	* @param integer $blue	the value has to be from 0 to 255
	* @access public
	*/
	function set($red, $green, $blue) {
		$this->_red = $red % 256;
		$this->_green = $green % 256;
		$this->_blue = $blue % 256;
		}

	/**
	* Get the stored color
	*
	* @return array	indexed array with three elements: one for each channel following the RGB order
	* @access public
	*/
	function get() {
		return array(
			$this->_red % 256,
			$this->_green % 256,
			$this->_blue % 256
			);
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	
}
