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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_dzproduct', JPATH_ADMINISTRATOR);
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_dzproduct.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_dzproduct' . $this->item->id)) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">

        <ul class="fields_list">

            			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_ID'); ?>:
			<?php echo $this->item->id; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_TITLE'); ?>:
			<?php echo $this->item->title; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_ALIAS'); ?>:
			<?php echo $this->item->alias; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_CATID'); ?>:
			<?php echo $this->item->catid_title; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_ORDERING'); ?>:
			<?php echo $this->item->ordering; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_STATE'); ?>:
			<?php echo $this->item->state; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_CHECKED_OUT'); ?>:
			<?php echo $this->item->checked_out; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_CHECKED_OUT_TIME'); ?>:
			<?php echo $this->item->checked_out_time; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_CREATED_BY'); ?>:
			<?php echo $this->item->created_by; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_IMAGES'); ?>:
			<?php echo $this->item->images; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_OTHER_IMAGES'); ?>:
			<?php echo $this->item->other_images; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_SHORT_DESC'); ?>:
			<?php echo $this->item->short_desc; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_LONG_DESC'); ?>:
			<?php echo $this->item->long_desc; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_VIDEO'); ?>:
			<?php echo $this->item->video; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_OPENURL'); ?>:
			<?php echo $this->item->openurl; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_PRICE'); ?>:
			<?php echo $this->item->price; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_SALEOFF'); ?>:
			<?php echo $this->item->saleoff; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_METAKEY'); ?>:
			<?php echo $this->item->metakey; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_METADESC'); ?>:
			<?php echo $this->item->metadesc; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_METADATA'); ?>:
			<?php echo $this->item->metadata; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_PARAMS'); ?>:
			<?php echo $this->item->params; ?></li>
			<li><?php echo JText::_('COM_DZPRODUCT_FORM_LBL_ITEM_LANGUAGE'); ?>:
			<?php echo $this->item->language; ?></li>


        </ul>

    </div>
    <?php if($canEdit): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_dzproduct&task=item.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_DZPRODUCT_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_dzproduct.item.'.$this->item->id)):
								?>
									<a href="javascript:document.getElementById('form-item-delete-<?php echo $this->item->id ?>').submit()"><?php echo JText::_("COM_DZPRODUCT_DELETE_ITEM"); ?></a>
									<form id="form-item-delete-<?php echo $this->item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_dzproduct&task=item.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
										<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
										<input type="hidden" name="jform[title]" value="<?php echo $this->item->title; ?>" />
										<input type="hidden" name="jform[alias]" value="<?php echo $this->item->alias; ?>" />
										<input type="hidden" name="jform[catid]" value="<?php echo $this->item->catid; ?>" />
										<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
										<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
										<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
										<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
										<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
										<input type="hidden" name="jform[images]" value="<?php echo $this->item->images; ?>" />
										<input type="hidden" name="jform[other_images]" value="<?php echo $this->item->other_images; ?>" />
										<input type="hidden" name="jform[short_desc]" value="<?php echo $this->item->short_desc; ?>" />
										<input type="hidden" name="jform[long_desc]" value="<?php echo $this->item->long_desc; ?>" />
										<input type="hidden" name="jform[video]" value="<?php echo $this->item->video; ?>" />
										<input type="hidden" name="jform[openurl]" value="<?php echo $this->item->openurl; ?>" />
										<input type="hidden" name="jform[price]" value="<?php echo $this->item->price; ?>" />
										<input type="hidden" name="jform[saleoff]" value="<?php echo $this->item->saleoff; ?>" />
										<input type="hidden" name="jform[metakey]" value="<?php echo $this->item->metakey; ?>" />
										<input type="hidden" name="jform[metadesc]" value="<?php echo $this->item->metadesc; ?>" />
										<input type="hidden" name="jform[metadata]" value="<?php echo $this->item->metadata; ?>" />
										<input type="hidden" name="jform[params]" value="<?php echo $this->item->params; ?>" />
										<input type="hidden" name="jform[language]" value="<?php echo $this->item->language; ?>" />
										<input type="hidden" name="option" value="com_dzproduct" />
										<input type="hidden" name="task" value="item.remove" />
										<?php echo JHtml::_('form.token'); ?>
									</form>
								<?php
								endif;
							?>
<?php
else:
    echo JText::_('COM_DZPRODUCT_ITEM_NOT_LOADED');
endif;
?>
