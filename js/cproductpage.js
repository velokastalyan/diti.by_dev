call('Ajax', 'generate_question', [1]).listen(function(r){
    if(r.response)
        $('#answer').attr('placeholder', r.response);
});
$(document).ready(function() {
    $(".modalbox").fancybox();
    $("#consultation").submit(function(){
        if(!$("#f_phone").val()){
            $("#f_phone").addClass("error");
            return false;
        }
    })
});
CloudZoom.quickStart();
$(function(){
    $('#zoom1').bind('click',function(){
        var cloudZoom = $(this).data('CloudZoom');
        cloudZoom.closeZoom();
        $.fancybox.open(cloudZoom.getGalleryList());
        return false;
    });
});

$(".normalTip").tipTip({
    delay: 0,
    fadeIn: 300,
    fadeOut: 150
});

$('.gallery_custom .thumbnail').find('.small_img:first').addClass('active');

$(".gallery_custom .thumbnail .small_img > a > img").click(function (){
    $(this).parents('.thumbnail').find('.small_img').removeClass('active');
    $(this).parents('.small_img').addClass('active');
    $("#large_img").attr({src: $(this).parent().find('.hide_large_img img').attr('src') });
});

//отложенные товары
$("#add_cookie").click(function(){
    var cookie_count;
    var dataFilter=$.cookie('otlozh');
    var value = $(this).attr('data-rel');
		if (dataFilter==null){
			$.cookie('otlozh',value,{ expires: 30, path: '/'});
    $("#message_window").fadeIn("slow");
			$("#message_window").empty();
			$("#message_window").append("<p>Товар отложен!</p>");
    $("#cookie_count").empty();
    $("#cookie_count").append("Отложенные (1)");
    setTimeout(function(){$("#message_window").fadeOut('slow')},4000);
    }
    else{
        var cookie_arr = dataFilter.split(',');
        var cookie_found = false;
        for (var i=0;i<cookie_arr.length;i++)
        if(cookie_arr[i] == value)
        cookie_found = true;
        if (cookie_found != true){
        dataFilter = dataFilter + "," + value;
        $.cookie('otlozh',dataFilter,{ expires: 30, path: '/'});
    $("#message_window").fadeIn("slow");
    $("#message_window").empty();
    $("#message_window").append("<p>Товар отложен!</p>");
    $("#cookie_count").empty();
    cookie_count = dataFilter.split(',');
    $("#cookie_count").append("Отложенные ("+ cookie_count.length +")");
    setTimeout(function(){$("#message_window").fadeOut('slow')},4000);
    } else {
        $("#message_window").fadeIn("slow");
        $("#message_window").empty();
        $("#message_window").append("<p>Данный товар уже был отложен!</p>");
        setTimeout(function(){$("#message_window").fadeOut('slow')},4000);
    }
    }
    return false;
    });

    //Добаление в корзину
    $('#add_to_cart').click(function() {
        var productID = $(this).attr('data-rel'),
        count = $('.countProduct').attr('value');
        call('Products', 'add_to_cart', [productID,count]).listen(function(res) {
        if (res.response) {
        $("#message_window").fadeIn("slow");
        $("#message_window").html("<p>Товар добавлен в корзину!</p>");
        setTimeout(function(){$("#message_window").fadeOut('slow')},4000);
    $('.basket').html('Корзина ('+ res.response +')');
    $('.basket').addClass("basket_active");
    $('#add_to_cart').hide();
    $('#go_to_cart').show();
    } else {
        $("#message_window").fadeIn("slow");
        $("#message_window").html("<p>Ошибка при добавлении!</p>");
        }
    });
    return false;
    });