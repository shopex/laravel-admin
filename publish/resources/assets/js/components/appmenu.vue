<template>
  <ul class="appmenu">
	<li v-for="(item,i) in menus">
		<i v-if="item.items && item.open" class="glyphicon glyphicon-menu-down"></i>
		<i v-if="item.items && !item.open" class="glyphicon glyphicon-menu-right"></i>
		<a v-if="item.items" v-on:click="toggle(i, $event)">{{item.label}}</a>
		<a v-else v-bind:href="item.link" target="window">{{item.label}}</a>
		<ul v-if="item.items" v-show="item.open">
			<li v-for="item in item.items">
				<a v-if="item.label" v-bind:href="item.link" target="window">{{item.label}}</a>
			</li>
		</ul>
	</li>
  </ul>
</template>

<style scope lang="scss">

$menu-border-color: rgba(255, 255, 255, 0.5);
$menu-hover-color: rgba(255, 255, 255, 0.3);
$menu-fg-color: #ccc;
$menu-label-height: 3.5rem;
$menu-item-height: 2.5rem;

ul.appmenu{
	margin:0;
	padding:0;
	border-top:1px solid $menu-border-color;

	a,  a:hover{
		text-decoration: none;
	}

	>li{
		border-bottom:1px solid $menu-border-color;

		>a{
			padding-left: 2rem;
			line-height: $menu-label-height;
			height: $menu-label-height;
			display: block;
			color: $menu-fg-color;
			cursor: pointer;
			z-index: 50;
		}

		>a:hover, >ul>li>a:hover{
			background: $menu-hover-color;
		}

		>ul{
			margin:0;
			padding:0;
			border-top:1px solid $menu-border-color;
			z-index: 0;
		}

		>i{
			float: right;
			line-height: $menu-label-height;
			margin-right:1.5rem;
		}

		>ul>li>a{
			display: block;
			padding: 0 0 0 3rem;
			cursor: pointer;
			color: $menu-fg-color;
			height: $menu-item-height;
			line-height: $menu-item-height;
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