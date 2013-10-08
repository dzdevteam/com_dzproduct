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

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Item controller class.
 */
class DzproductControllerOrder extends DZProductController
{

    /**
     * Method to save a user's profile data.
     *
     * @return  void
     * @since   1.6
     */
    public function save()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $response = recaptcha_check_answer(
            $this->privatekey, 
            $_SERVER["REMOTE_ADDR"], 
            $this->input->post->get('recaptcha_challenge_field', '', 'string'),
            $this->input->post->get('recaptcha_response_field', '', 'string')
        );
        if (!$response->is_valid) {
            throw new RuntimeException(JText::_('COM_DZPRODUCT_ERROR_INVALID_CAPTCHA'));
        }
        
        // Initialise variables.
        $app    = JFactory::getApplication();
        $model = $this->getModel('Order', 'DZProductModel');

        // Get the user data.
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');

        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);

        // Check for errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();
            $messages = array();
            
            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
            {
                if ($errors[$i] instanceof Exception)
                {
                    $messages[] = $errors[$i]->getMessage();
                }
                else
                {
                    $messages[] = $errors[$i];
                }
            }
            
            throw new RuntimeException(join(',', $messages));
        }

        // Attempt to save the data
        // Check for errors.
        if (!$model->save($data)) {
            throw new RuntimeException(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', join(',', $model->getErrors())));
        }
        $order_id = $model->getState($model->getName().'.id');
        
        // We add order items right on order edit view
        $data = JFactory::getApplication()->input->post->getArray(array('jform' => array('products' => 'array')));
        JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/models/');
        
        // Get the products model
        $products_model = JModelLegacy::getInstance('Items', 'DZProductModel');
        $filter_ids = array();
        foreach ($data['jform']['products'] as $id => $value) {
            $filter_ids[] = $id;
        }
        $products_model->setState('filter.ids', $filter_ids);
        $products = $products_model->getItems();
        
        // Prevent customer from hacking the price
        foreach($products as $product) {
            $data['jform']['products'][$product->id]['price'] = ($product->saleoff) ? $product->saleoff : $product->price;
        }
        
        foreach ($data['jform']['products'] as $item) {
            $item['order_id'] = $order_id;
            $item['id'] = 0;
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
        
        // Send email
        require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/dzproduct.php';
        JFactory::getLanguage()->load('com_dzproduct', JPATH_ADMINISTRATOR);
        DZProductHelper::sendOrder($order_id, DZProductHelper::EMAIL_CUSTOMER);
        DZProductHelper::sendOrder($order_id, DZProductHelper::EMAIL_ADMIN);
        
        
        // Exit with success
        jexit($this->encodeMessage(JText::_('COM_DZPRODUCT_ORDER_SUCCESSFULLY')));
    }    
}