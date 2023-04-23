<!-- 头部开始 -->

<!--私有样式-->
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/css/jquery.fullPage.css">
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/aqzztx.css" />
<style>
.section { text-align: center;}
.section p{ overflow:hidden;}
.section img{ display:block;}
.section1 em{ width:412px; height:412px; margin-right:52px;}
.section1 .img1{ display:none;}
.section1 .img2{ position:relative; margin-top:68px; left:-120%;}
.section1 .img3{ position:relative; margin-top:46px; left:-120%;}
.section1 .img4{ position:relative; margin-top:26px; left:-120%;}
.section2 .img1{ position: relative; margin-bottom:80px; left:-120%;}
.section2 .img2{ position: relative; bottom:-120%;}
.section3 .img1{ position:relative; margin-bottom:66px; left:-120%;}
.section3 .img2{ position:relative; left:-120%;}
.section3 .mg70{ margin-right:70px;}
.section3 span{ width:285px; height:352px; background:url({{ constant('STATIC_URL') }}mdg/images/aqzztx/page_wen_bc.png) no-repeat; display:none; padding-left:38px; padding-top:24px; text-align:left;}
.section4 .img1{ position:relative; margin-bottom:26px; left:-120%;}
.section4 .img2{ position:relative; bottom:-120%;}

.section3 span font{ font-size:14px; color:#fff; margin-bottom:8px;}
.section3 span .input1{ width:246px; height:35px; line-height:35px\9; background:url({{ constant('STATIC_URL') }}mdg/images/aqzztx/input_bc.png) no-repeat; background-position:0 0; padding-left:4px; margin-bottom:18px; color:#666; font-size:22px;}
.section3 span .btn{ width:102px; height:46px; background:url({{ constant('STATIC_URL') }}mdg/images/aqzztx/page_wen_btn1.png) no-repeat; background-position:0 0; margin-top:16px;}
.section3 span .btn:hover{ background-position:0 -53px;}
.section3 span strong{ font-weight:bold; color:#fff; font-size:14px; margin-bottom:8px;}
.section3 span i{ font-weight:normal;}
.section3 span .input2{ width:133px; height:35px; line-height:35px\9; background:url({{ constant('STATIC_URL') }}mdg/images/aqzztx/input_bc.png) no-repeat; background-position:0 -43px; padding-left:4px; color:#DC7C1A; font-size:22px;}
#f_img{ position:absolute; left:-235px; top:0; display:none;}
</style>
<script src="{{ constant('STATIC_URL') }}mdg/js/jquery-ui-1.10.3.min.js"></script>
<script src="{{ constant('STATIC_URL') }}mdg/js/jquery.fullPage.min.js"></script>
<script>
$(document).ready(function() {
    var timer = null;
    $.fn.fullpage({
        slidesColor: ['#fff', '#EAA82C', '#fff', '#F2F2F2'],
        anchors: ['page1', 'page2', 'page3', 'page4'],
        navigation: true,
        navigationTooltips:['检测原因', '检测现状', '检测方法', '国家标准'],
        afterRender: function(){
            $('.section1').find('.img1').fadeIn(1000);
            timer = setTimeout(function(){
                $('.section1').find('.img2').animate({
                    left: '0'
                }, 1000, 'easeOutExpo', function(){
                    $('.section1').find('.img3').animate({
                        left: '0'
                    }, 1000, 'easeOutExpo', function(){
                        $('.section1').find('.img4').animate({
                            left: '0'
                        }, 1500, 'easeOutExpo');
                    });
                });
            }, 500);
        },
        afterLoad: function(anchorLink, index){
            if(index == 1){
                $('.topNav').css({'position': 'absolute', 'top': '112px'});
                $('.section1').find('.img1').fadeIn(1000);
                timer = setTimeout(function(){
                    $('.section1').find('.img2').animate({
                        left: '0'
                    }, 1000, 'easeOutExpo', function(){
                        $('.section1').find('.img3').animate({
                            left: '0'
                        }, 1000, 'easeOutExpo', function(){
                            $('.section1').find('.img4').animate({
                                left: '0'
                            }, 1500, 'easeOutExpo');
                        });
                    });
                }, 800);
            }
            if(index == 2){
                $('.topNav').css({'position': 'fixed', 'top': '0'});
                $('.section2').find('.img1').delay(500).animate({
                    left: '0'
                }, 1000, 'easeOutExpo', function(){
                    $('.section2').find('.img2').animate({
                        bottom: '0'
                    }, 1500, 'easeOutExpo');
                });
            }
            if(index == 3){
                $('.section3').find('.img1').delay(500).animate({
                    left: '0'
                }, 1000, 'easeOutExpo', function(){
                    $('.section3').find('.img2').animate({
                        left: '0'
                    }, 1000, 'easeOutExpo', function(){
                        $('.section3').find('span').fadeIn(1000);
                    });
                });
            }
            if(index == 4){
                $('.section4').find('.img1').delay(500).animate({
                    left: '0'
                }, 1000, 'easeOutExpo', function(){
                    $('.section4').find('.img2').animate({
                        bottom: '0'
                    }, 1500, 'easeOutExpo');
                });
                //底部栏目滑出
                (function () {
                    var footerShow = false;
                    var $footer = $(".fIcp")
                    var footH = $footer.outerHeight();
                    $("#f_layer").bind("mouseenter", function () {
                        footerShow = true;
                        $footer.stop().animate({
                            "bottom": 0
                        }, 300)
                    })
                    $footer.bind("mouseleave", function () {
                        $footer.stop().animate({
                            "bottom": -footH + "px"
                        }, 300)
                    });
                })();
            }
        },
        onLeave: function(index, direction){
            if(index == 1){
                var timer2 = setTimeout(function(){
                    $('.topNav').css({'position': 'fixed', 'top': '0'});
                }, 400);
                $('.section1').find('.img1').fadeOut(2000);
                timer = setTimeout(function(){
                    $('.section1').find('.img2').animate({
                        left: '-120%'
                    }, 1000, 'easeOutExpo', function(){
                        $('.section1').find('.img3').animate({
                            left: '-120%'
                        }, 1000, 'easeOutExpo', function(){
                            $('.section1').find('.img4').animate({
                                left: '-120%'
                            }, 1500, 'easeOutExpo');
                        });
                    });
                }, 800);
            }
            if(index == 2){
                $('.section2').find('.img1').delay(500).animate({
                    left: '-120%'
                }, 1000, 'easeOutExpo', function(){
                    $('.section2').find('.img2').animate({
                        bottom: '-120%'
                    }, 1500, 'easeOutExpo');
                });
            }
            if(index == 3){
                $('.section3').find('.img1').delay(500).animate({
                    left: '-120%'
                }, 1000, 'easeOutExpo', function(){
                    $('.section3').find('.img2').animate({
                        left: '-120%'
                    }, 1000, 'easeOutExpo', function(){
                        $('.section3').find('span').fadeOut(300);
                    });
                });
            }
            if(index == 4){
                $('.section4').find('.img1').delay(500).animate({
                    left: '-120%'
                }, 1000, 'easeOutExpo', function(){
                    $('.section4').find('.img2').animate({
                        bottom: '-120%'
                    }, 1500, 'easeOutExpo');
                });
                
            }
        }
    });
    
});

function showImg(url){
    $('#f_img').attr('src', url);
    $('#f_img').fadeIn();
};
function hideImg(url){
    $('#f_img').fadeOut();
};

</script>
<div class="header">
{{ partial('layouts/page_header') }}
</div>
</div>
<!-- 头部结束 -->
<div class="section section1">
    <p class="w960" style="padding-top:60px; height:412px;">
        <em class="f-db f-fl"><img class="img1" src="{{ constant('STATIC_URL') }}mdg/images/aqzztx/page1_wen_img1.png" /></em>
        <span class="f-db f-fl f-oh">
            <img class="img2" src="{{ constant('STATIC_URL') }}mdg/images/aqzztx/page1_wen_img2.png" />
            <img class="img3" src="{{ constant('STATIC_URL') }}mdg/images/aqzztx/page1_wen_img3.png" />
            <img class="img4" src="{{ constant('STATIC_URL') }}mdg/images/aqzztx/page1_wen_img4.png" />
        </span>
    </p>
</div>
<div class="section section2">
    <p class="w960" style="height:378px;">
        <img class="img1" src="{{ constant('STATIC_URL') }}mdg/images/aqzztx/page2_wen_img1.png" />
        <img class="img2" src="{{ constant('STATIC_URL') }}mdg/images/aqzztx/page2_wen_img2.png" />
    </p>
</div>
<div class="section section3">
    <form action="/product/fao" method="post">
    <p class="w960" style="height:378px;">
        <em class="mg70 f-db f-fl f-oh">
            <img class="img1" src="{{ constant('STATIC_URL') }}mdg/images/aqzztx/page3_wen_img1.png" />
            <img class="img2" src="{{ constant('STATIC_URL') }}mdg/images/aqzztx/page3_wen_img2.png" />
        </em>
        <span class="f-db f-fl pr">
            <font class="f-db">农药瓶上标注的有效成分浓度（%）：</font>
            <input class="input1" type="text" name='c'  onfocus = "showImg('{{ constant('STATIC_URL') }}mdg/images/aqzztx/page3_wen_img3.png')" />
            <font class="f-db">喷洒农药的稀释倍数（倍）：</font>
            <input class="input1" type="text" name='n' onfocus = "showImg('{{ constant('STATIC_URL') }}mdg/images/aqzztx/page3_wen_img4.png')" />
            <font class="f-db">喷药后到收获前的天数（天）：</font>
            <input class="input1" type="text" name='x'   onfocus="hideImg()" />
            <em class="btn_box f-db f-oh">
                <input class="btn f-fl mr10" id="numbn" type="button" value="" />
                <b class="f-db f-fl f-oh">
                    <strong class="f-db">检测结果<i>（mg/kg）：</i></strong>
                    <input class="input2" type="text" id="num" value=""/>
                </b>
            </em>
            <img id="f_img" src="{{ constant('STATIC_URL') }}mdg/images/aqzztx/page3_wen_img3.png" />
            <!--<img class="f_img" src="{{ constant('STATIC_URL') }}mdg/images/aqzztx/page3_wen_img4.png" />-->
        </span>
    </p>
    </form>
</div>
<div class="section section4">
    <p class="w960" style="height:507px;">
        <img class="img1" src="{{ constant('STATIC_URL') }}mdg/images/aqzztx/page4_wen_img1.png" />
        <img class="img2" src="{{ constant('STATIC_URL') }}mdg/images/aqzztx/page4_wen_img2.png" />
    </p>
    <div id="f_layer" style="width:100%; height:20px; position:absolute; bottom:0;"></div>
</div>

<!-- 底部开始 -->
<div class="fIcp">
    <p>Copyright ©2014,版权所有北京天辰云农场有限公司，京ICP备14023165号-2</p>
    <p>北京天辰云农场有限公司 北京市朝阳区东三环中路39号建外SOHO东区9号楼22F</p>
</div>
<!-- 底部结束 -->
</body>
</html>
<script>
$('#numbn').click(function(){
        var c=$('input[name="c"]').val();
        var n=$('input[name="n"]').val();
        var x=$('input[name="x"]').val();
        var mark= /^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/;
        if(c!=''&&n!=''&&x!=''){
               if( !mark.test(c) || !mark.test(n) || !mark.test(x)){
                  $("#num").val("格式不正确");
               }else{
                  $.get('/product/checknum', {c:c,n:n,x:x},function(data) {
                     $("#num").val(data);
                   });
               }
          
        }else{
           $("#num").val("各项不能为空");
        }
});
</script>
