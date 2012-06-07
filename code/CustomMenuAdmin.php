<?php
/**
 * CustomMenuAdmin creates an admin area that allows developers to
 * create/edit/delete custom menu's for their site. These menu's can then be
 * accessed via the Control CustomMenu(Slug)
 * 
 */

class CustomMenuAdmin extends ModelAdmin {
    public static $url_segment = 'menus';
    public static $menu_title = 'Menus';
    public static $menu_priority = 10;
    public static $managed_models = array('CustomMenuHolder');
	
    public function init() {
        parent::init();
    }
}