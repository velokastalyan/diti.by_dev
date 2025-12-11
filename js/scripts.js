$(document).ready(function() {
    var mobile = /iphone|android/i.test(navigator.userAgent.toLowerCase());
    if(mobile) {
        $('.social_scroll').hide();
    }
});

$(function(){
    $(".more").toggle(function(){
        $(this).text("Свернуть").siblings(".more_text").css({opacity: 0.0, visibility: "visible", height: "100%"}).animate({opacity: 1.0}, "slow");
    }, function(){
        $(this).text("Подробнее").siblings(".more_text").css({opacity: 1.0, visibility: "hidden", height: "0"}).animate({opacity: 0.0}, "slow");
    });

    $("#message_window").hide();
    $("#carousel").jcarousel({
        scroll: 1
    });

    $("#carousel_floating").jcarousel({
        scroll: 1
    });

    $(".ft").click(function(){
        if ($(this).hasClass("active")){
            $(this).toggleClass("active");
            $("#float_panel").animate({"width": "-=220px"}, "slow");
            return false;
        }
        else {
            if ($("#float_panel").width()==0){
                $("#float_panel").animate({"width": "+=220px"}, "slow");
            }
            $("#fb_block").hide();
            $("#yt_block").hide();
            $("#od_block").hide();
            $("#vk_block").hide();
            $("#sk_block").hide();
            $("#ft_block").show();

            $(this).toggleClass("active");
            if ($(".fb").hasClass("active")) $(".fb").toggleClass("active");
            if ($(".od").hasClass("active")) $(".od").toggleClass("active");
            if ($(".yt").hasClass("active")) $(".yt").toggleClass("active");
            if ($(".vk").hasClass("active")) $(".vk").toggleClass("active");
            if ($(".sk").hasClass("active")) $(".sk").toggleClass("active");
            return false;
        }
    });

    $(".sk").click(function(){
        if ($(this).hasClass("active")){
            $(this).toggleClass("active");
            $("#float_panel").animate({"width": "-=220px"}, "slow");
            return false;
        }
        else {
            if ($("#float_panel").width()==0){
                $("#float_panel").animate({"width": "+=220px"}, "slow");
            }
            $("#fb_block").hide();
            $("#yt_block").hide();
            $("#od_block").hide();
            $("#vk_block").hide();
            $("#sk_block").show();
            $("#ft_block").hide();

            $(this).toggleClass("active");
            if ($(".fb").hasClass("active")) $(".fb").toggleClass("active");
            if ($(".od").hasClass("active")) $(".od").toggleClass("active");
            if ($(".yt").hasClass("active")) $(".yt").toggleClass("active");
            if ($(".vk").hasClass("active")) $(".vk").toggleClass("active");
            if ($(".ft").hasClass("active")) $(".ft").toggleClass("active");
            return false;
        }
    });

    $(".vk").click(function(){
        if ($(this).hasClass("active")){
            $(this).toggleClass("active");
            $("#float_panel").animate({"width": "-=220px"}, "slow");
            return false;
        }
        else {
            if ($("#float_panel").width()==0){
                $("#float_panel").animate({"width": "+=220px"}, "slow");
            }
            $("#fb_block").hide();
            $("#yt_block").hide();
            $("#od_block").hide();
            $("#sk_block").hide();
            $("#ft_block").hide();
            $("#vk_block").show();

            $(this).toggleClass("active");
            if ($(".fb").hasClass("active")) $(".fb").toggleClass("active");
            if ($(".od").hasClass("active")) $(".od").toggleClass("active");
            if ($(".yt").hasClass("active")) $(".yt").toggleClass("active");
            if ($(".ft").hasClass("active")) $(".ft").toggleClass("active");
            if ($(".sk").hasClass("active")) $(".sk").toggleClass("active");
            return false;
        }
    });

    $(".fb").click(function(){
        if ($(this).hasClass("active")){
            $(this).toggleClass("active");
            $("#float_panel").animate({"width": "-=220px"}, "slow");
            return false;
        }
        else {
            if ($("#float_panel").width()==0){
                $("#float_panel").animate({"width": "+=220px"}, "slow");
            }
            $("#fb_block").show();
            $("#yt_block").hide();
            $("#od_block").hide();
            $("#vk_block").hide();
            $("#sk_block").hide();
            $("#ft_block").hide();

            $(this).toggleClass("active");
            if ($(".vk").hasClass("active")) $(".vk").toggleClass("active");
            if ($(".od").hasClass("active")) $(".od").toggleClass("active");
            if ($(".yt").hasClass("active")) $(".yt").toggleClass("active");
            if ($(".ft").hasClass("active")) $(".ft").toggleClass("active");
            if ($(".sk").hasClass("active")) $(".sk").toggleClass("active");
            return false;
        }
    });

    $(".od").click(function(){
        if ($(this).hasClass("active")){
            $(this).toggleClass("active");
            $("#float_panel").animate({"width": "-=220px"}, "slow");
            return false;
        }
        else {
            if ($("#float_panel").width()==0){
                $("#float_panel").animate({"width": "+=220px"}, "slow");
            }
            $("#fb_block").hide();
            $("#yt_block").hide();
            $("#od_block").show();
            $("#vk_block").hide();
            $("#sk_block").hide();
            $("#ft_block").hide();

            $(this).toggleClass("active");
            if ($(".fb").hasClass("active")) $(".fb").toggleClass("active");
            if ($(".vk").hasClass("active")) $(".vk").toggleClass("active");
            if ($(".yt").hasClass("active")) $(".yt").toggleClass("active");
            if ($(".ft").hasClass("active")) $(".ft").toggleClass("active");
            if ($(".sk").hasClass("active")) $(".sk").toggleClass("active");
            return false;
        }
    });

    $(".yt").click(function(){
        if ($(this).hasClass("active")){
            $(this).toggleClass("active");
            $("#float_panel").animate({"width": "-=220px"}, "slow");
            return false;
        }
        else {
            if ($("#float_panel").width()==0){
                $("#float_panel").animate({"width": "+=220px"}, "slow");
            }
            $("#fb_block").hide();
            $("#yt_block").show();
            $("#od_block").hide();
            $("#vk_block").hide();
            $("#sk_block").hide();
            $("#ft_block").hide();

            $(this).toggleClass("active");
            if ($(".fb").hasClass("active")) $(".fb").toggleClass("active");
            if ($(".od").hasClass("active")) $(".od").toggleClass("active");
            if ($(".vk").hasClass("active")) $(".vk").toggleClass("active");
            if ($(".ft").hasClass("active")) $(".ft").toggleClass("active");
            if ($(".sk").hasClass("active")) $(".sk").toggleClass("active");
            return false;
        }
    });

    $(function(){
        var floatHeading;
         if ($.cookie('show_history') == 1)
         {

             $("#carousel_floating").show();
             var title = document.getElementById('floating-title');
             title.style.cssText = 'margin: 0 0 10px 0;';
         }
        else
         {
             $("#carousel_floating").hide();
             var title = document.getElementById('floating-title');
             title.style.cssText = 'margin: 0;';
         }

        floatHeading($( '#floating-panel' ));
        $( window ).scroll(function(){floatHeading($( '#floating-panel' ))});
        $( window ).resize(function(){
            wheight=(window.innerHeight)?window.innerHeight:
                    ((document.all)?document.body.offsetHeight:null);
            floatHeading($( '#floating-panel' ));
        });
    });

        function getScrollXY() {
            var scrOfX = 0, scrOfY = 0;
            if( typeof( window.pageYOffset ) == 'number' ) {
            scrOfY = window.pageYOffset;
            scrOfX = window.pageXOffset;
            } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
            scrOfY = document.body.scrollTop;
            scrOfX = document.body.scrollLeft;
            } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
            scrOfY = document.documentElement.scrollTop;
            scrOfX = document.documentElement.scrollLeft;
            }
        return [ scrOfX, scrOfY ];
        }
        $(".main_menu ul li").hover(function(){
            $(this).addClass("hover");
            $('ul.sub',this).css('visibility', 'visible');
            $('ul.sub',this).animate({ opacity: 1 },500);
        }, function(){
            $(this).removeClass("hover");
            $('ul.sub',this).animate({ opacity: 0 },500);
            $('ul.sub',this).css('visibility', 'hidden');
        });

        $(".main_menu > ul li:last").addClass('right_sub');
        $(".main_menu > ul li:last").prev().addClass('right_sub');
        $(".main_menu > ul li:last").prev().prev().addClass('right_sub');
        var mainSize = $(".main_menu > ul").width();
        var items = $(".main_menu > ul > li").length;
        var item = $(".main_menu > ul > li").width();
        var summ = 0;
        $(".main_menu > ul > li").each(function(){
            summ += $(this).width();
            });
        var padding = parseInt(((mainSize-summ)/items)/2);
        $(".main_menu > ul > li > a").css("paddingLeft",padding).css("paddingRight",padding);
        //fix
        var summ2 = 0;
        $(".main_menu > ul > li").each(function(){
            summ2 += $(this).width();
            });
        var fix = mainSize-summ2;
        var paddingfix = padding + fix;
        $(".main_menu > ul > li:first > a").css("paddingLeft",paddingfix);
});

function setCookie(name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}

$("#show_history").click(function() {
    var show_history = document.getElementById('show_history');
    if (show_history.innerHTML.indexOf('Скрыть')+1) {
        $("#carousel_floating").hide();
        show_history.innerHTML = 'Раскрыть <img src="http://diti.by/images/open.gif" />';
        $("#footer_footer").height($("#floating-panel").height());
        $.cookie("show_history", 0, {expires: 60*60*24*30*12});
        var title = document.getElementById('floating-title');
        title.style.cssText = 'margin: 0;';
    }
    else {
        $("#carousel_floating").show();
        show_history.innerHTML = 'Скрыть <img src="http://diti.by/images/close.gif" />';
        $("#footer_footer").height($("#floating-panel").height());
        $.cookie("show_history", 1, {expires: 60*60*24*30*12});
        var title = document.getElementById('floating-title');
        title.style.cssText = 'margin: 0 0 10px 0;';
    }
});