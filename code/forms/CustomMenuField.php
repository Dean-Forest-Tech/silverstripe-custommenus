<?php
/**
 * Duplicate of CheckboxSetField, will be customised in time
 *
 */
class CustomMenuField extends OptionsetField {

	protected $disabled = false;
	protected $defaultItems = array();

	function Field() {
		Requirements::css('custommenus/css/CheckboxSetField.css');

		$source = DataObject::get('SiteTree', "ParentID = 0");
		$values = $this->value;
		$output = '';
		$items = array();

		// Get values from the join, if available
		if(is_object($this->form)) {
			$record = $this->form->getRecord();
			if(!$values && $record && $record->hasMethod($this->name)) {
				$funcName = $this->name;
				$join = $record->$funcName();
				if($join) {
					foreach($join as $joinItem) {
						$values[] = $joinItem->ID;
					}
				}
			}
		}

		// Sometimes we pass a singluar default value thats ! an array && !DataObjectSet
		if(is_a($values, 'DataObjectSet') || is_array($values))
			$items = $values;

		if($source == null) {
			$source = array();
			$output = "<span>No options available</span>";
		}

		if($source) $output .= $this->makeList($source, $items, 0);

		return $output;
	}

        private function makeList($pages, $items = null, $level = null) {
            if(isset($level))
                $listOptions = "id=\"{$this->id()}\" class=\"optionset checkboxsetfield{$this->extraClass()}\"";
            else
                $listOptions = "";

            $odd = 0;

            $output = "<ul $listOptions>";

            if(count($pages)) {
                foreach($pages as $page) {
                    if(!($page instanceof ErrorPage)) {
			$key = $page->ID;
			$value = $page->MenuTitle;

                        $odd = ($odd + 1) % 2;
			$extraClass = $odd ? 'odd' : 'even';
			$extraClass .= ' val' . str_replace(' ', '', $key);
			$itemID = $this->id() . '_' . ereg_replace('[^a-zA-Z0-9]+', '', $key);
			$checked = '';

			if(isset($items))
				$checked = (in_array($key, $items) || in_array($key, $this->defaultItems)) ? ' checked="checked"' : '';

			$output .= "<li class=\"$extraClass\"><input id=\"$itemID\" name=\"$this->name[$key]\" type=\"checkbox\" value=\"$key\"$checked class=\"checkbox\" /> <label for=\"$itemID\">$value</label></li>\n";

                        if($childPages = DataObject::get("Page", "ParentID = ".$page->ID))
                            $output .= $this->makeList($childPages, $items);
                        
                        $output .= '</li>';
                    }
                }
            } else
                $output .= '<li>Currently no pages in site</li>';

            $output .= '</ul>';
            
            return $output;

        }

	function setDisabled($val) {
		$this->disabled = $val;
	}

	/**
	 * Default selections, regardless of the {@link setValue()} settings.
	 * Note: Items marked as disabled through {@link setDisabledItems()} can still be
	 * selected by default through this method.
	 *
	 * @param Array $items Collection of array keys, as defined in the $source array
	 */
	function setDefaultItems($items) {
		$this->defaultItems = $items;
	}

	/**
	 * @return Array
	 */
	function getDefaultItems() {
		return $this->defaultItems;
	}

	/**
	 * Load a value into this CheckboxSetField
	 */
	function setValue($value, $obj = null) {
		// If we're not passed a value directly, we can look for it in a relation method on the object passed as a second arg
		if(!$value && $obj && $obj instanceof DataObject && $obj->hasMethod($this->name)) {
			$funcName = $this->name;
			$selected = $obj->$funcName();
			$value = $selected->toDropdownMap('ID', 'ID');
		}

		parent::setValue($value, $obj);
	}

	/**
	 * Save the current value of this CheckboxSetField into a DataObject.
	 * If the field it is saving to is a has_many or many_many relationship,
	 * it is saved by setByIDList(), otherwise it creates a comma separated
	 * list for a standard DB text/varchar field.
	 *
	 * @param DataObject $record The record to save into
	 */
	function saveInto(DataObject $record) {
		$fieldname = $this->name ;
		if($fieldname && $record && ($record->has_many($fieldname) || $record->many_many($fieldname))) {
			$idList = array();
			if($this->value) foreach($this->value as $id => $bool) {
			   if($bool) {
					$idList[] = $id;
				}
			}
			$record->$fieldname()->setByIDList($idList);
		} elseif($fieldname && $record) {
			if($this->value) {
				$this->value = str_replace(',', '{comma}', $this->value);
				$record->$fieldname = implode(",", $this->value);
			} else {
				$record->$fieldname = '';
			}
		}
	}

	/**
	 * Return the CheckboxSetField value as an array
	 * selected item keys.
	 *
	 * @return string
	 */
	function dataValue() {
		if($this->value && is_array($this->value)) {
			$filtered = array();
			foreach($this->value as $item) {
				if($item) {
					$filtered[] = str_replace(",", "{comma}", $item);
				}
			}

			return implode(',', $filtered);
		}

		return '';
	}

	function performDisabledTransformation() {
		$clone = clone $this;
		$clone->setDisabled(true);

		return $clone;
	}

	/**
	 * Transforms the source data for this CheckboxSetField
	 * into a comma separated list of values.
	 *
	 * @return ReadonlyField
	 */
	function performReadonlyTransformation() {
		$values = '';
		$data = array();

		$items = $this->value;
		if($this->source) {
			foreach($this->source as $source) {
				if(is_object($source)) {
					$sourceTitles[$source->ID] = $source->Title;
				}
			}
		}

		if($items) {
			// Items is a DO Set
			if(is_a($items, 'DataObjectSet')) {
				foreach($items as $item) {
					$data[] = $item->Title;
				}
				if($data) $values = implode(', ', $data);

			// Items is an array or single piece of string (including comma seperated string)
			} else {
				if(!is_array($items)) {
					$items = preg_split('/ *, */', trim($items));
				}

				foreach($items as $item) {
					if(is_array($item)) {
						$data[] = $item['Title'];
					} elseif(is_array($this->source) && !empty($this->source[$item])) {
						$data[] = $this->source[$item];
					} elseif(is_a($this->source, 'DataObjectSet')) {
						$data[] = $sourceTitles[$item];
					} else {
						$data[] = $item;
					}
				}

				$values = implode(', ', $data);
			}
		}

		$title = ($this->title) ? $this->title : '';

		$field = new ReadonlyField($this->name, $title, $values);
		$field->setForm($this->form);

		return $field;
	}

	function ExtraOptions() {
		return FormField::ExtraOptions();
	}

}
?>