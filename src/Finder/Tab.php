<?php
namespace Shopex\LubanAdmin\Finder;

class Tab{
	
	use Shared;

	public $label;
	public $filters = [];

	public $hidden = ['filters'];

	function addFilter($filter){
		$this->filters[] = $filter;
		return $this;
	}
}