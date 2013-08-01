<?php
/**
 * @version     1.0.0
 * @package     com_dzproduct
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Dzproduct helper.
 */
class DzproductHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_DZPRODUCT_TITLE_ITEMS'),
			'index.php?option=com_dzproduct&view=items',
			$vName == 'items'
		);
		JHtmlSidebar::addEntry(
			'Categories (Items - Item )',
			"index.php?option=com_categories&extension=com_dzproduct.items.catid",
			$vName == 'categories.items'
		);
		
if ($vName=='categories.items.catid') {			
JToolBarHelper::title('DZ Products: Categories (Items - Item )');		
}		JHtmlSidebar::addEntry(
			JText::_('COM_DZPRODUCT_TITLE_FIELDS'),
			'index.php?option=com_dzproduct&view=fields',
			$vName == 'fields'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_DZPRODUCT_TITLE_GROUPS'),
			'index.php?option=com_dzproduct&view=groups',
			$vName == 'groups'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_DZPRODUCT_TITLE_RELATIONS'),
			'index.php?option=com_dzproduct&view=relations',
			$vName == 'relations'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_DZPRODUCT_TITLE_FIELDDATAS'),
			'index.php?option=com_dzproduct&view=fielddatas',
			$vName == 'fielddatas'
		);

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_dzproduct';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
