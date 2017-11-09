<template>
  <ul class="appmenu" :style="{display: (depth==1)?'block':'none'}">
	<li v-for="(item,i) in menus">
		<i :style="{'padding-left': ((depth-1)*deprem)+'rem'}" 
			v-if="item.items && item.open" class="icon glyphicon glyphicon-triangle-bottom"></i>
		<i :style="{'padding-left': ((depth-1)*deprem)+'rem'}"
			v-if="item.items && !item.open" class="icon glyphicon glyphicon-triangle-right"></i>
		<a :style="{'padding-left': (depth*deprem)+'rem'}" 
			v-if="item.items" v-on:click="toggle(i, $event)">{{item.label}}</a>
		<a :style="{'padding-left': (depth*deprem)+'rem'}"
			v-else v-bind:href="item.link" target="window">{{item.label}}</a>
		<appmenu :level="depth+1" :menus="item.items"></appmenu>
	</li>
  </ul>
</template>

<style scope lang="scss">

$menu-border-color: rgba(255, 255, 255, 0.5);
$menu-hover-color: rgba(255, 255, 255, 0.3);
$menu-fg-color: #ccc;
$menu-label-height: 3.5rem;
$menu-icon-color: $menu-fg-color;

ul.appmenu{
	margin:0;
	padding:0;

	a,  a:hover{
		text-decoration: none;
	}

	>li{
		position: relative;

		>a{
			line-height: $menu-label-height;
			height: $menu-label-height;
			display: block;
			color: $menu-fg-color;
			cursor: pointer;
			z-index: 50;
		}

		>a:hover{
			background: $menu-hover-color;
		}

		>.icon{
			position: absolute;
			color: $menu-icon-color;
			height: $menu-label-height;
			line-height: $menu-label-height;
			width: 2rem;
			text-align: center;
		}
	}

	li{
		list-style: none;
	    overflow: hidden;	
	}
}
</style>

<script>
export default {
	props: ["menus", "level"],
	computed: {
		depth(){
			return this.level || 1;
		}
	},
	data (){
		return {
			"deprem": 2.5
		}
	},
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