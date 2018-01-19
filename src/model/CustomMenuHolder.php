<?php

namespace ilateral\SilverStripe\CustomMenus\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\PermissionProvider;
use SilverStripe\Security\Permission;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\ORM\Connect\Database;
use SilverStripe\ORM\DB;
use SilverStripe\Core\Convert;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use ilateral\SilverStripe\CustomMenus\Tasks\CustomMenusMigrationTask;
use CustomMenuLink;

/**
 * A container of menu links that can then be rendered into a template
 * 
 * @author Mo <morven@ilateral.co.uk>
 * @package CustomMenus
 */
class CustomMenuHolder extends DataObject implements PermissionProvider
{
    private static $db = [
        'Title'	=> 'Varchar',
        'Slug'	=> 'Varchar',
        'Order'	=> 'Text',
    ];

    private static $has_one = [
        'Site' => SiteConfig::class
    ];

    private static $has_many = [
        "Links" => CustomMenuLink::class
    ];

    private static $many_many = [
        'Pages'	=> SiteTree::class
    ];
    
    private static $summary_fields = [
        'Title' => 'Title',
        'Slug'  => 'Slug',
        "Links.Count" => "# Links"
    ];
	
	private static $searchable_fields = [
		'Title'
    ];

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            if (!$this->canEdit()) {
                return;
            }
        
            $fields->removeByName('Pages');
            $fields->removeByName('Order');

            $slug_field = $fields->dataFieldByName("Slug");
            $slug_field->setDescription(_t(
                "CustomMenus.SlugDescription",
                "Call this in your templates"
            ));

            $links_field = $fields->dataFieldByName("Links");

            if ($links_field) {
                $links_field
                    ->getConfig()
                    ->addComponent(new GridFieldOrderableRows('SortOrder'));
            }
        });

        return parent::getCMSFields();
    }

    /**
    * Create default menu items if no items exist
    *
    * @see sapphire/core/model/DataObject#requireDefaultRecords()
    */

    function requireDefaultRecords()
    {
        parent::requireDefaultRecords();

        // Main Menu
        if ($this->class == 'CustomMenuHolder') {
            if(!DataObject::get_one($this->class)) {
                $menu = new CustomMenuHolder();
                $menu->Title = 'Main Menu';
                $menu->Slug = "main-menu";
                $menu->write();
                $menu->flushCache();
                if (method_exists('Database','alteration_message')) {
                    Database::alteration_message("Main menu created","created");
                } else {
                    DB::alteration_message("Main menu created","created");
                }

                $menu = new CustomMenuHolder();
                $menu->Title = 'Header Menu';
                $menu->Slug = "header-menu";
                $menu->write();
                $menu->flushCache();
                if (method_exists('Database','alteration_message')) {
                    Database::alteration_message("Header menu created","created");
                } else {
                    DB::alteration_message("Header menu created","created");
                }

                $menu = new CustomMenuHolder();
                $menu->Title = 'Footer Menu';
                $menu->Slug = "footer-menu";
                $menu->write();
                $menu->flushCache();
                if (method_exists('Database','alteration_message')) {
                    Database::alteration_message("Footer menu created","created");
                } else {
                    DB::alteration_message("Footer menu created","created");
                }
            }
        }

        // Run migration task (if needed)
        $migrate = CustomMenusMigrationTask::config()->run_during_dev_build;

        if ($migrate && class_exists(SiteTree::class)) {
            $task = new CustomMenusMigrationTask();
            $task->up();
        }
    }

    /**
     * Setup permissions
     *
     * @return void
     */
    public function providePermissions()
    {
        return [
            'MENU_VIEWALL' => [
                'name' => 'View all menus',
                'help' => 'Allow viewing of all menus in the "Menus" section',
                'category' => 'Menus',
                'sort' => 100
            ],
            'MENU_CREATE' => [
                'name' => 'Create menus',
                'help' => 'Allow creation of menus in the "Menus" section',
                'category' => 'Menus',
                'sort' => 110
            ],
            'MENU_DELETE' => [
                'name' => 'Delete menus',
                'help' => 'Allow deleting of menus in the "Menus" section',
                'category' => 'Menus',
                'sort' => 120
            ],
            'MENU_EDIT' => [
                'name' => 'Edit menus',
                'help' => 'Allow editing of menus in the "Menu" section',
                'category' => 'Menus',
                'sort' => 130
            ],
        ];
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        
        // Ensure the slug is URL safe
        $this->Slug = ($this->Slug) ? Convert::raw2url($this->Slug) : Convert::raw2url($this->Title);
    }
        
    /**
     * Clean up after delete
     */
    public function onBeforeDelete()
    {
        parent::onBeforeDelete();
        
        foreach ($this->Links() as $link) {
            $link->delete();
        }
    }
	
    public function canView($member = null)
    {
    	if (Permission::check(['ADMIN','MENU_VIEWALL'])) {
    		return true;
        } else {
            return false;
        }
    }
    
    public function canCreate($member = null) {
    	if (Permission::check(['ADMIN','MENU_CREATE'])) {
            return true;
        } else { 
            return false;
        }
    }
    
    public function canDelete($member = null) {
    	if (Permission::check(['ADMIN','MENU_DELETE'])) {
    		return true;
        } else { 
            return false;
        }
    }
    
    public function canEdit($member = null) {
    	if (Permission::check(['ADMIN','MENU_EDIT'])) {
			return true;
        } else { 
            return false;
        }
    }
}
