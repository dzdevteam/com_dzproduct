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

class DZProductItemsCategories extends JCategories
{
    public function __construct($options = array())
    {
        $options['table'] = '#__dzproduct_items';
        $options['extension'] = 'com_dzproduct.items.catid';

        parent::__construct($options);
    }
}