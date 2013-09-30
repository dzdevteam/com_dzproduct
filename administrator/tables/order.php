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

/**
 * Order table
 */
class DZProductTableOrder extends JTable {
    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(&$db) {
        parent::__construct('#__dzproduct_orders', 'id', $db);
    }
    
    /**
     * Overrides JTable::store to set created time and user id
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success.
     */
    public function store($updateNulls = false)
    {
        $date = JFactory::getDate();
        $user = JFactory::getUser();
        
        if (!$this->id) {
            // Newly created item
            if (!(int) $this->created) {
                $this->created = $date->toSql();
            }
        } else {
            // Store modified time
            $this->modified = $date->toSql();
            $this->modified_by = $user->get('id');
        }

        return parent::store($updateNulls);
    }
}