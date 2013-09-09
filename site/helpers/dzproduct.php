<?php
/**
 * @version     1.0.0
 * @package     com_dzproduct
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

defined('_JEXEC') or die;

abstract class DzproductHelper
{
    public static function availabilityText($availability)
    {
        $texts = array(
            JText::_('COM_DZPRODUCT_OPTION_COMING_SOON'),
            JText::_('COM_DZPRODUCT_OPTION_IN_STOCK'),
            JText::_('COM_DZPRODUCT_OPTION_OUT_OF_STOCK'),
        );
        return $texts[(int) $availability];
    }
}

