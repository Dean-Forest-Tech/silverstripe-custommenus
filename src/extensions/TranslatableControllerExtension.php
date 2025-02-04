<?php

namespace DFT\SilverStripe\CustomMenus\Extensions;

use SilverStripe\ORM\ArrayList;

class TranslatableControllerExtension extends ControllerExtension
{

    public function CustomMenu($menu = "")
    {
        $currentLocale = Translatable::get_current_locale();

        if (Translatable::default_locale() !== $currentLocale) {
            Translatable::set_current_locale(Translatable::default_locale());
        }

        $menu_items = parent::CustomMenu($menu);

        if (Translatable::default_locale() !== $currentLocale) {
            Translatable::set_current_locale($currentLocale);
            $menu_item_translatable = ArrayList::create();
            
            foreach ($menu_items as $item) {
                if ($t = $item->getTranslation($currentLocale)) {
                    $menu_item_translatable->push($t);
                }
            }

            $menu_items = $menu_item_translatable;
        }

        return $menu_items;
    }
}
