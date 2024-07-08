<?php

namespace DFT\SilverStripe\CustomMenus\Extensions;

use SilverStripe\ORM\DataExtension;
use DFT\SilverStripe\CustomMenus\Model\CustomMenuHolder;

class CustomMenuExtension extends DataExtension
{

    private static $belongs_many_many = [
        'CustomMenus'   => CustomMenuHolder::class
    ];
}
