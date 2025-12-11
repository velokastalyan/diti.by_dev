//удаление отложенных
$(".btn_close").click(function(){
    var cookie_count;
    var cookie_arr = [];
    var dataFilter = $.cookie('otlozh');
    if (dataFilter!=null)
    {
        cookie_count = dataFilter.split(',');
        var del = this.id;
        for (var i=0;i<cookie_count.length;i++)
        {
            if (cookie_count[i] != del)
            {
                cookie_arr[cookie_arr.length] = cookie_count[i];
            }
        }
        dataFilter = cookie_arr.join(",");
        if (cookie_arr.length!=0)
            $.cookie('otlozh',dataFilter,{ expires: 30, path: '/'});
        else $.cookie('otlozh','',{ expires: -1, path: '/'});
        $("#cookie_count").empty();
        $("#cookie_count").append("Отложенные ("+ cookie_arr.length +")");
        $("#r_"+this.id+"").remove();
    }
    if (cookie_arr.length==0)
        $(".products").append("<div class='no_product'>Отложенных товаров нет!</div>");
});