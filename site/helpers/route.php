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
    protected static $lookup = array();

    /**
     * Helper to find the right item route
     *
     * @param   integer  $id The item id
     *
     * @return string $link The route string
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
        
        if ($language && $language != "*" && JLanguageMultilang::isEnabled())
        {
            $db     = JFactory::getDbo();
            $query  = $db->getQuery(true)
                ->select('a.sef AS sef')
                ->select('a.lang_code AS lang_code')
                ->from('#__languages AS a');

            $db->setQuery($query);
            $langs = $db->loadObjectList();
            foreach ($langs as $lang)
            {
                if ($language == $lang->lang_code)
                {
                    $link .= '&lang='.$lang->sef;
                    $needles['language'] = $language;
                }
            }
        }
        
        if ($itemId = self::_findItemid($needles)) {
            $link .= '&Itemid='.$itemId;
        } elseif ($item = self::_findItem()) { // Find without language specific
            $link .= '&Itemid='.$item;
        }
        
        return $link;
    }
    
    /**
     * Helper to find the category route
     *
     * @param integer|JCategoryNode $catid The category id
     *
     * @return string $link The route
     */
    public static function getCategoryRoute($catid, $language = 0)
    {
        if ($catid instanceof JCategoryNode) {
            $id = $catid->id;
            $category = $catid;
        } else {
            $id = (int) $catid;
            $category = JCategories::getInstance('dzproduct.items')->get($id);
        }

        if ($id < 1)
        {
            $link = '';
        }
        else
        {
            $link = 'index.php?option=com_dzproduct&view=category&id='.$id;

            $needles = array(
                'category' => array($id)
            );

            if ($language && $language != "*" && JLanguageMultilang::isEnabled()) {
                $db     = JFactory::getDbo();
                $query  = $db->getQuery(true)
                    ->select('a.sef AS sef')
                    ->select('a.lang_code AS lang_code')
                    ->from('#__languages AS a');

                $db->setQuery($query);
                $langs = $db->loadObjectList();
                foreach ($langs as $lang)
                {
                    if ($language == $lang->lang_code)
                    {
                        $link .= '&lang='.$lang->sef;
                        $needles['language'] = $language;
                    }
                }
            }
            
            if ($item = self::_findItemid($needles)) {
                $link .= '&Itemid='.$item;
            } else {
                //Create the link
                if ($category)
                {
                    $catids = array_reverse($category->getPath());
                    $needles['category'] = $catids;

                    if ($item = self::_findItemid($needles)) {
                        $link .= '&Itemid='.$item;
                    } elseif ($item = self::_findItemid()) {
                        $link .= '&Itemid='.$item;
                    }
                }
            }
        }

        return $link;
    }


    protected  static function _findItemid($needles = null)
    {
        $app        = JFactory::getApplication();
        $menus      = $app->getMenu('site');
        $language   = isset($needles['language']) ? $needles['language'] : '*';
        
        // Prepare the reverse lookup array.
        if (!isset(self::$lookup[$language])) {
            self::$lookup[$language] = array();

            $component  = JComponentHelper::getComponent('com_dzproduct');
            
            $attributes = array('component_id');
            $values = array($component->id);

            if ($language != '*')
            {
                $attributes[] = 'language';
                $values[] = array($needles['language'], '*');
            }

            $items      = $menus->getItems($attributes, $values);
            
            foreach ($items as $item)
            {
                if (isset($item->query) && isset($item->query['view']))
                {
                    $view = $item->query['view'];
                    
                    if (!isset(self::$lookup[$language][$view]))
                        self::$lookup[$language][$view] = array();
                    
                    if (isset($item->query['id'])) {
                        self::$lookup[$language][$view][$item->query['id']] = $item->id;
                    }
                }
            }
        }
        
        if ($needles) {
            foreach ($needles as $view => $ids) {
                if (isset(self::$lookup[$language][$view])) {
                    foreach ($ids as $id) {
                        if (isset(self::$lookup[$language][$view][$id]))
                            return self::$lookup[$language][$view][$id];
                    }
                }
            }
        }
        
        // In case we do not find anything using the needles, 
        // We just take the active menu item instead
       $active = $menus->getActive();
        if ($active && $active->component == 'com_dzproduct' && ($active->language == '*' || !JLanguageMultilang::isEnabled())) {
            return $active->id;
        }

        // if not found, return language specific home link
        $default = $menus->getDefault($language);
        return !empty($default->id) ? $default->id : null;
    }
}
