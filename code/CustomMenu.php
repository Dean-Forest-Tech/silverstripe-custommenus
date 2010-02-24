<?php
class CustomMenu extends Extension {
	public function CustomMenu($menu = null) {
		$menu = Convert::raw2sql($menu);
		if($menu) {
			if(DataObject::get_one('CustomMenuHolder',"Slug = '$menu'")) {
				$result = DataObject::get_one('CustomMenuHolder',"Slug = '$menu'")->Pages();
				$pages = new DataObjectSet();
				
				foreach($result as $item) {
					$pages->push($item);
				}
				
				return $pages;
			}
		} else 
			return false;
	}
}