<?php

/**
 * @version     1.1.0
 * @package     com_dzproduct
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
require_once JPATH_COMPONENT.'/helpers/dzproduct.php';

/**
 * View to edit
 */
class DzproductViewItem extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;
    protected $params;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        
        $app    = JFactory::getApplication();
        $user       = JFactory::getUser();
        
        $this->state = $this->get('State');
        $this->item = $this->get('Data');
        
        // Merge item params. If this is single-item view, menu params override item params
        // Otherwise, item params override menu item params
        $this->params = $this->state->get('params');
        $active = $app->getMenu()->getActive();
        $temp = clone ($this->params);
        if ($active) {
            $currentLink = $active->link;
            // If the current view is the active item and an item view for this item, then the menu item params take priority
            if (strpos($currentLink, 'view=item') && (strpos($currentLink, '&id='.(string) $this->item->id)))
            {
                // $item->params are the item params, $temp are the menu item params
                // Merge so that the menu item params take priority
                $this->item->params->merge($temp);
            }
            else
            {
                $temp->merge($this->item->params);
                $this->item->params = $temp;
            }
        } else {
            // Merge so that item params take priority
            $temp->merge($this->item->params);
            $this->item->params = $temp;
        }
        
        $this->item->catid_title = $this->getModel()->getCategoryName($this->item->catid)->title;
        $this->form     = $this->get('Form');
        
        $this->item->tags = new JHelperTags;
        $this->item->tags->getItemTags('com_dzproduct.item' , $this->item->id);
        
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
        
        
        if($this->_layout == 'edit') {
            
            $authorised = $user->authorise('core.create', 'com_dzproduct');

            if ($authorised !== true) {
                throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }
        
        $this->_prepareDocument();

        parent::display($tpl);
    }


    /**
     * Prepares the document
     */
    protected function _prepareDocument()
    {
        $app    = JFactory::getApplication();
        $menus  = $app->getMenu();
        $title  = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if($menu)
        {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('com_dzproduct_DEFAULT_PAGE_TITLE'));
        }
        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->item->metadesc)
        {
            $this->document->setDescription($this->item->metadesc);
        }
        elseif (!$this->item->metadesc && $this->params->get('menu-meta_description'))
        {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->item->metakey)
        {
            $this->document->setMetadata('keywords', $this->item->metakey);
        }
        elseif (!$this->item->metakey && $this->params->get('menu-meta_keywords'))
        {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots'))
        {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
        
        $mdata = $this->item->metadata->toArray();
        foreach ($mdata as $k => $v)
        {
            if ($v)
            {
                $this->document->setMetadata($k, $v);
            }
        }
    }        
    
}
