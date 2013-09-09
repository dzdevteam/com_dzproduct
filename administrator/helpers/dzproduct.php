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
* DZProduct helper.
*/
class DZProductHelper
{
    protected static $_groupcatrelations = array();
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
            JText::_('COM_DZPRODUCT_TITLE_CATEGORIES'),
            "index.php?option=com_categories&extension=com_dzproduct.items.catid",
            $vName == 'categories.items'
        );
        
        if ($vName=='categories.items') {
            JToolBarHelper::title('DZ Products: Categories (Items - Item )');
            
            // A hack to use our categories template instead of built-in categories template
            $controller = JControllerLegacy::getInstance('', 'CategoriesController');
            $view       = $controller->getView();
            $view->addTemplatePath(JPATH_ADMINISTRATOR.'/components/com_dzproduct/views/categories/tmpl');
        }
        
        JHtmlSidebar::addEntry(
            JText::_('COM_DZPRODUCT_TITLE_FIELDS'),
            'index.php?option=com_dzproduct&view=fields',
            $vName == 'fields'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_DZPRODUCT_TITLE_GROUPS'),
            'index.php?option=com_dzproduct&view=groups',
            $vName == 'groups'
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
    
    /**
     * Get associated group for a given category
     */
    public static function getAssociatedGroup($catid)
    {
        if (empty(DZProductHelper::$_groupcatrelations)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('r.catid, g.name')
                  ->from('#__dzproduct_groupcat_relations as r')
                  ->join('INNER', '#__dzproduct_groups as g ON r.groupid = g.id AND g.state = 1');
            $db->setQuery($query);
            $result = $db->loadAssocList();
            foreach($result as $item) {
                DZProductHelper::$_groupcatrelations[$item['catid']] = $item['name'];
            }
            
        }

        if (isset(DZProductHelper::$_groupcatrelations[$catid]))
            return DZProductHelper::$_groupcatrelations[$catid];
        
        return JText::_('COM_DZPRODUCT_GROUPCATRELATION_N_A');
    }
}
