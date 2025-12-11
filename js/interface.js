function gotoURL(url){
	if (!url) url = "/";
	if (window.event){
		var src = window.event.srcElement;
		if((src.tagName != 'A') && ((src.tagName != 'IMG') || (src.parentElement.tagName != 'A'))){
			if (window.event.shiftKey) window.open(url);
			else document.location = url;
		}
	} else document.location = url;
}
function is_array(obj) {
   if (obj.constructor.toString().indexOf("Array") == -1)
      return false;
   else
      return true;
}

$.fn.AdminSelect = function(type) {
	type = type || "fx";
    return this.queue(type, function() {
    	var sel = $(this),
    	options = sel.children('option'),
    	id = sel.attr('id'),
    	name = sel.attr('name'),
    	disabled = sel.attr('disabled'),
    	readonly = sel.attr('readonly'),
    	val = sel.val(),
    	curr_title = sel.find('option[value="'+ val +'"]').text(), items = '';
    	
    	sel.after('<input type="hidden" id="'+ id +'" name="'+ name +'" data-inp="#bootstrap-sel-'+ id +'" value="'+ val +'" />');
    	options.each(function(){
    		items += '<li><a href="#bootstrap-sel-'+ id +'" data-value="'+ $(this).attr('value') +'">'+ $(this).text() +'</a></li>';
    	});
    	sel.after('\
			<div data-id="bootstrap-select-'+ id +'" class="btn-group admin-sel">\
				<button class="btn btn-small no-click">'+ curr_title +'</button>\
				<button class="btn btn-small dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>\
				<ul class="dropdown-menu">\
					'+ items +'\
				</ul>\
			</div>\
    	');
    	
    	$('div.btn-group[data-id="bootstrap-select-'+ id +'"] .dropdown-menu a').bind('click', function(){
			var inp_sel = $(this).attr('href'), val = $(this).attr('data-value'), title = $(this).text();
			$('div.btn-group[data-id="bootstrap-select-'+ id +'"] button.no-click').text(title);
			$('input[type="hidden"][data-inp="'+ inp_sel +'"]').val(val);
		});
    	
    	sel.remove();
        $(this).dequeue();
    });
};

$.fn.Watermark = function(txt, type) {
	type = type || "fx";
	txt = txt || 'Something text';
    return this.queue(type, function() {
    	if($(this).val() == '')
    		$(this).val(txt);
        $(this).blur(function(){
        	if($(this).val() == ''){
				$(this).val(txt);
			}
        });
        $(this).focus(function(){
        	if($(this).val() == txt){
				$(this).val('');
			}
        });
        $(this).dequeue();
    });
};
$.fn.resizableInp = function( type ) {
	type = type || "fx";
    return this.queue(type, function() {
    	var old_strlen = $(this).val().length;
    	$(this).keyup(function(eventObject){
	    	strlen_val = $(this).val().length;
			if(strlen_val !== old_strlen && strlen_val < 255 && strlen_val > 1)
			{
				$(this).width(strlen_val * 7);
				old_strlen = strlen_val;
			}
			else
			{
				$(this).width(14);
			}
		});
        $(this).dequeue();
    });
};
$.fn.autocompleteInp = function( values, notfnd_txt, type ) {
	values = values || [];
	notfnd_txt = notfnd_txt || 'Matches not found';
	type = type || "fx";
    return this.queue(type, function() {
    	$(this).focusin(function(){
    		$(this).focus();
    		$(this).keyup();
    	});
    	$(this).keyup(function(eventObject){
    		var val = $(this).val();
    		var matches = [];
    		var cont = $(this).parents('.select-cont .custom-select-cont');
    		cont.children('.custom-option').children('ul').html('');
    		$(this).parents('.select-cont').addClass('expand');
    		k = 0;
	    	for(i = 0; i < values.length; i++)
	    	{
	    		str = new String(values[i]);
	    		part = str.substring(0, val.length);
	    		if(part == val){
	    			matches[k] = values[i];
	    			k++;
	    		}
	    	}
	    	cont.children('.custom-option').show();
	    	if(matches.length > 0)
		    	for(i = 0; i < matches.length; i++)
		    	{
		    		cont.children('.custom-option').children('ul').append('<li>'+ matches[i] +'</li>');
		    	}
	    	else
	    		cont.children('.custom-option').children('ul').append('<li class="message">'+ notfnd_txt +'</li>');
		});
		
        $(this).dequeue();
    });
};
$.fn.Paginator = function( link, values, empty_txt, notfnd_txt, type ) {
	link = link || '';
	values = values || [];
	notfnd_txt = notfnd_txt || 'Pages not found';
	empty_txt = empty_txt || 'Matches not found';
	type = type || "fx";
    return this.queue(type, function() {
    	var paginator = $(this);
    	var object_id = $(this).attr('id');
    	var inp = $(this).find('#'+ object_id + '_val');
    	var cont_item = $(this).find('.custom-option ul');
    	var item = $(this).find('.custom-option li:not(.message)');
    	var old_val = $(this).val();
    	var old_strlen = $(this).val().length;
    	var button = $(this).find('.exp-butt');
    	var custom_option = $(this).find('.custom-option');
    	if(inp.val() == '')
    		inp.val(empty_txt);
    		
    	paginator.hover( function(){
    		$(this).addClass('hover');
    	}, function(){
    		$(this).removeClass('hover');
    		setTimeout('hidePaginator("'+ $(this).attr('id') +'")', 2000)
    	});
    		
    	inp.keyup(function(eventObject){
    		if(eventObject.keyCode == 13)
			{
				gotoURL(link + $(this).val());
				return false;
			}
	    	var strlen_val = $(this).val().length;
			if(strlen_val !== old_strlen && strlen_val < 255 && strlen_val > 1)
			{
				$(this).width(strlen_val * 7);
				old_strlen = strlen_val;
			}
			else
			{
				$(this).width(14);
			}
			
			
			cont_item.html('');
			var val = $(this).val();
    		var matches = [];
    		k = 0;
	    	for(i = 0; i < values.length; i++)
	    	{
	    		str = new String(values[i]);
	    		part = str.substring(0, val.length);
	    		if(part == val){
	    			matches[k] = values[i];
	    			k++;
	    		}
	    	}
	    	custom_option.show();
	    	if(matches.length > 0)
		    	for(i = 0; i < matches.length; i++)
		    	{
		    		cont_item.append('<li>'+ matches[i] +'</li>');
		    	}
	    	else
	    		cont_item.append('<li class="message">'+ notfnd_txt +'</li>');
		}).focus(function(){
        	if($(this).val() == empty_txt || $(this).val() == ''){
        		paginator.addClass('expand');
				$(this).val('');
				cont_item.html('');
				for(i = 0; i < values.length; i++)
		    	{
		    		cont_item.append('<li>'+ values[i] +'</li>');
		    	}
		    	custom_option.show();
			}
        }).blur(function(){
        	if($(this).val() == ''){
				$(this).val(empty_txt);
			}
        });
		
		button.click(function(){
			custom_option.toggle();
			if(custom_option.is(':visible'))
			{
				old_val = inp.val();
				inp.val('');
				inp.focus();
			}
			else
			{
				inp.val(old_val);
				paginator.removeClass('expand');
			}

			return false;		
		});
		
		item.live({
			mouseenter: function(){
				$(this).addClass('act');
			},
			mouseleave: function(){
				$(this).removeClass('act');
			},
			click: function(){
				gotoURL(link + $(this).text());
			}
		});
		
        $(this).dequeue();
    });
};

$.fn.wait = function(time, type) {
	time = time || 1000;
    type = type || "fx";
    return this.queue(type, function() {
    	var self = this;
        setTimeout(function() {
        	$(self).dequeue();
		}, time);
	});
};

function hidePaginator(paginator_id)
{
	var paginator = $('#'+ paginator_id);
	if(!paginator.hasClass('hover'))
	{
		paginator.find('.custom-option').hide();
		paginator.removeClass('expand');
		paginator.find('#'+ paginator_id + '_val').blur();
	}
	return true;
}