<?php

/**
 * Single link that will appear in this menu. This link can be
 * associated with any generic data object, as long as the object
 * has the following methods/Properties
 * 
 * Methods:
 * - Link
 * - AbsoluteLink
 * - RelativeLink
 * 
 * Properties:
 * - Title
 * - MenuTitle
 * 
 * @author Mo <morven@ilateral.co.uk>
 * @package CustomMenus
 */
class CustomMenuLink extends DataObject
{

    /**
     * Possible object classes that can be related to
     * this object
     *
     * @var array
     */
    private static $base_classes = [];

    private static $db = [
        'BaseClass'	=> 'VarChar',
        'ObjectID'	=> 'Int',
        'SortOrder'	=> 'Int'
    ];

    private static $has_one = [
        'Menu'	=> 'CustomMenuHolder'
    ];

    private static $default_sort = [
        "SortOrder" => "ASC"
    ];

    private static $casting = [
        'Title' => 'Varchar'
    ];

    private static $summary_fields = [
        'Title',
        'BaseClass',
        'ObjectID'
    ];

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            if (!$this->canEdit()) {
                return;
            }

            $fields->replaceField(
                "BaseClass",
                DropdownField::create(
                    "BaseClass",
                    $this->fieldLabel("BaseClass"),
                    $this->config()->base_classes
                )
            );

            $fields->removeByName("SortOrder");

            if ($this->BaseClass) {
                $fields->addFieldToTab(
                    'Root.Main',
                    HasOneAutocompleteField::create(
                        'ObjectID',
                        _t("CustomMenus.LinkedObject", "Linked Object"),
                        $this->BaseClass,
                        'Title'
                    ),
                    "MenuID"
                );
            } else {
                $fields->removeByName("ObjectID");
            }
        });

        return parent::getCMSFields();
    }

    /**
     * Get the associated object by it's ID
     *
     * @return DataObject
     */
    public function Object()
    {
        $id = $this->ObjectID;
        $class = $this->BaseClass;

        if ($class && is_int($id)) {
            $object = DataObject::get_by_id($class, $id);
            
            if (!$object) {
                $object = $class::create();
            }
        } else {
            $object = ArrayData::create([]);
        }

        return $object;
    }

    public function getTitle()
    {
        return $this->Object()->Title;
    }

    public function canView($member = null)
    {
    	return true;
    }

    public function canCreate($member = null)
    {
    	if (Permission::check(['ADMIN','MENU_CREATE'])) {
    		return true;
        } else {
            return false;
        }
    }

    public function canEdit($member = null)
    {
    	return $this->Menu()->canEdit($member);
    }

    public function canDelete($member = null)
    {
    	return $this->Menu()->canDelete($member);
    }
}
