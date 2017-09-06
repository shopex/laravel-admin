<template>
	<div class="modal" tabindex="-1" role="dialog">
	  <div class="modal-dialog" v-bind:class="{'modal-lg':large, 'modal-sm':small}" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">{{title}}</h4>
	      </div>
	      <div class="modal-body" v-bind:style="{'min-height': minH}">
	      	  <div ref="body"></div>
		      <div class="loading-box" v-if="loading">
				<div class="loading">
				  <div class="bounce1"></div>
				  <div class="bounce2"></div>
				  <div class="bounce3"></div>
				</div>
			  </div>
	      </div>
	      <div v-if="confirmMode" class="modal-footer" style="text-align:center">
	      	<button class="btn btn-default btn-lg" data-dismiss="modal">取消</button>
	      	<button class="btn btn-default btn-lg" @click="confirm($event)">确定</button>
	      </div>
	    </div>
	  </div>
	</div>
</template>

<style scoped>
.modal-body{
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
			minH: '20vh',
			confirmMode: false,
			title: "",
			loading: false,
			large: false,
			small: false
		}
	},
	methods: {
		show (){
			$(this.el).modal({});
		},
		confirm(ev){
			ev.stopPropagation();
	        ev.preventDefault();			
			$(this.$el).trigger('confirm');
		}
	}
}
</script>