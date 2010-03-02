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
		$output = '';

		$attributes = array(
			'type' => 'text',
			'class' => 'text' . ($this->extraClass() ? $this->extraClass() : ''),
			'id' => $this->id(),
			'name' => $this->Name(),
			'value' => $this->Value(),
			'tabindex' => $this->getTabIndex(),
			'maxlength' => ($this->maxLength) ? $this->maxLength : null,
			'size' => ($this->maxLength) ? min( $this->maxLength, 30 ) : null
		);

		if($this->disabled) $attributes['disabled'] = 'disabled';

		$output .= $this->createTag('input', $attributes);

		return $output;
	}

	function InternallyLabelledField() {
		if(!$this->value) $this->value = $this->Title();
		return $this->Field();
	}
}

?>