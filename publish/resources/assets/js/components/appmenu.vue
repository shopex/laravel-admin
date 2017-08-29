<template>
  <ul class="appmenu">
	<li v-for="(item,i) in menus">
		<i v-if="item.items && item.open" class="glyphicon glyphicon-menu-down"></i>
		<i v-if="item.items && !item.open" class="glyphicon glyphicon-menu-right"></i>
		<a v-if="item.items" v-on:click="toggle(i, $event)">{{item.label}}</a>
		<a v-else v-bind:href="item.link">{{item.label}}</a>
		<ul v-if="item.items" v-show="item.open">
			<li v-for="item in item.items">
				<a v-if="item.label" v-bind:href="item.link">{{item.label}}</a>
			</li>
		</ul>
	</li>
  </ul>
</template>

<style>
ul.appmenu{
	margin:0;
	padding:0;
}
ul.appmenu a, ul.appmenu a:hover{
	text-decoration: none;
}
ul.appmenu>li{
	border-bottom:1px solid #f0f0f0;
}
ul.appmenu li{
	list-style: none;
    overflow: hidden;	
}
ul.appmenu>li>a{
	padding-left: 2rem;
	line-height: 4rem;
	height: 4rem;
	display: block;
	color: #666;
	cursor: pointer;
	z-index: 50;
}
ul.appmenu>li>a:hover, ul.appmenu>li>ul>li>a:hover{
	background: #f0f0f0;
}
ul.appmenu>li>ul{
	margin:0;
	padding:0;
	border-top:1px solid #f0f0f0;
	z-index: 0;
}
ul.appmenu>li>i{
	float: right;
	line-height: 4rem;
	margin-right:1.5rem;
}
ul.appmenu>li>ul>li>a{
	display: block;
	padding: 0.5rem 0 0 3rem;
	cursor: pointer;
	color: #666;
}
</style>

<script>
export default {
	props: ["menus"],
	methods: {
		toggle (i, e){
			var that = this;
			if(this.menus[i].open){
				$('ul', $(e.target).parent('li')).slideUp(250, function(){
					that.$set(that.menus[i], "open", false);
				});
			}else{
				that.$set(that.menus[i], "open", true);
				$('ul', $(e.target).parent('li')).slideDown(250);
			}
		}
	}
}
</script>