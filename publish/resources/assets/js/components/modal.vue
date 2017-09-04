<template>
	<div class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog" v-bind:class="{'modal-lg':large, 'modal-sm':small}" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">{{title}}</h4>
	      </div>
	      <div class="modal-body">
	      	  <div ref="body"></div>
		      <div class="loading-box" v-if="loading">
				<div class="loading">
				  <div class="bounce1"></div>
				  <div class="bounce2"></div>
				  <div class="bounce3"></div>
				</div>
			  </div>
	      </div>
	    </div>
	  </div>
	</div>
</template>

<style scoped>
.modal-body{
	min-height: 40vh;
	position: relative;	
}
.loading-box{
	position: absolute;
	left:0;
	bottom:0;
	right:0;
	top:0;
	text-align: center;
	background: rgba(255,255,255,0.3);
	z-index: 999;
}
</style>

<script>
export default {
	data() {
		return {
			title: "",
			loading: false,
			large: false,
			small: false
		}
	},
	methods: {
		show (){
			$(this.el).modal({});
		}
	}
}
</script>