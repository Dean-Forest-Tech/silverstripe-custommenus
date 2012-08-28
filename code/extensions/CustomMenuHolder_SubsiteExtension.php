<?php
/**
 * CustomMenuHolder_SubsiteExtension is used if the Subsites module is detected,
 * adding extra features to the menu.
 *
 * @author morven
 */
class CustomMenuHolder_SubsiteExtension extends DataExtension {
    public function extraStatics($class = null, $extension = null) {
        return array(
            'has_one' => array(
                'Subsite' => 'Subsite'
            )
        );
    }

    function updateCMSFields(FieldList $fields) {
        
    }
}