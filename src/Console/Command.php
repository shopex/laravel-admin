<?php
namespace Shopex\LubanAdmin\Console;

use Illuminate\Support\Facades\Artisan;

class Command {

	static function register(){
		Artisan::command('make:form', function () {
		    $this->comment(Inspiring::quote());
		})->describe('Create a new adminui form');

		Artisan::command('make:curd', function () {
		    $this->comment(Inspiring::quote());
		})->describe('Create a new adminui curd');
	}

}