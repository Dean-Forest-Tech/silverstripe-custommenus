<?php
/**
 * CustomMenuHolder_SubsiteExtension is used if the Subsites module is detected,
 * adding extra features to the menu.
 *
 * @author morven
 */
class CustomMenuHolder_SubsiteExtension extends DataObjectDecorator {
    public function extraStatics() {
        return array(
            'has_one' => array(
                'Subsite' => 'Subsite'
            )
        );
    }

    function updateCMSFields(FieldSet &$fields) {
        
    }
}

?>
