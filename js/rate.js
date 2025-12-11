$.fn.rateSelect = function(id, type ) {
	var id = id;
	var name = name;
	type = type || "fx";
    return this.queue(type, function() {
    	select = $('select#'+ id);
    	select.children('option').each(function(){
    		if($(this).val() !== '')
    			select.before('<img src="http://diti.by/images/star_disable.png" class="rateSelect_'+ id +'" alt="'+ $(this).val() +'" />');
    	});
    	
    	if(select.val() !== '')
    	{
    		$('.rateSelect_'+ id +'[alt="'+ select.val() +'"]').attr('src', 'http://diti.by/images/star_enable.png');
    		$('.rateSelect_'+ id +'[alt="'+ select.val() +'"]').prevAll('img').attr('src', 'http://diti.by/images/star_enable.png');
    	}
    	
    	$('.rateSelect_'+ id).live('click', function(){ 
    		select.val($(this).attr('alt'));
    		$(this).attr('src', 'http://diti.by/images/star_enable.png');
    		$(this).prevAll('img').attr('src', 'http://diti.by/images/star_enable.png');
    		$(this).nextAll('img').attr('src', 'http://diti.by/images/star_disable.png');
    	});
    	
    	$('.rateSelect_'+ id).live({
    		mouseenter: function(){ 
	    		$(this).attr('src', 'http://diti.by/images/star_enable.png');
	    		$(this).prevAll('img').attr('src', 'http://diti.by/images/star_enable.png');
	    		$(this).nextAll('img').attr('src', 'http://diti.by/images/star_disable.png');
    		}
    	});
    	
    	select.parent().bind({
    		mouseleave: function(){
    			if(select.val() == ''){
    				$('.rateSelect_'+ id).attr('src', 'http://diti.by/images/star_disable.png');
    				return true;
    			}
    			star_c = $('.rateSelect_'+ id +'[alt="'+ select.val() +'"]').attr('src', 'http://diti.by/images/star_enable.png');
    			star_c.prevAll('img').attr('src', 'http://diti.by/images/star_enable.png');
    			star_c.nextAll('img').attr('src', 'http://diti.by/images/star_disable.png');
    		}
    	});
    	select.hide();
        $(this).dequeue();
    });
};