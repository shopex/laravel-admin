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
			Route::resource('admin/roles', 'Admin\\RolesController');
			Route::resource('admin/users', 'Admin\\UsersController');
	        Route::get('admin/desktop', ['as'=>'desktop', function () {
	        	            return view('admin::desktop');
	        	        }]);
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
			Route::any('admin/component/objectinput/{type}', ['uses' => '\Shopex\LubanAdmin\Controllers\ComponentController@objectInput']);
		});
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