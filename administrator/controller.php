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

class DzproductController extends JControllerLegacy
{
    public function __construct($config = array())
    {
        parent::__construct($config);
        
        // Register our tasks
        $this->registerTask('getFieldsJSON', 'getFieldsJSON');
    }
    /**
     * Method to display a view.
     *
     * @param   boolean         $cachable   If true, the view output will be cached
     * @param   array           $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return  JController     This object to support chaining.
     * @since   1.5
     */
    public function display($cachable = false, $urlparams = false)
    {
        require_once JPATH_COMPONENT.'/helpers/dzproduct.php';

        $view       = JFactory::getApplication()->input->getCmd('view', 'items');
        JFactory::getApplication()->input->set('view', $view);

        parent::display($cachable, $urlparams);

        return $this;
    }
    
    /**
     * Get Custom Fields for a category
     */
    public function getFieldsJSON()
    {
        $catid = JFactory::getApplication()->input->getInt('catid', 0);
        $itemid = JFactory::getApplication()->input->getInt('itemid', 0);
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select("f.id, f.name, f.type, d.value")
              ->from("#__dzproduct_fields as f")
              ->join("INNER", "#__dzproduct_groups as g ON FIND_IN_SET(f.id, g.fields)")
              ->join("INNER", "#__dzproduct_groupcat_relations as r ON g.id = r.groupid AND r.catid = $catid")
              ->join("LEFT", "#__dzproduct_field_data as d ON f.id = d.fieldid AND d.itemid = $itemid");
        $db->setQuery($query);
        $result = $db->loadAssocList();
        echo json_encode($result);
        
        // Close the application
        JFactory::getApplication()->close();
    }
}
