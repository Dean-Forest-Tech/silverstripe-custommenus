<?php
// Add custom menu controller to Controller
Controller::add_extension('CustomMenu');
SiteTree::add_extension('CustomMenuExtension');
LeftAndMain::add_extension('CustomMenu_LeftAndMain');

// Enable Subsite Support if needed
if(class_exists('Subsite')) {
    CustomMenuHolder::add_extension('CustomMenuHolder_SubsiteExtension');
    CustomMenuAdmin::add_extension('SubsiteMenuExtension');
}
