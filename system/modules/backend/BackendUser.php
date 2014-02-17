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
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class BackendUser
 *
 * Provide methods to manage back end users.
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Model
 */
class BackendUser extends User
{

	/**
	 * Current object instance (do not remove)
	 * @var object
	 */
	protected static $objInstance;

	/**
	 * Name of the corresponding table
	 * @var string
	 */
	protected $strTable = 'tl_user';

	/**
	 * Name of the current cookie
	 * @var string
	 */
	protected $strCookie = 'BE_USER_AUTH';

	/**
	 * Allowed excluded fields
	 * @var array
	 */
	protected $alexf = array();


	/**
	 * Initialize the object
	 */
	protected function __construct()
	{
		parent::__construct();

		$this->strIp = $this->Environment->ip;
		$this->strHash = $this->Input->cookie($this->strCookie);
	}


	/**
	 * Set the current referer and save the session
	 */
	public function __destruct()
	{
		$session = $this->Session->getData();

		// Main script
		if ($this->Environment->script == 'typolight/main.php' && $session['referer']['current'] != $this->Environment->requestUri && !$this->Input->get('act') && !$this->Input->get('key') && !$this->Input->get('token'))
		{
			$session['referer']['last'] = $session['referer']['current'];
			$session['referer']['current'] = $this->Environment->requestUri;
		}

		// File manager
		if ($this->Environment->script == 'typolight/files.php' && $session['referer']['current'] != $this->Environment->requestUri && !$this->Input->get('act') && !$this->Input->get('key') && !$this->Input->get('token'))
		{
			$session['fileReferer']['last'] = $session['referer']['current'];
			$session['fileReferer']['current'] = $this->Environment->requestUri;
		}

		// Store session data
		if (strlen($this->intId))
		{
			$this->Database->prepare("UPDATE " . $this->strTable . " SET session=? WHERE id=?")
						   ->execute(serialize($session), $this->intId);
		}
	}


	/**
	 * Extend parent getter class and modify some parameters
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'isAdmin':
				return $this->arrData['admin'] ? true : false;
				break;

			case 'groups':
				return is_array($this->arrData['groups']) ? $this->arrData['groups'] : array($this->arrData['groups']);
				break;

			case 'pagemounts':
				return is_array($this->arrData['pagemounts']) ? $this->arrData['pagemounts'] : (strlen($this->arrData['pagemounts']) ? array($this->arrData['pagemounts']) : false);
				break;

			case 'filemounts':
				return is_array($this->arrData['filemounts']) ? $this->arrData['filemounts'] : (strlen($this->arrData['filemounts']) ? array($this->arrData['filemounts']) : false);
				break;

			case 'fop':
				return is_array($this->arrData['fop']) ? $this->arrData['fop'] : (strlen($this->arrData['fop']) ? array($this->arrData['fop']) : false);
				break;

			default:
				return parent::__get($strKey);
				break;
		}
	}


	/**
	 * Return the current object instance (Singleton)
	 * @return object
	 */
	public static function getInstance()
	{
		if (!is_object(self::$objInstance))
		{
			self::$objInstance = new BackendUser();
		}

		return self::$objInstance;
	}


	/**
	 * Redirect to typolight/index.php if authentication fails
	 */
	public function authenticate()
	{
		// Do not redirect if authentication is successful
		if (parent::authenticate() || $this->Environment->script == 'typolight/index.php')
		{
			return;
		}

		$strRedirect = 'typolight/index.php';

		// Redirect to the last page visited on login
		if ($this->Environment->script == 'typolight/main.php' || $this->Environment->script == 'typolight/preview.php')
		{
			$strRedirect .= '?referer=' . base64_encode($this->Environment->request);
		}

		// Force JavaScript redirect on Ajax requests (IE requires an absolute link)
		if ($this->Environment->isAjaxRequest)
		{
			echo '<script type="text/javascript">location.replace("' . $strRedirect . '")</script>';
			exit;
		}

		$this->redirect($strRedirect);
	}


	/**
	 * Check whether the current user has a certain access right
	 * @param string
	 * @param array
	 * @return object
	 */
	public function hasAccess($field, $array)
	{
		if ($this->isAdmin)
		{
			return true;
		}

		if (is_array($this->$array) && array_intersect((array) $field, $this->$array))
		{
			return true;
		}

		// Enable all subfolders (filemounts)
		elseif ($array == 'filemounts')
		{
			foreach ($this->filemounts as $folder)
			{
				$return = preg_match('/^'.str_replace('/', '\/', $folder).'/i', $field[0]);
			}
		}

		return $return;
	}


	/**
	 * Return true if the current user is allowed to do the current operation on the current page
	 * @param int
	 * @param array
	 * @return boolean
	 */
	public function isAllowed($int, $row)
	{
		// Inherit CHMOD settings
		if (!$row['includeChmod'])
		{
			$pid = $row['pid'];

			$row['chmod'] = false;
			$row['cuser'] = false;
			$row['cgroup'] = false;

			$objParentPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
											->limit(1)
											->execute($pid);

			while (!$row['chmod'] && $pid > 0 && $objParentPage->numRows)
			{
				$pid = $objParentPage->pid;

				$row['chmod'] = $objParentPage->includeChmod ? $objParentPage->chmod : false;
				$row['cuser'] = $objParentPage->includeChmod ? $objParentPage->cuser : false;
				$row['cgroup'] = $objParentPage->includeChmod ? $objParentPage->cgroup : false;

				$objParentPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
												->limit(1)
												->execute($pid);
			}

			// Set default values
			if (!$row['chmod'])
			{
				$row['chmod'] = $GLOBALS['TL_CONFIG']['defaultChmod'];
			}
			if (!$row['cuser'])
			{
				$row['cuser'] = $GLOBALS['TL_CONFIG']['defaultUser'];
			}
			if (!$row['cgroup'])
			{
				$row['cgroup'] = $GLOBALS['TL_CONFIG']['defaultGroup'];
			}
		}

		// Set permissions
		$chmod = deserialize($row['chmod']);
		$chmod = is_array($chmod) ? $chmod : array($chmod);
		$permission = array('w'.$int);

		if (in_array($row['cgroup'], $this->groups))
		{
			$permission[] = 'g'.$int;
		}

		if ($row['cuser'] == $this->id)
		{
			$permission[] = 'u'.$int;
		}

		return count(array_intersect($permission, $chmod));
	}


	/**
	 * Set all user properties from a database record
	 * @param object
	 */
	protected function setUserFromDb()
	{
		$this->intId = $this->id;

		// Unserialize values
		foreach ($this->arrData as $k=>$v)
		{
			if (!is_numeric($v))
			{
				$this->$k = deserialize($v);
			}
		}

		$GLOBALS['TL_LANGUAGE'] = $this->language;
		$GLOBALS['TL_USERNAME'] = $this->username;

		$GLOBALS['TL_CONFIG']['showHelp'] = $this->showHelp;
		$GLOBALS['TL_CONFIG']['useRTE'] = $this->useRTE;
		$GLOBALS['TL_CONFIG']['thumbnails'] = $this->thumbnails;

		// Inherit permissions
		$always = array('alexf');
		$depends = array('modules', 'pagemounts', 'alpty', 'filemounts', 'fop', 'forms');

		// HOOK: Take custom permissions
		if (is_array($GLOBALS['TL_PERMISSIONS']) && count($GLOBALS['TL_PERMISSIONS'] > 0))
		{
		    $depends = array_merge($depends, $GLOBALS['TL_PERMISSIONS']);
		}

		// HOOK: add news archive permissions
		if (in_array('news', $this->Config->getActiveModules()))
		{
			$depends[] = 'news';
		}

		// HOOK: add calendar permissions
		if (in_array('calendar', $this->Config->getActiveModules()))
		{
			$depends[] = 'calendars';
		}

		// HOOK: add newsletters permissions
		if (in_array('newsletter', $this->Config->getActiveModules()))
		{
			$depends[] = 'newsletters';
		}

		// Overwrite user permissions if only group permissions shall be inherited
		if ($this->inherit == 'group')
		{
			foreach ($depends as $field)
			{
				$this->$field = array();
			}
		}

		// Merge permissions
		$inherit = in_array($this->inherit, array('group', 'extend')) ? array_merge($always, $depends) : $always;
		$time = time();

		foreach ((array) $this->groups as $id)
		{
			$objGroup = $this->Database->prepare("SELECT * FROM tl_user_group WHERE id=? AND disable!=1 AND (start='' OR start<?) AND (stop='' OR stop>?)")
									   ->limit(1)
									   ->execute($id, $time, $time);

			if ($objGroup->numRows > 0)
			{
				foreach ($inherit as $field)
				{
					$value = deserialize($objGroup->$field);

					if (is_array($value))
					{
						$this->$field = array_merge((is_array($this->$field) ? $this->$field : (strlen($this->$field) ? array($this->$field) : array())), $value);
						$this->$field = array_unique($this->$field);
					}
				}
			}
		}

		if (is_array($this->session))
		{
			$this->Session->setData($this->session);
			return;
		}

		$this->Database->prepare("UPDATE " . $this->strTable . " SET session='' WHERE id=?")
					   ->execute($this->intId);

		$this->session = array();
	}


	/**
	 * Generate the navigation menu and return it as array
	 * @return array
	 */
	public function navigation()
	{
		$arrModules = array();
		$session = $this->Session->getData();

		// Toggle nodes
		if ($this->Input->get('mtg'))
		{
			$session['backend_modules'][$this->Input->get('mtg')] = (isset($session['backend_modules'][$this->Input->get('mtg')]) && $session['backend_modules'][$this->Input->get('mtg')] == 0) ? 1 : 0;
			$this->Session->setData($session);
			$this->redirect(preg_replace('/(&(amp;)?|\?)mtg=[^& ]*/i', '', $this->Environment->request));
		}

		$arrInactiveModules = deserialize($GLOBALS['TL_CONFIG']['inactiveModules']);
		$blnCheckInactiveModules = is_array($arrInactiveModules);

		foreach ($GLOBALS['BE_MOD'] as $strGroupName=>$arrGroupModules)
		{
			if (count($arrGroupModules) && ($strGroupName == 'profile' || $this->hasAccess(array_keys($arrGroupModules), 'modules')))
			{
				$arrModules[$strGroupName]['icon'] = 'modMinus.gif';
				$arrModules[$strGroupName]['title'] = specialchars($arrModules[$strGroupName]['href']);
				$arrModules[$strGroupName]['label'] = (($label = is_array($GLOBALS['TL_LANG']['MOD'][$strGroupName]) ? $GLOBALS['TL_LANG']['MOD'][$strGroupName][0] : $GLOBALS['TL_LANG']['MOD'][$strGroupName]) != false) ? $label : $strGroupName;
				$arrModules[$strGroupName]['href'] = $this->addToUrl('mtg=' . $strGroupName);

				// Do not show modules if the group is closed
				if (isset($session['backend_modules'][$strGroupName]) && $session['backend_modules'][$strGroupName] < 1)
				{
					$arrModules[$strGroupName]['modules'] = false;
					$arrModules[$strGroupName]['icon'] = 'modPlus.gif';

					continue;
				}

				foreach ($arrGroupModules as $strModuleName=>$arrModuleConfig)
				{
					// Exclude inactive modules
					if ($blnCheckInactiveModules && in_array($strModuleName, $arrInactiveModules))
					{
						continue;
					}

					// Check access
					if ($strGroupName == 'profile' || $this->hasAccess($strModuleName, 'modules'))
					{
						$arrModules[$strGroupName]['modules'][$strModuleName] = $arrModuleConfig;
						$arrModules[$strGroupName]['modules'][$strModuleName]['title'] = specialchars($GLOBALS['TL_LANG']['MOD'][$strModuleName][0]);
						$arrModules[$strGroupName]['modules'][$strModuleName]['label'] = (($label = is_array($GLOBALS['TL_LANG']['MOD'][$strModuleName]) ? $GLOBALS['TL_LANG']['MOD'][$strModuleName][0] : $GLOBALS['TL_LANG']['MOD'][$strModuleName]) != false) ? $label : $strModuleName;
						$arrModules[$strGroupName]['modules'][$strModuleName]['icon'] = strlen($arrModuleConfig['icon']) ? sprintf(' style="background-image:url(\'%s\');"', $arrModuleConfig['icon']) : '';
						$arrModules[$strGroupName]['modules'][$strModuleName]['class'] = 'navigation ' . $strModuleName;
					}
				}
			}
		}

		return $arrModules;
	}
}
