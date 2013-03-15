<?php

class EditLinkPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_filters = array('public_navigation_admin_bar');

    public function filterPublicNavigationAdminBar($navLinks)
    {
        $view = get_view();
        if(isset($view->item)) {
            $record = $view->item;
            $aclRecord = $view->item;
        }
        
        if(isset($view->collection)) {
            $record = $view->collection;
            $aclRecord = $view->collection;
        }
        
        if(isset($view->simple_pages_page)) {
            $record = $view->simple_pages_page;
            $aclRecord = 'SimplePages_Page';
        }
        
        if(isset($view->exhibit_page)) {
            $record = $view->exhibit_page;
            $aclRecord = $view->exhibit;
        }                
        
        if(!isset($record)) {
            return $navLinks;
        }
        
        if(is_allowed($aclRecord, 'edit')) {
            set_theme_base_url('admin');
            if(get_class($record) == 'ExhibitPage') {
                $url = url('exhibits/edit-page-content/' . $record->id);
            } else {
                $url = record_url($record, 'edit');
            }
            //want to place it first in the navigation, so do an array merge
            $editLinks['Edit Link'] = array(
                    'label'=>'Edit',
                    'uri'=> $url
                    );
            revert_theme_base_url();
        }
        $navLinks = array_merge($editLinks, $navLinks);
        return $navLinks;
    }
}