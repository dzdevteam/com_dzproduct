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

/**
 * Methods supporting a list of Dzproduct records.
 */
class DzproductModelitems extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'title', 'a.title',
                'alias', 'a.alias',
                'catid', 'a.catid', 'cattitle', 'c.title',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created', 'a.created',
                'created_by', 'a.created_by',
                'video', 'a.video',
                'openurl', 'a.openurl',
                'price', 'a.price',
                'saleoff', 'a.saleoff',
                'language', 'a.language',
                'featured', 'a.featured',
                'new_arrival', 'a.new_arrival',
                'availability', 'a.availability',
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        //Filtering catid
        $this->setState('filter.catid', $app->getUserStateFromRequest($this->context.'.filter.catid', 'filter_catid', '', 'string'));

        // Load the parameters.
        $params = JComponentHelper::getParams('com_dzproduct');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.title', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param   string      $id A prefix for the store id.
     * @return  string      A store id.
     * @since   1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
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
        $query->select('c.title AS cattitle');
        $query->join('LEFT', '#__categories AS c ON c.id = a.catid');
        // Join over the user field 'created_by'
        $query->select('created_by.name AS created_by');
        $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

        
        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('a.state = '.(int) $published);
        } else if ($published === '') {
            $query->where('(a.state IN (0, 1))');
        }
    

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
            $query->where("a.catid = '".$db->escape($filter_catid)."'");
        }


        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems() {
        $items = parent::getItems();
        
        foreach ($items as &$item) {
            $registry = new JRegistry();
            $registry->loadString($item->images);
            $item->images = $registry->toArray();
            
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            
            $query->select("f.id, f.name, f.type, d.value")
                ->from("#__dzproduct_fields as f")
                ->join("INNER", "#__dzproduct_groups as g ON FIND_IN_SET(f.id, g.fields)")
                ->join("INNER", "#__dzproduct_groupcat_relations as r ON g.id = r.groupid AND r.catid = ".$item->catid)
                ->join("LEFT", "#__dzproduct_field_data as d ON f.id = d.fieldid AND d.itemid = ".$item->id);
            $db->setQuery($query);
            $item->fields = $db->loadAssocList();            
        }
        return $items;
    }

}
