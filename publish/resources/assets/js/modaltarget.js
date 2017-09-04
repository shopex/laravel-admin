$(function(){
	var loadPage = function(){

	};

	var getModal = function(target){
		try{
			var modal = false;
			var size = 'normal';

			if(target=='#modal-normal'){
				modal = true;
			}else if(target=='#modal'){
				modal = true;
			}else if(target=='#modal-large'){
				modal = true;
				size = 'large';
			}else if(target=='#modal-small'){
				modal = true;
				size = 'small';
			}

			if(modal){

				if(window._modal_component){
					var component = window._modal_component;
				}else{
					var Modal = Vue.component("modal");
					var component = new Modal().$mount();
					$(document.body).append(component.$el);
					window._modal_component = component;
				}

				if(size=='large'){
					component.large = true;
				}else if(size=='small'){
					component.small = true;
				}
				component.loading = true;

				$(component.$el).on('hidden.bs.modal', function (e) {
				  window._modal_component = undefined;
				  $(component.$el).remove();
				})

				$(component.$el).modal({});
				return component;
			}

			return false;
		}catch(error){
			console.info(error);
			return true;
		}
	}

	var modaltarget = function(el){
	  var that = this;
	  $(el).bind('submit', function(e){
	      var el = e.target;
	      if(el.tagName!='FORM'){
	        el = $(el).parents('form');
	        if(el.length==0){
	          return;
	        }
	      }
	      el = $(el);
	      var modal = getModal(el.attr('target'));
	      if(modal){
			e.stopPropagation();
	        e.preventDefault();	

	        $.ajax({
	        	url: href,
	            method: el.attr('method'),
	            data: el.serialize()
	        }).done(function(ret){
	        	modal.loading = false;
	        	$(modal.$refs.body).html(ret);
	        });
	      }
	  });

	  $(el).bind('click', function(e){
	      var el = e.target;
	      if(el.tagName!='A'){
	        el = $(el).parents('a');
	        if(el.length==0){
	          return;
	        }
	      }
	      el = $(el);
	      var href = el.attr('href');
	      var modal = getModal(el.attr('target'));
	      if(modal){
			e.stopPropagation();
	        e.preventDefault();

	        $.ajax({
	        	url: href
	        }).done(function(ret){
	        	modal.loading = false;
	        	$(modal.$refs.body).html(ret);
	        });
	      }
	  });
	}

	modaltarget($('#app'));
});