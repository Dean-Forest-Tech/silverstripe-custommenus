<?php

// Enable Subsite Support if needed
if(class_exists('Subsite')) {
    CustomMenuHolder::add_extension('CustomMenuHolder_SubsiteExtension');
    CustomMenuAdmin::add_extension('SubsiteMenuExtension');
}
