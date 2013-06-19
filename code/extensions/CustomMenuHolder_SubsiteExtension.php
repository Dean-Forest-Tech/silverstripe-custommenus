<?php
/**
 * CustomMenuHolder_SubsiteExtension is used if the Subsites module is detected,
 * adding extra features to the menu.
 *
 * @author morven
 */
class CustomMenuHolder_SubsiteExtension extends DataExtension {
    private static $has_one => array(
        'Subsite' => 'Subsite'
    );

    public function updateCMSFields(FieldList $fields) {}
}
