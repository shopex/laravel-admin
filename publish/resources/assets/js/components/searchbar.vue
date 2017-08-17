<template>
<form class="navbar-form navbar-right search-form">
<div class="form-group">
  <input v-model="value" @focus="focus" @blur="blur" type="text" class="form-control search-ipt">
  <div class="search-panel dropdown-menu" v-bind:style="{'width': panel_width}"
  	 v-if="show" style="left:15px;display:block">
  	<div class="search-opts">
      	<a v-for="item in items">
      		{{item.label}} <span class="search-value-mapper">{{value}}</span>
      	</a>
  	</div>
  	<slot></slot>
  </div>
</div>
</form>
</template>

<style>
.search-form{position: relative}
.search-form .dropdown-menu{display:none;}
.search-opts{min-height:190px;}
.search-opts a{display: block;padding:5px 10px; text-decoration: none; color:#666;}
.search-opts a:hover{background: $component-active-bg; color:$component-active-color;}
.search-panel-footer{
	border-top:1px solid #ccc; margin:0 10px; padding:10px 0 5px 0; text-align: right}
</style>

<script>
export default {
	props: ["items"],
	data(){
		return {
			"value": "",
			"panel_width": '200px',
			"show": false
		}
	},
	methods: {
		focus (){

		},
		blur (){
			this.show = false;
		}
	},
	mounted(){
		var sb = $('.search-ipt', this.$el);
		var panel = $('.search-panel', this.$el);
		window._search_ipt_w = sb.width();
		var that = this;
		sb.on('focus', function(){
			$('#navbar .hide-in-search').hide();
			sb.select();
			that.panel_width = $('.container').width() / 2+'px';
			sb.animate({width: $('.container').width() / 2}, 300, function(){
				panel.css('top', sb.outerHeight());
				panel.width(sb.outerWidth());
				that.show = true;
			});
		});
		sb.on('blur', function(){
			window._search_ipt_v = 0;
			panel.hide();
			sb.animate({width: window._search_ipt_w}, 300, function(){
				$('#navbar .hide-in-search').show();
			});
		})
	}
}
</script>