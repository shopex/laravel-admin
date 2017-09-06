<?php
namespace Shopex\LubanAdmin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Shopex\LubanAdmin\Finder\Input;
use Collective\Html\FormFacade as Form;

class Admin{

	private $objectInputs = [];

	public function routes(){
		Route::get('admin', 'Admin\\AdminController@index');
		Route::get('admin/give-role-permissions', 'Admin\\AdminController@getGiveRolePermissions');
		Route::post('admin/give-role-permissions', 'Admin\\AdminController@postGiveRolePermissions');
		Route::resource('admin/roles', 'Admin\\RolesController');
		Route::resource('admin/permissions', 'Admin\\PermissionsController');
		Route::resource('admin/users', 'Admin\\UsersController');
		Route::get('admin/generator', ['uses' => '\Shopex\LubanAdmin\Controllers\ProcessController@getGenerator']);
		Route::post('admin/generator', ['uses' => '\Shopex\LubanAdmin\Controllers\ProcessController@postGenerator']);
		Route::any('admin/component/objectinput/{type}', ['uses' => '\Shopex\LubanAdmin\Controllers\ComponentController@objectInput']);
	}

	public function api_routes(){

	}

	public function getObjectInput($name){
		return $this->objectInputs[$name];
	}

	public function RegisterObjectInput($name, $model){
		$input = new Input;
		$input->setModel($model)->setType($name);
		$this->objectInputs[$name] = &$input;

		Form::macro('finder_'.$name, [$input, 'html']);
		return $input;
	}

	public function loading(){
		return <<<EOF
<div class="loading">
  <div class="bounce1"></div>
  <div class="bounce2"></div>
  <div class="bounce3"></div>
</div>
EOF;
	}

}