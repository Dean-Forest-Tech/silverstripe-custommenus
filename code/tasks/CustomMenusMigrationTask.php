<?php

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
                    "BaseClass" => "SiteTree",
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
