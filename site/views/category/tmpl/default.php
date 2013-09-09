<?php
/**
 * @package     DZ Products
 * @update      April 2013
 * @copyright   Copyright © 2013 DZ Creative Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;
$itemid = JRequest::getVar('Itemid');
$menu = &JSite::getMenu();
$active = $menu->getItem($itemid);
$params = $menu->getParams( $active->id );
$pageclass_sfx = $params->get( 'pageclass_sfx' );
?>

<div class="component-inner products-category<?php if($pageclass_sfx) echo $pageclass_sfx?>">
    <!-- PAGE HEADING -->
    <?php if ($this->params->get('show_page_heading')) : ?>
    <h1 class="page-heading">
        <?php echo $this->escape($this->params->get('page_heading')); ?>
    </h1>
    <?php endif; ?>
    
    <!-- CATEGORY TITLE -->
    <h2 class="category-heading">Category Title Goes here</h2>
    <!-- CATEGORY IMAGE -->
    <div class="category-image">
        <img src="http://placehold.it/800x120" alt="Category title"/>
    </div>
    <!-- CATEGORY DESC -->
    <div class="category-desc">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
    </div>
    <!-- CATEGORY CHILD -->
    <div class="category-child">
        <ul>
            <li><a href="#">Sub category 1</a></li>
            <li><a href="#">Sub category 2</a></li>
            <li><a href="#">Sub category 3</a></li>
            <li><a href="#">Sub category 4</a></li>
        </ul>
    </div>
    <!-- CATEGORY FILTER -->
    <div class="category-filter">
        <select class="filter-dropdown">
        <option>Sub category 1</option>
        <option>Sub category 2</option>
        <option>Sub category 3</option>
       </select>
        <div class="filter-text input-append">
          <input class="input-medium" id="appendedInputButtons" type="text">
          <button class="btn" type="button">Search</button>
          <button class="btn" type="button">Reset</button>
        </div>
       
    </div>
    
    <!-- CATEGORY PRODUCTS -->

    <div class="product-grid">    
        <?php
            $column = 3 ;
            $length = 120;
        ?>        
        <?php foreach(array_chunk($this->items,$column) as $row) :?>
        <div class="row-fluid">
            <?php foreach ($row as $item) : ?>    
            <div class="span<?php echo 12/$column;?>">
                <div class="product-item">
                <?php $images = json_decode($item->images);?>
                <a href="<?php echo $item->link; ?>" class="product-link">
                    <div class="product-image"><img src="<?php echo $images->intro;?>" alt="<?php echo $item->title;?>"/></div>
                    <h3 class="product-title"><?php echo $item->title;?></h3>
                </a>
                    <div class="product-category"><?php echo $item->catid_title;?></div>
                    <div class="product-info">
                        <ul>
                            <li><span>Field 1: </span>123456</li>
                            <li><span>Field 2: </span>ABCZYZ</li>
                            <li><span>Field 3: </span>87381</li>
                        </ul>
                    </div>
                    <div class="product-intro">
                        <?php echo mb_substr(strip_tags($item->short_desc),0,$length, "UTF-8");?>...
                    </div>
                    <div class="product-price">
                        <?php if($item->saleoff) :?>
                            <span><?php echo $item->saleoff;?></span><em><?php echo $item->price;?></em>
                        <?php else :?>
                            <span><?php echo $item->price;?></span>
                        <?php endif;?>                            
                    </div>
                    <a href="<?php echo $item->link; ?>">View more</a>
                    
                    <div class="product-labels">
                    <?php if($item->saleoff) :?>
                    <span class="product-saleoff">
                        -<?php echo (100*($item->price - $item->saleoff)/$item->price) ;?>%
                    </span>
                    <?php endif;?>
                    <span class="product-featured">
                        featured
                    </span>
                    <span class="product-new">
                        new arrival
                    </span>
                    <span class="product-avail">
                        out of stock
                    </span>
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