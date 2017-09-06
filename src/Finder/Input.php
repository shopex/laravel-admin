<?php
namespace Shopex\LubanAdmin\Finder;

use Shopex\LubanAdmin\Finder;
use Collective\Html\HtmlFacade as Html;
use Illuminate\Support\HtmlString;

class Input extends Finder{

	private $type = '';

	public function setType($type){
		$this->type = $type;
		return $this;
	}
	
	public function html($name, $value=null, $attrs=[], $filters=[]){
		if(!isset($attrs['id']) || !$attrs['id']){
			$attrs['id'] = uniqid('el_');
		}
		$attrs['value'] = $value;
		$attrs['name'] = $name;

		$attrs['type'] = $this->type;
		$attrs['filters'] = json_encode($filters);

		$html = Html::tag('objectinput', '', $attrs);

		$el_id = $attrs['id'];
		$filters_json = json_encode($filters);

		$html .= <<<EOF
		<script>
		$(function(){
			new Vue({
				el: '#{$el_id}'
			})
		})
		</script>
EOF;

		return new HtmlString($html);
	}

	public function data(){
		$ret = [
			'baseUrl' => url('admin/component/objectinput/'.$this->type),
			'cols' => $this->_columns,
			'sorts' => $this->_sorts,
			'searchs' => $this->_searchs,
			'data' => $this->items()
		];

		return $this->output_data($ret);
	}

	public function view($view = NULL, $vars = []){
		$request = request();
		if($filters = $request->get('filters')){
			$this->_filters = Search::parse_filters($this->_searchs, $filters);
		}

		switch($request->get('finder_request')){
			case 'data':
				return $this->items();
			case 'sync':
				$values = $request->get('values');
				$query = call_user_func_array([$this->model, 'select']
					, [$this->_id_column.' as value', $this->_columns[0]->key.' as label']);
				return $query->whereIn($this->_id_column, explode(',', $values))
					->get();
		}

		return $this->data();
	}
}