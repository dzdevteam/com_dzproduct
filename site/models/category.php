<?php

/**
 * @version     1.0.0
 * @package     com_dzproduct
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
require_once JPATH_SITE.'/components/com_dzproduct/helpers/route.php';

/**
 * Methods supporting a list of Dzproduct records.
 */
class DzproductModelCategory extends JModelList {

    protected $_item = null;
    protected $_siblings = null;
    protected $_children = null;
    protected $_parent = null;
    
    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since   1.6
     */
    protected function populateState($ordering = null, $direction = null) {

        // Initialise variables.
        $app = JFactory::getApplication();
        
        // Load the parameters. Merge Global and Menu Item params into new object
        $params = $app->getParams();
        $menuParams = new JRegistry;

        if ($menu = $app->getMenu()->getActive())
        {
            $menuParams->loadString($menu->params);
        }

        $mergedParams = clone $menuParams;
        $mergedParams->merge($params);

        $this->setState('params', $mergedParams);
        
        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);

        $catid = $app->input->getInt('catid', 0);
        if ($catid) {
            $this->setState('filter.catid', $catid);
        }
        
        if(empty($ordering)) {
            $ordering = 'a.ordering';
        }

        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  JDatabaseQuery
     * @since   1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );

        $query->from('`#__dzproduct_items` AS a');

        
    // Join over the users for the checked out user.
    $query->select('uc.name AS editor');
    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
    
        // Join over the category 'catid'
        $query->select('catid.title AS catid_title');
        $query->join('LEFT', '#__categories AS catid ON catid.id = a.catid');
        // Join over the created by field 'created_by'
        $query->select('created_by.name AS created_by');
        $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
        

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.title LIKE '.$search.'  OR  a.alias LIKE '.$search.'  OR  a.short_desc LIKE '.$search.'  OR  a.long_desc LIKE '.$search.' )');
            }
        }

        

        //Filtering catid
        $filter_catid = $this->state->get("filter.catid");
        if ($filter_catid) {
            $query->where("a.catid = '".$filter_catid."'");
        }

        return $query;
    }

    public function getItems() {
        $items = parent::getItems();
        
        foreach ($items as &$item) {
            $item->link = JRoute::_(DZProductHelperRoute::getItemRoute($item->id));
            
            $registry = new JRegistry();
            $registry->loadString($item->images);
            $item->images = $registry->toArray();
            
            $registry = new JRegistry();
            $registry->loadString($item->other_images);
            $item->other_images = $registry->toArray();
            
            $registry = new JRegistry();
            $registry->loadString($item->params);
            $item->params = $registry->toArray();
            
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select("f.id, f.dname, f.type, d.value")
                ->from("#__dzproduct_fields as f")
                ->join("INNER", "#__dzproduct_groups as g ON FIND_IN_SET(f.id, g.fields)")
                ->join("INNER", "#__dzproduct_groupcat_relations as r ON g.id = r.groupid AND r.catid = ".$item->catid)
                ->join("LEFT", "#__dzproduct_field_data as d ON f.id = d.fieldid AND d.itemid = ".$item->id);
            $db->setQuery($query);
            $item->fields = $db->loadAssocList();
            foreach ($item->fields as &$field) {
                $registry = new JRegistry($field['dname']);
                $field['dname'] = $registry->toArray();
            }
        }
        
        return $items;
    }
    
    /**
     * Method to get category data for the current category
     *
     * @param   integer  An optional ID
     *
     * @return  object
     * @since   1.5
     */
    public function getCategory()
    {
        if (!is_object($this->_item))
        {
            $categories = JCategories::getInstance('dzproduct.items');
            $this->_item = $categories->get($this->getState('filter.catid', 'root'));

            // Compute selected asset permissions.
            if (is_object($this->_item))
            {
                // TODO: Why aren't we lazy loading the children and siblings?
                $this->_children = $this->_item->getChildren();
                $this->_parent = false;

                if ($this->_item->getParent())
                {
                    $this->_parent = $this->_item->getParent();
                }

                $this->_rightsibling = $this->_item->getSibling();
                $this->_leftsibling = $this->_item->getSibling(false);
            }
            else {
                $this->_children = false;
                $this->_parent = false;
            }
            
            $registry = new JRegistry();
            $registry->loadString($this->_item->params);
            $this->_item->params = $registry->toArray();
        }    

        return $this->_item;
    }
    
    /**
     * Get the parent category.
     *
     * @param   integer  An optional category id. If not supplied, the model state 'category.id' will be used.
     *
     * @return  mixed  An array of categories or false if an error occurs.
     * @since   1.6
     */
    public function getParent()
    {
        if (!is_object($this->_item))
        {
            $this->getCategory();
        }

        return $this->_parent;
    }
    
    /**
     * Get the left sibling (adjacent) categories.
     *
     * @return  mixed  An array of categories or false if an error occurs.
     * @since   1.6
     */
    function &getLeftSibling()
    {
        if (!is_object($this->_item))
        {
            $this->getCategory();
        }

        return $this->_leftsibling;
    }
    
    /**
     * Get the right sibling (adjacent) categories.
     *
     * @return  mixed  An array of categories or false if an error occurs.
     * @since   1.6
     */
    function &getRightSibling()
    {
        if (!is_object($this->_item))
        {
            $this->getCategory();
        }

        return $this->_rightsibling;
    }
    
    /**
     * Get the child categories.
     *
     * @param   integer  An optional category id. If not supplied, the model state 'category.id' will be used.
     *
     * @return  mixed  An array of categories or false if an error occurs.
     * @since   1.6
     */
    function &getChildren()
    {
        if (!is_object($this->_item))
        {
            $this->getCategory();
        }

        // Order subcategories
        if (count($this->_children))
        {
            $params = $this->getState()->get('params');
            if ($params->get('orderby_pri') == 'alpha' || $params->get('orderby_pri') == 'ralpha')
            {
                jimport('joomla.utilities.arrayhelper');
                JArrayHelper::sortObjects($this->_children, 'title', ($params->get('orderby_pri') == 'alpha') ? 1 : -1);
            }
        }

        return $this->_children;
    }
}
