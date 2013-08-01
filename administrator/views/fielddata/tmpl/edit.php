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
        
	js('input:hidden.itemid').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('itemidhidden')){
			js('#jform_itemid option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_itemid").trigger("liszt:updated");
	js('input:hidden.fieldid').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('fieldidhidden')){
			js('#jform_fieldid option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_fieldid").trigger("liszt:updated");
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'fielddata.cancel'){
            Joomla.submitform(task, document.getElementById('fielddata-form'));
        }
        else{
            
            if (task != 'fielddata.cancel' && document.formvalidator.isValid(document.id('fielddata-form'))) {
                
                Joomla.submitform(task, document.getElementById('fielddata-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_dzproduct&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="fielddata-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">

                			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('itemid'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('itemid'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->itemid as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="itemid" name="jform[itemidhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('fieldid'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('fieldid'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->fieldid as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="fieldid" name="jform[fieldidhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('value'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('value'); ?></div>
			</div>


            </fieldset>
        </div>

        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>