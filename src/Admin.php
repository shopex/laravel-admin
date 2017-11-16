<?php
namespace Shopex\LubanAdmin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Shopex\LubanAdmin\Finder\Input;
use Collective\Html\FormFacade as Form;

class Admin{

	private $objectInputs = [];

	public function routes(){
		Route::group(['middleware' => 'auth'], function () {
	        Route::get('admin/desktop', ['as'=>'admin.desktop', function () {
	        	            return view('admin::desktop');
	        	        }]);
			Route::any('admin/component/objectinput/{type}', ['uses' => '\Shopex\LubanAdmin\Controllers\ComponentController@objectInput']);
		});
	}

	public function super_routes(){
		Route::group(['middleware' => 'auth'], function () {
			Route::resource('admin/roles', 'Admin\\RolesController');
			Route::resource('admin/users', 'Admin\\UsersController');
			Route::any('admin/users/roles/data', ['uses'=>'Admin\\UsersController@getUserRolesModel','as'=>'users.roles.data'] );
			Route::Group(['prefix'=>'admin/generator'],function(){
				Route::get('/',[
						'uses' => '\Shopex\LubanAdmin\Controllers\ProcessController@index',
						'as'=>'admin.generator.index'
					]);
				Route::post('/',[
					'uses' => '\Shopex\LubanAdmin\Controllers\ProcessController@postGenerator',
					'as'=>'admin.generator.post'
				]);
				Route::get('/new',[
						'uses' => '\Shopex\LubanAdmin\Controllers\ProcessController@getGenerator',
						'as'=>'admin.generator.index',
					]);
				Route::get('{id}/edit',[
						'uses' => '\Shopex\LubanAdmin\Controllers\ProcessController@edit',
						'as'=>'admin.generator.edit'
					]);
			});
		});
	}

	public function api_routes(){

	}

	public function getObjectInput($name){
		return $this->objectInputs[$name];
	}
	/*
		根据模型名称获取在typeobject中的名称
	 */
	public function getObjectInputName($class_name){
		foreach ($this->objectInputs as $name => $input) {
			if ($input->_model == $class_name) {
				return $name;
			}
		}
		return '';
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