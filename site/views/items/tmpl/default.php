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
?>
<script type="text/javascript">
    function deleteItem(item_id){
        if(confirm("<?php echo JText::_('COM_DZPRODUCT_DELETE_MESSAGE'); ?>")){
            document.getElementById('form-item-delete-' + item_id).submit();
        }
    }
</script>

<div class="items">
    <ul class="items_list">
<?php $show = false; ?>
        <?php foreach ($this->items as $item) : ?>

            
				<?php
					if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_dzproduct.item.'.$item->id))):
						$show = true;
						?>
							<li>
								<a href="<?php echo JRoute::_('index.php?option=com_dzproduct&view=item&id=' . (int)$item->id); ?>"><?php echo $item->title; ?></a>
								<?php
									if(JFactory::getUser()->authorise('core.edit.state','com_dzproduct.item.'.$item->id)):
									?>
										<a href="javascript:document.getElementById('form-item-state-<?php echo $item->id; ?>').submit()"><?php if($item->state == 1): echo JText::_("COM_DZPRODUCT_UNPUBLISH_ITEM"); else: echo JText::_("COM_DZPRODUCT_PUBLISH_ITEM"); endif; ?></a>
										<form id="form-item-state-<?php echo $item->id ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_dzproduct&task=item.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[title]" value="<?php echo $item->title; ?>" />
											<input type="hidden" name="jform[alias]" value="<?php echo $item->alias; ?>" />
											<input type="hidden" name="jform[catid]" value="<?php echo $item->catid; ?>" />
											<input type="hidden" name="jform[ordering]" value="<?php echo $item->ordering; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo (int)!((int)$item->state); ?>" />
											<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
											<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
											<input type="hidden" name="jform[images]" value="<?php echo $item->images; ?>" />
											<input type="hidden" name="jform[other_images]" value="<?php echo $item->other_images; ?>" />
											<input type="hidden" name="jform[short_desc]" value="<?php echo $item->short_desc; ?>" />
											<input type="hidden" name="jform[long_desc]" value="<?php echo $item->long_desc; ?>" />
											<input type="hidden" name="jform[video]" value="<?php echo $item->video; ?>" />
											<input type="hidden" name="jform[openurl]" value="<?php echo $item->openurl; ?>" />
											<input type="hidden" name="jform[price]" value="<?php echo $item->price; ?>" />
											<input type="hidden" name="jform[saleoff]" value="<?php echo $item->saleoff; ?>" />
											<input type="hidden" name="jform[metakey]" value="<?php echo $item->metakey; ?>" />
											<input type="hidden" name="jform[metadesc]" value="<?php echo $item->metadesc; ?>" />
											<input type="hidden" name="jform[metadata]" value="<?php echo $item->metadata; ?>" />
											<input type="hidden" name="jform[params]" value="<?php echo $item->params; ?>" />
											<input type="hidden" name="jform[language]" value="<?php echo $item->language; ?>" />
											<input type="hidden" name="option" value="com_dzproduct" />
											<input type="hidden" name="task" value="item.save" />
											<?php echo JHtml::_('form.token'); ?>
										</form>
									<?php
									endif;
									if(JFactory::getUser()->authorise('core.delete','com_dzproduct.item.'.$item->id)):
									?>
										<a href="javascript:deleteItem(<?php echo $item->id; ?>);"><?php echo JText::_("COM_DZPRODUCT_DELETE_ITEM"); ?></a>
										<form id="form-item-delete-<?php echo $item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_dzproduct&task=item.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[title]" value="<?php echo $item->title; ?>" />
											<input type="hidden" name="jform[alias]" value="<?php echo $item->alias; ?>" />
											<input type="hidden" name="jform[catid]" value="<?php echo $item->catid; ?>" />
											<input type="hidden" name="jform[ordering]" value="<?php echo $item->ordering; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo $item->state; ?>" />
											<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
											<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
											<input type="hidden" name="jform[created_by]" value="<?php echo $item->created_by; ?>" />
											<input type="hidden" name="jform[images]" value="<?php echo $item->images; ?>" />
											<input type="hidden" name="jform[other_images]" value="<?php echo $item->other_images; ?>" />
											<input type="hidden" name="jform[short_desc]" value="<?php echo $item->short_desc; ?>" />
											<input type="hidden" name="jform[long_desc]" value="<?php echo $item->long_desc; ?>" />
											<input type="hidden" name="jform[video]" value="<?php echo $item->video; ?>" />
											<input type="hidden" name="jform[openurl]" value="<?php echo $item->openurl; ?>" />
											<input type="hidden" name="jform[price]" value="<?php echo $item->price; ?>" />
											<input type="hidden" name="jform[saleoff]" value="<?php echo $item->saleoff; ?>" />
											<input type="hidden" name="jform[metakey]" value="<?php echo $item->metakey; ?>" />
											<input type="hidden" name="jform[metadesc]" value="<?php echo $item->metadesc; ?>" />
											<input type="hidden" name="jform[metadata]" value="<?php echo $item->metadata; ?>" />
											<input type="hidden" name="jform[params]" value="<?php echo $item->params; ?>" />
											<input type="hidden" name="jform[language]" value="<?php echo $item->language; ?>" />
											<input type="hidden" name="option" value="com_dzproduct" />
											<input type="hidden" name="task" value="item.remove" />
											<?php echo JHtml::_('form.token'); ?>
										</form>
									<?php
									endif;
								?>
							</li>
						<?php endif; ?>

<?php endforeach; ?>
        <?php
        if (!$show):
            echo JText::_('COM_DZPRODUCT_NO_ITEMS');
        endif;
        ?>
    </ul>
</div>
<?php if ($show): ?>
    <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>


									<?php if(JFactory::getUser()->authorise('core.create','com_dzproduct')): ?><a href="<?php echo JRoute::_('index.php?option=com_dzproduct&task=item.edit&id=0'); ?>"><?php echo JText::_("COM_DZPRODUCT_ADD_ITEM"); ?></a>
	<?php endif; ?>