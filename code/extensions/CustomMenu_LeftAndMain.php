<?php

class CustomMenu_LeftAndMain extends LeftAndMainExtension {
	public function init() {
		parent::init();
		
		Requirements::css('custommenus/css/admin.css');
	}
}
