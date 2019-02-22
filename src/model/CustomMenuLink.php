<?php

namespace ilateral\SilverStripe\CustomMenus\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\DropdownField;
use SilverStripe\View\ArrayData;
use SilverStripe\Security\Permission;
use NathanCox\HasOneAutocompleteField\Forms\HasOneAutocompleteField;

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

    const TITLE_FIELD = 'Title';

    const LABEL_FIELD = 'Label';

    const SEARCH_FIELD = 'SearchFields';

    private static $table_name = 'CustomMenuLink';

    /**
     * Possible object classes that can be related to
     * this object
     * 
     * This can either be a simple list of ClassNames, EG:
     * 
     * ilateral\SilverStripe\CustomMenus\Model\CustomMenuLink:
     *   base_classes:
     *    'SilverStripe\CMS\Model\SiteTree': "Page on site"
     *
     * OR, you can provide a multi-dimensional array of classes and specific
     * labels and search_fields (which allows you to customise how each class
     * is searched for). EG:
     * 
     * ilateral\SilverStripe\CustomMenus\Model\CustomMenuLink:
     *   base_classes:
     *    'SilverStripe\CMS\Model\SiteTree': "Page on site"
     *    'App\Model\Product':
     *      Title: 'My Product'
     *      Label: 'Title'
     *      SearchFields:
     *        - Label
     *        - URLSegment
     *        - StockID
     * 
     * @var array
     */
    private static $base_classes = [];

    /**
     * The field used by the HasOneField to display the linked objects name.
     * 
     * Changing this will allow you change which field is show when an object is linked
     * (EG: URLSegment, MenuTitle, etc).
     * 
     * @var string
     */
    private static $default_label_field = "Title";

    private static $db = [
        'BaseClass'	=> 'Varchar(255)',
        'ObjectID'	=> 'Int',
        'SortOrder'	=> 'Int'
    ];

    private static $has_one = [
        'Menu'	=> CustomMenuHolder::class
    ];

    private static $default_sort = [
        "SortOrder" => "ASC"
    ];

    private static $casting = [
        'Type' => 'Varchar',
        'Title' => 'Varchar'
    ];

    private static $summary_fields = [
        'Title',
        'Type',
        'ObjectID'
    ];

    private static $searchable_fields = [
        'Title'
    ];

    /**
     * Get a list of all base classes that are multi dimensional in nature
     * 
     * @return array
     */
    protected function getAssociativeClasses()
    {
        return array_filter(
            $this->config()->base_classes,
            'is_array'
        );
    }

    /**
     * Get a list of all base classes that are in a list of simple array values
     * 
     * @return array
     */
    protected function getNonAssociativeClasses()
    {
        $return = $this->config()->base_classes;

        foreach ($this->getAssociativeClasses() as $class => $value) {
            if (isset($return[$class])) {
                unset($return[$class]);
            }
        }
        return $return;
    }

    /**
     * Get a list of classes suitable for loading into a dropdown
     * 
     * @return array
     */
    public function getClassesForDropdown()
    {
        $return = [];

        // First check any associative classes
        foreach ($this->getAssociativeClasses() as $class => $values) {
            if (isset($values[self::TITLE_FIELD])) {
                $return[$class] = $values[self::TITLE_FIELD];
            } else {
                $return[$class] = $class;
            }
        }

        // Next check any basic class mappings
        foreach ($this->getNonAssociativeClasses() as $class => $title) {
            $return[$class] = $title;
        }

        // Finally return list
        return $return;
    }

    /**
     * See if the configured classes have a custom label
     * 
     * @return string
     */
    public function getLabelField()
    {
        $base_class = $this->BaseClass;
        $default = $this->config()->default_label_field;

        // Is base class not currently set?
        if (empty($base_class)) {
            return $default;
        }

        // First check any associative classes
        foreach ($this->getAssociativeClasses() as $class => $values) {
            if ($class == $base_class && isset($values[self::LABEL_FIELD])) {
                return $values[self::LABEL_FIELD];
            }
        }

        // Finally return default
        return $default;
    }

    /**
     * Get a list of search fields for the current class (if defined)
     * 
     * @return array
     */
    public function getSearchFields()
    {
        $base_class = $this->BaseClass;

        // Is base class not currently set?
        if (empty($base_class)) {
            return [];
        }

        // First check any associative classes
        foreach ($this->getAssociativeClasses() as $class => $values) {
            if ($class == $base_class && isset($values[self::SEARCH_FIELD])) {
                return $values[self::SEARCH_FIELD];
            }
        }

        // Finally return default
        return [];
    }

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            if (!$this->canEdit()) {
                return;
            }

            $fields->removeByName("SortOrder");

            $fields->replaceField(
                "BaseClass",
                DropdownField::create(
                    "BaseClass",
                    $this->fieldLabel("BaseClass"),
                    $this->getClassesForDropdown()
                )
            );

            if ($this->BaseClass) {
                $search_fields = $this->getSearchFields();
                $fields->addFieldToTab(
                    'Root.Main',
                    $link_field = HasOneAutocompleteField::create(
                        'ObjectID',
                        _t("CustomMenus.LinkedObject", "Linked Object"),
                        $this->BaseClass,
                        $this->getLabelField()
                    ),
                    "MenuID"
                );

                if (is_array($search_fields) && count($search_fields)) {
                    $link_field->setSearchFields($search_fields);
                }
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
        $id = (int)$this->ObjectID;
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

    /**
     * Get the configured title for the linked object
     * 
     * @return string
     */
    public function getTitle()
    {
        $label = $this->getLabelField();
        return $this->Object()->{$label};
    }

    /**
     * Get the type of object linked to this menu item
     * 
     * @return string
     */
    public function getType()
    {
        $class = $this->BaseClass;
        $list = $this->getClassesForDropdown();

        if (empty($class) || !in_array($class, array_keys($list))) {
            return "";
        }
        
        return $list[$class];
    }

    public function canView($member = null)
    {
    	return true;
    }

    public function canCreate($member = null, $context = [])
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
