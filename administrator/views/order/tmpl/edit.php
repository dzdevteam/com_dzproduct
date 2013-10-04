<?php
/**
 * @version     1.0.0
 * @package     com_dztour
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
$document->addScript('components/com_dzproduct/assets/js/order.js');
$document->addScriptDeclaration('Joomla.rootUrl = "' . JUri::root().'";');

JText::script('COM_DZPRODUCT_ITEMS_REMOVE');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function(){
        
    js('input:hidden.tourid').each(function(){
        var name = js(this).attr('name');
        if(name.indexOf('touridhidden')){
            js('#jform_tourid option[value="'+js(this).val()+'"]').attr('selected',true);
        }
    });
    js("#jform_tourid").trigger("liszt:updated");
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'order.cancel'){
            Joomla.submitform(task, document.getElementById('order-form'));
        }
        else{
            
            if (task != 'order.cancel' && document.formvalidator.isValid(document.id('order-form'))) {
                
                Joomla.submitform(task, document.getElementById('order-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_dzproduct&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="order-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_DZPRODUCT_DETAILS', true)); ?>
            <div class="span6">
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('name'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('phone'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('phone'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('email'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('email'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('address'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('address'); ?></div>
                </div>
            </div>
            <div class="span6">
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('comment'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('comment'); ?></div>
                </div>
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
                <div class="control-label"><?php echo $this->form->getLabel('modified'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('modified'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('modified_by'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('modified_by'); ?></div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.endTabSet'); ?>
            <div class="clearfix"></div>
            <hr />
            <h4><?php echo JText::_('COM_DZPRODUCT_ITEM_ORDERED_PRODUCTS'); ?></h4>
            <table class="table table-striped" id="ordered">
                <thead>
                    <tr>
                        <th width="20%"><?php echo JText::_('COM_DZPRODUCT_ITEMS_TITLE'); ?></th>
                        <th width="10%"><?php echo JText::_('COM_DZPRODUCT_ITEMS_IMAGE'); ?></th>
                        <th><?php echo JText::_('COM_DZPRODUCT_ITEMS_DESCRIPTION'); ?></th>
                        <th><?php echo JText::_('COM_DZPRODUCT_ITEMS_PRICE'); ?></th>
                        <th><?php echo JText::_('COM_DZPRODUCT_ITEMS_QUANTITY'); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->products as $product) : ?>
                    <tr>
                        <td class="item-title">
                            <?php echo $product->title; ?>
                            <input type="hidden" name="jform[ordered][<?php echo $product->id; ?>][id]" value="<?php echo $product->id; ?>" />
                        </td>
                        <td class="item-image">
                            <img src="<?php echo JUri::root().$product->image; ?>" />
                            <input type="hidden" name="jform[ordered][<?php echo $product->id; ?>][image]" value="<?php echo $product->image; ?>" />
                        </td>
                        <td class="item-description">
                            <?php echo $this->escape($product->description); ?>
                            <input type="hidden" name="jform[ordered][<?php echo $product->id; ?>][description]" value='<?php echo $this->escape($product->description); ?>' />
                        </td>
                        <td class="item-price">
                            <span contenteditable="true"><?php echo $product->price; ?></span>
                            <input type="hidden" name="jform[ordered][<?php echo $product->id; ?>][price]" value='<?php echo (int) $product->price; ?>' />
                        </td>
                        <td class="item-quantity">
                            <span contenteditable="true"><?php echo $product->quantity; ?></span>
                            <input type="hidden" name="jform[ordered][<?php echo $product->id; ?>][quantity]" value='<?php echo (int) $product->quantity; ?>' />
                        </td>
                        <td class="item-delete">
                            <button type="button" class="btn btn-link" data-id="<?php echo $product->id; ?>">
                            <?php echo JText::_('COM_DZPRODUCT_ITEMS_REMOVE'); ?>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><span class="pull-right"><strong>Total Price</strong></span></td>
                        <td colspan="4" id="total-price"></td>
                    </tr>
                </tfoot>
            </table>
            <h5>Add product to this order</h5>
            <div class="control-group">
                <div class="control-label">
                    <label for="jform_items">Select a product</label>
                </div>
                <div class="controls"><?php echo $this->form->getInput('items'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label for="input-item-quantity">Choose quantity *</label>
                </div>
                <div class="controls"><input type="text" id="input-item-quantity" class="validate-numeric" value="1"/></div>
            </div>
            <div class="control-group">
                <div class="controls"><button type="button" class="btn btn-primary" id="btn-add-item"><?php echo JText::_('COM_DZPRODUCT_ORDERITEM_BTN_ADD_ITEM'); ?></button></div>
            </div>
        </div>

        <div class="clr"></div>
        <!-- Begin Sidebar -->
        <?php echo JLayoutHelper::render('joomla.edit.details', $this); ?>
        <!-- End Sidebar -->

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>