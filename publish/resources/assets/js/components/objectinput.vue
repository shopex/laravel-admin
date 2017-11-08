<template>
	<a href="#" class="outter" @click="open($event)" v-bind:class="{multiple: is_multiple}">
			<transition-group name="fade" v-if="first_loaded">
			  <span class="label" 
			  		v-bind:class="{'label-primary':!item.remove, 'label-danger':item.remove}"
			  		v-bind:key="item.value"
			  		v-for="(item, i) in values">
					{{item.label}}
					<i  @mouseover="$set(item,'remove',true)"
						@mouseout="$set(item,'remove',false)"
						@click="remove(i,$event)"
						class="glyphicon glyphicon-remove"></i>
			  </span>
			  </transition-group>
	      		<div class="loading" v-else>
				  <div class="bounce1"></div>
				  <div class="bounce2"></div>
				  <div class="bounce3"></div>
				</div>

			  <input type="hidden" :name="name" :value="return_value" />

		<div class="modal" tabindex="-1" role="dialog" ref="modal">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<div class="input-finder-pager">
					<div ref="finderPager"></div>
				</div>
		        <h4 class="modal-title">请选择</h4>
		      </div>
		      <div class="modal-body">
		      	<div ref="finderHeader"></div>
		      	<div ref="finderContent">
		      		<div class="loading">
					  <div class="bounce1"></div>
					  <div class="bounce2"></div>
					  <div class="bounce3"></div>
					</div>
		      	</div>
		      </div>
		      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
			        <button type="button" class="btn btn-primary" @click="confirm">确定</button>
		      </div>
		    </div>
		  </div>
		</div>

	</a>
</template>

<style scoped>
.outter{
	display: inline-block;
	border:1px solid #ccc; 
	min-width:20rem;
	min-height:2.8rem;
	cursor: pointer;
	white-space: normal;
	line-height: 1.5rem;
	text-decoration: none;
	display: flex;
	align-items: center;
	padding-left:5px;
}
.outter:hover{
	text-decoration: none;	
}
.outter .label{
	cursor: default;
	margin:5px 0 5px 5px;
	display: inline-block;
}
.outter .label>.glyphicon{
	cursor: pointer;
}
.outter .caret{
	float: right;
	margin-top:1rem;
}
.outter .loading{
	position: absolute;
	margin:0;
	padding:0;
	transform: scale(0.35);
	display: inline-block;
}
.outter .loading > div{
	background-color: #ccc;
}
.outter .handle{
	margin-right:1rem;
}
.modal-footer{
	text-align: center;
}
.modal-body{
	padding:0;
	min-height: 30vh;	
}
.multiple .fade-enter-active,.multiple .fade-leave-active {
  transition: opacity .3s
}
.multiple .fade-enter,.multiple .fade-leave-to{
  opacity: 0
}
.input-finder-pager{
	position: absolute;
	right: 5rem;
	top: 1rem;
}
</style>

<script>
export default {
	props: ["name", "value", "multiple", "type", "filters"],
	mounted(){
		var that = this;
		this.baseurl = $('meta[name="admin-baseurl"]').attr('content')+'/admin/component/objectinput/'+this.type;
		$(this.$refs.modal).on('show.bs.modal', function () {
			that.load();
		});
		if(this.value){
			this.sync();
		}
	},
	computed: {
		return_value(){
			if(!this.first_loaded){
				return this.value;
			}
			var ret = [];
			$(this.values).each(function(i, v){
				ret.push(v.value);
			});
			return ret.join(",")
		},
		is_multiple(){
			return this.multiple!=undefined;
		},
		values_map(){
			var ret = {};
			$(this.values).each(function(i, v){
				ret[v.value] = true;
			});
			return ret;
		}
	},
	methods: {
		open(ev){
			ev.stopPropagation();
			ev.preventDefault();
			$(this.$refs.modal).modal({});
		},
		remove(i, ev){
	      	ev.stopPropagation();
	      	ev.preventDefault();
			this.$delete(this.values, i);
		},
		sync(){
			var that = this;
			$.ajax({
				url: this.baseurl,
				data: 'finder_request=sync&values='+this.value,
				success (rsp){
					that.values = rsp;
					that.first_loaded = true;
				}
			});
		},
		load (){
			var that = this;
			if(this.finder){
				this.checkbox = [];
				this.v_select_all = false;				
				this.finder.reload();
			}else if(!this.loading){
				$(document.body).append(this.$refs.modal);
				$.ajax({
					url: this.baseurl,
					complete (){
						that.loading = false;
					},
					success (rsp){
						var Finder = Vue.component("finder");
						that.finder = new Finder({
							data: {
								finder: rsp,
								select_mode: (that.is_multiple?'multi':'single')
							}
						}).$mount();

						$(that.$refs.finderHeader).replaceWith(that.finder.$refs.header);
						$(that.$refs.finderContent).replaceWith(that.finder.$refs.content);
						$(that.$refs.finderPager).replaceWith(that.finder.$refs.pager);
					}
				});
				that.loading = true;
			}
		},
		confirm (){
			$(this.$refs.modal).modal('hide');
			if(!this.finder){
				return;
			}
			if(this.is_multiple){
				var map = this.values_map;
				for(var i=0;i<this.finder.checked_result.length;i++){
					if(!map[this.finder.checked_result[i].value]){
						this.values.push(this.finder.checked_result[i]);
					}
				}
			}else{
				this.values = [
					{
						value: this.finder.radio,
						label: this.finder.radio_label
					}
				]
			}
		}
	},
	data (){
		return {
			first_loaded: false,
			values: [],
			baseurl: '',
			loading: false,
			finder: undefined
		}
	}
}
</script>