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
class JFormFieldMultilang extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'multilang';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
        $html = '';
        $langs = JLanguage::getKnownLanguages(JPATH_SITE);
        JFactory::getDocument()->addScript(JUri::root().'administrator/components/com_dzproduct/assets/js/multilang.js');
        
        $html .= '<div id="'.$this->id.'" class="form-horizontal field_multilang">';
        foreach ($langs as $lang) {
            $id = $this->id.'_'.$lang['tag'];
            
            $html .= '<div class="input-prepend">';
            $html .= '<span class="add-on">' . $lang['tag'] . '</span>';
            $html .= '<input id="' . $id . '" type="text" data-lang="' . $lang['tag']. '" class="field_child"/>';
            $html .= '</div>';
            $html .= '<br /><br />';
        }
        $html .= '<input type="hidden" name="'.$this->name.'" class="field_root" value=\''.$this->value.'\'/>';
        $html .= '</div>';
		return $html;
	}
}