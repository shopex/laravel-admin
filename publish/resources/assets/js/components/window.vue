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
				<div class="w-pin" 
					:class="{active: is_pin && !is_max}"
					@click="is_pin=!is_pin">
					<i class="glyphicon glyphicon-pushpin"></i>
				</div>
				<div class="w-icons" 
					:class="{deactive: !icon_hover}"
					@mouseover="icon_hover=true" 
					@mouseout="icon_hover=false">
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

.w-body >>> .error-page{
	position: absolute;
	left: 0;
	right: 0;
	bottom: 0;
	top: 0;
	overflow: auto;
}

.w-body >>> .error-page h1{
	font-size: 3rem;
}
.w-body >>> .error-page h2{
	font-size: 2rem;
}
.w-body >>> .error-page h3{
	font-size: 1.5rem;
}
.w-body >>> .error-page svg{
	display: none;
}

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
.w-box.focus{
	box-shadow:0px 0px 25px rgba(0,0,0,0.3);
}
.w-box.is_max{
	border-radius: 0;
	border-top: 1px solid #ccc;
	box-shadow: none;
	border-left: 1px solid #ccc;
}
.w-body{
	background: #fff;
	position: relative;
}
.w-title{
	line-height: 2.5rem;
	padding-left: 0.5rem;
	flex:5rem 1;
	font-weight: bold;
}
.w-icons{
	flex:4rem 0;
	margin-left: 1rem;
	padding-left: 1rem;
	text-align: right;
	line-height: 2.5rem;
	padding-right:1rem;
	white-space: pre;
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
.w-pin{
	flex:1rem 0;
	line-height: 2.5rem;
	cursor: pointer;
}
.w-pin i{
	color: #ccc;
}
.w-pin.active i{
	color: red;
	text-shadow:2px 2px 3px rgba(0,0,0,0.5);	
}
.focus .w-head{
	color: #000;
}
.w-icons i{
	background: #ccc;
	border-radius: 100%;
	font-size: 0.3rem;
	display: inline-block;
	color: #333;
	width: 1rem;
	height: 1rem;
}
.w-icons i::before{
	content: '';
}
.w-icons .ico-min, .w-icons .ico-max, .w-icons .ico-close{
	cursor: pointer;
}
.focus .w-icons .ico-min{
	background: #ffbe2e;
}
.focus .w-icons .ico-max{
	background: #2bca41;
}
.focus .w-icons .ico-close{
	background: #ff6058;
}
.is_max .w-icons .ico-min,.is_max  .w-icons .ico-max,.is_max  .w-icons .ico-close{
	background: #ccc;
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
	z-index: 999;
}
.w-sider-b{
	position: absolute;
	left:0;
	right:10px;
	bottom:0;
	height:5px;	
	/*background: green;*/
	cursor: ns-resize;
	z-index: 999;
}
.w-sider-rb{
	position: absolute;
	right:0;
	bottom:0;
	height:10px;
	width:10px;
/*	background: blue;*/
	cursor: nwse-resize;
	z-index: 999;
}
</style>

<script>
export default {
	props: ["left", "top", "zindex", "isfocus", 
			"initwidth", "initheight", "name",
			"id", "initurl", "initmax"],
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
			is_pin: false,
			url: "",
			icon_hover: false,
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

		this.is_max = this.initmax;

		this.width = this.initwidth;
		this.height = this.initheight;

		this.win.on('click', this.onFocus);
		this.win.on('focus', this.onFocus);
		this.$watch('title', function(){
			that.$emit('title', that.id, that.title);
		});
		this.$watch('is_pin', function(){
			that.$emit('pin', that.id, that.is_pin);
		});
		this.$watch('is_max', function(){
			that.$emit('max', that.id, that.is_max);
		});
		this.$watch('is_min', function(){
			that.$emit('min', that.id, that.is_min);
		});

		this.title = "title";
		if(this.initurl){
			this.load(this.initurl);
			this.url = this.initurl;
		}

		this.$watch('url', function(){
			that.$emit('url', that.id, that.url);
		});

		$(window).on('resize', this.on_resize);

		if(this.is_max){
			this.max();
		}
	},
	methods: {
		link_action(ev){
			var el = this.find_el(ev.target, 'A', 3);
			if($(el).hasClass('external')){
				return;
			}
			if(el && !$(el).attr('target')){
				var url = $(el).attr('href');
				if(url){
					ev.stopPropagation();
			        ev.preventDefault();
					this.load(url);
				}
			}
		},
		form_action(ev){
			var el = $(ev.target);
			if(el.hasClass('external')){
				return;
			}

			var url = el.attr('action') || this.url;
			ev.stopPropagation();
	        ev.preventDefault();
			this.load(url, {
				method: el.attr('method') || "POST",
				data: el.serialize()
			});	        
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
		load(url, options){
			var that = this;
			options = options || {};
			options.success = function(data){
					var el = $('<div></div>').html(data);
					that.title = $('meta[name="page-title"]', el).attr('content');
					$('meta[name="page-title"]').attr('content', $('meta[name="csrf-token"]', el).attr('content'));

					var content = $('.main-content', el);
					var scripts = $('script', content).remove();
					that.body.empty().append(content);

					(function(){

						var $ = function(s,c,r){
							if(s == window.document){
								s = content;
							}
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
						for(var i in window.Vue){
							Vue[i] = window.Vue[i];
						}
						try{
							for(var i=0; i<scripts.length; i++){
								if(scripts[i].getAttribute('src')){
									content.append(scripts[i]);
								}else{
									eval(scripts[i].innerHTML);
								}
							}
						}catch(e){
							console && console.error(scripts[i].innerHTML);
						}
					})();
				};
			options.error = function(rsp){
				if(rsp.readyState==4){
					var el = $('<div class="error-page"></div>').html(rsp.responseText);
					var content = $('.container' , el);
					el.empty().append(content);
					that.body.empty().append(el);
				}
			}
			options.method = options.method || "GET";
			options.url = url;
			$.ajax(options);
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
				that.full();
			});
		},
		full(){
			this.win.css('left', 0);
			this.win.css('top', 0);
			this.width = $(this.$el).width();
			this.height = $(this.$el).height() - $(this.$refs.title).height();
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
			if(this.is_max){
				return;
			}
			this.masker_sync();
			var that = this;
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
			if(this.is_max){
				this.is_min = false;
				return;
			}
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
			if(this.is_max){
				return;
			}
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
		},
		on_resize(e){
			if(this.is_max){
				this.full();
			}
		}
	}
}
</script>