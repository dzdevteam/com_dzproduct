<?php
/**
 * @package     DZ Products
 * @update      April 2013
 * @copyright   Copyright © 2013 DZ Creative Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;
$productid = JRequest::getVar('Itemid');
$app = JFactory::getApplication();
$menu = &JSite::getMenu();
$active = $menu->getItem($productid);
$params = $menu->getParams( $active->id );
$pageclass_sfx = $params->get( 'pageclass_sfx' );
?>

<div class="component-inner products-category<?php if($pageclass_sfx) echo $pageclass_sfx?>">
    <?php if ($this->params->get('show_page_heading')) : ?>
    <!-- PAGE HEADING -->
    <h1 class="page-heading">
        <?php echo $this->escape($this->params->get('page_heading')); ?>
    </h1>
    <?php endif; ?>
    
    <?php if ($this->category->id != 0) : // Do not show root information ?>
    <?php if ($this->params->get('category_show_title', 1)) : ?>
    <!-- CATEGORY TITLE -->    
    <h2 class="category-heading"><?php echo $this->category->title; ?></h2>
    <?php endif; ?>
    
    <?php if ($this->params->get('category_show_image', 1)) : ?>
    <!-- CATEGORY IMAGE -->
    <div class="category-image">
        <img src="<?php echo $this->category->params['image']; ?>" alt="Category title"/>
    </div>
    <?php endif; ?>
    
    <?php if ($this->params->get('category_show_description', 1)) : ?>
    <!-- CATEGORY DESC -->
    <div class="category-desc">
        <?php echo $this->category->description; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($this->params->get('category_show_subcats', 1)) : ?>
    <!-- CATEGORY CHILD -->
    <div class="category-child">
        <ul>
            <?php foreach ($this->children as $child) { ?>
            <li><a href="#"><?php echo $child->title; ?></li>
            <?php } ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <!-- CATEGORY FILTER -->
    <div class="category-filter">
        <?php if ($this->params->get('category_show_subcats_filter', 1)) : ?>
        <select class="filter-dropdown">
        <option value=""><?php echo JText::_('JGLOBAL_CHOOSE_CATEGORY_LABEL'); ?>
        <?php foreach ($this->children as $child) { ?>
        <option value="<?php echo $child->id; ?>"><?php echo $child->title; ?></option>
        <?php } ?>
        </select>
        <?php endif; ?>
        <?php if ($this->params->get('category_show_text_filter', 1)) : ?>
        <div class="filter-text input-append">
          <input class="input-medium" id="appendedInputButtons" type="text">
          <button class="btn" type="button">Search</button>
          <button class="btn" type="button">Reset</button>
        </div>
       <?php endif; ?>
    </div>
    <?php endif; // End Root Category check?>
    
    <!-- CATEGORY PRODUCTS -->

    <div class="product-grid">    
        <?php
            $column = $this->params->get('category_number_of_columns', 3) ;
            $length = $this->params->get('category_item_intro_text_length', 180);
        ?>        
        <?php foreach(array_chunk($this->products,$column) as $row) :?>
        <div class="row-fluid">
            <?php foreach ($row as $product) : ?>    
            <div class="span<?php echo 12/$column;?>">
                <div class="product-item">
                <a href="<?php echo $product->link; ?>" class="product-link">
                    <?php if ($this->params->get('category_show_item_intro_image')) : ?>
                    <div class="product-image"><img src="<?php echo JUri::root().'/'.$product->images['intro'];?>" alt="<?php echo $product->title;?>"/></div>
                    <?php endif; ?>
                    
                    <?php if ($this->params->get('category_show_item_title', 1)) : ?>
                    <h3 class="product-title"><?php echo $product->title;?></h3>
                    <?php endif; ?>
                </a>
                    <?php if ($this->params->get('category_show_item_category', 1)) : ?>
                    <div class="product-category"><?php echo $product->catid_title;?></div>
                    <?php endif; ?>
                    
                    <?php if ($this->params->get('category_show_item_fields', 1)) : ?>
                    <div class="product-info">
                        <ul>
                            <?php foreach ($product->fields as $field) { ?>
                            <?php $tag = JFactory::getLanguage()->getTag(); ?>
                            <li><span><?php echo $field['dname'][$tag]; ?>: <?php echo $field['value']; ?>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($this->params->get('category_show_item_intro_text', 1)) : ?>
                    <div class="product-intro">
                        <?php echo mb_substr(strip_tags($product->short_desc),0,$length, "UTF-8");?>...
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($this->params->get('category_show_item_price', 1)) : ?>
                    <div class="product-price">
                        <?php if($product->saleoff) :?>
                            <span><?php echo $product->saleoff;?></span><em><?php echo $product->price;?></em>
                        <?php else :?>
                            <span><?php echo $product->price;?></span>
                        <?php endif;?>                            
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($this->params->get('category_show_item_readmore_btn', 1)) : ?>
                    <a href="<?php echo $product->link; ?>">View more</a>
                    <?php endif; ?>
                    
                    <?php if ($this->params->get('category_show_item_saleoff_label', 1)) : ?>
                    <div class="product-labels">
                    <?php if($product->saleoff) :?>
                    <span class="product-saleoff">
                        -<?php echo (100*($product->price - $product->saleoff)/$product->price) ;?>%
                    </span>
                    <?php endif;?>
                    <?php endif; ?>
                    
                    <?php if ($this->params->get('category_show_item_featured_label', 1)) : ?>
                    <span class="product-featured">
                        <?php echo $product->featured; ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if ($this->params->get('category_show_item_new_arrival_label', 1)) : ?>
                    <span class="product-new">
                        <?php echo $product->new_arrival; ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if ($this->params->get('category_show_item_availability_label', 1)) : ?>
                    <span class="product-avail">
                        <?php echo DZProductHelper::availabilityText($product->availability); ?>
                    </span>
                    <?php endif; ?>
                    </div>
                
                </div>                
               
            </div>
            <?php endforeach; ?>
        
        </div>
        <?php endforeach; ?>

        
    </div>
    <!-- CATEGORY PAGINATION -->
    <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
    <!-- CATEGORY BOTTOM MODULE -->
<?php
jimport('joomla.application.module.helper');
$modules = JModuleHelper::getModules('after-category'); 
;?>
<?php if (!empty($modules)) :?>
    <div class="category-modules">
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