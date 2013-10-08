<?php
/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

defined('_JEXEC') or die;

JLoader::register('ContentHelper', JPATH_ADMINISTRATOR . '/components/com_content/helpers/content.php');

/**
 * DZ Product HTML helper
 *
 */
abstract class JHtmlProductAdministrator
{    
    /**
     * Returns a published state on a grid
     *
     * @param   integer       $value         The state value.
     * @param   integer       $i             The row index
     * @param   string|array  $prefix        An optional task prefix or an array of options
     * @param   boolean       $enabled       An optional setting for access control on the action.
     * @param   string        $checkbox      An optional prefix for checkboxes.
     *
     * @return  string  The HTML markup
     *
     * @see     JHtmlJGrid::state
     * @since   1.6
     */
    public static function published($value, $i, $prefix = '', $enabled = true, $checkbox = 'cb')
    {
        if (is_array($prefix))
        {
            $options = $prefix;
            $enabled = array_key_exists('enabled', $options) ? $options['enabled'] : $enabled;
            $checkbox = array_key_exists('checkbox', $options) ? $options['checkbox'] : $checkbox;
            $prefix = array_key_exists('prefix', $options) ? $options['prefix'] : '';
        }

        $states = array(1 => array('unpublish', 'COM_DZPRODUCT_OPTION_CONFIRMED', 'COM_DZPRODUCT_HTML_UNPUBLISH_ITEM', 'COM_DZPRODUCT_OPTION_CONFIRMED', true, 'publish', 'publish'),
            0 => array('publish', 'COM_DZPRODUCT_OPTION_PENDING', 'COM_DZPRODUCT_HTML_PUBLISH_ITEM', 'COM_DZPRODUCT_OPTION_PENDING', true, 'unpublish', 'unpublish'),
            2 => array('unpublish', 'COM_DZPRODUCT_OPTION_ARCHIVED', 'COM_DZPRODUCT_HTML_UNPUBLISH_ITEM', 'COM_DZPRODUCT_OPTION_ARCHIVED', true, 'archive', 'archive'),
            -2 => array('publish', 'COM_DZPRODUCT_OPTION_CANCELLED', 'COM_DZPRODUCT_HTML_PUBLISH_ITEM', 'COM_DZPRODUCT_OPTION_CANCELLED', true, 'trash', 'trash'));

        return JHtmlJGrid::state($states, $value, $i, $prefix, $enabled, true, $checkbox);
    }
}
