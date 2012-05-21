<?php
class CustomMenuExtension extends DataExtension {
	function extraStatics($class = null, $extension = null) {
		return array(
			'belongs_many_many' => array(
				'CustomMenus'	=> 'CustomMenuHolder'
			)
		);
	}
}