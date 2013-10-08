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

JHtml::_('jquery.framework');
JHtml::_('script', 'com_dzproduct/jquery.cookie.js', true, true);
JHtml::_('script', 'com_dzproduct/jquery.validate.min.js', true, true);
JHtml::_('script', 'com_dzproduct/messages_vi.js', true, true);

JFactory::getDocument()->addScriptDeclaration('Joomla.rootUrl = "' . JURI::root() . '";'); // Need this to display images
JFactory::getDocument()->addScriptDeclaration('Joomla.loadingGIF = "'.JUri::root().'/media/system/images/modal/spinner.gif'.'"');
JHtml::_('script', 'com_dzproduct/cart.js', true, true);

// reCaptcha
require_once JPATH_COMPONENT.'/helpers/recaptchalib.php';
$publickey = $this->params->get('order_recaptcha_publickey');

?>
<h2>Your Cart</h2>
<form id="order-form" method="POST" action="<?php echo JUri::root().'?option=com_dzproduct&task=order.save&format=json'; ?>">
<table id="cart" class="table table-striped">
    <thead>
        <tr>
            <th><?php echo JText::_('COM_DZPRODUCT_ORDER_TITLE'); ?></th>
            <th width="20%"><?php echo JText::_('COM_DZPRODUCT_ORDER_IMAGE'); ?></th>
            <th><?php echo JText::_('COM_DZPRODUCT_ORDER_DESCRIPTION'); ?></th>
            <th><?php echo JText::_('COM_DZPRODUCT_ORDER_PRICE'); ?></th>
            <th class="text-center"><?php echo JText::_('COM_DZPRODUCT_ORDER_QUANTITY'); ?></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right"><strong><?php echo JText::_('COM_DZPRODUCT_ORDER_TOTAL_PRICE'); ?></strong></td>
            <td id="total-price"></td>
            <td></td>
    </tfoot>
</table>
<div class="form-horizontal">
    <div id="alert-area"></div>
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
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('comment'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('comment'); ?></div>
    </div>
    <?php echo JHtml::_('form.token'); ?>
    <div class="control-group">
        <div class="controls">
            <?php echo recaptcha_get_html($publickey); ?>
            <button id="btn-submit" class="btn btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
        </div>
    </div>
</div>
</form>
<style type="text/css">
.table th.text-right, .table td.text-right { text-align: right; }
.table th.text-center, .table td.text-center { text-align: center; }
.item-quantity [class^="icon-"] {cursor: pointer;}
.item-quantity span {margin: 0px 5px;}
.item-quantity {text-align: center !important;}
</style>