<?php
namespace Shopex\LubanAdmin\Permission;

use Shopex\LubanAdmin\Permission\Configs;
use Auth;
class Data
{
	//获取当前用户所拥有的角色是否有数据权限限制
	//
	//
	private $model;
	private $dataPermission;
	public function __construct($model)
	{
		$this->model = $model;
		$config = new Configs();
		$this->dataPermission = $config->getPermission('data');
	}
	/**
	 * 验证数据权限
	 *
	 * @return void
	 * @author 
	 **/
	public function check()
	{

        //验证当前模型是否开启数据权限
        $this->permission = array_get($this->dataPermission,$this->model);
        if ($this->permission) {
			return $this->userCheck();        	
        }
        return [];
	}
	private function userCheck(){
		//循环当前用户所拥有的角色，判断是否有指定数据的数据
		$user = Auth::user();
		$result = [];
		foreach ($user->roles as $role) {
			$datas = json_decode($role->datas,1);
			$role_data = array_get($datas,$this->model.'.row');
			if ($role_data) {
				$dataPsermission = array_only($this->permission['permissions'],$role_data);
				$res = $this->callPermission($dataPsermission,$role);
				if ($res) {
					$result[$role->id] = $res;
				}
			}
		}
		return $result;
	}
	public function callPermission($permission,$role)
	{
		$result = [];
		if (!$permission) {
			return $result;
		}
		foreach ($permission as $key => $row) {
			$result[] = $this->{"parse".ucfirst($row['type'])}($row,$role);
		}
		return $result;

	}
	public function parseField($data,$role){
		return [$data['field']=>[$role->pivot->user_id]] ;
	}
	public function parseModel($data,$role){
		
		if ($role->pivot->datas) {
    		$datas =  json_decode($role->pivot->datas,1);
    		$ids = array_get($datas,$this->model . '.' . $data['field']);
    		if ($ids) {
    			return [$data['field']=>$ids];
    		}
    	}
    	return [];
	}
	public function parseAll($data,$role){
		return [];
	}
	public function parseFunc($data,$role){
		$class_name  = array_get($data['func'],0);
		$func   = array_get($data['func'],1);
		$params = array_get($data['func'],2,[]);

		if (class_exists($class_name)) {
			$obj = new $class_name;
			if (is_callable([$obj,$func])) {
				if ($params && !is_array($params)) {
					$params = [$params];
				}
				$ids = call_user_func_array([$obj,$func], $params);
				if ($ids) {
	    			return [$data['field']=>$ids];
	    		}
	    		return [];
			}else{
				throw new \Exception("calss ".$class_name. "func ".$func."not callable", 1);
			}
		}else{
			throw new \Exception($class_name."not found", 1);
			
		}
		return [];
	}
}



