<?php
/**
 * @version     1.1.0
 * @package     com_dzproduct
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

// No direct access
defined('_JEXEC') or die;

/**
 * @param   array   A named array
 * @return  array
 */
function DzproductBuildRoute(&$query)
{
    $segments = array();

    // get a menu item based on Itemid or currently active
    $app = JFactory::getApplication();
    $menu = $app->getMenu();

    // we need a menu item.  Either the one specified in the query, or the current active one if none specified
    if (empty($query['Itemid']))
    {
        $menuItem = $menu->getActive();
        $menuItemGiven = false;
    }
    else
    {
        $menuItem = $menu->getItem($query['Itemid']);
        $menuItemGiven = true;
    }

    // check again
    if ($menuItemGiven && isset($menuItem) && $menuItem->component != 'com_dzproduct')
    {
        $menuItemGiven = false;
        unset($query['Itemid']);
    }

    if (isset($query['view']))
    {
        $view = $query['view'];
    }
    else
    {
        // we need to have a view in the query or it is an invalid URL
        return $segments;
    }
    
    if ($menuItem instanceof stdClass) { 
        // are we dealing with an item that is attached to a menu item?
        if($menuItem->query['view'] == $query['view'] && isset($query['id']) && $menuItem->query['id'] == (int) $query['id'])
        {
            unset($query['view']);

            if (isset($query['layout']))
            {
                unset($query['layout']);
            }
            
            if (isset($query['catid']))
            {
                unset($query['catid']);
            }
            
            unset($query['id']);

            return $segments;
        }
        
        // We can imply a single view from its list view
        // Thus we can remove view from the query in some cases
//         if ($menuItem->query['view'] == 'category' && $query['view'] == 'item')
//             unset($query['view']);
    }
    

    if ($view == 'item' || $view == 'category')
    {
        if (!$menuItemGiven)
        {
            $segments[] = $view;
        }

        unset($query['view']);
        
        if ($view == 'item') {
            if (isset($query['id']) && isset($query['catid']) && $query['catid'])
            {
                $catid = $query['catid'];
                
                // Make sure we have the id and the alias
                if (strpos($query['id'], ':') === false)
                {
                    $db = JFactory::getDbo();
                    $dbQuery = $db->getQuery(true)
                        ->select('alias')
                        ->from('#__dzproduct_items')
                        ->where('id=' . (int) $query['id']);
                    $db->setQuery($dbQuery);
                    $alias = $db->loadResult();
                    $query['id'] = $query['id'] . ':' . $alias;
                }
            } else {
                // we should have id set for this view.  If we don't, it is an error
                return $segments;
            }
        } else {
            if (isset($query['id'])) {
                $catid = $query['id'];
            } else {
                // we should have id set for this view.  If we don't, it is an error
                return $segments;
            }
        }
        
        if ($menuItemGiven && isset($menuItem->query['id'])) {
            $mCatid = $menuItem->query['id'];
        } else {
            $mCatid = 0;
        }
        
        $categories = JCategories::getInstance('dzproduct.items');
        $category = $categories->get($catid);

        if (!$category) {
            // we couldn't find the category we were given.  Bail.
            return $segments;
        }
        
        $path = array_reverse($category->getPath());

        $array = array();

        foreach ($path as $id) {
            if ((int) $id == (int) $mCatid) {
                break;
            }

            list($tmp, $id) = explode(':', $id, 2);

            $array[] = $id;
        }

        $array = array_reverse($array);

        if (count($array)) {
            $array[0] = (int) $catid . ':' . $array[0];
        }

        $segments = array_merge($segments, $array);

        if ($view == 'item') {
            $segments[] = $query['id'];
        }
        
        unset($query['id']);
        unset($query['catid']);
    } else if ($view == 'order') {
        unset($query['view']);
    }

    // if the layout is specified and it is the same as the layout in the menu item, we
    // unset it so it doesn't go into the query string.
    if (isset($query['layout']))
    {
        if ($menuItemGiven && isset($menuItem->query['layout']))
        {
            if ($query['layout'] == $menuItem->query['layout'])
            {
                unset($query['layout']);
            }
        }
        else
        {
            if ($query['layout'] == 'default')
            {
                unset($query['layout']);
            }
        }
    }
    
    return $segments;
}

/**
 * @param   array   A named array
 * @param   array
 *
 * Formats:
 *
 * index.php?/dzproduct/task/id/Itemid
 *
 * index.php?/dzproduct/id/Itemid
 */
function DzproductParseRoute($segments)
{
    $vars = array();
    
    $app = JFactory::getApplication();
    $menu = $app->getMenu();
    $menuItem = $menu->getActive();
    
    // Count route segments
    $count = count($segments);
    
    // Standard routing for items
    if (!isset($item)) {
        $vars['view'] = $segments[0];
        $vars['id'] = $segments[$count - 1];
    }
    
    // If there is only one segment, this may be either a category or an item
    // Test whether the category exists, if not it would be an item instead
    if ($count == 1) {
        // we check to see if an alias is given.  If not, we assume it is an item
        if (strpos($segments[0], ':') === false)
        {
            $vars['view'] = 'item';
            $vars['id'] = (int) $segments[0];
            return $vars;
        }
        
        list($id, $alias) = explode(':', $segments[0], 2);
        
        // first we check if it is a category
        $category = JCategories::getInstance('dzproduct.items')->get($id);
        
        if ($category && $category->alias == $alias) {
            $vars['view'] = 'category';
            $vars['id'] = $id;

            return $vars;
        } else {
            $query = 'SELECT alias, catid FROM #__dzproduct_items WHERE id = ' . (int) $id;
            $db = JFactory::getDbo();
            $db->setQuery($query);
            $item = $db->loadObject();

            if ($item)
            {
                if ($item->alias == $alias)
                {
                    $vars['view'] = 'item';
                    $vars['catid'] = (int) $item->catid;
                    $vars['id'] = (int) $id;

                    return $vars;
                }
            }
        }
    }
    
    // if there was more than one segment, then we can determine where the URL points to
    // because the first segment will have the target category id prepended to it.  If the
    // last segment has a number prepended, it is an item, otherwise, it is a category.
    $cat_id = (int) $segments[0];

    $item_id = (int) $segments[$count - 1];

    if ($item_id > 0)
    {
        $vars['view'] = 'item';
        $vars['catid'] = $cat_id;
        $vars['id'] = $item_id;
    }
    else
    {
        $vars['view'] = 'category';
        $vars['id'] = $cat_id;
    }
    
    return $vars;
}
