var prev_video = null;
var cur_video = "#v0";
$('.tab').click(function(){

    prev_video = cur_video;
    cur_video = "#v" + this.id;

    if (prev_video != null)
    {
        var video = $(prev_video).attr("src");
        $(prev_video).attr("src","");
        $(prev_video).attr("src",video);
    }
})

$(".newslider-vertical").sliderkit({
    auto:false,
    shownavitems:5,
    verticalnav:true,
    navitemshover:false
});

$('#full-width-slider').royalSlider({
    arrowsNav: true,
    loop: false,
    keyboardNavEnabled: true,
    controlsInside: false,
    imageScaleMode: 'none',
    arrowsNavAutoHide: false,
    autoScaleSliderWidth: 940,
    autoScaleSliderHeight: 280,
    controlNavigation: 'bullets',
    thumbsFitInViewport: false,
    navigateByClick: false,
    startSlideId: 0,
    autoScaleSlider: true,
    autoPlay: {
        enabled: true,
        pauseOnHover: true,
        delay: 5000
    },
    transitionType:'slide',
    globalCaption: true
});
$('.rsArrow').css('display', 'none');
$('#full-width-slider').hover(function(){
    $('.rsArrow').fadeIn(100);
}, function(){
    $('.rsArrow').fadeOut(100);
});