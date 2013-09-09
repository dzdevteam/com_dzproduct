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
        
        JText::script('COM_DZPRODUCT_FIELD_CUSTOM_FIELD_ERROR_NO_FIELDS');
        JText::script('COM_DZPRODUCT_FIELD_CUSTOM_FIELD_ERROR_LOAD_FIELDS');
        JText::script('COM_DZPRODUCT_FIELD_CUSTOM_FIELD_BTN_EDIT_GROUP');
        
        $html = '<div 
                    id="' . $this->id . '" 
                    class="form-horizontal customfield" 
                    data-fieldname="'. $this->fieldname .'" 
                    data-controller="'. $this->element['controller'] .'" 
                    data-itemid="'. $itemid .'">
                    <div class="form-container"></div>
                    <img class="img-loading" src="'.JUri::root().'/media/system/images/modal/spinner.gif" />
                    <div class="control-group">
                        <div class="controls">
                            <button class="btn btn-primary btn-reload"><span class="icon-refresh"></span>&nbsp;'.JText::_('COM_DZPRODUCT_RELOAD_FIELDS').'</button>
                        </div>
                    </div>
                </div>';
        return $html;
    }
}