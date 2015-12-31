<?php
/**
 * CustomMenuAdmin creates an admin area that allows developers to
 * create/edit/delete custom menu's for their site. These menu's can then be
 * accessed via the Control CustomMenu(Slug)
 *
 */

class CustomMenuAdmin extends ModelAdmin
{
    public static $url_segment = 'menus';
    public static $menu_title = 'Menus';
    public static $menu_priority = 10;
    public static $managed_models = array('CustomMenuHolder');

    public $showImportForm = false;

    public function init()
    {
        parent::init();
    }

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);
        $fields = $form->Fields();

        if ($this->modelClass == 'CustomMenuHolder') {
            $gridField = $fields->fieldByName('CustomMenuHolder');

            // Tidy up category config and remove default add button
            $field_config = $gridField->getConfig();
            $field_config
                ->removeComponentsByType('GridFieldExportButton')
                ->removeComponentsByType('GridFieldPrintButton')
                ->removeComponentsByType('GridFieldAddNewButton');

            // Add creation button if member has create permissions
            if (Permission::check('ADMIN') || Permission::check('MENU_CREATE')) {
                $add_button = new GridFieldAddNewButton('toolbar-header-left');
                $add_button->setButtonName(_t('CustomMenus.AddMenu', 'Add Menu'));

                $field_config->addComponent($add_button);
            }

            // Update list of items for subsite (if used)
            if (class_exists('Subsite')) {
                $list = $gridField
                    ->getList()
                    ->filter(array(
                        'SubsiteID' => Subsite::currentSubsiteID()
                    ));

                $gridField->setList($list);
            }
        }

        return $form;
    }
}
