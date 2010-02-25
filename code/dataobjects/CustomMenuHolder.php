<?php
class CustomMenuHolder extends DataObject {
	static $db = array(
		'Title'	=> 'Text',
		'Slug'	=> 'Text',
	);

	static $many_many = array(
		'Pages'	=> 'SiteTree'
	);
	
	/**
	 * Create default menu items - Currently disabled due to issues with
	 * 2.4 beta 1.
	 * 
	 * @see sapphire/core/model/DataObject#requireDefaultRecords()
	 */
	/*
	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		
		// Main Menu
		if($this->class == 'CustomMenuHolder') {
		    if(!DataObject::get_one($this->class)) {
			    $menu = new CustomMenuHolder();
			    $menu->Title = 'Main Menu';
			    $menu->Slug = "main-menu";
			    $menu->write();
			    $menu->flushCache();
			    if(method_exists('Database','alteration_message'))
				    Database::alteration_message("Main menu created","created");
			    else
				    DB::alteration_message("Main menu created","created");

			    $menu = new CustomMenuHolder();
			    $menu->Title = 'Header Menu';
			    $menu->Slug = "header-menu";
			    $menu->write();
			    $menu->flushCache();
			    if(method_exists('Database','alteration_message'))
				    Database::alteration_message("Header menu created","created");
			    else
				    DB::alteration_message("Header menu created","created");

			    $menu = new CustomMenuHolder();
			    $menu->Title = 'Footer Menu';
			    $menu->Slug = "footer-menu";
			    $menu->write();
			    $menu->flushCache();
			    if(method_exists('Database','alteration_message'))
				    Database::alteration_message("Footer menu created","created");
			    else
				    DB::alteration_message("Footer menu created","created");
		    }
		}
	}*/
}