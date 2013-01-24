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
	
	public $showImportForm = false;
	
    public function init() {
        parent::init();
    }
    
    public function getList() {
        $list = parent::getList();
        
        // If subsites in use, filter by subsite
        if(class_exists('Subsite') && $this->modelClass == 'CustomMenuHolder') {
            $subsite_id = ($this->owner->CurrentSubsite()) ? $this->owner->CurrentSubsite()->ID : 0;            
            
            $list->where("SubsiteID = '{$subsite_id}'");
        }
            
        return $list;
    }
	
	public function getEditForm($id = null, $fields = null) {
    	$form = parent::getEditForm($id, $fields);
		
		$fields = $form->Fields();
		$gridField = $fields->fieldByName('CustomMenuHolder');
		
		// Tidy up category config and remove default add button
		$field_config = $gridField->getConfig();
		$field_config
            ->removeComponentsByType('GridFieldExportButton')
            ->removeComponentsByType('GridFieldPrintButton')
            ->removeComponentsByType('GridFieldAddNewButton');

        // Add creation button if member has create permissions
        if(Permission::check('ADMIN') || Permission::check('MENU_CREATE')) {
		    $add_button = new GridFieldAddNewButton('toolbar-header-left');
		    $add_button->setButtonName('Add Menu');
		    
            $field_config->addComponent($add_button);
        }
            
        return $form;
    }
}
