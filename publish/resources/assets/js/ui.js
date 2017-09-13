require('./modaltarget');

Vue.component('searchbar', require('./components/searchbar'));
Vue.component('appsel', require('./components/appsel'));
Vue.component('filters', require('./components/filters'));
Vue.component('finder', require('./components/finder'));
Vue.component('appmenu', require('./components/appmenu'));
Vue.component('modal', require('./components/modal'));
Vue.component('objectinput', require('./components/objectinput'));

$(function(){
	new Vue({
		el: '.admin-header',
		data :{
			searchbar: searchbar,
			appinfo_url: window.appinfo_url
		}
	})

	new Vue({
		el: '.admin-sidebar',
		data :{
			menus: window.menus
		}
	})
});