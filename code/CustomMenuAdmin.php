<?php
/**
 * CustomMenuAdmin creates an admin area that allows developers to
 * create/edit/delete custom menu's for their site. These menu's can then be
 * accessed via the Control CustomMenu(Slug)
 * 
 */

class CustomMenuAdmin extends LeftAndMain {
	static $url_segment = 'menus';
	static $menu_title = 'Menus';
	static $menu_priority = 30;
	static $url_priority = 41;
	static $url_rule = '/$Action/$ID/$OtherID';
	static $tree_class = "CustomMenuHolder";
	
	public function init() {
		parent::init();
		Requirements::css('custommenus/css/CustomMenu.css');
	}
        
        /**
         * Basic action to display a blank pannel when the root node is selected
         * from the left sitetree.
         *
         * @return <type> false
         */
        public function root() {
            return false;
        }


	/**
	 * get_menus retrieves all CustomMenuHolder objects from the database,
         * which will be rendered in the left pain by the template.
	 * 
	 * @return DataObjectSet
	 */
	public function get_menus() {
		$result = DataObject::get('CustomMenuHolder');
		return $result;
	}
	
	/**
	 * Generate the editform, only if there is a URL ID available
	 * @see cms/code/LeftAndMain#getEditForm($id)
	 */
	function getEditForm($id = null) {
		if(!$id)
			$id = $this->urlParams['ID'];
		
		if($id) {
			// Create form fields
			$fields = new FieldSet(
                            new TabSet('Root',
                                new Tab(
                                    _t('CustomMenus.MAINTABTITLE','Main'),
                                    new HiddenField('ID','id #',$id),
                                    new HeaderField('MenuHeading',_t('CustomMenuAdmin.MENUHEADING','Edit Menu')),
                                    new TextField('Title', _t('CustomMenuAdmin.MENUTITLE','Menu Title')),
                                    new TextField('Slug', _t('CustomMenuAdmin.MENUSLUG','Menu Slug (used in your control call)'))
                                ), new Tab(_t('CustomMenus.PAGESTABTITLE','Pages'),
                                    new CustomMenuField('Pages',_t('CustomMenuAdmin.MENUPAGES','Pages in Menu'))
                                )
                            )
			);
	
			$actions = new FieldSet(
				new FormAction('doDeleteMenu', _t('CustomMenuAdmin.DELETEMENU','Delete Menu')),
				new FormAction('doUpdateMenu', _t('CustomMenuAdmin.UPDATEMENU','Update Menu'))
			);

			$form = new Form($this, "EditForm", $fields, $actions);

			$currentMenu = DataObject::get_by_id('CustomMenuHolder',$id);

			$form->loadDataFrom($currentMenu);

			return $form;
		}
	}
	
	function leftMenuForm() {
		// Create form fields
		$fields = new FieldSet(
			new HiddenField('ID','id#','new')
		);
	
		$actions = new FieldSet(
			new FormAction('doCreateMenu', _t('CustomMenuAdmin.CREATEMENU','Create Menu'))
		);

		$form = new Form($this, "LeftMenuForm", $fields, $actions);
		
		return $form;
	}
 
	function doUpdateMenu($data, $form) {
		$id = $data['ID'];
		
		$record = DataObject::get_by_id("CustomMenuHolder", $id);
		$record->Status = "Saved (update)";
		$form->saveInto($record);
		
		if($record->write()) {
			FormResponse::status_message(_t('MenuAdmin.UPDATEDMENU','Updated Menu'), 'good');
			FormResponse::update_status($record->Status);
			FormResponse::set_node_title($id, $record->Title);
			FormResponse::get_page($id);
		} else {
			FormResponse::status_message(_t('MenuAdmin.UPDATEFAILED','Update Failed'), 'bad');
		}

		return FormResponse::respond();
	}
	
	function doDeleteMenu($data, $form) {
		$id = $data['ID'];
		
		$record = DataObject::get_by_id("CustomMenuHolder", $id);
		
		if($record->delete())
			FormResponse::status_message(_t('MenuAdmin.DELETEMENU','Deleted Menu'), 'good');
		 else
			FormResponse::status_message(_t('MenuAdmin.DELETEFAILED','Delete Failed'), 'bad');

		return FormResponse::respond();
	}
	
	function doCreateMenu($data, $form) {
		
		$menu = new CustomMenuHolder();
		$menu->Title = 'New Menu';
		$menu->Slug = "new-menu";

		if($menu->write())
			FormResponse::status_message(_t('MenuAdmin.CREATEMENU','Menu Created'), 'good');
		 else
			FormResponse::status_message(_t('MenuAdmin.CREATEFAILED','Failed Creating Menu'), 'bad');

		return FormResponse::respond();
	}
}
