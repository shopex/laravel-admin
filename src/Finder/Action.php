<?php
namespace Shopex\LubanAdmin\Finder;

class Action{
	
	use Shared;

	public $label;
	public $handle;
	public $url;
	public $target;
	public $confirm;
	public $modal_height;
	public $modal_width;
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

	function modalCustom($height,$width){
		$this->modal_height = $height;
		$this->modal_width= $width;
		$this->target = '#modal-custom';
		return $this;
	}

	function confirm($msg)
	{
		$this->confirm = $msg;
		return $this;
	}

}
