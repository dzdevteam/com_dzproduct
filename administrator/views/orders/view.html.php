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

jimport('joomla.application.component.view');

/**
 * View class for a list of Dzproduct.
 */
class DzproductViewOrders extends JViewLegacy
{
    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->state        = $this->get('State');
        $this->items        = $this->get('Items');
        $this->pagination   = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
        DzproductHelper::addSubmenu('orders');
        
        $this->addToolbar();
        
        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        require_once JPATH_COMPONENT.'/helpers/dzproduct.php';

        $state  = $this->get('State');
        $canDo  = DzproductHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_DZPRODUCT_TITLE_ITEMS'), 'items.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/order';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('order.add','JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('order.edit','JTOOLBAR_EDIT');
            }

        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('orders.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('orders.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'orders.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('orders.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('orders.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'items.delete','JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('orders.trash','JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }
        
        JToolBarHelper::custom('orders.mailadmin', 'mail', 'mail', 'COM_DZPRODUCT_TOOLBAR_SEND_ADMIN', true);
        JToolBarHelper::custom('orders.mailcustomer', 'mail', 'mail', 'COM_DZPRODUCT_TOOLBAR_SEND_CUSTOMER', true);
        
        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_dzproduct');
        }
        
        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_dzproduct&view=orders');
        
        $this->extra_sidebar = '';

        $options = array();
        $options[0]         = new stdClass();
        $options[0]->value  = 1;
        $options[0]->text   = JText::_('COM_DZPRODUCT_OPTION_CONFIRMED');
        $options[1]         = new stdClass();
        $options[1]->value  = 0;
        $options[1]->text   = JText::_('COM_DZPRODUCT_OPTION_PENDING');
        $options[2]         = new stdClass();
        $options[2]->value  = 2;
        $options[2]->text   = JText::_('COM_DZPRODUCT_OPTION_ARCHIVED');
        $options[3]         = new stdClass();
        $options[3]->value  = -2;
        $options[3]->text   = JText::_('COM_DZPRODUCT_OPTION_CANCELLED');
        JHtmlSidebar::addFilter(

            JText::_('JOPTION_SELECT_PUBLISHED'),

            'filter_published',

            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.state'), true)

        );
    }
    
    protected function getSortFields()
    {
        return array(
        'a.id' => JText::_('JGRID_HEADING_ID'),        
        'a.state' => JText::_('JSTATUS'),
        'a.created' => JText::_('COM_DZPRODUCT_ORDERS_CREATED'),
        'total_price' => JText::_('COM_DZPRODUCT_ORDERS_TOTAL_PRICE'),
        'a.name' => JText::_('COM_DZPRODUCT_ORDERS_NAME'),
        'code' => JText::_('COM_DZPRODUCT_ORDERS_CODE'),
        'a.email' => JText::_('COM_DZPRODUCT_ORDERS_EMAIL'),      
        );
    }

    
}
