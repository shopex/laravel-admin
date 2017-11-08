<?php
namespace Shopex\LubanAdmin\Permission;
use Config;

class Configs
{
	
	public function getRouterPermission($type = null)
	{
		if ($type) {
			$routerType = Config::get('permissions.router.'.$type);
			if (is_array($routerType) && $routerType) {
				return $$routerType;
			}
		}
		$type = Config::get('permissions.defaults.router');
		$routerType = Config::get('permissions.router.'.$type);
		if (is_array($routerType) && $routerType) {
			return $routerType;
		}else{
			return [];
		}

	}
} // END class Config