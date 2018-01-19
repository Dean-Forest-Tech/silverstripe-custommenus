<?php

namespace ilateral\SilverStripe\CustomMenus\Extensions;

use SilverStripe\ORM\DataExtension;
use ilateral\SilverStripe\CustomMenus\Model\CustomMenuHolder;

class CustomMenuExtension extends DataExtension
{

    private static $belongs_many_many = [
        'CustomMenus'   => CustomMenuHolder::class
    ];

}
