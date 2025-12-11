$(window).scroll(function() {
    if ($(this).scrollTop()&&$(this).scrollTop()>=200) {
        $('#ScrollTop').fadeIn();
    } else {
        $('#ScrollTop').fadeOut();
    }
});

$(document).on("click","#ScrollTop",function () {

      $("html, body").animate({ scrollTop: 0 }, "slow");

});
function setLoadProduct(){
    function scrollLoadProducts(){
        let url =  $('.wrapper_pagination .pagination li.current').next().children('a').attr('href');    //  URL, из которого будем брать элементы
        if (url !== undefined) {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'html',
                beforeSend: function(){
                    pagBlock.addClass('loadProducts');
                },
                success: function(data){
                    pagBlock.children('.pagination').remove();
                    let elements = $(data).find('.list_products li'),  //  Ищем элементы
                        pagination = $(data).find('.wrapper_pagination .pagination');//  Ищем навигацию
                    targetContainer.append(elements);   //  Добавляем посты в конец контейнера
                    pagBlock.append(pagination); //  добавляем навигацию следом
                },
                complete:function(){
                    pagBlock.removeClass('loadProducts');
                },
            })
        }
    }
    
    let targetContainer = $('.list_products'),          //  Контейнер, в котором хранятся элементы
    pagBlock = $('.wrapper_pagination');
    if (pagBlock.length>0 && targetContainer.length>0){
            pagBlock.append(
                    $('<div>', {
                    'class': 'loading-spinner'
                    })
                );
        $(document).on('scroll',function(){
            if (($(document).scrollTop() + $(window).outerHeight()*3 > $('.wrapper_pagination').offset().top ) && !pagBlock.hasClass('loadProducts')){
                scrollLoadProducts();
            }
        });                  
    }    
}
$(document).ready(setLoadProduct);