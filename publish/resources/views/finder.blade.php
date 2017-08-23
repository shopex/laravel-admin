@extends('admin::layout')

@section('title', $finder->title() )

@section('action-bar')
<div class="finder-action-bar">
	<div>
		<div class="btn-group" role="group">
			<a v-for="(action, idx) in finder.actions" 
				class="btn btn-default" 
				v-bind:href="action_url[idx]"
				v-bind:target="action.target"
				v-on:click="go_action(idx, $event)">
				@{{action.label}}
			</a>
		</div>
	</div>
	<div class="finder-tabber">
		  <ul class="nav nav-tabs" role="tablist">
		    <li v-for="(panel, tab_id) in finder.tabs" v-bind:class="{'active': tab_id==finder.tab_id}">
		    	<a v-on:click="select_tab(tab_id)">@{{panel.label}}</a>
		    </li>
			<li v-bind:class="{'active': 'workdesk'==finder.tab_id}">
		    	<a v-on:click="select_tab('workdesk')">
					<i class="glyphicon glyphicon-duplicate"></i>
					<sup v-show="workdesk.length>0" style="background: red" class="badge">
						@{{workdesk.length}}
					</sup>
		    	</a>
		    </li>
		  </ul>
	</div>
	<div class="finder-pager">
		<span class="dropdown">
		  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true">
		    @{{(finder.data.currentPage-1)*finder.data.perPage+1}}
		    -
		    @{{Math.min(finder.data.currentPage*finder.data.perPage,finder.data.total)}}, 共@{{finder.data.total}}项
		  </button>
		  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
		    <li v-for="(sort, idx) in finder.sorts">
			    <a v-on:click="finder.sort_id=idx;reload()">
			    	@{{sort.label}}
			    	<i class="glyphicon glyphicon-ok" v-if="idx==finder.sort_id"></i>
			    </a>
		    </li>
		    <li role="separator" class="divider"></li>
			<li v-for="(col, col_id) in finder.cols">
				<a>
					@{{col.label}}
					<i class="glyphicon glyphicon-ok"></i>
				</a>
			</li>
		  </ul>
		</span>
		<button class="btn btn-default" v-on:click="go_page(-1)" v-bind:disabled="finder.data.currentPage==1">
			<i class="glyphicon glyphicon-menu-left"></i>
		</button>
		<button class="btn btn-default" v-on:click="go_page(1)" v-bind:disabled="finder.data.hasMorePages==false">
			<i class="glyphicon glyphicon-menu-right"></i>
		</button>
	</div>
</div>
@endsection

@if (array_key_exists('finder-footer', View::getSections()))
@section('footer')
@yield('finder-footer')
@endsection
@endif

@section('header')
<div class="finder-header">

	@if (array_key_exists('finder-header', View::getSections()))
	<div class="finder-user-header">
		@yield('finder-header')
	</div>
	@endif

	<form class="finder-search-bar" v-on:submit="reload()" v-if="'workdesk'!=finder.tab_id && finder.searchs">

		<div class="form-inline" v-for="(search, idx) in finder.searchs">
		  <div class="form-group">
		    @{{search.label}}
		  </div>
		  <div class="form-group">
		    <select name="mode[]" v-model="search.mode" 
		    	v-on:change="search.value&&reload()" v-if="search.type=='string'">
		    	<option value="=">是</option>
		    	<option value="!=">不是</option>
		    	<option value="begin">开始于</option>
		    	<option value="has">包含</option>
		    	<option value="not_begin">不开始于</option>
		    	<option value="not_has">不包含</option>
		    </select>
		    <select name="mode[]" v-model="search.mode"
		    	v-on:change="search.value&&reload()" v-else-if="search.type=='number'">
		    	<option value="=">=</option>
		    	<option value="gt">&gt;</option>
		    	<option value="lt">&lt;</option>
		    </select>
		    <span v-else>
		    	:
		    	<input type="hidden" name="mode[]" value="=" />
		    </span>
		  </div>
		  <div class="form-group">
		    <input type="text" name="value[]" v-model="search.value" v-on:change="reload()" />
		  </div>
		</div>

		@yield('finder-search')

	</form>

	<div class="finder-workdesk-bar" v-if="'workdesk'==finder.tab_id">
		操作台: 一个临时收纳台.
		<button class="btn btn-default btn-sm" v-on:click="clear_workdesk">清空列表</button>
	</div>
	<div class="finder-row">
		<label class="finder-col-sel" v-if="finder.batchActions.length>0">
			<input type="checkbox" v-on:click="select_all" v-model="v_select_all" />
		</label>
		<div class="row api-top-title">
			<div v-for="(col, col_id) in finder.cols" v-bind:class="col_class[col_id]">
				@{{col.label}}
			</div>
		</div>
	</div>
</div>
@endsection

@section('content')
<div class="finder-body">

	<div class="finder-item" v-for="(item,idx) in finder.data.items" v-bind:class="{'selected':checkbox[idx], 'detail':current_detail==idx}">
		<div class="finder-row">
			<label class="finder-col-sel" v-if="finder.batchActions.length>0">
				<input type="checkbox" v-model="checkbox[idx]" />
			</label>
			<div class="row api-top-title" v-on:click="toggle_detail(idx)">
				<div v-for="(col, col_id) in finder.cols" v-bind:class="col_class[col_id]">
					<span v-if="typeof(item[col_id])=='object' && item[col_id].date">
						@{{item[col_id].date}}
					</span>
					<span v-else-if="col.html" v-html="item[col_id]"></span>
					<span v-else>@{{item[col_id]}}</span>
				</div>
			</div>
		</div>
		<div class="finder-detail" v-if="current_detail==idx">
			  <ul class="nav nav-tabs" role="tablist">
			    <li v-for="(panel, panel_id) in finder.infoPanels" v-bind:class="{'active': panel_id==current_panel}">
			    	<a v-on:click="show_panel(idx, panel_id)">@{{panel.label}}</a>
			    </li>
			  </ul>
			  <div class="tab-content">
			    <div role="tabpanel" class="tab-pane active" v-for="(panel, panel_id) in finder.infoPanels" v-if="panel_id==current_panel">
			    	<div class="finder-detail-content" v-html="item.panels[panel_id]"></div>
			    </div>
			  </div>
		</div>
	</div>

</div>

<div class="finder-batch-action-bar" v-if="selected.length>0">
	<div>
		<span>
		已选择: @{{selected.length}}项
		</span>

		<form v-bind:target="batch_action_target" method="POST">
			<input type="hidden" name="finder_request" value="batch_action" />
			<input type="hidden" name="action_id" v-bind:value="batch_action_id" />
			<input type="hidden" name="id[]" v-bind:value="id" v-for="id in selected" />

			<div class="btn-group" role="group">
				<button v-for="(action, idx) in finder.batchActions"
						v-on:click="batch_action_id=idx;batch_action_target=action.target"
						type="submit"
						class="btn btn-default">
						@{{action.label}}
				</button>
				<button v-if="'workdesk'==finder.tab_id" v-on:click="del_workdesk($event)" class="btn btn-default">移出操作台</button>
				<button v-else v-on:click="put_workdesk($event)" class="btn btn-default">放入操作台</button>
			</div>
		</form>
	</div>
</div>

<div v-show="items_loading" class="finder-masker" v-bind:style="{'background': masker_bgcolor}"></div>
@endsection

@section('scripts')
<script>
$(function(){
	var app = new Vue({
	  el: '.main-content',
	  mounted: function(){
	  },
	  computed: {
	  	col_class: function(){
	  		ret = [];
	  		for(var i=0;i<this.finder.cols.length;i++){
	  			var obj = {};
	  			if(this.finder.cols[i].className){
	  				obj[this.finder.cols[i].className] = true;
	  			}
	  			obj['col-md-'+this.finder.cols[i].size] = true;
	  			ret[i] = obj;
	  		}
	  		return ret;
	  	},
	  	selected: function(){
	  		var ret = [];
	  		for(var i=0; i<this.checkbox.length;i++){
	  			if(this.checkbox[i]==true){
	  				ret.push(this.finder.data.items[i].$id);
	  			}
	  		}
	  		return ret;
	  	},
	  	action_url: function(){
	  		var ret = [];
	  		for(var i=0; i<this.finder.actions.length; i++){
	  			ret[i] = this.finder.actions[i].url;
	  			if(!ret[i]){
	  				ret[i] = this.finder.baseUrl+'?finder_request=action&id='+i;
	  			}
	  		}
	  		return ret;
	  	}
	  },
	  methods:{
	  	reload: function(page){
	  		this.items_loading = true;
			this.current_detail = undefined;

			var filters = [];
			for(var i=0; i<this.finder.searchs.length; i++){
				if(this.finder.searchs[i].value){
					filters.push([i, this.finder.searchs[i].value, this.finder.searchs[i].mode]);
				}
			}

			var that = this;
			$.ajax({
				'url': this.finder.baseUrl,
				'data': {
					'finder_request':'data', 
					'page': page, 
					'sort': this.finder.sort_id,
					'tab_id': this.finder.tab_id,
					'filters': JSON.stringify(filters)
				},
				complete: function(){
					that.items_loading = false;
				}
			}).done(function(response){
				this.checkbox = [];
				that.finder.data = response;
			});
	  	},
		select_all: function(e){
			if(this.v_select_all){
				for(var i=0;i<this.finder.data.items.length;i++){
					this.$set(this.checkbox, i, true);
				}
			}else{
				this.checkbox = [];
			}
		},
		put_workdesk: function(e){
          e.stopPropagation();
          e.preventDefault();
          for(var i=0; i<this.checkbox.length; i++){
          	if(this.checkbox[i]){
          		if(!this.workdesk_ids[this.finder.data.items[i].$id]){
          			this.workdesk_ids[this.finder.data.items[i].$id] = true;
					this.workdesk.push(this.finder.data.items[i]);
          		}
          	}
          }
          this.checkbox = [];
		  this.v_select_all = false;
		},
		reload_workdesk: function(){
			this.finder.data = {
				items: this.workdesk,
				currentPage: 1,
				perPage: this.workdesk.length,
				total: this.workdesk.length,
				hasMorePages: false
			}
		},
		clear_workdesk: function(){
			this.workdesk_ids={};
			this.workdesk=[];
			this.reload_workdesk();
		},
		del_workdesk: function(e){
          e.stopPropagation();
          e.preventDefault();
          var map = {};
          for(var i=0; i<this.checkbox.length; i++){
          	if(this.checkbox[i]){
          		this.$delete(this.workdesk_ids, this.workdesk[i].$id);
          		this.workdesk[i] = undefined;
          	}
          }
          var new_workdesk = [];
          for(var i=0;i< this.workdesk.length; i++){
          	if(this.workdesk[i]){
          		new_workdesk.push(this.workdesk[i]);
          	}
          }
          this.workdesk = new_workdesk;
          this.checkbox = [];
		  this.v_select_all = false;
          this.reload_workdesk();
		},
		toggle_detail: function(id){
			if(this.current_detail==id){
				this.current_detail = undefined;
			}else{
				this.current_detail = id;
				this.show_panel(id, 0);
			}
		},
		go_page: function(v){
			this.reload(this.finder.data.currentPage+v);
		},
		select_tab: function(tab_id){
			this.finder.tab_id = tab_id;
			this.v_select_all = false;
			this.page_num = 0;
			if(tab_id=='workdesk'){
				this.reload_workdesk();
			}else{
				this.reload(0);
			}
		},
		show_panel: function(item_idx, panel_id){	
			if(!this.finder.data.items[item_idx]){
				return;
			}
			if(!this.finder.data.items[item_idx].panels){
				this.$set(this.finder.data.items[item_idx], 'panels', {});
			}
			if(!this.finder.data.items[item_idx].panels[panel_id]){
				this.$set(this.finder.data.items[item_idx].panels, panel_id, 'loading...');				
				var that = this;
				$.ajax({
					'url': this.finder.baseUrl,
					'data': {
						'finder_request':'detail', 
						'panel_id': panel_id, 
						'item_id': this.finder.data.items[item_idx].$id
					}
				}).done(function(response){
					that.$set(that.finder.data.items[item_idx].panels, panel_id, response);
				});
			}
			this.current_panel = panel_id;
		}
	  },
	  data: {
	    finder: {!! $finder->json() !!},
	    current_detail: undefined,
	    current_panel: 0,
	    checkbox: [],
	    workdesk: [],
	    workdesk_ids: {},
	    v_select_all: false,
	    items_loading: false,
	    panel_loading: false,
		batch_action_target: '',
		batch_action_id: -1,
		masker_bgcolor: 'rgba(255,255,255,0.4)'
	  }
	})
})
</script>

@yield('scripts')
@endsection