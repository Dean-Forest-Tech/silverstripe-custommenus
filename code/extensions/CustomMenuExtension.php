<?php
class CustomMenuExtension extends DataExtension {
	private static $belongs_many_many => array(
		'CustomMenus'	=> 'CustomMenuHolder'
	);
}
