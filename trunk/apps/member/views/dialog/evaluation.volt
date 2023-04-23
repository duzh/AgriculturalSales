<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<title>店铺查看-评分弹框</title>

<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/base.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/shop_view.css" />
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/jquery-1.8.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/zh_CN.js"></script>

</head>

<body>
<div class="dialog_pingfen">

    <form action="/member/dialog/evaluationsave" method="post" id="starForm">
        <ul class="pingfen" id="pingfen">
            <li class="clearfix">
                <font>服务态度：</font>
                <div class="star">
                    <a href="javascript:;" class="star1" _index="1"></a>
                    <a href="javascript:;" class="star2" _index="2"></a>
                    <a href="javascript:;" class="star3" _index="3"></a>
                    <a href="javascript:;" class="star4" _index="4"></a>
                    <a href="javascript:;" class="star5" _index="5"></a>
                </div>
                <i></i>
                <input type="hidden" value="" name="fwtdVal"  autocomplete="off" data-target="#fwtdYz" />
                <span id="fwtdYz" class="yzTips"></span>
            </li>
            <li class="clearfix">
                <font>陪同程度：</font>
                <div class="star">
                    <a href="javascript:;" class="star1" _index="1"></a>
                    <a href="javascript:;" class="star2" _index="2"></a>
                    <a href="javascript:;" class="star3" _index="3"></a>
                    <a href="javascript:;" class="star4" _index="4"></a>
                    <a href="javascript:;" class="star5" _index="5"></a>
                </div>
                <i></i>
                <input type="hidden" value="" name="ptcdVal" data-target="#ptcdYz" />
                <span id="ptcdYz" class="yzTips"></span>
            </li>
            <li class="clearfix">
                <font>供货能力：</font>
                <div class="star">
                    <a href="javascript:;" class="star1" _index="1"></a>
                    <a href="javascript:;" class="star2" _index="2"></a>
                    <a href="javascript:;" class="star3" _index="3"></a>
                    <a href="javascript:;" class="star4" _index="4"></a>
                    <a href="javascript:;" class="star5" _index="5"></a>
                </div>
                <i></i>
                <input type="hidden" value="" name="ghnlVal" data-target="#ghnlYz" />
                <span id="ghnlYz" class="yzTips"></span>
            </li>
            <li class="clearfix">
                <font>描述相符：</font>
                <div class="star">
                    <a href="javascript:;" class="star1" _index="1"></a>
                    <a href="javascript:;" class="star2" _index="2"></a>
                    <a href="javascript:;" class="star3" _index="3"></a>
                    <a href="javascript:;" class="star4" _index="4"></a>
                    <a href="javascript:;" class="star5" _index="5"></a>
                </div>
                <i></i>
                <input type="hidden" value="" name="msxfVal" data-target="#msxfYz" />
                <span id="msxfYz" class="yzTips"></span>
            </li>
            <li class="clearfix">
                <font>评论：</font>
                <div class="txt" id="textArea">
                    <textarea class="f-ff0" name="pl_area" data-target="#plYz"></textarea>
                    <em>最多可以输入<label>200</label>个字</em>
                    <input type="hidden" id="srNum" name="srNum" value="" />
                    <span id="plYz" class="plTips"></span>
                </div>
            </li>
            <li id="msg_holder"></li>
        </ul>
        <input type='hidden' value="{{shop_id}}" name='shop_id'>
        <input class="pingfen_btn" type="submit" value="确定评价" />
    </form>
</div>
</body>

<script>
$(function(){
    $('#pingfen .star a').click(function(){
        $(this).parent().find('a').removeClass('active');
        $(this).addClass('active');
        var num = $(this).attr('_index');
        $(this).parent().parent().find('i').text(num + '分');
        $(this).parent().parent().find('input').val(num);
    });
    
    $(document).keyup(function() {
        var text=$("#textArea textarea").val();
        var counter=text.length;
        if(counter > 200){
            $('#srNum').val('');
            return ;
        }else{
            $("#textArea label").text(200-counter);
            $('#srNum').val(200-counter);
        };
    });
    
    $('#starForm').validator({
        //stopOnError : true,
        messages: {
            required : "{0}不能为空"
        },
        rules: {
            myRule: function(el, param, field){
                var me = $("#textArea textarea").val().length;
                if(me > 200){
                    return '请输入0-200个字';
                };
            },
        },
        fields: {
            fwtdVal: "评分:required;",
            ptcdVal: "评分:required;",
            ghnlVal: "评分:required;",
            msxfVal: "评分:required;",
            pl_area: "评论:required;myRule;",
        }
    });
});
</script>
</html>
