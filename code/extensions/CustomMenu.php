<?php
class CustomMenu extends Extension {
	public function CustomMenu($menu = null) {
		$menu = Convert::raw2sql($menu);
		if($menu) {
			if(DataObject::get_one('CustomMenuHolder',"Slug = '$menu'")) {
				$menu = DataObject::get_one('CustomMenuHolder',"Slug = '$menu'");
				if($menu->Order)
				    $order = explode(',', $menu->Order);

				$pages = new ArrayList();

				if(isset($order) && is_array($order) && count($order) > 0) {
				    foreach($order as $item) {
					$pages->push($menu->Pages()->find('ID',$item));
				    }
				} else {
				    foreach($menu->Pages() as $item) {
					$pages->push($item);
				    }
				}

				if($pages->exists())
				    return $pages;
			}
		} else 
			return false;
	}
}