<?php
/**
 * CustomMenuHolder_SubsiteExtension is used if the Subsites module is detected,
 * adding extra features to the menu.
 *
 * @author morven
 */
class CustomMenuSiteConfigExtension extends DataExtension
{
    
    private static $has_many = array(
        'Menus' => 'CustomMenuHolder'
    );

    public function updateCMSFields(FieldList $fields)
    {
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
            ->removeComponentsByType('GridFieldExportButton')
            ->removeComponentsByType('GridFieldPrintButton');
    }
}
