<?php
// Add custom menu controller to ContentController
Object::add_extension('ContentController', 'CustomMenu');
Object::add_extension('SiteTree','CustomMenuDecorator');