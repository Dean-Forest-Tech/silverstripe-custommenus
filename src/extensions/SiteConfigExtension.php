<?php

namespace ilateral\SilverStripe\CustomMenus\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\FieldList;
use ilateral\SilverStripe\CustomMenus\Model\CustomMenuHolder;

/**
 * CustomMenuHolder_SubsiteExtension is used if the Subsites module is detected,
 * adding extra features to the menu.
 *
 * @author Mo <morven@ilateral.co.uk>
 */
class SiteConfigExtension extends DataExtension
{
    
    private static $has_many = [
        'Menus' => CustomMenuHolder::class
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->removeByName("Menus");
        
        $fields->addFieldToTab(
            "Root.Menus",
            GridField::create(
                "Menus",
                $this->owner->fieldLabel("Menus"),
                $this->owner->Menus(),
                $config = GridFieldConfig_RelationEditor::create()
            )
        );

        // Tidy up category config and remove default add button
        $config
            ->removeComponentsByType(GridFieldExportButton::class)
            ->removeComponentsByType(GridFieldPrintButton::class);
    }
}
