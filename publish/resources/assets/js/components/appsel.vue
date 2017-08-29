<template>
<span class="dropdown">
  <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="glyphicon glyphicon-th"></i>
  </a>
  <ul class="dropdown-menu dropdown-menu-right app-sel">
    <li v-for="item in items">
	    <a v-bind:href="item.url">
	    	<img v-bind:src="item.icon" width="48px" />
	    	<div>{{item.label}}</div>
	    </a>
    </li>
  </ul>
</span>
</template>

<style>
.app-sel{
	width:22rem;
	margin:0;
	padding:1rem;
}
.app-sel li{
	float:left;
	margin:0.5rem 0;
}
.app-sel li a{
	text-align: center;
	display: block;
}
.app-sel img{
	width:3rem;
	height:3rem;
}
</style>

<script>
export default {
	props: ["appinfo_url"],
	data () {
		return {
			'items' : []
		}
	},
	mounted (){
		var that = this;
		$.ajax({
			url: this.appinfo_url,
		}).done(function(rsp){
			that.items = rsp;
		})
	}
}
</script>