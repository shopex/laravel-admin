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
		foreach ($modle->find($ids)->toArray() as $datarow) {
			$res[$datarow['id']] = $datarow[$this->showName];
		}
		foreach ($data as $key => $row) {
			$data[$key][$field] = $res[$row[$field]];
		}
		return $data;
	}
}