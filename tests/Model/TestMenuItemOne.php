<?php

namespace DFT\SilverStripe\CustomMenus\Tests\Model;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;

class TestMenuItemOne extends DataObject implements TestOnly
{
    private static $table_name = 'CustomMenus_TestMenuItemOne';

    private static $db = [
        'Title' => 'Varchar',
        'MenuTitle' => 'Varchar',
        'AnotherID' => 'Varchar'
    ];
}
