<?php

namespace App\Helpers;

 

class SidebarHelper 

{

	static function getItems() {

		$items = [
			array('page' =>'narocila','title' => 'Naročila', 'permissions' => 'orders', 'icon' => ' glyphicon-shopping-cart'),
			array('page' =>'odpreme','title' => 'Odpreme', 'permissions' => 'orders', 'icon' => ' glyphicon-send'),
			array('page' =>'skatle','title' => 'Škatle', 'permissions' => 'orders', 'icon' => ' glyphicon-gift'),
			array(
				'permissions' => 'all',
				'dropdown' =>'test',
				'dropdownidentity' => 1,
				'title' => 'Uničene tablice',
				'icon' => ' glyphicon-trash',
				'childs' => array(
					array('dropdownidentity' => 1, 'icon' => ' glyphicon-trash', 'dropdownItem' =>'unicene-tablice','dropdownItemTitle' => 'Iskanje uničenih tablic'),
					array('dropdownidentity' => 1, 'icon' => ' glyphicon-search', 'dropdownItem' =>'ponarejene-tablice','dropdownItemTitle' => 'Iskanje ponaredkov')
				)
			),
			array('page' =>'paketi','title' => 'Paketi', 'permissions' => 'orders', 'icon' => ' glyphicon-briefcase')
			// array('link' =>'http://kig.si/trgovina/','title' => 'Spletna trgovina', 'permissions' => 'all', 'icon' => 'glyphicon-user')
		];

		return $items;  

	}


// 	case 'Naročila':
// 		$icon = 'glyphicon-shopping-cart';
// 		break;
// 	case 'Odpreme':
// 		$icon = 'glyphicon-send';
// 		break;
// 	case 'Škatle':
// 		$icon = 'glyphicon-inbox';
// 		break;
// 	case 'Uničene tablice':
// 		$icon = 'glyphicon-fire';
// 		break;
// 	case 'Paketi':
// 		$icon = 'glyphicon-gift';
// 		break;
// 	case 'Spletna trgovina':
// 		$icon = 'glyphicon-globe';
// 		break;
// 	default:
// 		$icon = 'glyphicon-user';
// 		break;
// }


	static function getSidebarItems($permissions) {

		if ($permissions == "orders") {

			return self::getItems();

		} else if ($permissions == "all") {

			$items = self::getItems();

			return array_filter($items, function($v){return $v['permissions'] == "all";});

		}

	} 

}