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
}