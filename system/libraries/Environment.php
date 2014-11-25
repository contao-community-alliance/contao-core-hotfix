<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class Environment
 *
 * Provide methods to get OS independent environment parameters.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Library
 */
class Environment
{

	/**
	 * Current object instance (Singleton)
	 * @var object
	 */
	protected static $objInstance;

	/**
	 * Cache array
	 * @var array
	 */
	protected $arrCache = array();


	/**
	 * Prevent direct instantiation (Singleton)
	 */
	protected function __construct() {}


	/**
	 * Prevent cloning of the object (Singleton)
	 */
	final private function __clone() {}


	/**
	 * Set an environment parameter
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrCache[$strKey] = $varValue;
	}


	/**
	 * Return an environment parameter
	 * @param  string
	 * @return string
	 * @throws Exception
	 */
	public function __get($strKey)
	{
		if (!array_key_exists($strKey, $this->arrCache))
		{
			if (in_array($strKey, get_class_methods($this)))
			{
				$this->arrCache[$strKey] = $this->$strKey();
				return $this->arrCache[$strKey];
			}

			$arrChunks = preg_split('/([A-Z]+[a-z]*)/', $strKey, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

			$strServerKey = strtoupper(implode('_', $arrChunks));
			$this->arrCache[$strKey] = $_SERVER[$strServerKey];
		}

		return $this->arrCache[$strKey];
	}


	/**
	 * Return the current object instance (Singleton)
	 * @return object
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new Environment();
		}

		return self::$objInstance;
	}


	/**
	 * Return the absolute path to the script (e.g. /home/www/html/website/index.php)
	 * @return string
	 */
	protected function scriptFilename()
	{
		if (!array_key_exists('script_filename', $this->arrCache))
		{
			$this->arrCache['scriptFilename'] = str_replace('//', '/', str_replace('\\', '/', (php_sapi_name() == 'cgi' || php_sapi_name() == 'isapi' || php_sapi_name() == 'cgi-fcgi') && ($_SERVER['ORIG_PATH_TRANSLATED'] ? $_SERVER['ORIG_PATH_TRANSLATED'] : $_SERVER['PATH_TRANSLATED']) ? ($_SERVER['ORIG_PATH_TRANSLATED'] ? $_SERVER['ORIG_PATH_TRANSLATED'] : $_SERVER['PATH_TRANSLATED']) : ($_SERVER['ORIG_SCRIPT_FILENAME'] ? $_SERVER['ORIG_SCRIPT_FILENAME'] : $_SERVER['SCRIPT_FILENAME'])));
		}

		return $this->arrCache['scriptFilename'];
	}


	/**
	 * Return the relative path to the script (e.g. /website/index.php)
	 * @return string
	 */
	protected function scriptName()
	{
		if (!array_key_exists('scriptName', $this->arrCache))
		{
			$this->arrCache['scriptName'] = (php_sapi_name() == 'cgi' || php_sapi_name() == 'cgi-fcgi') && ($_SERVER['ORIG_PATH_INFO'] ? $_SERVER['ORIG_PATH_INFO'] : $_SERVER['PATH_INFO']) ? ($_SERVER['ORIG_PATH_INFO'] ? $_SERVER['ORIG_PATH_INFO'] : $_SERVER['PATH_INFO']) : ($_SERVER['ORIG_SCRIPT_NAME'] ? $_SERVER['ORIG_SCRIPT_NAME'] : $_SERVER['SCRIPT_NAME']);
		}

		return $this->arrCache['scriptName'];
	}


	/**
	 * Alias for scriptName()
	 * @return string
	 */
	protected function phpSelf()
	{
		return $this->scriptName();
	}


	/**
	 * Return the document root (e.g. /home/www/user/)
	 *
	 * Calculated as SCRIPT_FILENAME minus SCRIPT_NAME as some CGI versions
	 * and mod-rewrite rules might return an incorrect DOCUMENT_ROOT.
	 * @return string
	 */
	protected function documentRoot()
	{
		if (!array_key_exists('documentRoot', $this->arrCache))
		{
			$strDocumentRoot = '';
			$arrUriSegments = array();

			// Fallback to DOCUMENT_ROOT if SCRIPT_FILENAME and SCRIPT_NAME point to different files
			if (basename($this->scriptName()) != basename($this->scriptFilename()))
			{
				return str_replace('//', '/', str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])));
			}

			if (substr($this->scriptFilename(), 0, 1) == '/')
			{
				$strDocumentRoot = '/';
			}

			$arrSnSegments = explode('/', strrev($this->scriptName()));
			$arrSfnSegments = explode('/', strrev($this->scriptFilename()));

			foreach ($arrSfnSegments as $k=>$v)
			{
				if ($arrSnSegments[$k] != $v)
				{
					$arrUriSegments[] = $v;
				}
			}

			$strDocumentRoot .= strrev(implode('/', $arrUriSegments));

			if (strlen($strDocumentRoot) < 2)
			{
				$strDocumentRoot = substr($arrSfnSegments, 0, -(strlen($strDocumentRoot) + 1));
			}

			$this->arrCache['documentRoot'] = str_replace('//', '/', str_replace('\\', '/', realpath($strDocumentRoot)));
		}

		return $this->arrCache['documentRoot'];
	}


	/**
	 * Return the query string (e.g. id=2)
	 *
	 * @return string The query string
	 */
	protected function queryString()
	{
		if (!isset($_SERVER['QUERY_STRING']))
		{
			return '';
		}

		return $this->encodeRequestString($_SERVER['QUERY_STRING']);
	}


	/**
	 * Return the request URI [path]?[query] (e.g. /contao/index.php?id=2)
	 *
	 * @return string
	 */
	protected function requestUri()
	{
		if (!empty($_SERVER['REQUEST_URI']))
		{
			$strRequest = $_SERVER['REQUEST_URI'];
		}
		else
		{
			$strRequest = '/' . preg_replace('/^\//', '', $this->scriptName) . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
		}

		return $this->encodeRequestString($strRequest);
	}


	/**
	 * Return accepted user languages as array
	 * @return array
	 */
	protected function httpAcceptLanguage()
	{
		if (!array_key_exists('httpAcceptLanguage', $this->arrCache))
		{
			$arrAccepted = array();
			$arrLanguages = explode(',', strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']));

			foreach ($arrLanguages as $strLanguage)
			{
				$arrAccepted[] = substr($strLanguage, 0, 2);
			}

			$this->arrCache['httpAcceptLanguage'] = array_values(array_unique($arrAccepted));
		}

		return $this->arrCache['httpAcceptLanguage'];
	}


	/**
	 * Return accepted encoding types as array
	 * @return array
	 */
	protected function httpAcceptEncoding()
	{
		if (!array_key_exists('httpAcceptEncoding', $this->arrCache))
		{
			$this->arrCache['httpAcceptEncoding'] = array_values(array_unique(explode(',', strtolower($_SERVER['HTTP_ACCEPT_ENCODING']))));
		}

		return $this->arrCache['httpAcceptEncoding'];
	}


	/**
	 * Return the user agent as string
	 * @return string
	 */
	protected function httpUserAgent()
	{
		if (!array_key_exists('httpUserAgent', $this->arrCache))
		{
			$ua = strip_tags($_SERVER['HTTP_USER_AGENT']);
			$ua = preg_replace('/javascript|vbscri?pt|script|applet|alert|document|write|cookie|window/i', '', $ua);

			$this->arrCache['httpUserAgent'] = $ua;
		}

		return $this->arrCache['httpUserAgent'];
	}


	/**
	 * Return true if the current page was requested via an SSL connection
	 * @return boolean
	 */
	protected function ssl()
	{
		if (!array_key_exists('ssl', $this->arrCache))
		{
			$this->arrCache['ssl'] = ($_SERVER['SSL_SESSION_ID'] || $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ? true : false;
		}

		return $this->arrCache['ssl'];
	}


	/**
	 * Return the current URL without path or query string
	 * @return string
	 */
	protected function url()
	{
		if (!array_key_exists('url', $this->arrCache))
		{
			$this->arrCache['url'] = ($this->ssl() ? 'https://' : 'http://') . (strlen($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] . '/' : '') . $_SERVER['HTTP_HOST'];
		}

		return $this->arrCache['url'];
	}


	/**
	 * Return the real REMOTE_ADDR even if a proxy server is used
	 * @return string
	 */
	protected function ip()
	{
		if (!array_key_exists('ip', $this->arrCache))
		{
			$this->arrCache['ip'] = strlen($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		}

		return $this->arrCache['ip'];
	}


	/**
	 * Return the SERVER_ADDR
	 * @return string
	 */
	protected function server()
	{
		if (!array_key_exists('server', $this->arrCache))
		{
			$this->arrCache['server'] = strlen($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];

			// Special workaround for Strato users
			if (!strlen($this->arrCache['server']))
			{
				$this->arrCache['server'] = @gethostbyname($_SERVER['SERVER_NAME']);
			}
		}

		return $this->arrCache['server'];
	}


	/**
	 * Return the relative path to the base directory (e.g. /path)
	 * @return string
	 */
	protected function path()
	{
		return TL_PATH;
	}


	/**
	 * Return the relativ path to the script (e.g. index.php)
	 * @return string
	 */
	protected function script()
	{
		if (!array_key_exists('script', $this->arrCache))
		{
			$this->arrCache['script'] = preg_replace('/^' . preg_quote(TL_PATH, '/') . '\/?/i', '', $this->scriptName());
		}

		return $this->arrCache['script'];
	}


	/**
	 * Return the relativ path to the script and include the request (e.g. index.php?id=2)
	 * @return string
	 */
	protected function request()
	{
		return preg_replace('/^' . preg_quote(TL_PATH, '/') . '\/?/', '', $this->requestUri);
	}


	/**
	 * Return the current URL and path that can be used in a <base> tag
	 * @return string
	 */
	protected function base()
	{
		if (!array_key_exists('base', $this->arrCache))
		{
			$this->arrCache['base'] = $this->url() . TL_PATH . '/';
		}

		return $this->arrCache['base'];
	}


	/**
	 * Return the current host name
	 * @return string
	 */
	protected function host()
	{
		if (!array_key_exists('host', $this->arrCache))
		{
			$parse_url = parse_url($this->url());
			$this->arrCache['host'] = preg_replace('/^www\./i', '', $parse_url['host']);
		}

		return $this->arrCache['host'];
	}


	/**
	 * Encode a request string preserving certain reserved characters
	 *
	 * @param string $strRequest The request string
	 *
	 * @return string The encoded request string
	 */
	protected function encodeRequestString($strRequest)
	{
		return preg_replace_callback('/[^A-Za-z0-9\-_.~&=+,\/?%\[\]]+/', function($matches) { return rawurlencode($matches[0]); }, $strRequest);
	}
}

?>