<template>

<div class="admin-header-searchbar">
	<div class="dropdown" v-bind:class="{'open': (show && options_filter.length>0)}">
		<input type="text" 
			v-on:focus="display"
			v-on:blur="blur"
			v-on:keyup="keyup($event)"
			v-model="value"
			style="width:25rem"
			/>
	  	<div class="search-opts dropdown-menu">
	      	<a v-bind:class="{'selected': selected==idx}"
	      	   v-for="(item, idx) in options_filter"
	      	   v-bind:href="item.href"
	      	   v-on:click="clear"
	      	   v-on:mousedown="mouseDown"
	      	   v-on:mouseover="selected=idx">
	      		{{item.label}} <span class="search-value-mapper">{{value}}</span>
	      	</a>
	  	</div>
  	</div>
</div>

</template>

<style>
.search-form{position: relative}
.search-form .dropdown-menu{display:none;}
.search-opts{
	min-height:20rem;
	min-width:25rem;
}
.search-opts a{line-height:2rem; display: block;padding:0.5rem; text-decoration: none; color:#666;}
.search-opts a:hover{background: $component-active-bg; color:$component-active-color;}
.search-opts a.selected{
	background: #000;
	color: #fff;
}
.search-panel-footer{
	border-top:1px solid #ccc; 
	margin:0 10px; 
	padding:10px 0 5px 0;
	text-align: right;
}

.admin-header-searchbar input{
	line-height: 2.4rem;
	padding:0 0.5rem;
	background: transparent;
	border:1px solid #ccc;
}

.admin-header-searchbar > .dropdown{
	line-height: 2.4rem;
	margin:1.3rem 0;
}
</style>

<script>
export default {
	props: ["items"],
	data(){
		return {
			value: "",
			show: false,
			selected: 0,
			options_filter: [],
			md: false
		}
	},
	methods: {
		display (){
			this.show = true;
			this.md = false;
			this.selected = 0;
		},
		blur (){
			var that = this;
			setTimeout(function(){
				if(!that.md){
					that.show=false;
				}
			}, 50);
		},
		mouseDown (){
			this.md = true;
		},
		clear (){
			this.md = false;
			this.show = false;
			this.value = '';
			this.options_filter = [];
			$('input', this.$el).blur();			
		},
		keyup (e){
			if(e.code=="ArrowUp"){
				this.selected--;
				if(this.selected==-1){
					this.selected = this.options_filter.length-1;
				}
			}else if(e.code=="ArrowDown"){
				this.selected++;
				if(this.selected==this.options_filter.length){
					this.selected = 0;
				}
			}else if(e.code=="Enter"){
				var event;

				if (document.createEvent) {
					event = document.createEvent("HTMLEvents");
					event.initEvent("click", true, true);
				} else {
					event = document.createEventObject();
					event.eventType = "click";
				}

				event.eventName = "click";

				var element = $('a.selected', this.$el)[0];

				if (document.createEvent) {
					element.dispatchEvent(event);
				} else {
					element.fireEvent("on" + event.eventType, event);
				}

				this.clear();
			}else{
				if(!this.value){
					this.options_filter = [];
				}
				var ret = [];
				for(var i=0;i<this.items.length;i++){
					if(this.value.match(this.items[i].re)){
						this.items[i].href = this.items[i].action.replace('{{value}}', this.value);
						ret.push(this.items[i]);
					}
				}
				this.options_filter = ret;
				this.selected = 0;
			}
		}
	},
	mounted(){
		$('.dropdown-toggle', this.$el).dropdown();
		for(var i=0;i<this.items.length;i++){
			this.items[i].re = new RegExp(this.items[i].regexp);
		}
	}
}
</script>