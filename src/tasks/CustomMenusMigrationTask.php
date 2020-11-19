<?php

namespace ilateral\SilverStripe\CustomMenus\Tasks;

use SilverStripe\ORM\DB;
use SilverStripe\Control\Director;
use SilverStripe\Dev\MigrationTask;
use SilverStripe\ORM\DatabaseAdmin;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\SiteConfig\SiteConfig;
use ilateral\SilverStripe\CustomMenus\Model\CustomMenuLink;
use ilateral\SilverStripe\CustomMenus\Model\CustomMenuHolder;

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
        $config = SiteConfig::current_site_config();
        $menus = CustomMenuHolder::get();
        $i = 0;
        
        foreach ($menus as $menu) {
            $config->Menus()->add($menu);
            DB::alteration_message('Re-Linked Custom Menu to SiteConfig', 'changed');
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
        $config->write();

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
            if (Director::is_cli()) {
                echo $text . "\n";
            } else {
                echo $text . "<br/>";
            }
        }
    }
}
