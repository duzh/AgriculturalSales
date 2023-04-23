
<!-- 内容页样式、js（放到公用样式最下面） -->
<link rel="stylesheet" type="text/css" href="/mdg/css/mix-bq-gy.css" />
<link href="{{ constant('JS_URL') }}lightSlider/css/gowuche.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}lightSlider/js/jquery.jqzoom.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lightSlider/js/base.js"></script>
<!-- 头部开始 -->
{{ partial('layouts/orther_herder') }}
<!-- 头部结束 -->


<!-- 主体内容开始 -->
<div class="wrapper f-oh" style="background:#f2f2f2;">
<div class="ur_here w960">
    <span>
        <a href="/index.php">首页</a>&nbsp;>&nbsp;
        <a href="/sell/index/">供应大厅</a>&nbsp;>&nbsp;
        <a href="/sell/info/{{ sid }}">{{ sname }}</a>&nbsp;>&nbsp;
        作业相册
    </span>
</div>
<div class="gy_zyxc_box w960">
    <div class="title">作业相册</div>
    <ul class="clearfix">
        {% for val in data %}
        <li>
            <a href="#">
                <div class="imgs">
                    <img src="{{ val['path'] }}" width="180" height="180" />
                </div>
                <div class="msg">
                    <?php echo isset($_operate_type[$te]) ?$_operate_type[$te] : ''; ?>
                </div>
            </a>
        </li>
        {% endfor %}

        
    </ul>
</div>
</div>
<!-- 主体内容结束 -->

</body>
</html>
    