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
        
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'item.cancel'){
            Joomla.submitform(task, document.getElementById('item-form'));
        }
        else{
            
            if (task != 'item.cancel' && document.formvalidator.isValid(document.id('item-form'))) {
                
                Joomla.submitform(task, document.getElementById('item-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_dzproduct&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="item-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_DZPRODUCT_DETAILS', true)); ?>
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('openurl'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('openurl'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('price'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('price'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('saleoff'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('saleoff'); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('short_desc'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('short_desc'); ?></div>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('video'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('video'); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('long_desc'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('long_desc'); ?></div>
            </div>
            
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_DZPRODUCT_IMAGES', true)); ?>
            <div class="row-fluid">
                <?php $images_fieldset = $this->form->getFieldset('images'); ?>
                <div class="span6">
                    <h2><?php echo JText::_('COM_DZPRODUCT_IMAGES'); ?></h2>
                    <?php foreach ($images_fieldset as $field) : ?>
                    <div class="control-group">
                        <div class="control-label"><?php echo $field->label; ?></div>
                        <div class="controls"><?php echo $field->input; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php $other_images_fieldset = $this->form->getFieldset('other_images'); ?>
                <div class="span6">
                    <h2><?php echo JText::_('COM_DZPRODUCT_OTHER_IMAGES'); ?></h2>
                    <?php foreach ($other_images_fieldset as $field) : ?>
                    <div class="control-group">
                        <div class="control-label"><?php echo $field->label; ?></div>
                        <div class="controls"><?php echo $field->input; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'fields', JText::_('COM_DZPRODUCT_CATEGORY_FIELDS', true)); ?>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('catid'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('fields'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('fields'); ?></div>
            </div>
                
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_DZPRODUCT_PUBLISHING', true)); ?>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('id'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('created'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('created'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'metadata', JText::_('JGLOBAL_FIELDSET_METADATA_OPTIONS', true)); ?>
                <?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php if (JFactory::getUser()->authorise('core.admin','com_dzproduct')): ?>
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_DZPRODUCT_RULES', true)); ?>
                <div class="fltlft" style="width:86%;">
                <fieldset class="panelform">
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <?php echo $this->form->getInput('rules'); ?>
                <?php echo JHtml::_('sliders.end'); ?>
                </fieldset>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php endif; ?>

            <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        </div>

        <div class="clr"></div>
        <!-- Begin Sidebar -->
        <?php echo JLayoutHelper::render('joomla.edit.details', $this); ?>
        <!-- End Sidebar -->

        <div class="clr"></div>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>