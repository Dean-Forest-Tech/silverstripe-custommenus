<?php
class CustomMenuDecorator extends DataObjectDecorator {
	function extraStatics() {
		return array(
			'belongs_many_many' => array(
				'CustomMenus'	=> 'CustomMenuHolder'
			)
		);
	}
}