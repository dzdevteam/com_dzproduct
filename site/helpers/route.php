<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * DZ Product Route Helper
 *
 * @static
 * @package     com_dzproduct
 */
abstract class DZProductHelperRoute
{
    protected static $lookup = null;

    /**
     * @param   integer  The route of the content item
     */
    public static function getItemRoute($id, $catid = 0, $language = 0)
    {
        //Create the link
        $link = 'index.php?option=com_dzproduct&view=item&id='. $id;
        
        $needles = array(
            'item' => array((int) $id)
        );
        
        if ((int) $catid > 1) {
            $categories = JCategories::getInstance('dzproduct.items');
            $category = $categories->get((int) $catid);
            if ($category) {
                $needles['category'] = array_reverse($category->getPath());
                $link .= '&catid='.$catid;
            }
        }
        if ($itemId = self::_findItemid($needles))
            $link .= '&Itemid='.$itemId;
        
        return $link;
    }


    protected  static function _findItemid(array $needles)
    {
        $app        = JFactory::getApplication();
        $menus      = $app->getMenu('site');
        
        // Prepare the reverse lookup array.
        if (self::$lookup === null)
        {
            self::$lookup = array();

            $component  = JComponentHelper::getComponent('com_dzproduct');
            $items      = $menus->getItems('component_id', $component->id);
            
            foreach ($items as $item)
            {
                if (isset($item->query) && isset($item->query['view']))
                {
                    $view = $item->query['view'];
                    
                    if (!isset(self::$lookup[$view]))
                        self::$lookup[$view] = array();
                    
                    if (isset($item->query['id'])) {
                        self::$lookup[$view][$item->query['id']] = $item->id;
                    }
                }
            }
        }
        
        foreach ($needles as $view => $ids) {
            if (isset(self::$lookup[$view])) {
                foreach ($ids as $id) {
                    if (isset(self::$lookup[$view][$id]))
                        return self::$lookup[$view][$id];
                }
            }
        }
            
        return null;
    }
}
