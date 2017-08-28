<?php
namespace Shopex\LubanAdmin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Admin{

	public function routes(){
		Route::get('admin', 'Admin\\AdminController@index');
		Route::get('admin/give-role-permissions', 'Admin\\AdminController@getGiveRolePermissions');
		Route::post('admin/give-role-permissions', 'Admin\\AdminController@postGiveRolePermissions');
		Route::resource('admin/roles', 'Admin\\RolesController');
		Route::resource('admin/permissions', 'Admin\\PermissionsController');
		Route::resource('admin/users', 'Admin\\UsersController');
		Route::get('admin/generator', ['uses' => '\Shopex\LubanAdmin\Controllers\ProcessController@getGenerator']);
		Route::post('admin/generator', ['uses' => '\Shopex\LubanAdmin\Controllers\ProcessController@postGenerator']);
	}

	public function api_routes(){

	}

}