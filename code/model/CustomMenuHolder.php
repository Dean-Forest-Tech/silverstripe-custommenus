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
    
    public function getCMSFields() {
        // Create form fields
        $fields = new FieldList(
            new TabSet('Root',
            new Tab(
                _t('CustomMenus.FormMain','Main'),
                new HiddenField('ID','id #',$id),
                new HeaderField('MenuHeading',_t('CustomMenus.FormMainHeader','Edit Menu')),
                new TextField('Title', _t('CustomMenus.FormMainTitle','Menu Title')),
                new TextField('Slug', _t('CustomMenus.FormMainSlug','Menu Slug (used in your control call)'))
            ), new Tab(_t('CustomMenus.FormPages','Pages'),
                new CustomMenuField('Pages',_t('CustomMenus.FormPagesPages','Pages in menu'))
            ), new Tab(_t('CustomMenus.FormOrder','Order'),
                new CustomMenuOrderField('OrderList',_t('CustomMenus.FormOrderOrderList','This is how your menu is currently ordered')),
                new TextField('Order',_t('CustomMenus.FormOrderOrder','Customise this order (list of page IDs, seperated by a comma)'))
            )
            )
        );
        
        $this->extend('updateCMSFields', $fields);
        
        return $fields;
    }
    
    public function getCMSActions() {
        $actions = new FieldList(
            new FormAction('doDeleteMenu', _t('CustomMenus.FormActionDelete','Delete Menu')),
            new FormAction('doUpdateMenu', _t('CustomMenus.FormActionUpdate','Update Menu'))
        );
        
        $this->extend('updateCMSActions', $actions);
        
        return $actions;
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