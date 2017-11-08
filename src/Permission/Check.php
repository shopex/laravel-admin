<?php
namespace Shopex\LubanAdmin\Permission;

use Shopex\LubanAdmin\Permission\Configs;

class Check
{
	private $perKv = [];
	public function prcessPermission(){
		$config = new Configs();
		$permissions = $config->getRouterPermission();
		$this->perKv = [];
		foreach ($permissions as $group) {
		 	foreach ($group['permissions'] as $code => $info) {
		 		$this->perKv[$code] = $info['routes'];
		 	}
		}
	}
	public function hasPermission($curRouteName, $checkPermissions)
	{
		foreach ($checkPermissions as $hascode) {
			if (isset($this->perKv[$hascode]) && in_array($curRouteName,$this->perKv[$hascode])) {
				return true;
			}
		}
		return false;
	}
} // END class Config