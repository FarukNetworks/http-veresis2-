<?php

namespace App\Helpers;

 

class SidebarHelper 

{

	static function getItems() {

		$items = [

			array('page' =>'narocila','title' => 'Naročila', 'permissions' => 'orders'),

			array('page' =>'odpreme','title' => 'Odpreme', 'permissions' => 'orders'),

			array('page' =>'skatle','title' => 'Škatle', 'permissions' => 'orders'),

			array('permissions' => 'all','dropdown' =>'test', 'dropdownidentity' => 1,'title' => 'Uničene tablice', 

					'childs' => array(

						array('dropdownidentity' => 1, 'dropdownItem' =>'unicene-tablice','dropdownItemTitle' => 'Iskanje uničenih tablic'),

						array('dropdownidentity' => 1, 'dropdownItem' =>'ponarejene-tablice','dropdownItemTitle' => 'Iskanje ponaredkov')

					) 

			),

			array('page' =>'paketi','title' => 'Paketi', 'permissions' => 'orders'),

			array('link' =>'http://kig.si/trgovina/','title' => 'Spletna trgovina', 'permissions' => 'all')/*,

			array('link' =>'http://reklamne-tablice.si/konfigurator/','title' => 'Reklamne tablice', 'permissions' => 'all')*/

		];

		return $items;  

	}



	static function getSidebarItems($permissions) {

		if ($permissions == "orders") {

			return self::getItems();

		} else if ($permissions == "all") {

			$items = self::getItems();

			return array_filter($items, function($v){return $v['permissions'] == "all";});

		}

	} 

}