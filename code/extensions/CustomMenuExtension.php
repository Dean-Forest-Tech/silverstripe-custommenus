<?php
class CustomMenuExtension extends DataExtension {
	public static $belongs_many_many = array(
		'CustomMenus'	=> 'CustomMenuHolder'
	);
}
