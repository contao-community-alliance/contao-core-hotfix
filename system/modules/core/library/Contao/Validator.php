<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Library
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;


/**
 * Validates arbitrary data
 * 
 * Usage:
 * 
 *     if (Validator::isEmail($recipient))
 *     {
 *         $email->sendTo($recipient);
 *     }
 * 
 * @package   Library
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2005-2013
 */
class Validator
{

	/**
	 * Numeric characters (including full stop [.] minus [-] and space [ ])
	 * 
	 * @param mixed $varValue The value to be validated
	 * 
	 * @return boolean True if the value is numeric
	 */
	public static function isNumeric($varValue)
	{
		return preg_match('/^[\d \.-]*$/', $varValue);
	}


	/**
	 * Alphabetic characters (including full stop [.] minus [-] and space [ ])
	 * 
	 * @param mixed $varValue The value to be validated
	 * 
	 * @return boolean True if the value is alphabetic
	 */
	public static function isAlphabetic($varValue)
	{
		if (function_exists('mb_eregi'))
		{
			return mb_eregi('^[[:alpha:] \.-]*$', $varValue);
		}
		else
		{
			return preg_match('/^[\pL \.-]*$/u', $varValue);
		}
	}


	/**
	 * Alphanumeric characters (including full stop [.] minus [-], underscore [_] and space [ ])
	 * 
	 * @param mixed $varValue The value to be validated
	 * 
	 * @return boolean True if the value is alphanumeric
	 */
	public static function isAlphanumeric($varValue)
	{
		if (function_exists('mb_eregi'))
		{
			return mb_eregi('^[[:alnum:] \._-]*$', $varValue);
		}
		else
		{
			return preg_match('/^[\pN\pL \._-]*$/u', $varValue);
		}
	}


	/**
	 * Characters that are usually encoded by class Input [=<>()#/])
	 * 
	 * @param mixed $varValue The value to be validated
	 * 
	 * @return boolean True if the value does not match the characters
	 */
	public static function isExtendedAlphanumeric($varValue)
	{
		return !preg_match('/[#\(\)\/<=>]/', $varValue);
	}


	/**
	 * Valid date formats
	 * 
	 * @param mixed $varValue The value to be validated
	 * 
	 * @return boolean True if the value is a valid date format
	 */
	public static function isDate($varValue)
	{
		return preg_match('~^'. \Date::getRegexp(\Date::getNumericDateFormat()) .'$~i', $varValue);
	}


	/**
	 * Valid time formats
	 * 
	 * @param mixed $varValue The value to be validated
	 * 
	 * @return boolean True if the value is a valid time format
	 */
	public static function isTime($varValue)
	{
		return preg_match('~^'. \Date::getRegexp(\Date::getNumericTimeFormat()) .'$~i', $varValue);
	}


	/**
	 * Valid date and time formats
	 * 
	 * @param mixed $varValue The value to be validated
	 * 
	 * @return boolean True if the value is a valid date and time format
	 */
	public static function isDatim($varValue)
	{
		return preg_match('~^'. \Date::getRegexp(\Date::getNumericDatimFormat()) .'$~i', $varValue);
	}


	/**
	 * Valid e-mail address
	 * 
	 * @param mixed $varValue The value to be validated
	 * 
	 * @return boolean True if the value is a valid e-mail address
	 */
	public static function isEmail($varValue)
	{
		return preg_match('/^(\w+[!#\$%&\'\*\+\-\/=\?^_`\.\{\|\}~]*)+(?<!\.)@\w+([_\.-]*\w+)*\.[A-Za-z]{2,6}$/', \Idna::encodeEmail($varValue));
	}


	/**
	 * Valid URL
	 * 
	 * @param mixed $varValue The value to be validated
	 * 
	 * @return boolean True if the value is a valid URL
	 */
	public static function isUrl($varValue)
	{
		return preg_match('/^[a-zA-Z0-9\.\+\/\?#%:,;\{\}\(\)\[\]@&=~_-]*$/', \Idna::encodeUrl($varValue));
	}


	/**
	 * Valid alias name
	 * 
	 * @param mixed $varValue The value to be validated
	 * 
	 * @return boolean True if the value is a valid alias name
	 */
	public static function isAlias($varValue)
	{
		if (function_exists('mb_eregi'))
		{
			return mb_eregi('^[[:alnum:]\._-]*$', $varValue);
		}
		else
		{
			return preg_match('/^[\pN\pL\._-]*$/u', $varValue);
		}
	}


	/**
	 * Valid folder alias name
	 * 
	 * @param mixed $varValue The value to be validated
	 * 
	 * @return boolean True if the value is a valid folder alias name
	 */
	public static function isFolderAlias($varValue)
	{
		if (function_exists('mb_eregi'))
		{
			return mb_eregi('^[[:alnum:]\/\._-]*$', $varValue);
		}
		else
		{
			return preg_match('/^[\pN\pL\/\._-]*$/u', $varValue);
		}
	}


	/**
	 * Valid phone number
	 * 
	 * @param mixed $varValue The value to be validated
	 * 
	 * @return boolean True if the value is a valid phone number
	 */
	public static function isPhone($varValue)
	{
		return preg_match('/^(\+|\()?(\d+[ \+\(\)\/-]*)+$/', $varValue);
	}


	/**
	 * Valid percentage
	 * 
	 * @param mixed $varValue The value to be validated
	 * 
	 * @return boolean True if the value is a valid percentage
	 */
	public static function isPercent($varValue)
	{
		return (is_numeric($varValue) && $varValue >= 0 && $varValue <= 100);
	}


	/**
	 * Insecure path potentially containing directory traversal
	 *
	 * @param string $varValue The file path
	 *
	 * @return boolean True if the file path is insecure
	 */
	public static function isInsecurePath($strPath)
	{
		// Normalize backslashes
		$strPath = str_replace('\\', '/', $strPath);
		$strPath = preg_replace('#/+#', '/', $strPath);

		// Begins with ./
		if (substr($strPath, 0, 2) == './')
		{
			return true;
		}

		// Begins with ../
		if (substr($strPath, 0, 3) == '../')
		{
			return true;
		}

		// Ends with /.
		if (substr($strPath, -2) == '/.')
		{
			return true;
		}

		// Ends with /..
		if (substr($strPath, -3) == '/..')
		{
			return true;
		}

		// Contains /../
		if (strpos($strPath, '/../') !== false)
		{
			return true;
		}

		return false;
	}
}
