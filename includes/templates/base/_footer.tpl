<?php $page_class = $GLOBALS['app']->Router->CurrentPage['class']; ?>
<div class="row">
    <div class="twelve columns">
        <script type="text/javascript" src="//vk.com/js/api/openapi.js?71"></script>
        <div id="vk_groups0"></div>
        <script type="text/javascript">
            VK.Widgets.Group("vk_groups0", {mode: 0, width: "940", height: "230", color1:"#0d0a16", color2: "#ffffff", color3: "#282633"}, 69595988);
        </script>
    </div>
</div>

<div class="social_scroll">
    <div class="socials">
        <!-- <a class="ft" href="javascript:;" title="FaceTime"></a>
        <a class="sk" href="javascript:;" title="Skype"></a> -->
        <a class="vk" href="javascript:;" title="Мы ВКонтакте"></a>
        <a class="fb" href="/" title="Мы на Facebook"></a>
        <!--<a class="od" href="/" title="Мы в Одноклассниках"></a>-->
        <a class="yt" href="/" title="Мы на Youtube"></a>
    </div>
<div id="float_panel">
    <div id="ft_block">
        <span class="contact-title">FaceTime</span>
        <a class="contact-number" href="facetime:<?php echo $sd_facetime ?>"><?php echo $sd_facetime ?></a>
    </div>
    <div id="sk_block">
        <span class="contact-title">Skype</span>
        <a class="contact-number" href="skype:<?php echo $sd_skype_item ?>"><?php echo $sd_skype_item ?></a>
    </div>
    <div id="vk_block">
        <script type="text/javascript" src="//vk.com/js/api/openapi.js?71"></script>
        <div id="vk_groups"></div>
        <script type="text/javascript">
            VK.Widgets.Group("vk_groups", {mode: 0, width: "220", height: "256", color1:"#0d0a16", color2: "#ffffff", color3: "#282633"}, 69595988);
        </script>
    </div>

    <div id="fb_block">
        <div class="fb-like-box" data-width="220" data-height="230" data-href="https://www.facebook.com/pages/ditiby/1387527144827237" data-colorscheme="dark" data-show-faces="true" data-header="true" data-stream="false" data-show-border="false"></div>
    </div>

    <div id="od_block"><div id="od_groups"></div></div>
    <script>
        !function (d, id, did, st) {
            var js = d.createElement("script");
            js.src = "https://connect.ok.ru/connect.js";
            js.onload = js.onreadystatechange = function () {
                if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
                    if (!this.executed) {
                        this.executed = true;
                        setTimeout(function () {
                            OK.CONNECT.insertGroupWidget(id,did,st);
                        }, 0);
                    }
                }}
            d.documentElement.appendChild(js);
        }(document,"od_groups","50582132228315","{width:220,height:256}");
    </script>

    <div id="yt_block">
        <script src="https://apis.google.com/js/platform.js"></script>
        <div class="g-ytsubscribe" data-channel="timistkas" data-layout="full" data-theme="dark" data-count="default"></div>
    </div>
</div>
</div>

<div class="wrapper_line">
    <div class="dark_line">
        <div class="row">
            <? if($brand_main_found): ?>
            <div class="twelve columns brand-slider">
                <div id="carousel" class="skin" <? if (count($brand_main_arr) < 7) echo 'align="center" '; ?>>
                    <ul class="list_company" >
                        <? $i = 0; ?>
                        <? foreach($brand_main_arr as $item1): ?>
                            <? if (strlen($item1['image_filename'])): ?>
                                <li>
                                    <? if ($item1['link'] != ''): ?><a href="<? echo htmlspecialchars($item1['link']); ?>" target="_blank" rel="nofollow"><? endif; ?>
                                    <img width="125" height="70" src="<? echo $HTTP; ?>pub/footer/<? echo $item1['id']; ?>/<? echo $item1['image_filename']; ?>" alt="<? echo $item1['title']; ?>">
                                    <? if ($item1['link'] != ''): ?></a><? endif; ?>
                                </li>
                            <? endif; ?>
                        <? endforeach; ?>
                    </ul>
                </div>
            </div>
            <? endif; ?>
        </div>
    </div>
</div>
<? if ($page_class != 'CProductPage' && $page_class != 'CCategoriesPage' && $page_class != 'CProductsPage' && $page_class != 'CNewsPage' && $page_class != 'CSnoubordPage'): ?>
</section>
<?php endif; ?>
    <footer id="footer">
        <div class="row">
            <div class="six columns">
                <? if($sd_found): ?>
                    <? echo $sd_copyright; ?>
                <? endif; ?>
                <div class="developer">
                		<a href="http://xpgraph.by">Разработка сайта - XPGraph.by</a>
                </div>
            </div>
            <div class="text_right six columns">
                <!-- Логотипы платежных систем удалены -->
            </div>
        </div>

		<div id="footer_footer" <? if ($history_found): ?>style="height: <? if ($show_history) echo '140px'; else echo '26px'; ?>"<? endif; ?>></div>

    </footer>
</div>

<?php
$page_class = $GLOBALS['app']->Router->CurrentPage['class'];
$append = array();
if ($page_class == 'CIndexPage')
{
    $append[] = 'js_royalslider';
    $append[] = 'js_sliderkit';
    $append[] = 'js_indexpage';
}
if ($page_class == 'CProductPage' || $page_class == 'CNewsPage' || $page_class == 'CArticlesPage')
{
    $append[] = 'js_rate';
    $append[] = 'js_scroll';
    $append[] = 'js_lightbox';
    $append[] = 'js_cloudzoom';
    $append[] = 'js_fancybox';
    $append[] = 'js_tiptip';
}
if ($page_class == 'CProductPage')
{
    $append[] = 'js_productpage';
}
elseif ($page_class == 'CPagesPage')
{
    $append[] = 'js_scroll';
    $append[] = 'js_pagespage';
}

if (!empty($append)) $append = ',' . implode(',', $append);
else $append = '';

?>

<script src="/minify/g=js_jquery,js_cookie,js_scriptsresp,js_foundation<?php echo $append; ?>,js_jcarousel"></script>
<script src="/js/rl.js"></script>
<link rel="stylesheet" href="/css/jslider.css" type="text/css">
<link rel="stylesheet" href="/css/jslider.plastic.css" type="text/css">
<script type="text/javascript" src="/js/jshashtable-2.1_src.js"></script>
<script type="text/javascript" src="/js/jquery.numberformatter-1.2.3.js"></script>
<script type="text/javascript" src="/js/tmpl.js"></script>
<script type="text/javascript" src="/js/jquery.dependClass-0.1.js"></script>
<script type="text/javascript" src="/js/draggable-0.1.js"></script>
<script type="text/javascript" src="/js/jquery.slider.js"></script>

<script type="text/javascript" charset="utf-8">
    $("#Slider2").slider({
        from: 0,
        to: 12000,
        step: 100,
        round: 1,
        dimension: 'BYN',
        skin: 'plastic' , onstatechange: function( value ){
            var Url = $("#Slider2").attr("data-value-main");
            window.location = Url+value.replace(";",",");
            //console.log(value.replace(";",","));
           //console.log(Url+value.replace(";",","));
        }
    });
</script>

<script>
    $('#assist_form').submit();
    <? if ($page_class == 'CProductPage' || $page_class == 'CNewsPage' || $page_class == 'CArticlesPage'): ?>

        $('.gallery_custom .thumbnail').find('.small_img:first').addClass('active');

        $(".gallery_custom .thumbnail .small_img > img").click(function (){
            $(this).parents('.thumbnail').find('.small_img').removeClass('active');
            $(this).parents('.small_img').addClass('active');
            $("#large_img").attr({src: $(this).parent().find('.hide_large_img img').attr('src') });
        });

        <?php if ($this->product_uri != ''): ?>
        $("#"+ select).rateSelect(select);
        <?php endif; ?>

        $(".add_review").click( function() {
            $("#add_review_button").hide();
            $("#review_form").fadeIn(
                    function(){
                        $.scrollTo('#review_form',800);
                    });

        });

        $('.review_form .send').click( function(){
            $('#review_form').hide();
            $("#add_review_button").fadeIn();
            $('#floating-panel').css("opacity","1");
        });

        $('.reviews_all').click(function(){
            $.scrollTo('#reviews_message',800);
        })
    <? endif; ?>
</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter20321512 = new Ya.Metrika({id:20321512,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true});
                } catch(e) { }
            });
            var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/20321512" style="position:absolute; left:-9999px;" alt="" ></div></noscript>
<!-- /Yandex.Metrika counter -->

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-43728980-1', 'diti.by');
  ga('send', 'pageview');

</script>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WX8VL62"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<script async src="//call-tracking.by/scripts/calltracking.js?936fc503-8d44-4d7d-8609-78564a53af74"></script>

<div id='ScrollTop'><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm0 7.58l5.995 5.988-1.416 1.414-4.579-4.574-4.59 4.574-1.416-1.414 6.006-5.988z"/></svg></div>

</body>

</html>
