<?php
namespace Shopex\LubanAdmin\Permission;
use Config;

class Configs
{
	public function getPermissionArray($type){
		$dataType = Config::get('permissions.'.$type);
		if (is_array($dataType) && $dataType) {
			return $dataType;
		}else{
			return [];
		}
	}
	/**
	 * 根据权限类型和分组获取权限
	 * @param $type required 权限类型，router data
	 * @param $permission_type required 权限分组，不填默认去default去获取
	 * @return array
	 **/
	public function getPermission($type,$permission_type=null)
	{
		if (!$permission_type) {
			$permission_type = Config::get('permissions.defaults.'.$type);
		}
		return $this->getPermissionArray($type.'.'.$permission_type);
	}
	/**
	 * 获取全部权限
	 * 
	 * @return void
	 * @author 
	 **/
	public function getAllpermission($router=null,$data=null)
	{
		$routerPers = $this->getPermission('router',$router);
		$dataPers = $this->getPermission('data',$data);
		foreach ($routerPers as &$routerPer) {
			if (isset($routerPer['models'])) {
				foreach ($routerPer['models'] as $key => $model) {
					if ($dataPers[$model]) {
						$routerPer['datas'][$model] = $dataPers[$model];
					}
				}
			}
		}
		return $routerPers;
	}
}