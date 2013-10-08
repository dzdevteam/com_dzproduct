<?php
/**
 * @package     DZ Products
 * @update      April 2013
 * @copyright   Copyright © 2013 DZ Creative Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
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
defined('_JEXEC') or die;
$itemid = JRequest::getVar('Itemid');
$params = $this->item->params;
$pageclass_sfx = $params->get( 'pageclass_sfx' );
?>
<style>
.product-images img {height:80px; margin:5px;}
</style>
<div class="component-inner products-category<?php if($pageclass_sfx) echo $pageclass_sfx?>">

    <!-- PAGE HEADING -->
    <?php if ($this->params->get('show_page_heading')) : ?>
    <h1 class="page-heading">
        <?php echo $this->escape($this->params->get('page_heading')); ?>
    </h1>
    <?php endif; ?>     
    <!-- PRODUCT IMAGES & INFO -->
    <div class="row-fluid">
        <div class="span5">
            <div class="product-image"><a href="<?php echo JUri::root().$this->item->images['full']; ?>"><img src="<?php echo JUri::root().$this->item->images['intro']; ?>" alt="<?php echo $this->item->title;?>"/></a></div>
            <div class="product-images">
                <?php foreach ($this->item->other_images as $image) : ?>
                <?php if (!empty($image)) : ?>
                <img src="<?php echo JUri::root().$image; ?>" />
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="span7">
            <?php if ($params->get('item_show_item_title', 1)) : ?>
            <h2 class="product-title"><?php echo $this->item->title; ?></h2>
            <?php endif; ?>
            <div class="product-info">
                <ul>
                    <li><span>Category: </span><a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->title;?></a></li>
                    <?php foreach ($this->item->fielddata as $data) { ?>                   
                    <li><span><?php echo $data['dname'][JFactory::getLanguage()->getTag()]; ?>: </span><?php echo $data['value']; ?></li>
  
                    <?php } ?>
                </ul>
            </div>
            <?php if ($params->get('item_show_item_intro_text', 1)) : ?>
            <div class="product-intro">
                <?php echo $this->item->short_desc;?>
            </div>
            <?php endif; ?>
            
            <?php if ($params->get('item_show_item_price', 1)) : ?>
            <div class="product-price">
                <?php if($this->item->saleoff) :?>
                            <span><?php echo $this->item->saleoff;?></span><em><?php echo $this->item->price;?></em>
                        <?php else :?>
                            <span><?php echo $this->item->price;?></span>
                        <?php endif;?>                            
            </div>
            <?php endif; ?>
            
            
            <div class="product-labels">
                <?php if ($params->get('item_show_item_saleoff_label', 1)) : ?>
                <?php if($this->item->saleoff) :?>
                <span class="product-saleoff">
                    -<?php echo (100*($this->item->price - $this->item->saleoff)/$this->item->price) ;?>%
                </span>
                <?php endif;?>
                <?php endif; ?>
                
                <?php if ($params->get('item_show_item_featured_label', 1)) : ?>
                <span class="product-featured">
                    <?php echo $this->item->featured; ?>
                </span>
                <?php endif; ?>
                
                <?php if ($params->get('item_show_item_new_arrival_label', 1)) : ?>
                <span class="product-new">
                    <?php echo $this->item->new_arrival; ?>
                </span>
                <?php endif; ?>
                
                <?php if ($params->get('item_show_item_availability_label', 1)) : ?>
                <span class="product-avail">
                    <?php echo DzproductHelper::availabilityText($this->item->availability); ?>
                </span>
                <?php endif; ?>
            </div>
            
            <?php if ($params->get('item_show_item_open_url', 1)) : ?>
            <div class="product-url">
                <a href="<?php echo $this->item->openurl; ?>">Call to action</a>
            </div>        
            <?php endif; ?>
        </div>
    </div>
    
    <?php if ($params->get('item_show_item_long_desc', 1)) : ?>
    <!-- PRODUCT DESC -->
    <div class="product-desc">
        <h3>Description</h3>
        <?php echo $this->item->long_desc; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($params->get('item_show_item_video', 1)) : ?>
    <!-- PRODUCT VIDEO -->
    <div class="product-video">
        <h3>Video</h3>
        <?php echo $this->item->video; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($params->get('item_show_item_tags', 1)) : ?>
    <!-- PRODUCT TAGS -->
    <div class="product-tags">
     <?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
    <?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
    </div>
    <?php endif; ?>
    <!-- PRODUCT FOOTER -->
    <div class="product-footer">
        <button class="btn btn-primary btn-order"><i class="icon-shopping-cart icon-white"></i><?php echo JText::_('COM_DZPRODUCT_BTN_ADD_TO_CART'); ?></button>
    </div>
    
    <!-- PRODUCT BOTTOM MODULE -->
<?php
jimport('joomla.application.module.helper');
$modules = JModuleHelper::getModules('after-content'); 
;?>
<?php if (!empty($modules)) :?>
    <div class="product-modules">
        <?php foreach($modules as $module) :?>
        <?php 
        $module_params= json_decode($module->params) ;
        $module->content = JModuleHelper::renderModule($module)
        ;?>

            <?php if (!empty ($module->content)) : ?>
            <div id="m<?php echo $module->id;?>" class="module<?php echo $module_params->moduleclass_sfx;?>">
                <div class="module-inner">
                <?php if ($module->showtitle) :?>
                    <h3 class="module-header"><?php echo $module->title;?></h3>
                <?php endif; ?>
                    <div class="module-content">
                        <?php echo $module->content;?>
                    </div>
                </div>       
            </div>
            <?php endif;?>

        <?php endforeach ;?>
    </div>
<?php endif;?>
    
    
    
</div>

