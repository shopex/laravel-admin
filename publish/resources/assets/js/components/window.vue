<template>
	<div class="w-ground" ref="ground">
		<div class="w-box" ref="win" 
			v-show="is_min!=true"
			v-bind:style="{'z-index': zindex}"
			v-bind:class="{focus: isfocus, is_max: is_max}">
			<div class="w-head" ref="title">
				<div class="w-title" ref="handle" 
					@dblclick="dblclick()"
					@mousedown="start_drag($event, 1)">
					{{title}}
				</div>
				<div class="w-icons">
					<i class="ico-min glyphicon glyphicon-minus" @click="min()"></i>
					<i v-if="is_max" @click="normal()" class="ico-max glyphicon glyphicon-unchecked"></i>
					<i v-else @click="max()" class="ico-max glyphicon glyphicon-unchecked"></i>					
					<i v-if="closeAble" @click="$emit('close')" class="ico-close glyphicon glyphicon-remove"></i>
				</div>
			</div>
			<div class="w-body" ref="body" 
			 	@click="link_action" 
			 	@submit="form_action"
				v-bind:style="{
					width: width+'px',
					height: height+'px'
				}">
			</div>
			<div v-if="resizeAble" class="w-sider-r" @mousedown="start_drag($event, 2)"></div>
			<div v-if="resizeAble" class="w-sider-b" @mousedown="start_drag($event, 3)"></div>
			<div v-if="resizeAble" class="w-sider-rb" @mousedown="start_drag($event, 4)"></div>
		</div>
		<div class="w-mask" ref="masker" style="display:none"></div>
	</div>
</template>

<style scoped>
.w-ground{
	position: absolute;
	left:0;
	top:0;
	bottom: 0;
	right: 0;
}
.w-box{
	background: #f0f0f0;
	position: absolute;
	border-radius: 5px;
	box-shadow:0px 0px 8px rgba(0,0,0,0.5);
}
.w-box.is_max{
	border-radius: 0;
	border-top: 1px solid #ccc;
}
.w-box.focus{
	box-shadow:0px 0px 25px rgba(0,0,0,0.3);
}
.w-body{
	background: #fff;
	position: relative;
}
.w-title{
	line-height: 2.5rem;
	padding-left: 0.5rem;
	flex:1 1;
	font-weight: bold;
}
.w-icons{
	flex:8rem 0;
	text-align: right;
	line-height: 2.5rem;
	padding-right:1rem;
}
.w-head{
	display: flex;
	color: #999;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;		
}
.focus .w-head{
	color: #000;
}
.w-icons .ico-min, .w-icons .ico-max, .w-icons .ico-close{
	cursor: pointer;
}
.focus .w-icons .ico-min{
	color: #ffbe2e;
}
.focus .w-icons .ico-max{
	color: #2bca41;
}
.focus .w-icons .ico-close{
	color: #ff6058;
}
.is_max .w-icons .ico-min,.is_max  .w-icons .ico-max,.is_max  .w-icons .ico-close{
	color: #ccc;
}
.w-mask{
	position: absolute;
	border: 3px solid #ccc;
	border-radius: 5px;
	z-index: 9999;
	top:0;
	left:0;
}
.w-sider-r{
	position: absolute;
	top:0;
	right:0;
	bottom:10px;
	width:5px;
	/*background: red;*/
	cursor: ew-resize;
}
.w-sider-b{
	position: absolute;
	left:0;
	right:10px;
	bottom:0;
	height:5px;	
	/*background: green;*/
	cursor: ns-resize;
}
.w-sider-rb{
	position: absolute;
	right:0;
	bottom:0;
	height:10px;
	width:10px;
/*	background: blue;*/
	cursor: nwse-resize;
}
</style>

<script>
export default {
	props: ["left", "top", "zindex", "isfocus", "initwidth", "initheight", "id", "url"],
	data() {
		return {
			width: 640,
			height: 480,
			minWidth: 400,
			minHeight: 300,
			resizeAble: true,
			closeAble: true,
			is_model: false,
			is_max: false,
			is_min: false,
			title: "",
			child: [],
			draging: {},
			normal_stat: {}
		}
	},
	mounted(){
		var that = this;
		this.win = $(this.$refs.win);
		this.masker = $(this.$refs.masker);
		this.body = $(this.$refs.body);
		this.ground = $(this.$refs.ground);
		this.win.css('left', this.left);
		this.win.css('top', this.top);

		this.width = this.initwidth;
		this.height = this.initheight;

		this.win.on('click', this.onFocus);
		this.win.on('focus', this.onFocus);
		this.$watch('title', function(){
			that.$emit('title', that.id, that.title);
		});

		this.title = "title";
		if(this.url){
			this.load(this.url);
		}
	},
	methods: {
		link_action(ev){
			var el = this.find_el(ev.target, 'A', 3);
			if(el && (!$(el).attr('target') || $(el).attr('target')=='window')){
				var url = $(el).attr('href');
				if(url){
					ev.stopPropagation();
			        ev.preventDefault();
					this.load(url);
				}
			}
		},
		form_action(ev){
			console.info(ev);
			ev.stopPropagation();
	        ev.preventDefault();
		},
		find_el(el, tag, n){
			if(el.tagName==tag){
				return el;
			}else if(n!=0){
				return this.find_el(el.parentNode, tag, n-1);
			}else{
				return false;
			}
		},		
		load(url){
			var that = this;
			$.ajax({
				url: url,
				method: 'get',
				success: function(data){
					var el = $('<div></div>').html(data);
					var content = $('.main-content', el);
					var scripts = $('script', content).remove();
					that.title = $('title', el).text();
					that.body.empty().append(content);

					(function(){

						var $ = function(s,c,r){
							c = c || that.$refs.body;
							return jQuery.fn.init(s, c, r);
						}

						var Vue = function(options){
							if(typeof options.el == 'string'){
								options.el = $(options.el, that.$refs.body)[0];
							}
							return new window.Vue(options);
						}
						Vue.prototype=window.Vue;

						for(var i=0; i<scripts.length; i++){
							eval(scripts[i].innerHTML);
						}
					})();
				}
			});
		},
		max(){
			var that = this;
			this.masker_sync();
			this.masker.show().animate({
				left: 0,
				top: 0,
				width: that.ground.width(),
				height: that.ground.height()
			}, 80 ,function(){
				that.masker.hide();				
				that.is_max = true;
				var pos = that.win.position();
				that.normal_stat = {
					left: that.win.css('left'),
					top: that.win.css('top'),				
					width: that.width,
					height: that.height
				};
				that.win.css('left', 0);
				that.win.css('top', 0);
				that.width = $(that.$el).width();
				that.height = $(that.$el).height() - $(that.$refs.title).height();
			});
		},
		taskbarOffset(){
			var taskbar = $('#taskbar-'+this.id);
			if(taskbar.length>0){
				var offset = taskbar.offset();
				offset.width = taskbar.width();
				offset.left -= this.ground.offset().left;
				offset.height = 0;				
			}else{
				var offset = {left: 0, top: 0};
				offset.width = 0;
				offset.height = 0;
			}
			return offset;
		},
		min(){
			this.is_min = true;
			this.masker_sync();

			var that = this;

			this.$emit('min');
			var offset = this.taskbarOffset();
			this.masker.show().animate({
				left: offset.left,
				top: offset.top,
				width: offset.width,
				height: offset.height
			}, 80, function(){
				that.masker.hide();
			});

		},
		normal(){
			var that = this;
			this.masker_sync();
			this.masker.show().animate({
				left: that.normal_stat.left,
				top: that.normal_stat.top,
				width: that.normal_stat.width,
				height: that.normal_stat.height
			}, 50 ,function(){
				that.masker.hide();
				that.win.css('left', that.normal_stat.left);
				that.win.css('top', that.normal_stat.top);
				that.width = that.normal_stat.width;
				that.height = that.normal_stat.height;
				that.is_max = false;
			});
		},
		min_restore(){
			var that = this;
			var offset = this.taskbarOffset();
			this.masker.css('left', offset.left)
					   .css('top', offset.top)
					   .css('width', offset.width)
					   .css('height', offset.height);

			this.masker.show().animate({
				left: this.win.css('left'),
				top: this.win.css('top'),
				width: this.win.width(),
				height: this.win.height()
			}, 50 ,function(){
				that.masker.hide();
				that.is_min = false;
			});

		},
		masker_sync() {
			this.masker.height(this.win.height());
			this.masker.width(this.win.width());
			this.masker.css('top', this.win.css('top'));
			this.masker.css('left', this.win.css('left'));
			return this.masker;
		},
		dblclick(){
			if(this.is_max){
				this.normal();
			}else{
				this.max();
			}
		},
		start_drag(e, type) {
			$(window).on('mousemove', this.on_mousemove);
			$(window).on('mouseup', this.on_mouseup);
			this.draging = {
				startX: e.pageX,
				startY: e.pageY,
				startOffset: this.win.offset(),
				startWidth: this.win.width(),
				startHeight: this.win.height(),
				type: type
			}
		},
		onFocus(){
			this.$emit('focus');
		},
		on_mousemove(e){
			if(this.draging.working){
				if(this.draging.type==1){
					this.draging.currentOffset = {left: this.draging.startOffset.left + (e.pageX - this.draging.startX),
								top: this.draging.startOffset.top + (e.pageY - this.draging.startY)};
					this.masker.offset(this.draging.currentOffset);
				}else if(this.draging.type==2){
					if(this.draging.startWidth + (e.pageX - this.draging.startX) > this.minWidth){
						this.draging.currentWidth = this.draging.startWidth + (e.pageX - this.draging.startX);
						this.masker.width(this.draging.currentWidth);
					}
				}else if(this.draging.type==3){
					if(this.draging.startHeight + (e.pageY - this.draging.startY) > this.minHeight){					
						this.draging.currentHeight = this.draging.startHeight + (e.pageY - this.draging.startY);
						this.masker.height(this.draging.currentHeight);
					}
				}else if(this.draging.type==4){
					if(this.draging.startWidth + (e.pageX - this.draging.startX) > this.minWidth){
						this.draging.currentWidth = this.draging.startWidth + (e.pageX - this.draging.startX);
						this.masker.width(this.draging.currentWidth);
					}
					if(this.draging.startHeight + (e.pageY - this.draging.startY) > this.minHeight){					
						this.draging.currentHeight = this.draging.startHeight + (e.pageY - this.draging.startY);
						this.masker.height(this.draging.currentHeight);
					}
				}
			}else{
				if(Math.abs(e.pageX - this.draging.startX) >= 3 || 
						Math.abs(e.pageY - this.draging.startY) >= 3){
					this.masker_sync();
					this.masker.show();
					this.$emit('dragstart');
					this.draging.working = true;
				}
			}
		},
		on_mouseup(e){
			$(window).off('mousemove', this.on_mousemove);
			$(window).off('mouseup', this.on_mouseup);
			this.masker.hide();
			if(this.draging.working){
				if(this.draging.type==1){
					if(this.draging.currentOffset){
						this.win.offset(this.draging.currentOffset);
					}
				}else if(this.draging.type==2){
					if(this.draging.currentWidth){
						this.width = this.draging.currentWidth;
					}
				}else if(this.draging.type==3){
					if(this.draging.currentHeight){
						this.height = this.draging.currentHeight - $(this.$refs.title).height();
					}
				}else if(this.draging.type==4){
					if(this.draging.currentWidth){
						this.width = this.draging.currentWidth;
						this.height = this.draging.currentHeight - $(this.$refs.title).height();
					}
				}
			}
			this.draging = {};
			this.$emit('dragend');	
		}
	}
}
</script>