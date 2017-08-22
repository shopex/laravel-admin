<?php
namespace Shopex\LubanAdmin;

use Shopex\LubanAdmin\Finder\Action;
use Shopex\LubanAdmin\Finder\Column;
use Shopex\LubanAdmin\Finder\InfoPanel;
use Shopex\LubanAdmin\Finder\SearchOption;
use Shopex\LubanAdmin\Finder\Tab;
use Illuminate\Support\Facades\Route;

class Finder{

	private $_title;
	private $_model;
	private $_baseUrl = '';
	private $_tabs = [];
	private $_actions = [];
	private $_batch_actions = [];
	private $_searchOptions = [];
	private $_infoPanels = [];
	private $_columns = [];

	static function new($model, $title){
		$finder = new Finder;
		$currentRouter = Route::getFacadeRoot()->current();
		if(false == in_array('POST', $currentRouter->methods())){
			throw new \Exception('使用Finder的路由必须支持POST.');
		}
		$currentPath= $currentRouter->uri();
		$finder->setBaseUrl('/'.$currentPath);
		$finder->setModel($model);
		$finder->setTitle($title);
		return $finder;
	}

	public function setTitle($title){
		$this->_title = $title;
	}

	public function setModel($model){
		$this->model = $model;
	}

	public function setBaseUrl($url){
		$this->_baseUrl = $url;
		return $this;
	}

	public function addAction($label, $do){
		$action = $this->mkAction($label, $do);
		$this->_actions[] = $action;
		return $this;
	}

	public function addBatchAction($label, $do){
		$action = $this->mkAction($label, $do);
		$this->_batch_actions[] = $action;
		return $this;
	}

	public function mkAction($label, $do){
		$action = new Action($this);
		if(is_callable($do)){
			$action->handle = $do;
		}elseif(is_string($do)){
			$action->url = $do;
		}else{
			throw new \Exception('Action必须是url字符, 或者callable');
		}
		$action->label = $label;
		return $action;
	}

	public function addTab($label, $options){
		$tab = new Tab($this);
		$tab->label = $label;
		$tab->options = $options;
		$this->_tabs[] = $tab;
		return $this;
	}

	public function addInfoPanel($label, $handle){
		$panel = new InfoPanel($this);
		$panel->handle = $handle;
		$panel->label = $label;
		$this->_infoPanels[] = $panel;
		return $this;
	}

	public function addSearchOption($label, $key, $optionType){
		$search = new SearchOption($this);
		$search->key = $key;
		$search->label = $label;
		$search->optionType = $optionType;
		$this->_searchOptions[] = $search;
		return $this;
	}

	public function addColumn($label, $key){
		$col = new Column($this);
		$col->label = $label;
		$col->key = $key;
		$this->_columns[] = $col;
		return $col;
	}	

	public function actions(){
		return $this->_actions;
	}

	public function baseurl(){
		return $this->_baseurl;
	}

	public function searchOptions(){
		return $this->_searchOptions;
	}

	public function infoPanels(){
		return $this->_infoPanels;
	}

	public function items(){
		$cols = [];
		$items = [];
		foreach($this->_columns as $col){
			if($col->key){
				$cols[] = $col->key;
			}
		}
		$query = call_user_func_array([$this->model, 'select'], $cols);
		$rows = $query->get();
		foreach($rows as $row){
			foreach($this->_columns as $i=>$col){
				$item[$i] = $col->key?$row[$col->key]:'';
				if($col->modifier){
					$item[$i] = call_user_func_array($col->modifier, [$item[$i], $row]);
				}
			}
			$items[] = $item;
		}
		return $items;
	}

	public function cols(){
		return $this->_columns;
	}

	public function title(){
		return $this->_title;
	}

	public function view($view = null){
		$request = request();
		switch($request->get('finder_request')){
			case 'detail':
				return call_user_func_array($this->_infoPanels[$request->get('panel_id')]->handle, [$request]);
		}
		return view($view?:'admin::finder', ['finder'=>$this]);
	}

	public function json(){
		$ret = [
			'baseUrl' => $this->_baseUrl,
			'title' => $this->_title,
			'tabs' => $this->_tabs,
			'cols' => $this->_columns,
			'infoPanels' => $this->_infoPanels,
			'actions' => [],
			'batchActions' => [],
		];
		foreach($this->_actions as $act){
			$ret['actions'][] = [
					'label'=>$act->label,
					'url'=>$act->url,
				];
		}
		foreach($this->_batch_actions as $act){
			$ret['batchActions'][] = [
					'label'=>$act->label,
					'url'=>$act->url,
				];
		}

		$ret['items'] = $this->items();

		return json_encode($ret, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}
}