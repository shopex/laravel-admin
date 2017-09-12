<?php
namespace Shopex\LubanAdmin\Console;

use Illuminate\Support\Facades\Artisan;
use Response;

class Command {

	public function register(){
		Artisan::command('admin:publish {file?}', function () {
			$commandArg = ['--provider' => 'Shopex\LubanAdmin\Providers\LubanAdminProvider', 
				'--tag' => 'resources',
				'--force' => true];
			$this->call('vendor:publish', $commandArg);
		})->describe('Create a new adminui form');
	}

}