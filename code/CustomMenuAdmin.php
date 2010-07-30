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
        Requirements::javascript('custommenus/javascript/CustomMenu_right.js');
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

        else {
            // Create form fields
            $fields = new FieldSet(
                new TabSet(
                    'Root',
                    new Tab(
                        _t('CustomMenus.FormMain'),
                        new HiddenField('ID','id #',$id),
                        new HeaderField('MenuHeading',_t('CustomMenus.FormMainHeader')),
                        new TextField('Title', _t('CustomMenus.FormMainTitle')),
                        new TextField('Slug', _t('CustomMenus.FormMainSlug'))
                    ),
                    new Tab(_t('CustomMenus.FormPages'),
                        new CustomMenuField('Pages',_t('CustomMenus.FormPagesPages'))
                    ),
                    new Tab(_t('CustomMenus.FormOrder'),
                        new CustomMenuOrderField('OrderList',_t('CustomMenus.FormOrderOrderList')),
                        new TextField('Order',_t('CustomMenus.FormOrderOrder'))
                    )
                )
            );

            $actions = new FieldSet(
                new FormAction('doUnpublishMenu', _t('CustomMenus.FormActionUnpublish','Unpublish')),
                new FormAction('doDeleteMenu', _t('CustomMenus.FormActionDelete','Delete')),
                new FormAction('doSaveMenu', _t('CustomMenus.FormActionSave','Save')),
                new FormAction('doPublishMenu', _t('CustomMenus.FormActionPublish','Publish'))
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

    /**
    * doSaveMenu saves the data in the menu object to the draft version
    * of the site.
    *
    * @param <type> $data
    * @param <type> $form
    * @return <type> Form Response
    */
    function doSaveMenu($data, $form) {
        $record = Versioned::get_one_by_stage('CustomMenuHolder', "Stage", "ID = '{$data['ID']}'");
        $form->saveInto($record);

        $record->Status = "Saved (update)";
        $record->writeToStage('Stage');
        $record->flushCache();
        
        FormResponse::status_message(_t('CustomMenuAdmin.FormSaved','Saved'), 'good');
        
        return FormResponse::respond();
    }

    /**
    * doPublishMenu publish menu to
    *
    * @param <type> $data
    * @param <type> $form
    * @return <type> Form Response
    */
    function doPublishMenu($data, $form) {
        $record = Versioned::get_one_by_stage('CustomMenuHolder', "Stage", "ID = '{$data['ID']}'");
        $form->saveInto($record);
        $record->Status = "Published";
        $record->Publish('Stage','Live');
        $record->flushCache();
        
        FormResponse::status_message(_t('CustomMenuAdmin.FormPublished','Published'), 'good');

        return FormResponse::respond();
    }

    function doDeleteMenu($data, $form) {
        $record = DataObject::get_by_id("CustomMenuHolder", $data['ID']);
        $record->Status = "Deleted";
        $record->delete();
        FormResponse::status_message(_t('MenuAdmin.FormDeleted','Deleted Menu'), 'good');

        return FormResponse::respond();
    }

    function doUnpublishMenu($data, $form) {
        $record = DataObject::get_by_id("CustomMenuHolder", $data['ID']);

        if(!$record->canDeleteFromLive())
            FormResponse::status_message(_t('MenuAdmin.FormError','There was an error'), 'bad');
        else {
            $record->Status = "Unpublished";
            $record->delete();
            FormResponse::status_message(_t('MenuAdmin.FormUnpublished','Menu removed from live site'), 'good');
        }

        return FormResponse::respond();
    }

    function doCreateMenu($data, $form) {
        $menu = new CustomMenuHolder();
        $menu->Title = 'New Menu';
        $menu->Slug = "new-menu";
        $menu->writeToStage('Stage');

        FormResponse::status_message(_t('MenuAdmin.CREATEMENU','Menu Created'), 'good');
        return FormResponse::respond();
    }
}
