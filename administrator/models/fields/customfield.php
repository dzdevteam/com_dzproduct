<?php
/**
 * @version     1.0.0
 * @package     com_dzproduct
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 */
class JFormFieldCustomField extends JFormField
{
    /**
     * The form field type.
     *
     * @var     string
     * @since   1.6
     */
    protected $type = 'customfield';

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     * @since   1.6
     */
    protected function getInput()
    {
        JHtml::_('jquery.framework');
        $itemid = JFactory::getApplication()->input->getInt('id', 0);
        $document = JFactory::getDocument();
        $document->addScript(JUri::root().'administrator/components/com_dzproduct/assets/js/customfield.js');
        $document->addScriptDeclaration("customFieldSetup('" . $this->fieldname . "','" . $this->element['controller'] . "', $itemid);");
        $html = '<div id="' . $this->id . '" class="form-horizontal"></div>';
        $html .= '<img id="' . $this->id . '-loader" src="' . JUri::root().'administrator/components/com_dzproduct/assets/images/loading.gif' . '" />';

        return $html;
    }
}