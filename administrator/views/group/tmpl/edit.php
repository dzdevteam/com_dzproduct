<?php
/**
* @version     1.0.0
* @package     com_dzproduct
* @copyright   Copyright (C) 2013. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
* @author      DZ Team <dev@dezign.vn> - dezign.vn
*/
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_dzproduct/assets/css/dzproduct.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function(){
        
    js('input:hidden.fields').each(function(){
        var name = js(this).attr('name');
        if(name.indexOf('fieldshidden')){
            js('#jform_fields option[value="'+js(this).val()+'"]').attr('selected',true);
        }
    });
    js("#jform_fields").trigger("liszt:updated");
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'group.cancel'){
            Joomla.submitform(task, document.getElementById('group-form'));
        }
        else{
            
            if (task != 'group.cancel' && document.formvalidator.isValid(document.id('group-form'))) {
                
    if(js('#jform_fields option:selected').length == 0){
        js("#jform_fields option[value=0]").attr('selected','selected');
    }
                Joomla.submitform(task, document.getElementById('group-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_dzproduct&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="group-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('id'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('name'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('icon'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('icon'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('fields'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('fields'); ?></div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <a class="btn btn-primary btn-add-field" href="index.php?option=com_dzproduct&amp;task=field.edit" target="_blank" >
                            <span class="icon-file-add"></span>&nbsp;<?php echo JText::_('COM_DZPRODUCT_FIELD_CUSTOM_FIELD_BTN_CREATE_FIELD'); ?>
                        </a>
                        <span class="help-inline"><?php echo JText::_('COM_DZPRODUCT_FIELD_CUSTOM_FIELD_BTN_CREATE_FIELD_HELP'); ?></span>
                    </div>
                </div>
                <?php
                    foreach((array)$this->item->fields as $value): 
                        if(!is_array($value)):
                            echo '<input type="hidden" class="fields" name="jform[fieldshidden]['.$value.']" value="'.$value.'" />';
                        endif;
                    endforeach;
                ?>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('state'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
                </div>

            </fieldset>
        </div>

        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>