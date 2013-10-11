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

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

// Map this model to the admin model
JForm::addFormPath(JPATH_COMPONENT_ADMINISTRATOR.'/models/forms');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/models/order.php';