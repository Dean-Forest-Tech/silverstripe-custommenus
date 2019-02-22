<?php

namespace ilateral\SilverStripe\CustomMenus\Tests\Model;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;

class TestMenuItemTwo extends DataObject implements TestOnly
{
    private static $table_name = 'CustomMenus_TestMenuItemTwo';

    private static $db = [
        'Title' => 'Varchar',
        'MenuTitle' => 'Varchar',
        'URLSegment' => 'Varchar'
    ];
}
