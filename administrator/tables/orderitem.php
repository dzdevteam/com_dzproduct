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

class DZProductTableOrderItem extends JTable 
{
    /**
     * Constructor
     */
    public function __construct(&$db)
    {
        parent::__construct('#__dzproduct_order_items', 'id', $db);
    }
}