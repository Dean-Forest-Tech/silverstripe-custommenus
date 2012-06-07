<?php
/**
 * CustomMenuOrderField field generates a hidden field that contains the
 * ordering of pages in the menu. It also generates a jquery order list that allows users to (what?)
 *
 */
class CustomMenuOrderField extends FormField {
	protected $maxLength;
	/**
	 * Returns an input field, class="text" and type="text" with an optional maxlength
	 */
	function __construct($name, $title = null, $value = "", $maxLength = null, $form = null){
		$this->maxLength = $maxLength;
		parent::__construct($name, $title, $value, $form);
	}

	function Field($properties = array()) {
            ////// Get values from the join, if available
	    if(is_object($this->form)) {
		$menu = $this->form->getRecord();


		if($menu->Order) {
                    $order = str_replace(' ', '', $menu->Order);
                    $order = explode(',', $order);
                }

		$output = "<ul id=\"{$this->id()}\">";

		if(isset($order) && is_array($order) && (count($order) > 0) && ($menu->Pages()->Count() > 0)) {
		    foreach($order as $item) {
                        if($menu->Pages()->find('ID',$item))
                            $output .= "<li>&raquo;&nbsp;&nbsp;{$menu->Pages()->find('ID',$item)->MenuTitle} (ID #: {$menu->Pages()->find('ID',$item)->ID})</li>";
		    }
		} else {
		    foreach($menu->Pages() as $item) {
			$output .= "<li>&raquo;&nbsp;&nbsp;{$item->MenuTitle} (ID #: {$item->ID})</li>";
		    }
		}

		$output .= "</ul>";

		return $output;
	    } else
		return "<span>Unable to find any pages linked to this menu</span>";
	}


	function InternallyLabelledField() {
		if(!$this->value) $this->value = $this->Title();
		return $this->Field();
	}
}

?>