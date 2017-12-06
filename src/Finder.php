<?php
namespace Shopex\LubanAdmin;

use Shopex\LubanAdmin\Finder\Action;
use Shopex\LubanAdmin\Finder\Column;
use Shopex\LubanAdmin\Finder\InfoPanel;
use Shopex\LubanAdmin\Finder\Search;
use Shopex\LubanAdmin\Finder\Tab;
use Shopex\LubanAdmin\Finder\Sort;
use Shopex\LubanAdmin\Finder\Modifier;
use Illuminate\Support\Facades\Route;

class Finder{

	public $_title;
	public $_model;
	public $_baseUrl = '';
	public $_tabs = [];
	public $_actions = [];
	public $_batch_actions = [];
	public $_searchs = [];
	public $_infoPanels = [];
	public $_columns = [];
	public $_sorts = [];
	public $_pagenum = 20;
	public $_current_sort_id = 0;
	public $_current_tab_id = 0;
	public $_id_column = '';

	static function create($model, $title){
		$finder = new Finder;
		$currentRouter = Route::getFacadeRoot()->current();
		if(false == in_array('GET', $currentRouter->methods())){
			throw new \Exception('使用Finder的路由必须支持POST.');
		}
		$finder->setBaseUrl(url()->current());
		$finder->setModel($model);
		$finder->setTitle($title);
		$request = request();
		return $finder;
	}
	static function OrmModifier($model,$show_field)
	{
		$orm = new Modifier($model,$show_field);
		return $orm;
	}
	public function setTitle($title){
		$this->_title = $title;
		return $this;
	}

	public function setModel($model){
		$this->model = $model;
		return $this;
	}

	public function setBaseUrl($url){
		$this->_baseUrl = $url;
		return $this;
	}

	public function setPageNum($n){
		$this->_pagenum = $n;
		return $this;
	}

	public function addSort(){
		$args = func_get_args();
		$sort = new Sort($this);

		$sort->label = array_shift($args);
		$sort->orderBy = $args;

		$this->_sorts[] = $sort;
		return $sort;
	}
	public function sorts(){
		return $this->_sorts;
	}
	public function addAction($label, $do){
		$action = $this->mkAction($label, $do);
		$this->_actions[] = $action;
		return $action;
	}

	public function addBatchAction($label, $do){
		$action = $this->mkAction($label, $do);
		$this->_batch_actions[] = $action;
		return $action;
	}

	public function actions(){
		return $this->_actions;
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

	public function addTab($label, $filters=[]){
		$tab = new Tab($this);
		$tab->label = $label;
		$tab->filters = $filters;
		$this->_tabs[] = $tab;
		return $tab;
	}

	public function tabs()
	{
		return $this->_tabs;
	}

	public function setId($colname){
		$this->_id_column = $colname;
		return $this;
	}

	public function addInfoPanel($label, $handle){
		$panel = new InfoPanel($this);
		$panel->handle = $handle;
		$panel->label = $label;
		$this->_infoPanels[] = $panel;
		return $panel;
	}

	public function infoPanels(){
		return $this->_infoPanels;
	}

	public function addSearch($label, $key, $type=''){
		$search = new Search($this);
		if (!is_array($key)) {
			$search->field = $key;
		}
		$search->label = $label;
		$search->key = $key;		
		$search->type = $type;
		$this->_searchs[] = $search;
		return $search;
	}
	public function searchs(){
		return $this->_searchs;
	}
	public function addColumn($label, $key){
		$col = new Column($this);
		$col->label = $label;
		$col->key = $key;
		$this->_columns[] = $col;
		return $col;
	}
	public function cols(){
		return $this->_columns;
	}
	
	public function baseurl(){
		return $this->_baseurl;
	}

	public function searchOptions(){
		return $this->_searchOptions;
	}

	public function items(){
		$cols = [];
		$items = [];
		foreach($this->_columns as $col){
			if($col->key){
				$cols[] = $col->key;
			}
		}

		$cols[] = $this->_id_column;

		$query = call_user_func_array([$this->model, 'select'], $cols);
		if(isset($this->_sorts[$this->_current_sort_id])){
			$query = call_user_func_array([$query, 'orderBy'], 
				$this->_sorts[$this->_current_sort_id]->orderBy);
		}
		if(isset($this->_tabs[$this->_current_tab_id])){
			foreach($this->_tabs[$this->_current_tab_id]->filters as $filter){
				$query = call_user_func_array([$query, 'where'], $filter);
			}
		}
		if(isset($this->_filters[0])){
			foreach($this->_filters as $filter){
				if (is_array($filter[2])) {
					$query = call_user_func_array([$query, 'whereIn'], [$filter[0],$filter[2]]);
				}else{
					$query = call_user_func_array([$query, 'where'], $filter);
				}
			}
		}
		$results = $query->paginate($this->_pagenum);

		$modifierName = [];
		foreach($results as $row){
			foreach($this->_columns as $i=>$col){
				$item['$id'] = $row[$this->_id_column];
				$item[$i] = $col->key?$row[$col->key]:'';
				if(is_callable($col->modifier) ){
					$item[$i] = call_user_func_array($col->modifier, [$item[$i], $row]);
				}elseif (is_object($col->modifier)) {
					$modifierName[$i] = $col->modifier;
				}
			}
			$items[] = $item;
		}
		if ($modifierName) {
			foreach ($modifierName as $field => $orm) {
				$items = $orm->process($items,$field);
			}
		}
		$data = [
			'count' => $results->count(),
			'currentPage' => $results->currentPage(),
			'hasMorePages' => $results->hasMorePages(),
			'lastPage' => $results->lastPage(),
			'perPage' => $results->perPage(),
			'total' => $results->total(),
			'items' => $items,
		];

		return $data;
	}

	public function title(){
		return $this->_title;
	}

	public function view($view = null, $vars=[]){
		$request = request();

		$this->_current_sort_id = $request->get('sort', 0);
		$this->_current_tab_id = $request->get('tab_id', 0);

		if($filters = $request->get('filters')){
			$this->_filters = Search::parse_filters($this->_searchs, $filters);
		}

		if(!isset($this->_tabs[0])){
			$this->addTab('全部', []);
		}
		switch($request->get('finder_request')){
			case 'batch_action':
				return call_user_func_array($this->_batch_actions[$request->get('action_id')]->handle, 
					[$request->get('id')]);
			case 'action':
				return call_user_func_array($this->_actions[$request->get('id')]->handle, []);
			case 'detail':
				return call_user_func_array($this->_infoPanels[$request->get('panel_id')]->handle, 
					[$request->get('item_id')]);
			case 'data':
				return $this->items();
		}
		$vars['finder'] = $this;
		return view($view?:'admin::finder', $vars);
	}

	public function data(){
		$ret = [
			'baseUrl' => $this->_baseUrl,
			'title' => $this->_title,
			'tabs' => $this->_tabs,
			'cols' => $this->_columns,
			'sorts' => $this->_sorts,
			'infoPanels' => $this->_infoPanels,
			'actions' => $this->_actions,
			'searchs' => $this->_searchs,
			'batchActions' => $this->_batch_actions,
			'data' => $this->items(),
			'tab_id' => $this->_current_tab_id,
			'sort_id' => $this->_current_sort_id,
		];

		return $this->output_data($ret);
	}

	public function output_data($array){

		foreach($array as &$item){
			if(is_array($item) && isset($item[0]) && is_object($item[0]) && 
				property_exists($item[0], 'hidden') && is_array($item[0]->hidden)){
				foreach($item as &$value){
					$new_value = [];

					$hidden = array_flip($value->hidden);
					$hidden['hidden'] = true;

					foreach(get_object_vars($value) as $k=>$v){
						if(!isset($hidden[$k])){
							$new_value[$k] = $v;
						}
					}
					$value = $new_value;
				}
			}
		}
		
		return $array;
	}

	public function json(){
		$data = $this->data();
		return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}
}