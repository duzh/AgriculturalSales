{{ partial('layouts/page_header') }}
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/base.css">
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/index.css">
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/css/wuliu.css">
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/js/jquery-1.11.1.min.js"></script>
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/js/index.js"></script>


<script src="{{ constant('STATIC_URL')}}/mdg/market/js/jquery-1.7.2.js"></script>
<script src="{{ constant('STATIC_URL')}}/mdg/market/js/highcharts.js"></script>


<script type='text/javascript' src='{{ constant('STATIC_URL')}}/mdg/js/jquery-autocomplete/lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='{{ constant('STATIC_URL')}}/mdg/js/jquery-autocomplete/jquery.autocomplete.js'></script>
<script type='text/javascript' src='{{ constant('STATIC_URL')}}/mdg/js/jquery-autocomplete/demo/localdata.js'></script>
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL')}}/mdg/js/jquery-autocomplete/jquery.autocomplete.css" />

    <div class="wuliu_body_v2">
        <div class="wuliu_v2 w1190 mtauto">
            <div class="mianbao_v2">
                <a href="/">首页</a>
                <span>&gt;</span>
                <a href="/market/index/">价格行情</a>
            </div>
            
            <div class="jiage_box1_v2">
                <div class="jiage_box1_left">
                    <div class="jiage_zoushi_title">
                        <div class="jiage_zstLeft">价格走势图</div>
                        <div class="jiage_zstRight">
                            <form action="/market/index">
                            <input type="submit" name="" value="搜索" class="zs_searchBtn_v2">
                            <input type="text"  placeholder='走势图' name='keyword' id='keyword' value='{{ keyword }}'  class="zs_searchText_v2">
                            </form>
                        </div>
                        <div class="jiage_hot_v2">
                            <span>热门：</span>
                            {% for key , item in VirtualData %}
                            <a href="/market/index?cid={{ key }}" {% if cid == key %}class="on"{% endif %}>{{ item }}</a>
                            {% endfor %}
                        </div>
                    </div>
                    <div class="jiange_zsCon_v2 f-oh" id='homeListEcharts' >
                        {% if  !info or !data  %}
                        <div class="no-data f-tac" style="margin-top:170px;">暂无数据</div>
                        {% endif %}
                    </div>
                </div>
    <!--             <div class="jiage_box1_right">
                    <div class="hangqing_title_v2">
                        <strong>实时行情</strong>
                    </div>
                    <div class="hangqing_con_v2">
                        <div id="hangqing_scroll_v2" class="hangqing_scroll_v2">
                            <ul>
                                {% for order in orders %}
                                <li class="active">
                                    <em></em>
                                    <strong>{{ order['pubtime']}}</strong>
                                    <div class="hangqing_text_v2">
                                        {{ order['areas_name'] }}{{ order['purname'] }}成功以
                                        <strong>{{ order['price'] }}</strong>
                                        元/{{ order['goods_unit'] ? goods_unit[order['goods_unit']] : ''}}
                                        <br />
                                        采购了
                                        <strong>{{ order['quantity'] }}{{ order['goods_unit'] ? goods_unit[order['goods_unit']]  : '' }}</strong> 
                                        {{ order['goods_name']}}
                                    </div>
                                </li>
                                {% endfor %}
                            </ul>
                        </div>
                    </div>
                </div> -->
            </div>
            
            {% for key ,item in homeData %}
                {% if item['isChid'] > 0  %}
                <div class="jiage_box2_v2 {{ item['class']}}_v2">
                    <div class="jiage_box2Left_v2">
                        <div class="zljg_title_v2">
                            <strong><?php echo $floor++; ?>F {{ item['className']}}</strong>
                            <a href="/market/catelist?cid={{item['id']}}" title="">更多&gt;</a>
                        </div>
                        <div class="zlTop_v2"><strong>TOP5 价格浮动</strong></div>
                        <ul class="zljg_ul_v2" id='top_{{ item['id'] }}' ></ul>
                    </div>
                    <div class="jiage_box2Right_v2">
                        <ul class="jgfd_bigUl_v2" id='hot_{{ item['id']}}' >
                            
                        </ul>
                    </div>
                </div>
                <script type="text/javascript">
                $(function () {
                    $('#top_'+{{ item['id'] }}).load("/marketajax/gettopcate/{{ item['id']}}");
                    $('#hot_'+{{ item['id'] }}).load("/marketajax/gethotcate/{{ item['id']}}");
                })
                </script>
                {% endif %}
           {% endfor %}
        </div>
    </div>
{{ partial('layouts/footer') }}
<script>
$(function () {
        function format(mail) {
            return mail.name;
        }
        $("#keyword").autocomplete('/market/search', {
            multiple: false,
            dataType: "json",
            parse: function(data) {
                return $.map(data, function(row) {
                    return {
                        data: row,
                        result: row.name
                    }
                });
            },
            formatItem: function(item) {
                return format(item);
            }
        }).result(function(e, item) {
            $("#content").append("<p>" + format(item) + "</p>");
        });


});


$(function(){
    {% if info and data %}
        $('#homeListEcharts').highcharts({
            chart : {
                height : 280,
                type : 'line',
                margin : [100]
            },
            credits : {
                enabled : false
            },
            title : {
                text : '全国{{ info.goods_name }}一周价格走势',
                useHTML : 'strong',
                style : {
                    background : '#f4b22d',
                    height : '30px',
                    color : '#fff',
                    padding : '0px 15px',
                    lineHeight : '30px',
                    boxShadow : '2px 2px 2px #ccc',
                    marginBottom : '50px'
                },
                align : 'left'
            },
            subtitle : {
                text : '',
                x : -20
            },
            xAxis : {
                categories : ['{{ data["keys"]}}']

                },
            yAxis : {
                title : {
                    text : '价格(元/斤)'
                },
                //alternateGridColor : '#f1f1f1',       //隔行显示颜色
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#000'
                }],
                gridLineColor : '#f1f1f1',      //网格线的颜色
                gridLineDashStyle : 'Dash'      //网格线的样式
            },
            tooltip: {
                valueSuffix: '元/斤',
                backgroundColor : '#f9ab14',
                borderColor : 'none',
                borderRadius : 5,
                shadow : false,
                style : {
                    color : '#fff',
                    fontSize : '14px',
                    padding : '8px',
                    lineHeight : '24px',
                    fontWeight : 'bold'
                },
                valueDecimals : 2,
                formatter: function () {
                    var s = '<div class="toolTip1">' + this.x + '</div>';

                    $.each(this.points, function () {
                        s += '<br/>' + this.series.name + ': ' +
                            this.y + '元/斤';
                    });

                    return s;
                },
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0

            },
            series: [{
                name: '{{ info.goods_name }}价格',
                data: [{{ data['vals']}}],
                color : '#f9ab14'
            }]
        });
{% endif %}
    })
    

</script>
<script>
    (function($){
        $.fn.extend({
                Scroll:function(opt,callback){
                        //参数初始化
                        if(!opt) var opt={};
                        var timerID;
                        var _this=this.eq(0).find("ul:first");
                        var     lineH=_this.find("li:first").outerHeight(), //获取行高
                                line=opt.line?parseInt(opt.line,10):parseInt(this.outerHeight()/lineH,10), //每次滚动的行数，默认为一屏，即父容器高度
                                speed=opt.speed?parseInt(opt.speed,10):500; //卷动速度，数值越大，速度越慢（毫秒）
                                timer=opt.timer //?parseInt(opt.timer,10):3000; //滚动的时间间隔（毫秒）
                        if(line==0) line=1;
                        var upHeight=0-line*lineH;
                        //滚动函数
                        var scrollUp=function(){

                                _this.animate({
                                        marginTop:upHeight
                                },speed,function(){
                                        for(i=1;i<=line;i++){
                                                _this.find("li:first").appendTo(_this);
                                        }
                                        _this.css({marginTop:0});
                                });
                                _this.find("li").removeClass('active');
                                _this.find("li").eq(1).addClass('active');
                        }
                        //Shawphy:向下翻页函数
                        var scrollDown=function(){
                                for(i=1;i<=line;i++){
                                        _this.find("li:last").show().prependTo(_this);
                                }
                                _this.css({marginTop:upHeight});
                                _this.animate({
                                        marginTop:0
                                },speed);
                        }
                       //Shawphy:自动播放
                        var autoPlay = function(){
                                if(timer)timerID = window.setInterval(scrollUp,timer);
                        };
                        var autoStop = function(){
                                if(timer)window.clearInterval(timerID);
                        };
                         //鼠标事件绑定
                        _this.hover(autoStop,autoPlay).mouseout();
                }       
        })
        })(jQuery);

        $("#hangqing_scroll_v2").Scroll({line:1,speed:500,timer:3000,up:"but_up",down:"but_down"});
    </script>