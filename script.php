<?php
/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class com_dzproductInstallerScript
{
    /**
     * Set up the default parameter for this component
     *
     * @param string $type
     * @param string $parent
     *
     * @return void
     */
    public function postflight($type, $parent)
    {
        if ($type == 'install' || $type == 'discover_install' || $type == 'update') {
            $params = $this->getParams();
            if (empty($params['order_email_admin_subject'])) {
                $params['order_mail_admin_subject'] = "A new order has just been placed!";
            }
            if (empty($params['order_mail_admin_body'])) {
                $params['order_mail_admin_body'] = 
                '<h1>%subject$s</h1>
                <h3>Customer Contact</h3>
                <ul>
                <li><strong>Name:</strong> %name$s</li>
                <li><strong>Email:</strong> %email$s</li>
                <li><strong>Phone:</strong> %phone$s</li>
                <li><strong>Address:</strong> %address$s</li>
                </ul>
                <h3>Customer Order</h3>
                <p>%ordertable$s</p>
                <h3>Additional Request</h3>
                <p>%comment$s</p>';
            }
            if (empty($params['order_mail_customer_subject'])) {
                $params['order_mail_customer_subject'] = "You have ordered our products!";
            }
            if (empty($params['order_mail_customer_body'])) {
                $params['order_mail_customer_body'] = 
                '<h1>%subject$s</h1>
                <h3>Your Contact</h3>
                <ul>
                <li><strong>Name:</strong> %name$s</li>
                <li><strong>Email:</strong> %email$s</li>
                <li><strong>Phone:</strong> %phone$s</li>
                <li><strong>Address:</strong> %address$s</li>
                </ul>
                <h3>Your Order</h3>
                <p>%ordertable$s</p>
                <h3>Additional Request (if available)</h3>
                <p>%comment$s</p>';
            }
            echo '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">×</button>Setting up default email templates...</div>';
            $this->setParams($params);
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><strong>DZ Product has been configured successfully!</strong></div>';
        }
    }
    
    public function setParams($param_array) {
        if ( count($param_array) > 0 ) {
            // read the existing component value(s)
            $db = JFactory::getDbo();
            $db->setQuery('SELECT params FROM #__extensions WHERE name = "com_dzproduct"');
            $params = json_decode( $db->loadResult(), true );
            // add the new variable(s) to the existing one(s)
            foreach ( $param_array as $name => $value ) {
                    $params[ (string) $name ] = (string) $value;
            }
            // store the combined new and existing values back as a JSON string
            $paramsString = json_encode( $params );
            $db->setQuery('UPDATE #__extensions SET params = ' .
                $db->quote( $paramsString ) .
                ' WHERE name = "com_dzproduct"' );
                $db->query();
        }
    }
    
    public function getParams() {
        $db = JFactory::getDbo();
        $db->setQuery('SELECT params FROM #__extensions WHERE name = "com_dzproduct"');
        $params = json_decode( $db->loadResult(), true );
        
        return $params;
    }
}