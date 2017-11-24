<?php
namespace Shopex\LubanAdmin\Finder;

class Modifier{
	
	private $model;
	private $showName;
	private $field;
	private $pos;

	public function __construct($model,$showName)
	{
		$this->model = $model;
		$this->showName = $showName;
	}
	public function process($data,$field)
	{	$ids = array_column($data,$field);
		$modle = new $this->model;
		$showName = $this->showName;

		$res = $modle->find($ids)->mapWithKeys(function ($item) use ($showName)  {
		    return [$item['id'] => $item[$showName]];
		})->all();
		foreach ($data as $key => $row) {
			$data[$key][$field] = $res[$row[$field]];
		}
		return $data;
	}
}