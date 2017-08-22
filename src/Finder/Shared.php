<?php
namespace Shopex\LubanAdmin\Finder;

trait Shared{

	private $_finder;

	function __construct($finder){
		$this->_finder = $finder;
	}
	
	function __call($method, $args){
		if(method_exists($this, $method)){
			return call_user_func_array([$this, $method], $args);
		}elseif(property_exists($this, $method)){
			$this->$method = $args[0];
			return $this;
		}else{
			return call_user_func_array([$this->_finder, $method], $args);
		}
	}
}