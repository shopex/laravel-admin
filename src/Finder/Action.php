<?php
namespace Shopex\LubanAdmin\Finder;

class Action{
	
	use Shared;

	public $label;
	public $handle;
	public $url;
	public $target;
	public $confirm;
	public $hidden = ['handle'];

	function newWindow(){
		$this->target = '_blank';
		return $this;
	}

	function modal(){
		$this->target = '#modal-normal';
		return $this;
	}

	function modalSmall(){
		$this->target = '#modal-small';
		return $this;
	}

	function modalLarge(){
		$this->target = '#modal-large';
		return $this;
	}
	function confirm($msg)
	{
		$this->confirm = $msg;
		return $this;
	}
}
