<?php

namespace ilateral\SilverStripe\CustomMenus\Tasks;

use SilverStripe\Dev\MigrationTask;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\ORM\DatabaseAdmin;
use SilverStripe\ORM\DB;
use ilateral\SilverStripe\CustomMenus\Model\CustomMenuHolder;
use ilateral\SilverStripe\CustomMenus\Model\CustomMenuLink;

class CustomMenusMigrationTask extends MigrationTask
{
	protected $title = "Migration of CustomMenu assotiated pages";
    
    protected $description = "Migrate pages associated with a CustomMenu holder to the new CustomMenuHolderLink";

    /**
     * Should this task be invoked automatically via dev/build?
     *
     * @config
     *
     * @var bool
     */
    private static $run_during_dev_build = true;

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        
        $menus = CustomMenuHolder::get();
        $i = 0;
        
        foreach ($menus as $menu) {
            foreach ($menu->Pages() as $page) {
                $link = CustomMenuLink::create([
                    "BaseClass" => SiteTree::class,
                    "ObjectID" => $page->ID,
                    "MenuID" => $menu->ID
                ]);
                    
                $link->write();
                $menu->Pages()->remove($page);
                
                $i++;
            }
        }

        $this->log(sprintf(
            'Migrated %s menu page links.',
            $i
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->log('CustomMenusMigrationTask::down() not implemented');
    }

    /**
     * @param string $text
     */
    protected function log($text)
    {
        if (Controller::curr() instanceof DatabaseAdmin) {
            DB::alteration_message($text, 'obsolete');
        } else {
            if(Director::is_cli()) {
                echo $text . "\n";
            } else {
                echo $text . "<br/>";
            }
        }
    }
}
