<?php
namespace Shopex\LubanAdmin;

use Shopex\LubanAdmin\Finder\Action;
use Shopex\LubanAdmin\Finder\Column;
use Shopex\LubanAdmin\Finder\InfoPanel;
use Shopex\LubanAdmin\Finder\SearchOption;
use Shopex\LubanAdmin\Finder\Tab;

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

	public function addAction($label, $method, $httpMethod='POST'){
		$action = $this->mkAction($label, $method, $httpMethod);
		$this->_actions[] = $action;
		return $this;
	}

	public function addBatchAction($label, $method, $httpMethod='POST'){
		$action = $this->mkAction($label, $method, $httpMethod);
		$this->_batch_actions[] = $action;
		return $this;
	}

	public function mkAction($label, $method, $httpMethod='POST'){
		$action = new Action;
		$action->method = $method;
		$action->label = $label;
		$action->httpMethod = $httpMethod;
		return $action;
	}

	public function addTab($label, $options){
		$tab = new Tab;
		$tab->label = $label;
		$tab->options = $options;
		$this->_tabs[] = $tab;
		return $this;
	}

	public function addInfoPanel($label, $method){
		$panel = new InfoPanel;
		$panel->method = $method;
		$panel->label = $label;
		$this->_panels[] = $panel;
		return $this;
	}

	public function addSearchOption($label, $key, $optionType){
		$search = new SearchOption;
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
		$this->_columns[$key] = $col;
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

	public function hasInfoPanels(){
		return count($this->infoPanels()) > 0;
	}

	public function infoPanels(){
		return $this->_infoPanels;
	}

	public function items(){
		$query = call_user_func_array([$this->model, 'select'], array_keys($this->_columns));
		return $query->get();
	}

	public function cols(){
		return $this->_columns;
	}

	public function title(){
		return $this->_title;
	}

	public function response(){
		return view('admin::ui.finder', ['finder'=>$this]);
	}
}