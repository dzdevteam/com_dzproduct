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

jimport('joomla.application.component.controllerform');

require_once JPATH_COMPONENT.'/helpers/dzproduct.php';

/**
 * Group controller class.
 */
class DZProductControllerOrder extends JControllerForm
{

    function __construct() {
        $this->view_list = 'orders';
        parent::__construct();
    }
    
    /**
     * Update the ordered items in post save hook
     */
    protected function postSaveHook(JModelLegacy $model, $validData = array()) { 
        $order_id = $model->getState($model->getName().'.id');
        $data = JFactory::getApplication()->input->post->getArray(array('jform' => array('ordered' => 'array', 'deleted' => 'array')));
        
        // We add order items right on order edit view
        foreach ($data['jform']['ordered'] as $item) {
            $item['order_id'] = $order_id;
            $model = JModelLegacy::getInstance('OrderItem', 'DZProductModel');
            
            $form = $model->getForm($item, false);
            if (!$form) {
                continue;
            }
            
            $validItem = $model->validate($form, $item);
            if ($validItem === false) {
                continue;
            }
            
            if (!$model->save($validItem)) {
                continue;
            }
        }
        
        // Make sure the submitted deleted ids are all integer
        JArrayHelper::toInteger($data['jform']['deleted']);
        
        // Remove items
        $model = JModelLegacy::getInstance('OrderItem', 'DZProductModel');
        $model->delete($data['jform']['deleted']);        
    }
    
    public function mailAdmin($key = null, $urlVar = null)
    {
        $model = $this->getModel();
        $table = $model->getTable();
        
        // Determine the name of the primary key for the data.
        if (empty($key))
        {
            $key = $table->getKeyName();
        }

        // To avoid data collisions the urlVar may be different from the primary key.
        if (empty($urlVar))
        {
            $urlVar = $key;
        }
        
        $recordId = $this->input->getInt($urlVar);
        
        if ($result = DZProductHelper::sendOrder($recordId)) {
            $message = JText::_('COM_DZPRODUCT_MAILS_SENT_SUCCESSFULLY');
        } else {
            $message = JText::sprintf('COM_DZPRODUCT_MAILS_SENT_FAIL', $recordId);
        }
        // Redirect back to the edit screen.
        $this->setRedirect(
            JRoute::_(
                'index.php?option=' . $this->option . '&view=' . $this->view_item
                . $this->getRedirectToItemAppend($recordId, $urlVar), false
            ),
            $message,
            ($result) ? 'message' : 'error'
        );
        
        return $result;
    }
    
    public function mailCustomer($key = null, $urlVar = null)
    {
        $model = $this->getModel();
        $table = $model->getTable();
        
        // Determine the name of the primary key for the data.
        if (empty($key))
        {
            $key = $table->getKeyName();
        }

        // To avoid data collisions the urlVar may be different from the primary key.
        if (empty($urlVar))
        {
            $urlVar = $key;
        }
        
        $recordId = $this->input->getInt($urlVar);
        
        if ($result = DZProductHelper::sendOrder($recordId, DZProductHelper::EMAIL_CUSTOMER)) {
            $message = JText::_('COM_DZPRODUCT_MAILS_SENT_SUCCESSFULLY');
        } else {
            $message = JText::sprintf('COM_DZPRODUCT_MAILS_SENT_FAIL', $recordId);
        }
        // Redirect back to the edit screen.
        $this->setRedirect(
            JRoute::_(
                'index.php?option=' . $this->option . '&view=' . $this->view_item
                . $this->getRedirectToItemAppend($recordId, $urlVar), false
            ),
            $message,
            ($result) ? 'message' : 'error'
        );
        
        return $result;
    }
}