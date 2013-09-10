<?php
/**
 * @version     1.0.0
 * @package     com_dzproduct
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

/**
 * Dzproduct model.
 */
class DzproductModelItem extends JModelForm
{
    
    var $_item = null;
    
    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since   1.6
     */
    protected function populateState()
    {
        $app = JFactory::getApplication('com_dzproduct');

        // Load state from the request userState on edit or from the passed variable on default
        if (JFactory::getApplication()->input->get('layout') == 'edit') {
            $id = JFactory::getApplication()->getUserState('com_dzproduct.edit.item.id');
        } else {
            $id = JFactory::getApplication()->input->get('id');
            JFactory::getApplication()->setUserState('com_dzproduct.edit.item.id', $id);
        }
        $this->setState('item.id', $id);

        // Load the parameters.
        $params = $app->getParams();
        $params_array = $params->toArray();
        if(isset($params_array['item_id'])){
            $this->setState('item.id', $params_array['item_id']);
        }
        $this->setState('params', $params);

    }
        

    /**
     * Method to get an ojbect.
     *
     * @param   integer The id of the object to get.
     *
     * @return  mixed   Object on success, false on failure.
     */
    public function &getData($id = null)
    {
        if ($this->_item === null)
        {
            $this->_item = false;

            if (empty($id)) {
                $id = $this->getState('item.id');
            }

            // Get a level row instance.
            $table = $this->getTable();

            // Attempt to load the row.
            if ($table->load($id))
            {
                // Check published state.
                if ($published = $this->getState('filter.published'))
                {
                    if ($table->state != $published) {
                        return $this->_item;
                    }
                }

                // Convert the JTable to a clean JObject.
                $properties = $table->getProperties(1);
                $this->_item = JArrayHelper::toObject($properties, 'JObject');
                
                // Convert metadata field to JRegistry
                $registry = new JRegistry();
                $registry->loadString($this->_item->metadata);
                $this->_item->metadata = $registry;
                
                // Convert images field to array
                $registry = new JRegistry();
                $registry->loadString($this->_item->images);
                $this->_item->images = $registry->toArray();
                
                // Convert other_images field to array
                $registry = new JRegistry();
                $registry->loadString($this->_item->other_images);
                $this->_item->other_images = $registry->toArray();
                
                // Convert params field to registry
                $globalParams = JComponentHelper::getParams('com_dzproduct', true);
                $itemParams = new JRegistry();
                $itemParams->loadString($this->_item->params);
                $this->_item->params = $this->getState('params');
                
                // create an array of just the params set to 'use_item'
                $menuParamsArray = $this->getState('params')->toArray();
                $itemArray = array();

                foreach ($menuParamsArray as $key => $value)
                {
                    if ($value === 'use_item')
                    {
                        // if the item has a value, use it
                        if ($itemParams->get($key) != '')
                        {
                            // get the value from the item
                            $itemArray[$key] = $itemParams->get($key);
                        }
                        else
                        {
                            // otherwise, use the global value
                            $itemArray[$key] = $globalParams->get($key);
                        }
                    }
                }

                // merge the selected article params
                if (count($itemArray) > 0)
                {
                    $itemParams = new JRegistry;
                    $itemParams->loadArray($itemArray);
                    $this->_item->params->merge($itemParams);
                }
                
                // Load the field data for this item
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('f.name, f.dname, fd.value');
                $query->from('#__dzproduct_fields as f, #__dzproduct_field_data as fd, #__dzproduct_groupcat_relations as gr, #__dzproduct_groups as g');
                $query->where('f.id = fd.fieldid');
                $query->where('gr.groupid = g.id');
                $query->where('gr.catid = ' . (int) $this->_item->catid);
                $query->where('FIND_IN_SET(fd.fieldid, g.fields)');
                $query->where('fd.itemid = ' . (int) $this->_item->id);
                $db->setQuery($query);
                $this->_item->fielddata = $db->loadAssocList();
                foreach ($this->_item->fielddata as &$data) {
                    $data['dname'] = json_decode($data['dname'], true);
                    foreach ($data['dname'] as &$value) {
                        $value = urldecode($value);
                    }
                }
            } elseif ($error = $table->getError()) {
                $this->setError($error);
            }
        }

        return $this->_item;
    }
    
    public function getTable($type = 'Item', $prefix = 'DzproductTable', $config = array())
    {   
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
        return JTable::getInstance($type, $prefix, $config);
    }     

    
    /**
     * Method to check in an item.
     *
     * @param   integer     The id of the row to check out.
     * @return  boolean     True on success, false on failure.
     * @since   1.6
     */
    public function checkin($id = null)
    {
        // Get the id.
        $id = (!empty($id)) ? $id : (int)$this->getState('item.id');

        if ($id) {
            
            // Initialise the table
            $table = $this->getTable();

            // Attempt to check the row in.
            if (method_exists($table, 'checkin')) {
                if (!$table->checkin($id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Method to check out an item for editing.
     *
     * @param   integer     The id of the row to check out.
     * @return  boolean     True on success, false on failure.
     * @since   1.6
     */
    public function checkout($id = null)
    {
        // Get the user id.
        $id = (!empty($id)) ? $id : (int)$this->getState('item.id');

        if ($id) {
            
            // Initialise the table
            $table = $this->getTable();

            // Get the current user object.
            $user = JFactory::getUser();

            // Attempt to check the row out.
            if (method_exists($table, 'checkout')) {
                if (!$table->checkout($user->get('id'), $id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }

        return true;
    }    
    
    /**
     * Method to get the profile form.
     *
     * The base form is loaded from XML 
     * 
     * @param   array   $data       An optional array of data for the form to interogate.
     * @param   boolean $loadData   True if the form is to load its own data (default case), false if not.
     * @return  JForm   A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_dzproduct.item', 'item', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed   The data for the form.
     * @since   1.6
     */
    protected function loadFormData()
    {
        $data = $this->getData(); 
        
        return $data;
    }

    /**
     * Method to save the form data.
     *
     * @param   array       The form data.
     * @return  mixed       The user id on success, false on failure.
     * @since   1.6
     */
    public function save($data)
    {
        $id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('item.id');
        $state = (!empty($data['state'])) ? 1 : 0;
        $user = JFactory::getUser();

        if($id) {
            //Check the user can edit this item
            $authorised = $user->authorise('core.edit', 'com_dzproduct.item.'.$id) || $authorised = $user->authorise('core.edit.own', 'com_dzproduct.item.'.$id);
            if($user->authorise('core.edit.state', 'com_dzproduct.item.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
                $data['state'] = 0;
            }
        } else {
            //Check the user can create new items in this section
            $authorised = $user->authorise('core.create', 'com_dzproduct');
            if($user->authorise('core.edit.state', 'com_dzproduct.item.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
                $data['state'] = 0;
            }
        }

        if ($authorised !== true) {
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            return false;
        }
        
        $table = $this->getTable();
        if ($table->save($data) === true) {
            return $id;
        } else {
            return false;
        }
        
    }
    
     function delete($data)
    {
        $id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('item.id');
        if(JFactory::getUser()->authorise('core.delete', 'com_dzproduct.item.'.$id) !== true){
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            return false;
        }
        $table = $this->getTable();
        if ($table->delete($data['id']) === true) {
            return $id;
        } else {
            return false;
        }
        
        return true;
    }
    
    function getCategoryName($id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query 
            ->select('title')
            ->from('#__categories')
            ->where('id = ' . $id);
        $db->setQuery($query);
        return $db->loadObject();
    }
    
}