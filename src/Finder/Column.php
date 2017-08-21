<?php
namespace Shopex\LubanAdmin\Finder;

class Column{

	public $key;
	public $label;
	public $sortAble;
	public $defaultInList;
	public $size=2;
	public $className;
	public $modifier;
	private $_finder;

	function __construct($finder){
		$this->_finder = $finder;
	}

	function width($size){
		$this->size = $size;
		return $this;
	}

	function class($class){
		$this->className = $class;
		return $this;
	}

	function setModifier($func){
		$this->modifier = $func;
		return $this;
	}

	function sort($able){
		$this->sortAble = $able;
		return $this;
	}

	function default($show){
		$this->defaultInList = $show;
		return $this;
	}
	
	function __call($method, $args){
		if(method_exists($this, $method)){
			return call_user_func_array([$this, $method], $args);
		}else{
			return call_user_func_array([$this->_finder, $method], $args);
		}
	}
}