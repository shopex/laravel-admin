<?php
namespace Shopex\LubanAdmin\Finder;

class SearchOption{
	
	use Shared;

	const TYPE_STRING = 0;
	const TYPE_NUMBER = 1;
	const TYPE_DATE = 3;
	const TYPE_BOOL = 3;

	public $key;
	public $label;
	public $optionType;
}