<?php
/**
 * @version     1.1.0
 * @package     com_dzproduct
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

require_once JPATH_COMPONENT.'/helpers/dzproduct.php';

/**
 * Groups list controller class.
 */
class DzproductControllerOrders extends JControllerAdmin
{
    protected $text_prefix = 'COM_DZPRODUCT';
    protected $view_list = 'orders';
    
    /**
     * Proxy for getModel.
     * @since   1.6
     */
    public function getModel($name = 'order', $prefix = 'DZProductModel')
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }
    
    public function mailadmin()
    {
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $error_ids = array();
        
        if (!is_array($cid) || count($cid) < 1)
        {
            JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
        }
        else
        {
            foreach ($cid as $id) {
                if (!DZProductHelper::sendOrder($id))
                    $error_ids[] = $id;
            }
        }
        if (count($error_ids)) {
            $message = JText::sprintf('COM_DZPRODUCT_MAILS_SENT_FAIL', implode(', ', $error_ids));
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message, 'error');
            return false;
        } else {
            $message = JText::_('COM_DZPRODUCT_MAILS_SENT_SUCCESSFULLY');
             $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message);
            return true;
        }
    }
    
    public function mailcustomer()
    {
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $error_ids = array();
        
        if (!is_array($cid) || count($cid) < 1)
        {
            JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
        }
        else
        {
            foreach ($cid as $id) {
                if(!DZProductHelper::sendOrder($id, DZProductHelper::EMAIL_CUSTOMER))
                    $error_ids[] = $id;
            }
        }
        if (count($error_ids)) {
            $message = JText::sprintf('COM_DZPRODUCT_MAILS_SENT_FAIL', implode(', ', $error_ids));
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message, 'error');
            return false;
        } else {
            $message = JText::_('COM_DZPRODUCT_MAILS_SENT_SUCCESSFULLY');
             $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $message);
            return true;
        }
    }
}