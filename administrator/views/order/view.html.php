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
 * View to edit
 */
class DZProductViewOrder extends JViewLegacy
{
    protected $state;
    protected $item;
    protected $form;
    protected $products;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->state    = $this->get('State');
        $this->item     = $this->get('Item');
        $this->products = $this->get('Products');
        $this->form     = $this->get('Form');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $user       = JFactory::getUser();
        $isNew      = ($this->item->id == 0);
        if (isset($this->item->checked_out)) {
            $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
        $canDo      = DZProductHelper::getActions();

        JToolBarHelper::title(JText::_('COM_DZPRODUCT_TITLE_ORDER'), 'order.png');

        // If not checked out, can save the item.
        if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
        {

            JToolBarHelper::apply('order.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('order.save', 'JTOOLBAR_SAVE');
        }
        if (!$checkedOut && ($canDo->get('core.create'))){
            JToolBarHelper::custom('order.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
        }
        // If an existing item, can save to a copy.
        if (!$isNew && $canDo->get('core.create')) {
            JToolBarHelper::custom('order.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
        }        
        if (empty($this->item->id)) {
            JToolBarHelper::cancel('order.cancel', 'JTOOLBAR_CANCEL');
        } else {
            JToolBarHelper::custom('order.mailadmin', 'mail', 'mail', 'COM_DZPRODUCT_TOOLBAR_SEND_ADMIN', false);
            JToolBarHelper::custom('order.mailcustomer', 'mail', 'mail', 'COM_DZPRODUCT_TOOLBAR_SEND_CUSTOMER', false);
            JToolBarHelper::cancel('order.cancel', 'JTOOLBAR_CLOSE');
        }

    }
}
