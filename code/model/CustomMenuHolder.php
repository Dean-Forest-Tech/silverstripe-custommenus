<?php
class CustomMenuHolder extends DataObject {
    public static $db = array(
        'Title'	=> 'Text',
        'Slug'	=> 'Text',
        'Order'	=> 'Text',
    );

    public static $many_many = array(
        'Pages'	=> 'SiteTree'
    );
    
    public static $summary_fields = array(
        'Title' => 'Menu Title',
        'Slug'  => 'Menu Slug'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        
        $fields->addFieldToTab('Root.Main', new HeaderField('MenuSettings',_t('CustomMenus.FormSettingsHeader','Menu Settings')));
        $fields->addFieldToTab('Root.Main', new TextField('Title', _t('CustomMenus.FormMainTitle','Title')));
        $fields->addFieldToTab('Root.Main', new TextField('Slug', _t('CustomMenus.FormMainSlug','Slug (used in your control call)')));
        
        $fields->addFieldToTab('Root.Main', new HeaderField('MenuPages',_t('CustomMenus.FormPagesHeader','Pages in this Menu')));
        $fields->addFieldToTab('Root.Main', new TreeMultiselectField('Pages',_t('CustomMenus.FormPagesPages','Select pages'), 'SiteTree'));
        
        $fields->addFieldToTab('Root.Main', new HeaderField('MenuOrder',_t('CustomMenus.FormOrderHeader','Order of Pages')));
        $fields->addFieldToTab('Root.Main', new CustomMenuOrderField('OrderList',_t('CustomMenus.FormOrderOrderList','This is how your menu is currently ordered')));
        $fields->addFieldToTab('Root.Main', new TextField('Order',_t('CustomMenus.FormOrderOrder','Customise this order (list of page IDs, seperated by a comma)')));
        
        $this->extend('updateCMSFields', $fields);
        
        return $fields;
    }

    /**
    * Create default menu items if no items exist
    *
    * @see sapphire/core/model/DataObject#requireDefaultRecords()
    */

    function requireDefaultRecords() {
        parent::requireDefaultRecords();

        // Main Menu
        if($this->class == 'CustomMenuHolder') {
            if(!DataObject::get_one($this->class)) {
                $menu = new CustomMenuHolder();
                $menu->Title = 'Main Menu';
                $menu->Slug = "main-menu";
                $menu->write();
                $menu->flushCache();
                if(method_exists('Database','alteration_message'))
                    Database::alteration_message("Main menu created","created");
                else
                    DB::alteration_message("Main menu created","created");

                $menu = new CustomMenuHolder();
                $menu->Title = 'Header Menu';
                $menu->Slug = "header-menu";
                $menu->write();
                $menu->flushCache();
                if(method_exists('Database','alteration_message'))
                    Database::alteration_message("Header menu created","created");
                else
                    DB::alteration_message("Header menu created","created");

                $menu = new CustomMenuHolder();
                $menu->Title = 'Footer Menu';
                $menu->Slug = "footer-menu";
                $menu->write();
                $menu->flushCache();
                if(method_exists('Database','alteration_message'))
                    Database::alteration_message("Footer menu created","created");
                else
                    DB::alteration_message("Footer menu created","created");
            }
        }
    }
}