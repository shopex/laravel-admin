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
		//当当前列值，在另外一个表中不存在时，显示原始值
		foreach ($data as $key => $row) {
			if (isset($res[$row[$field]])) {
				$data[$key][$field] = $res[$row[$field]];
			}else{
				$data[$key][$field] = $row[$field];
			}
			
		}
		return $data;
	}
}