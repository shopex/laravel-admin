<template>
	<div v-bind:class="{unselectable: draging}" class="desktop">

		<div class="sidepanel">
			<h1 class="title">logo</h1>
			<div class="searchbar">
				<searchbar :items="search"></searchbar>
			</div>
			<div class="menus">
				<button @click="open()">start</button>
			</div>
		</div>

		<div class="main">

			<div class="topbar">
				<transition-group name="taskbar" class="taskbar unselectable">
					<div v-for="win in windows"
						class="taskbar-item"					
						:id="'taskbar-'+win.id"
						:class="{active: win.isfocus}"
						:key="win.id">
						<span class="taskbar-item-title"
								@click="show(win.id)"
								@click.middle="close(win.id)"
								@click.right="console.info($event)">
							{{win.title}}
						</span>
						<span class="taskbar-item-split"></span>
					</div>
				</transition-group>
				<div class="icons">icons</div>
				<div class="topbar-shadow"></div>
			</div>

			<div class="workspace" ref="workspace">
				<transition-group name="window">
					<window v-for="win in windows" ref="win" :key="win.id"
						:left="win.left"
						:top="win.top"
						:initwidth="win.width"
						:initheight="win.height"
						:zindex="10+win.zindex"
						:isfocus="win.isfocus"
						:id="win.id"
						@min="win.is_min=true;setLayers()"
						@focus="active(win.id)"
						@close="close(win.id)"
						@dragend="draging=false;active(win.id)"
						@title="onTitleChange"
						@dragstart="draging=true"></window>
				</transition-group>
			</div>
		</div>

		<div class="background"></div>
	</div>
</template>
<style scoped lang="scss">
$topbar-height: 3rem;
$topbar-bg: #fff;
$topbar-active-bg: #f0f0f0;
$sidebar-bg: #002833;
$sidebar-fg: #fff;
$topbar-icons-width: 10rem;

.desktop{
	display: flex;
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;

	.background{
		z-index: -99;
		position: absolute;
		left:0;
		right:0;
		top:0;
		bottom: 0;
	}	
}

.taskbar-enter-active, .taskbar-leave-active {
  transition: opacity .2s
}
.taskbar-enter, .taskbar-leave-to{
  opacity: 0;
}

.window-enter-active, .window-leave-active {
  transition: all .2s;
  z-index: 999;
}
.window-enter, .window-leave-to{
  opacity: 0;
  transform: scale(0.6);
  z-index: 999;
}

.unselectable{
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	cursor: default;
}

.topbar{
	border-bottom: 1px solid #ccc;
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	height: topbar-height;
	background: $topbar-bg;	

	.topbar-shadow{
		position: absolute;
		left: 0;
		top: 0;
		bottom: 0;
		right: 0;
		z-index: -20;
		background: transparent;
		box-shadow:0px 0px 10px rgba(0,0,0,0.3);	
	}
	.taskbar{
		line-height: $topbar-height;
		display: flex;
		margin-right: $topbar-icons-width;
		height: $topbar-height;
	}
	.taskbar-item{
		flex: 10rem 0;
		display: flex;
	}
	.taskbar-item-title{
		flex: 1 1;
		padding: 0 0.5rem;
		overflow: hidden;
		cursor: pointer;
		display: flex;
		align-items: center;
	}
	.active .taskbar-item-title{
		background: $topbar-active-bg;
		cursor: default;
	}
	.taskbar-item-split{
		flex: 1px 0;
		background: #ccc;
		margin: 5px 0;
	}
	.icons{
		position: absolute;
		right: 0;
		top: 0;
		min-width: $topbar-icons-width;
		height: $topbar-height;
		text-align: right;
		line-height: $topbar-height;
		padding-left: 1rem;		
		padding-right: 1rem;
		background: $topbar-bg;
	}
}

.main{
	flex: 1 1;
	position: relative;

	.workspace{
		overflow: hidden;
		position: absolute;
		left: 0;
		right: 0;
		bottom: 0;
		top: $topbar-height;
	}
}

.sidepanel{
	background: $sidebar-bg;
	border-right:1px solid #ccc;
	flex: 20rem 0;
	color: sidebar-fg;
	padding: 8px;
	display: flex;
	flex-direction: column;

	.searchbar input{
		background: rgba(255,255,255, 0.2);
		border: 1px solid rgba(255,255,255, 0.5);
	}

	.title{
		flex: 4rem 0;
	}

	.searchbar{
		flex: 3rem 0;
	}

	.menus{
		flex: 1 1;
	}
}
</style>

<script>
export default {
	props: ["menus", "search"],
	data() {
		return {
			draging: false,
			win_id: 0,
			windows: {},
			layers: []
		}
	},
	mounted(){
		this.$watch('layers', this.setLayers);
	},
	methods: {
		open() {
			var win = {
				id: this.win_id++,
				width: 640,
				height: 480,
				zindex: 0,
				title: "",
				is_min: false
			};

			win.left = 20 + ($(this.$refs.workspace).width() - win.width - 20) * Math.random();
			win.top = 20 + ($(this.$refs.workspace).height() - win.height - 50 - 20) * Math.random();

			this.layers.push(win.id);
			this.$set(this.windows, win.id, win);
		},
		active(id){
			this.layers = this.delete(this.layers, id);
			this.layers.push(id);
		},
		delete(list, item){
			var len = list.length;
			var new_list = [];
			for(var i=0;i<len;i++){
				if(item!=list[i]){
					new_list.push(list[i]);
				}
			}
			return new_list;
		},
		show(id){
			for(var i=0; i<this.$refs.win.length; i++){
				var win = this.$refs.win[i];
				if(win.id==id){
					if(win.is_min){
						this.windows[id].is_min = false;
						win.min_restore();
						break;						
					}else if(this.windows[id].isfocus){
						win.min();
						return;
					}
				}
			}
			this.active(id);
		},
		setLayers() {
			var len = this.layers.length;
			var last_show;
			for(var i=0;i<len;i++){
				this.$set(this.windows[this.layers[i]], 'zindex', i);
				this.$set(this.windows[this.layers[i]], 'isfocus', false);
				if(this.windows[this.layers[i]].is_min==false){
					last_show = this.windows[this.layers[i]];
				}
			}
			if(last_show){
				last_show.isfocus = true;
			}
		},
		close(id){
			this.layers = this.delete(this.layers, id);
			this.$delete(this.windows, id);			
		},
		onTitleChange(id, title){
			this.windows[id].title = title;
		}
	}
}
</script>