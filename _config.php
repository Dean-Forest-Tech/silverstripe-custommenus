<?php
i18n::include_locale_file('custommenus', 'en_US');

// Add custom menu controller to ContentController
ContentController::add_extension('CustomMenu');
SiteTree::add_extension('CustomMenuExtension');
LeftAndMain::add_extension('CustomMenu_LeftAndMain');

// Enable Subsite Support if needed
if(class_exists('Subsite')) CustomMenuHolder::add_extension('CustomMenuHolder_SubsiteExtension');
