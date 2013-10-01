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

jimport('joomla.application.component.controlleradmin');

/**
 * Items list controller class.
 */
class DZProductControllerItems extends JControllerAdmin
{
    /**
     * Return items in JSON format
     */
    public function items()
    {
        $app = JFactory::getApplication();
        $ids = $app->input->get('ids', array(), 'array');
        $model = JModelLegacy::getInstance('Items', 'DZProductModel', array('ignore_request' => true));
        $model->setState('filter.ids', $ids);
        $items = $model->getItems();
        
        // Extract result from fetched items
        $result = array();
        foreach ($items as $item) {
            $result[] = array(
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->short_desc,
                'image' => $item->images['intro'],
                'price' => $item->price
            );
        }
        
        // Return result
        header('Content-Type:application/json');
        echo json_encode($result);
        JFactory::getApplication()->close();
    }
    
    /**
     * Return items in JSON format
     */
    public function item()
    {
        $app = JFactory::getApplication();
        $id = $app->input->get('id', 0, 'integer');
        $model = JModelLegacy::getInstance('Item', 'DZProductModel', array('ignore_request' => true));
        $item = $model->getItem($id);
        
        // Extract result from fetched items
        if ($item->id) {
            $result = array(
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->short_desc,
                'image' => $item->images['intro'],
                'price' => $item->price
            );
        } else {
            $result = new stdClass();
        }
        
        // Return result
        header('Content-Type:application/json');
        echo json_encode($result);
        JFactory::getApplication()->close();
    }
}