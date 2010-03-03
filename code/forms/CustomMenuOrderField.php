<?php
/**
 * CustomMenuOrderField field generates a hidden field that contains the
 * ordering of pages in the menu. It also generates a jquery order list that allows users to
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

	function Field() {
		return $this->getOrderedPages($this->Value());
	}

	private function getOrderedPages($order = null) {
	    //// Get values from the join, if available
	    if(is_object($this->form)) {
		$menu = $this->form->getRecord();
		
		if($menu->Order)
			$order = explode(',', $menu->Order);

		$output = "<ul id=\"{$this->id()}\">";

		if(isset($order) && is_array($order) && count($order) > 0) {
		    foreach($order as $item) {
			$output .= "<li>&raquo;&nbsp;&nbsp;{$menu->Pages()->find('ID',$item)->Title} ({$menu->Pages()->find('ID',$item)->ID})</li>";
		    }
		} else {
		    foreach($menu->Pages() as $item) {
			$output .= "<li>&raquo;&nbsp;&nbsp;{$item->Title} ({$item->ID})</li>";
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