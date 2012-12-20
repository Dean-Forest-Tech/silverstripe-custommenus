<?php
class CustomMenu extends Extension {
	public function CustomMenu($menu = null) {
		if($menu) {
		    // Ensure argument is safe for database
		    $menu = Convert::raw2sql($menu);
		    
		    $filter = array(
		        'Slug' => $menu
		    );
		    
		    // Add subsites support
		    if(class_exists('Subsite') && $this->owner->CurrentSubsite())
		        $filter['SubsiteID'] = $this->owner->CurrentSubsite()->ID;
		    
			if($menu = CustomMenuHolder::get()->filter($filter)->first()) {
			
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
