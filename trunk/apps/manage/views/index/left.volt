<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>供应商管理</title>
    <link href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="{{ constant('JS_URL') }}easyui/jquery.min.js"></script>
</head>

<body>

    <div class="">
        <div class="main_left">

            <div class="left_nav f-fl">
                <h6>{% if info  %}{{ info.title }}{% endif %}</h6>
                <!-- main_left 开始  -->
                
                <ul class="left_nav_list" id="left_nav_list2">
                    <li>
                        <ul class="hover_left_nav_list">
                            {% for key, nl in navsLeft %}
                            <li class="left_nav_list_x">
                                <a href="{{ nl['url'] }}" title="{{ nl['title'] }}" target="rightMain">{{ nl['title'] }}</a>
                                <span>></span>
                            </li>
                            {% endfor %}
                        </ul>
                    </li>
                </ul>
                <!-- main_left 结束  --> 
            </div>
        </div>
<script>
jQuery(document).ready(function($) {
    $('#left_nav_list2 li a').hover(function() {
        $(this).parent('li').addClass('hover');
        }, function() {
        $(this).parent('li').removeClass('hover');
    });
    
    var url = $('#left_nav_list2 a').eq(0).attr('href');
    parent.$('#rightMain').attr('src',url);

});

</script>
</body>
</html>