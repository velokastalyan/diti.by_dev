<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
    <title><? echo $PAGE_TITLE; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <? header("Content-Type: text/html; charset=utf-8"); ?>
	<link rel="stylesheet" href="<? echo $CSS; ?>admin.core.css" type="text/css" />
	<link rel="stylesheet" href="<? echo $CSS; ?>admin.navigator.css" type="text/css" />
	<link rel="stylesheet" href="<? echo $JS; ?>jquery/admin.ui/css/smoothness/jquery-ui-1.8.15.custom.css" type="text/css" />
	<!--[if IE]><link rel="stylesheet" href="css/admin.ie.css" type="text/css" /><![endif]-->
	<!--[if IE 7]><link rel="stylesheet" href="css/admin.ie7.css" type="text/css" /><![endif]-->
	<link rel="stylesheet" href="<? echo $CSS; ?>admin.bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="<? echo $CSS; ?>admin.bootstrap-responsive.css" type="text/css" />
	<script type="text/javascript" src="<? echo $JS; ?>jquery/jquery-1.8.1.min.js"></script>
	<script type="text/javascript" src="<? echo $JS; ?>jquery/admin.ui/jquery-ui-1.8.15.custom.min.js"></script>
	<script type="text/javascript" src="<? echo $JS; ?>bootstrap.js"></script>
	<script type="text/javascript" src="<? echo $JS; ?>interface.js"></script>
	<script type="text/javascript" src="<? echo $JS; ?>ajax-connector.js"></script>
	<script type="text/javascript" src="<? echo $JS; ?>observer.js"></script>
    <script type="text/javascript" src="<? echo $JS; ?>jquery/jquery.cookie.js"></script>
	<script type="text/javascript" src="<? echo $JS; ?>plupload/plupload.js"></script>
	<script type="text/javascript" src="<? echo $JS; ?>plupload/plupload.gears.js"></script>
	<script type="text/javascript" src="<? echo $JS; ?>plupload/plupload.silverlight.js"></script>
	<script type="text/javascript" src="<? echo $JS; ?>plupload/plupload.flash.js"></script>
	<script type="text/javascript" src="<? echo $JS; ?>plupload/plupload.browserplus.js"></script>
	<script type="text/javascript" src="<? echo $JS; ?>plupload/plupload.html4.js"></script>
	<script type="text/javascript" src="<? echo $JS; ?>plupload/plupload.html5.js"></script>
	<script type="text/javascript">
	$(function(){
		$( ".inpDate" ).each(function(){
			date = $(this).val();
			$(this).datepicker();
			$(this).datepicker("option", "dateFormat", 'yy-mm-dd');
			$(this).datepicker("setDate" , date);
		});
		$( ".inpDate" ).datepicker("option", "dateFormat", 'yy-mm-dd');
		
		$( '.popup-open' ).click(function(){
			$('.popupbg').fadeIn(299);
			if($(this).hasClass('popup-swappos'))
				$('#'+ $(this).attr('dbnavigator') +'_dbposition').fadeIn(300);
			return false;
		});
		$( '.popup a.close' ).click(function(){
			$('.popupblock').fadeOut(300);
			$('.popupbg').fadeOut(300);
			if($(this).hasClass('popup-swappos'))
				document.location = "";
			return false;
		});
		
		$('span[rel="tooltip"]').tooltip();
		
		$('.admin-sel').AdminSelect();
		$('.no-click').live('click', function(){return false;});
        $('.nav-tabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        $('.nav-tabs a').on('shown', function (e) {
            var url = String(e.target), tabs_id = $(this).parents('.nav-tabs').attr('id');
            url = url.substr(url.lastIndexOf('#'));
            $.cookie(tabs_id, url);
        });
        $('.nav-tabs').each(function(){
            var tabs_id = $(this).attr('id');
            if($.cookie(tabs_id) !== null && $(this).find('a[href="'+ $.cookie(tabs_id) +'"]').length > 0)
                $(this).find('a[href="'+ $.cookie(tabs_id) +'"]').trigger('click');
            else
                $(this).find('a:first').trigger('click');
        });
	});
	</script>