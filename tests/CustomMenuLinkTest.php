<?php

namespace ilateral\SilverStripe\CustomMenus\Tests;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\Core\Config\Config;
use ilateral\SilverStripe\CustomMenus\Model\CustomMenuLink;
use ilateral\SilverStripe\CustomMenus\Tests\Model\TestMenuItemOne;
use ilateral\SilverStripe\CustomMenus\Tests\Model\TestMenuItemTwo;

class CustomMenuLinkTest extends SapphireTest
{
    protected static $fixture_file = "CustomMenuLinkTest.yml";

    /**
     * Setup test only objects
     *
     * @var array
     */
    protected static $extra_dataobjects = [
        TestMenuItemOne::class,
        TestMenuItemTwo::class,
    ];

    /**
     * Add some extra config on construction
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        Config::modify()->set(
            CustomMenuLink::class,
            'base_classes',
            [
                TestMenuItemOne::class => 'A simple title',
                TestMenuItemTwo::class => [
                    'Title' => 'A complex title',
                    'Label' => 'MenuTitle',
                    'SearchFields' => [
                        'Title',
                        'MenuTitle',
                        'URLSegment'
                    ]
                ]
            ]
        );
    }

    public function testGetClassesForDropdown()
    {
        $link = CustomMenuLink::singleton();
        $list = $link->getClassesForDropdown();

        $this->assertTrue(is_array($list));
        $this->assertCount(2, $list);
        $this->assertArrayHasKey(TestMenuItemOne::class, $list);
        $this->assertContains("A simple title", $list);
        $this->assertArrayHasKey(TestMenuItemTwo::class, $list);
        $this->assertContains("A complex title", $list);
    }

    public function testGetLabelField()
    {
        $link_one = $this->objFromFixture(CustomMenuLink::class, 'linkone');
        $link_two = $this->objFromFixture(CustomMenuLink::class, 'linktwo');

        $this->assertEquals('Title', $link_one->getLabelField());
        $this->assertEquals('MenuTitle', $link_two->getLabelField());
    }

    public function testGetSearchFields()
    {
        $link_one = $this->objFromFixture(CustomMenuLink::class, 'linkone');
        $link_two = $this->objFromFixture(CustomMenuLink::class, 'linktwo');

        $this->assertTrue(is_array($link_one->getSearchFields()));
        $this->assertTrue(is_array($link_two->getSearchFields()));
    }

    public function testGetTitle()
    {
        // Manually assign links to objects as this cannot be done through fixtures
        $link_one = $this->objFromFixture(CustomMenuLink::class, 'linkone');
        $item_one = $this->objFromFixture(TestMenuItemOne::class, 'itemone');
        $item_one->write();
        $link_one->ObjectID = $item_one->ID;
        $link_one->write();

        $link_two = $this->objFromFixture(CustomMenuLink::class, 'linktwo');
        $item_two = $this->objFromFixture(TestMenuItemTwo::class, 'itemtwo');
        $item_two->write();
        $link_two->ObjectID = $item_two->ID;
        $link_two->write();

        $this->assertEquals('Item One Test', $link_one->getTitle());
        $this->assertEquals('Item Two Menu', $link_two->getTitle());
    }

    public function testGetType()
    {
        $link_one = $this->objFromFixture(CustomMenuLink::class, 'linkone');
        $link_two = $this->objFromFixture(CustomMenuLink::class, 'linktwo');

        $this->assertEquals('A simple title', $link_one->getType());
        $this->assertEquals('A complex title', $link_two->getType());
    }
}
