<template>
	<div>
			<div class="finder-action-bar" ref="actionbar">
				<div>
					<div class="btn-group" role="group">
						<a v-for="(action, idx) in finder.actions" 
							class="btn btn-default" 
							v-bind:href="action_url[idx]"
							v-bind:target="action.target">
							{{action.label}}
						</a>
					</div>
				</div>
				<div class="finder-tabber">
					  <ul class="nav nav-tabs" role="tablist">
					    <li v-for="(panel, tab_id) in finder.tabs" v-bind:class="{'active': tab_id==finder.tab_id}">
					    	<a v-on:click="select_tab(tab_id)">{{panel.label}}</a>
					    </li>
						<li v-bind:class="{'active': 'workdesk'==finder.tab_id}">
					    	<a v-on:click="select_tab('workdesk')">
								<i class="glyphicon glyphicon-duplicate"></i>
								<sup v-show="workdesk.length>0" style="background: red" class="badge">
									{{workdesk.length}}
								</sup>
					    	</a>
					    </li>
					  </ul>
				</div>
				<div class="finder-pager">
					<span class="dropdown">
					  <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true">
					    {{(finder.data.currentPage-1)*finder.data.perPage+1}}
					    -
					    {{Math.min(finder.data.currentPage*finder.data.perPage,finder.data.total)}}, 共{{finder.data.total}}项
					  </button>
					  <ul class="dropdown-menu">
					    <li v-for="(sort, idx) in finder.sorts">
						    <a v-on:click="finder.sort_id=idx;reload()">
						    	{{sort.label}}
						    	<i class="glyphicon glyphicon-ok" v-if="idx==finder.sort_id"></i>
						    </a>
					    </li>
					    <li role="separator" class="divider"></li>
						<li v-for="(col, col_id) in finder.cols">
							<a v-on:click="toggle_col(col_id)">
								{{col.label}}
								<i class="glyphicon glyphicon-ok" v-if="!col.hidden"></i>
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

			<div class="finder-header" ref="header">
				<form class="finder-search-bar" v-on:submit="reload()" v-if="'workdesk'!=finder.tab_id && finder.searchs">

					<div class="form-inline" v-for="(search, idx) in finder.searchs">
					  <div class="form-group">
					    {{search.label}}
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
				</form>

				<div class="finder-workdesk-bar" v-if="'workdesk'==finder.tab_id">
					操作台: 一个临时收纳台.
					<button class="btn btn-default btn-sm" 
					v-bind:disabled="this.workdesk.length==0"
					v-on:click="clear_workdesk">清空列表</button>
				</div>
				<div class="finder-row">
					<label class="finder-col-sel" v-if="finder.batchActions.length>0">
						<input type="checkbox" v-on:click="select_all" v-model="v_select_all" />
					</label>
					<div class="row api-top-title">
						<div v-for="(col, col_id) in finder.cols" v-bind:class="col_class[col_id]" v-if="!col.hidden">
							{{col.label}}
						</div>
					</div>
				</div>
			</div>

		<div name="finder-content" ref="content">
			<div class="finder-body" v-on:mouseup="sel_mup($event)" v-bind:class="{'unselectable': unselectable}">

				<div class="finder-item" v-for="(item,idx) in finder.data.items" v-bind:class="{'selected':checkbox[idx], 'detail':current_detail==idx}">
					<div class="finder-row"
							v-on:mouseout="sel_mout(idx,$event)"
							v-on:mouseover="sel_mover(idx,$event)">
						<label class="finder-col-sel" 
							v-on:mousedown="sel_mdown(idx,$event)"
							v-if="finder.batchActions.length>0">
							<input type="checkbox" v-model="checkbox[idx]" />
						</label>
						<div class="row api-top-title" v-on:click="toggle_detail(idx, $event)">
							<div v-for="(col, col_id) in finder.cols" v-bind:class="col_class[col_id]" v-if="!col.hidden">
								<span v-if="typeof(item[col_id])=='object' && item[col_id].date">
									{{item[col_id].date}}
								</span>
								<span v-else-if="col.html" v-html="item[col_id]"></span>
								<span v-else>{{item[col_id]}}</span>
							</div>
						</div>
					</div>
					<div class="finder-detail" v-if="current_detail==idx">
						  <ul class="nav nav-tabs" role="tablist" v-if="finder.infoPanels.length>1">
						    <li v-for="(panel, panel_id) in finder.infoPanels" v-bind:class="{'active': panel_id==current_panel}">
						    	<a v-on:click="show_panel(idx, panel_id)">{{panel.label}}</a>
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

			<transition name="finder-slide-bottom">
				<div class="finder-batch-action-bar" v-if="selected.length>0">
					<div>
						<span>{{selected.length}}</span>

						<form v-bind:target="batch_action_target" method="POST">
							<input type="hidden" name="finder_request" value="batch_action" />
							<input type="hidden" name="action_id" v-bind:value="batch_action_id" />
							<input type="hidden" name="id[]" v-bind:value="id" v-for="id in selected" />

							<div class="btn-group" role="group">
								<button v-for="(action, idx) in finder.batchActions"
										v-on:click="batch_action_id=idx;batch_action_target=action.target"
										type="submit"
										class="btn btn-default">
										{{action.label}}
								</button>
							</div>
							
							<button v-if="'workdesk'==finder.tab_id" v-on:click="del_workdesk($event)" class="btn btn-default">移出操作台</button>
							<button v-else v-on:click="put_workdesk($event)" class="btn btn-default">放入操作台</button>
						</form>
					</div>
				</div>
			</transition>

			<div v-show="items_loading" class="finder-masker" v-bind:style="{'background': masker_bgcolor}"></div>
		</div>
	</div>
</template>

<style>
.finder-body .row, .finder-header .finder-row .row{
	padding-left: 0.5rem;
	line-height: 3rem;
	height:3rem;
	white-space: nowrap;
	overflow: hidden;
	cursor: default;
}
.finder-header .finder-row{
	border-left: 0.3rem solid #666;	
	background: #666;
	color:#fff;
}
.finder-group-title.row{
	background: #f0f0f0;
	color:#000;
}
.finder-row{
	clear:both;
	display: flex;
}
.finder-col-sel{
	flex:1 0 auto;
	width:3rem;
	z-index: 99;
	text-align: center;
	line-height: 3rem;
	margin:0;
}
.finder-row .row{
	flex: 999;
	cursor: pointer;
}
.finder-item{
	border-left: 0.3rem solid transparent;
	border-bottom: 1px solid #ccc;
}
.finder-item.detail{
	border-left-color: #0769ad;
}
.finder-item.selected{
	background: #f0f0f0;
}
.finder-detail{
	border-top: 1px dashed #ccc;
}
.finder-detail .nav-tabs{
	padding: 0.5rem 0.5rem 0 0.5rem;
	display: flex;
	/*justify-content: center;*/
}
.finder-detail .nav-tabs li{

}
.finder-detail .nav > li > a{
	padding: 0.3rem 1rem;
	cursor: pointer;
}
.finder-detail-content{
	margin: 0.5rem;
	min-height: 15rem;
	overflow: hidden;
	word-break: break-all;
}
.finder-search-bar{
	border-top: 1px solid #ccc;
	padding: 0.5rem;
	background: #f0f0f0;
}
.finder-workdesk-bar{
	border-top: 1px solid #ccc;
	background: #9f9;
	color: #000;
	padding: 0.3rem;
	text-align: center;
}
.finder-batch-action-bar{
	position: absolute;
	bottom: 0;
	left:0;
	right:0;
	display: flex;
	justify-content: center;
}
.finder-batch-action-bar>div{
	border:1px solid #ccc;
	padding:1.5rem .5rem .5rem .5rem;
	z-index: 999;
	border-top-left-radius: 1rem;
	border-top-right-radius: 1rem;
	border-bottom: none;
	background: #000;
	color: #ccc;	
}
.finder-batch-action-bar>div span{
	border:0.5rem solid #000;	
	background: #666;
	color: #ccc;
	position: absolute;
	height:4rem;
	width:4rem;
	text-align: center;
	display: block;
	top: -2.5rem;
	border-radius: 100%;
	line-height: 3rem;
	left: 50%;
	margin-left: -2rem;
}
.finder-batch-action-bar .btn-default{
	background: #000;
	color: #ccc;
}
.finder-action-bar > div{
	line-height: 3rem;
}
.finder-tabber{
	flex:1; 
	padding-left: 2.5rem;
	margin:-0.4rem; 
	display: flex; 
	align-items: flex-end;
}
.finder-tabber .nav-tabs{
	display: flex;
	justify-content: center;
	border-bottom: none;
}
.finder-tabber .nav > li > a{
	cursor: pointer;
	padding: 0.7rem 1.5rem;
}
.finder-action-bar{
	display: flex;
}
.finder-pager{
	flex:0 0;
	text-align: right;
	white-space: nowrap;
}
.finder-pager >.dropdown > .btn-default{
	border: none;
}
.finder-masker{
	background: #fff; 
	z-index: 9999; 
	position: absolute; 
	width: 100%; 
	height: 100%; 
	left:0; top:0;
}

.finder-search-bar .form-inline{
	display: inline-block;
	margin: 0 3rem 0.5rem 0;
}
.finder-user-header{
	border-top: 1px solid #ccc;
}

.finder-slide-bottom-enter-active {
  transition: all .3s ease;
}
.finder-slide-bottom-leave-active {
  transition: all .3s ease;
}
.finder-slide-bottom-enter, .finder-slide-bottom-leave-to{
  transform: translateY(100%);
  opacity: 0;
}
</style>

<script>
export default {
	  mounted (){
	  },
	  computed: {
	  	col_class (){
	  		var ret = [];
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
	  	selected (){
	  		var ret = [];
	  		for(var i=0; i<this.checkbox.length;i++){
	  			if(this.checkbox[i]==true){
	  				ret.push(this.finder.data.items[i].$id);
	  			}
	  		}
	  		return ret;
	  	},
	  	action_url (){
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
	  	reload (page){
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
				complete (){
					that.items_loading = false;
				}
			}).done(function(response){
				this.checkbox = [];
				that.finder.data = response;
			});
	  	},
		select_all (e){
			if(this.v_select_all){
				for(var i=0;i<this.finder.data.items.length;i++){
					this.$set(this.checkbox, i, true);
				}
			}else{
				this.checkbox = [];
			}
		},
		toggle_col (id){
			this.$set(this.finder.cols[id], "hidden", this.finder.cols[id].hidden?false:true);
		},
		put_workdesk (e){
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
		reload_workdesk (){
			this.finder.data = {
				items: this.workdesk,
				currentPage: 1,
				perPage: this.workdesk.length,
				total: this.workdesk.length,
				hasMorePages: false
			}
		},
		clear_workdesk (){
			this.workdesk_ids={};
			this.workdesk=[];
			this.reload_workdesk();
		},
		del_workdesk (e){
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
		toggle_detail (id, e){
			if( ["A","BUTTON","SELECT","INPUT"].indexOf(e.target.tagName)>=0 ){
				return;
			}
			if(this.current_detail==id){
				this.current_detail = undefined;
			}else{
				this.current_detail = id;
				this.show_panel(id, 0);
			}
		},
		go_page (v){
			this.reload(this.finder.data.currentPage+v);
		},
		select_tab (tab_id){
			this.finder.tab_id = tab_id;
			this.v_select_all = false;
			this.page_num = 0;
			if(tab_id=='workdesk'){
				this.reload_workdesk();
			}else{
				this.reload(0);
			}
		},
		show_panel (item_idx, panel_id){	
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
		},
		sel_mup (e){
			if(!this.batch_select_mode){
				return;
			}
			this.batch_select_mode = false;
			this.batch_select_value = undefined;
			this.batch_select_lastidx = 0;
			this.unselectable = false;
		},
		sel_mdown (idx,e){
			this.batch_select_mode = true;
		},
		sel_mover (idx,e){
			if(!this.batch_select_mode || this.batch_select_lastidx==idx){
				return;
			}
			var to = Math.max(this.batch_select_lastidx, idx);
			for(var i=Math.min(this.batch_select_lastidx, idx)+1; i<=to; i++){
				this.$set(this.checkbox, i, this.batch_select_value);
			}
			this.batch_select_lastidx = idx;
		},
		sel_mout (idx,e){
			if(!this.batch_select_mode){
				return;
			}
			if(this.batch_select_value==undefined){
				if(this.checkbox[idx]){
					this.$set(this.checkbox, idx, false);
				}else{
					this.$set(this.checkbox, idx, true);
				}
				this.batch_select_value = this.checkbox[idx];
				this.batch_select_lastidx = idx;
				this.unselectable = true;
			}
		}
	  },
	  data (){
	  	return {
		    current_detail: undefined,
		    current_panel: 0,
		    checkbox: [],
		    workdesk: [],
		    workdesk_ids: {},
		    v_select_all: false,
		    items_loading: false,
		    panel_loading: false,
		    unselectable: false,
			batch_action_target: '',
			batch_action_id: -1,
			batch_select_mode: false,
			batch_select_value: undefined,
			batch_select_lastidx: 0,
			masker_bgcolor: 'rgba(255,255,255,0.4)'
	  	}
	  }
	}
</script>