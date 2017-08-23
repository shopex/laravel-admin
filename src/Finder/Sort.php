<?php
namespace Shopex\LubanAdmin\Finder;

class Sort{
	
	use Shared;

	public $orderBy = [];
	public $label;

	public $hidden = ['orderBy'];
}