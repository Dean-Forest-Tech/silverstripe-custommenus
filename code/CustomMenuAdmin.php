<?php
class CustomMenuAdmin extends LeftAndMain {
	static $url_segment = 'menus';
	static $menu_title = 'Menus';
	static $menu_priority = 30;
	static $url_priority = 41;
	static $url_rule = '/$Action/$ID/$OtherID';
	static $tree_class = "CustomMenuHolder";
	
	public function init() {
		parent::init();
		Requirements::css('sitemenus/css/CustomMenu.css');
	}

	/**
	 * get_menus retrieves all CustomMenuHolder objects from the database, which will be rendered
	 * in the left pain by the template
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
				new HiddenField('ID','id #',$id),
				new HeaderField('MenuHeading',_t('CustomMenuAdmin.MENUHEADING','Edit Menu')),
				new TextField('Title', _t('CustomMenuAdmin.MENUTITLE','Menu Title')),
				new TextField('Slug', _t('CustomMenuAdmin.MENUSLUG','Menu Slug (used in your control call)')),
				new CheckboxSetField('Pages','Pages in Menu',DataObject::get('Page',"ParentID = '0'"))
			);
	
			$actions = new FieldSet(
				new FormAction('doUpdateMenu', _t('CustomMenuAdmin.UPDATEMENU','Update Menu'))
			);

			$form = new Form($this, "EditForm", $fields, $actions);

			$currentMenu = DataObject::get_by_id('CustomMenuHolder',$id);

			$form->loadDataFrom($currentMenu);

			return $form;
		}
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
}