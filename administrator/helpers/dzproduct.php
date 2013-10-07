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

/**
* DZProduct helper.
*/
class DZProductHelper
{
    const EMAIL_ADMIN = 'admin';
    const EMAIL_CUSTOMER = 'customer';
    
    protected static $_groupcatrelations = array();
    /**
    * Configure the Linkbar.
    */
    public static function addSubmenu($vName = '')
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_DZPRODUCT_TITLE_ITEMS'),
            'index.php?option=com_dzproduct&view=items',
            $vName == 'items'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_DZPRODUCT_TITLE_CATEGORIES'),
            "index.php?option=com_categories&extension=com_dzproduct.items.catid",
            $vName == 'categories.items'
        );
        
        if ($vName=='categories.items') {
            JToolBarHelper::title('DZ Products: Categories (Items - Item )');
            
            // A hack to use our categories template instead of built-in categories template
            $controller = JControllerLegacy::getInstance('', 'CategoriesController');
            $view       = $controller->getView();
            $view->addTemplatePath(JPATH_ADMINISTRATOR.'/components/com_dzproduct/views/categories/tmpl');
        }
        
        JHtmlSidebar::addEntry(
            JText::_('COM_DZPRODUCT_TITLE_FIELDS'),
            'index.php?option=com_dzproduct&view=fields',
            $vName == 'fields'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_DZPRODUCT_TITLE_GROUPS'),
            'index.php?option=com_dzproduct&view=groups',
            $vName == 'groups'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_DZPRODUCT_TITLE_ORDERS'),
            'index.php?option=com_dzproduct&view=orders',
            $vName == 'orders'
        );
    }

    /**
    * Gets a list of the actions that can be performed.
    *
    * @return   JObject
    * @since    1.6
    */
    public static function getActions()
    {
        $user   = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_dzproduct';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }
    
    /**
     * Get associated group for a given category
     */
    public static function getAssociatedGroup($catid)
    {
        if (empty(DZProductHelper::$_groupcatrelations)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('r.catid, g.name')
                  ->from('#__dzproduct_groupcat_relations as r')
                  ->join('INNER', '#__dzproduct_groups as g ON r.groupid = g.id AND g.state = 1');
            $db->setQuery($query);
            $result = $db->loadAssocList();
            foreach($result as $item) {
                DZProductHelper::$_groupcatrelations[$item['catid']] = $item['name'];
            }
            
        }

        if (isset(DZProductHelper::$_groupcatrelations[$catid]))
            return DZProductHelper::$_groupcatrelations[$catid];
        
        return JText::_('COM_DZPRODUCT_GROUPCATRELATION_N_A');
    }
    
    /**
     * Function to send order emails to admin list defined in component config
     *
     * @param integer $orderid The order id
     * @param string 
     * @return boolean True on success
     */
    public static function sendOrder($orderid, $mode = self::EMAIL_ADMIN)
    {
        $model = JModelLegacy::getInstance('Order', 'DZProductModel');
        $params = JComponentHelper::getParams('com_dzproduct');
        $order = $model->getItem($orderid);
        if ($order == null) {
            return false;
        }
        
        /* --- BUILD EMAIL TEMPLATE --- */
        // Basic information
        switch ($mode) {
            case self::EMAIL_ADMIN:
                $data['subject']    = $params->get('order_mail_admin_subject', '');
                $data['body']       = $params->get('order_mail_admin_body', '');
                break;
            case self::EMAIL_CUSTOMER:
                $data['subject']    = $params->get('order_mail_customer_subject', '');
                $data['body']       = $params->get('order_mail_customer_body', '');
                break;
            default:
                $data['subject']    = '';
                $data['body']       = '';
                break;
        }
        $data['code']       = $order->id . 'O' . date_format(new DateTime($order->created), 'dm') . 'T';
        
        // Customer contact
        $data['comment']    = $order->comment;
        $data['name']       = $order->name;
        $data['email']      = $order->email;
        $data['phone']      = $order->phone;
        $data['address']    = $order->address;
        
        // Ordered products
        $data['ordertable']  = '<table style="border: 1px solid #ddd; border-radius: 4px; border-spacing: 0px;">';
        $data['ordertable'] .= '<thead><tr>';
        $data['ordertable'] .= '<th width="20%" style="padding: 8px;">'.JText::_('COM_DZPRODUCT_ITEMS_TITLE').'</th>';
        $data['ordertable'] .= '<th width="10%" style="border-left: 1px solid #ddd;">'.JText::_('COM_DZPRODUCT_ITEMS_IMAGE').'</th>';
        $data['ordertable'] .= '<th style="border-left: 1px solid #ddd;">'.JText::_('COM_DZPRODUCT_ITEMS_DESCRIPTION').'</th>';
        $data['ordertable'] .= '<th width="15%" style="border-left: 1px solid #ddd;">'.JText::_('COM_DZPRODUCT_ITEMS_PRICE').'</th>';
        $data['ordertable'] .= '<th width="15%" style="border-left: 1px solid #ddd;">'.JText::_('COM_DZPRODUCT_ITEMS_QUANTITY').'</th>';
        $data['ordertable'] .= '</tr></thead>';
        $data['ordertable'] .= '<tbody>';
        $products            = $model->getProducts($orderid);
        $total_price         = 0;
        foreach ($products as $product) {
            $data['ordertable'] .= '<tr>';
            $data['ordertable'] .= '<td style="border-top: 1px solid #ddd;padding: 8px;padding: 8px;">'.$product->title.'</td>';
            $data['ordertable'] .= '<td style="border-top: 1px solid #ddd;border-left: 1px solid #ddd;padding: 8px;"><img src="'.JUri::root().$product->image.'" width="100%"/></td>';
            $data['ordertable'] .= '<td style="border-top: 1px solid #ddd;border-left: 1px solid #ddd;padding: 8px;">'.$product->description.'</td>';
            $data['ordertable'] .= '<td style="text-align: right; border-top: 1px solid #ddd;border-left: 1px solid #ddd;padding: 8px;">'.$product->price.'</td>';
            $data['ordertable'] .= '<td style="text-align: right; border-top: 1px solid #ddd;border-left: 1px solid #ddd;padding: 8px;">'.$product->quantity.'</td>';
            $data['ordertable'] .= '</tr>';
            $total_price        += $product->price * $product->quantity;
        }
        $data['ordertable'] .= '</tbody>';
        $data['ordertable'] .= '<tfoot><tr>';
        $data['ordertable'] .= '<td colspan="3" style="text-align: right; border-top: 1px solid #ddd;padding: 8px;"><strong>'.JText::_('COM_DZPRODUCT_ORDERS_TOTAL_PRICE').'</strong></td>';
        $data['ordertable'] .= '<td style="text-align: right; border-top: 1px solid #ddd;border-left: 1px solid #ddd;padding: 8px;">'.$total_price.'</td><td style="border-top: 1px solid #ddd;border-left: 1px solid #ddd;"></td>';
        $data['ordertable'] .= '</tr></tfoot>';
        $data['ordertable'] .= '</table>';
        
        /**
         * Build the body
         * Supported tags are
         * %subject$s, %code$s,
         * %comment$s, %name$s, %email$s, %phone$s, %address$s,
         * %ordertable$s
         */
        $data['body']       = self::sprintf($data['body'], $data);
        
        /* BUILD THE MAILER */
        $mailer = JFactory::getMailer();
        $app = JFactory::getApplication();
        
        // Do basic setup
        $mailer->setSender(array($app->getCfg('mailfrom'), $app->getCfg('fromname')));
        $mailer->isHtml(true);
        switch ($mode) {
        case self::EMAIL_ADMIN:
            $list = explode(',', $params->get('order_mail_admin_list', ''));
            break;
        case self::EMAIL_CUSTOMER:
            if ($order->email)
                $list = array($order->email);
            else 
                $list = array();
            break;
        }
        
        if (empty($list)) {
            return false; // There's no one to send
        }
        $mailer->addRecipient($list);
        $mailer->setSubject($data['subject']);
        $mailer->setBody($data['body']);
        
        // Now send
        $response = $mailer->send();
        if ($response instanceof Exception)
            return false;
        
        return true;
    }
    
    /**
     * Helper function to mimic sprintf with expressive identifiers rather than just numbers
     * 
     * @param string $format
     * @param array $data
     * 
     * @return string formatted string
     */
    public static function sprintf($format, $data)
    {
        preg_match_all( '/ (?<!%) % ( (?: [[:alpha:]_-][[:alnum:]_-]* | ([-+])? [0-9]+ (?(2) (?:\.[0-9]+)? | \.[0-9]+ ) ) ) \$ [-+]? \'? .? -? [0-9]* (\.[0-9]+)? \w/x', $format, $match, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
        $offset = 0;
        $keys = array_keys($data);
        foreach( $match as &$value )
        {
            if ( ( $key = array_search( $value[1][0], $keys, TRUE) ) !== FALSE || ( is_numeric( $value[1][0] ) && ( $key = array_search( (int)$value[1][0], $keys, TRUE) ) !== FALSE) )
            {
                $len = strlen( $value[1][0]);
                $format = substr_replace( $format, 1 + $key, $offset + $value[1][1], $len);
                $offset -= $len - strlen( 1 + $key);
            }
        }
        return vsprintf( $format, $data);
    }
}
