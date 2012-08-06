<?php
i18n::include_locale_file('custommenus', 'en_US');

// Add custom menu controller to ContentController
Object::add_extension('ContentController', 'CustomMenu');

if(class_exists('Subsite')) Object::add_extension('CustomMenuHolder', 'CustomMenuHolder_SubsiteExtension');
Object::add_extension('SiteTree','CustomMenuExtension');
