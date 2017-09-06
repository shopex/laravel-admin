<?php

namespace Shopex\LubanAdmin\Controllers;

use Shopex\LubanAdmin\Facades\Admin;
use Shopex\LubanAdmin\Finder\Search;
use App\Http\Controllers\Controller;
use Artisan;
use File;
use Illuminate\Http\Request;
use Response;
use Session;
use View;


class ComponentController extends Controller
{
	function objectInput($type){
		$input = Admin::getObjectInput($type)->setPageNum(10);
		return $input->view();
	}
}
