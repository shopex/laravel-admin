<?php
namespace Shopex\LubanAdmin\Finder;

class Search{
	
	use Shared;

	public $key;
	public $label;
	public $optionType;
	public $mode = '=';
	public $value;
	public $modifier;
	public $type;
	public $hidden = ['key', 'optionType','modifier'];

	static public function parse_filters(&$searchs, $filters){
		$return = [];
		foreach(json_decode($filters) as $item){
			$i = $item[0];
			$value = $item[1];
			$mode = isset($item[2])?$item[2]:'=';

			$key = $searchs[$i]->key;
			$searchs[$i]->mode = $mode;
			$searchs[$i]->value = $value;
			switch($searchs[$i]->type){
				case 'string':
					switch($mode){
						case '=':
						$mode = '=';
						break;

						case '!=':
						$mode = '!=';
						break;

						case 'begin':
						$mode = 'like';
						$value = $value.'%';
						break;

						case 'has':
						$mode = 'like';
						$value = '%'.$value.'%';
						break;

						case 'not like':
						$mode = 'not like';
						$value = '%'.$value;
						break;

						case 'not_has':
						$mode = 'not like';
						$value = '%'.$value.'%';
						break;
					}
					break;

				case 'number':
					switch($mode){
						case '=':
						$mode = '=';
						break;

						case '!=':
						$mode = '!=';
						break;

						case 'gt':
						$mode = '>';
						break;

						case 'lt':
						$mode = '<';
						break;
					}
					break;
				default:
					$mode = '=';
			}
			if($value){
				if ($searchs[$i]->modifier) {
					$value = call_user_func_array($searchs[$i]->modifier, [$value]);
				}
				$return[] = [$key, $mode, $value];
			}
		}

		return $return;
	}
}