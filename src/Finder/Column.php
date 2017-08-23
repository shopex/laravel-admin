<?php
namespace Shopex\LubanAdmin\Finder;

class Column{

	use Shared;

	public $key;
	public $label;
	public $sortAble;
	public $default;
	public $size = 2;
	public $className;
	public $modifier;
	public $html = false;

	public $hidden = ['modifier'];

	function id(){
		$this->_finder->setId($this->$key);
		return $this;
	}
}