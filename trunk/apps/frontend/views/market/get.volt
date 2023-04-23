<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/css/base.css">
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/css/index.css">
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/css/wuliu.css">
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/js/jquery-1.11.1.min.js"></script>
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/js/index.js"></script>

<script src="{{ constant('STATIC_URL')}}/mdg/market/js/highcharts.js"></script>
<script type='text/javascript' src='{{ constant('STATIC_URL')}}/mdg/js/jquery-autocomplete/lib/jquery.bgiframe.min.js'>
</script>
<script type='text/javascript' src='{{ constant('STATIC_URL')}}/mdg/js/jquery-autocomplete/lib/thickbox-compressed.js'>
</script>
<script type='text/javascript' src='{{ constant('STATIC_URL')}}/mdg/js/jquery-autocomplete/jquery.autocomplete.js'>
</script>
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL')}}/mdg/js/jquery-autocomplete/jquery.autocomplete.css" />
{{ partial('layouts/page_header') }}
<div class="wuliu_body_v2">
    <div class="wuliu_v2 w1190 mtauto">
        <div class="mianbao_v2">
            <a href="/">首页</a>
            <span>&gt;</span>
            <a href="/market/index">价格行情</a>
            <span>&gt;</span>
            <a href="#">{{ data.goods_name }}行情详细</a>
        </div>
        
        <div class="fenlei_v2">
            <div class="fenlei_top_v2">
                <span>分类：</span>
                <div class="fenlei_zhonglei_v2">
                    <a href="{{ url }}pid=0" {% if pid== 0 %}class="on"{% endif %} >全部</a>
                    {% for key , item in getMarketProvince %}
                    <a href="{{ url }}pid={{ item['province_id']}}"  {% if item['province_id'] == pid %}class="on"{% endif %}>{{ item['province_name']}}</a>
                    {% endfor %}
                </div>
            </div>
            <div class="fenlei_bot_v2">
                <span>产品搜索：</span>
                <div class="fenlei_zimu_v2">
                 <form action="/market/get">
                    <input type="text" name='keyword' value='<?php if(isset($_GET["keyword"])){ echo $_GET["keyword"];}?>' class="wl-citySearchInput" placeholder="搜索价格行情如：菠萝蜜">
                    <input type="submit" style='width:80px;line-height:25px;' >
                 </form>   
                </div>
            </div>
        </div>

        <div class="fenlei_con_v2">
            <div class="fenlei_list_v2">
                <div class="xiangqing_title_v2"><strong>{{ data.goods_name }}{{ pname }}</strong></div>
                <div class="quanguo_title_v2"><strong>全国价格行情分析</strong></div>
                <div class="fenxi_box_v2">
                    <table>
                        <tr>
                            <td width="25%">今日均价：{% if getAnalysisAvg[3]  > 0   %}<font>{{ getAnalysisAvg[3]}}</font>元/{{ data.unit}} {% else %}无{% endif %}</td>
                            <td width="25%">昨日均价：{% if getAnalysisAvg[2]  > 0   %}<font>{{ getAnalysisAvg[2]}}</font>元/{{ data.unit}}{% else %}无{% endif %}</td>
                            <td width="25%">
                                 <?php 

                        $avgprice = \Mdg\Models\MarketAvgprice::getCateAvg($data->
                category_id, $pid);
                        if($avgprice > 0 ) {
                            $intval = $getAnalysisAvg[3] - $getAnalysisAvg[2];

                        echo "
                <span class='fenxi_sheng_v2'>￥{$intval}({$avgprice}%)</span>
                ";
                    }else if($avgprice == 0 ){
                        $intval = ($getAnalysisAvg[3] - $getAnalysisAvg[2]);
                        echo "
                <span>￥{$intval}({$avgprice}%)</span>
                ";
                    }else{
                         $intval = ($getAnalysisAvg[3] - $getAnalysisAvg[2]);
                        echo "
                <span class='fenxi_jiang_v2'>￥{$intval}({$avgprice}%)</span>
                ";
                    }
                    ?>
                  </td>
                  <td width="25%">一周内均价：      
                    {% if getAnalysisAvg[1]  > 0   %}
                <font>{{ getAnalysisAvg[1]}}</font>
                元/{{ data.unit}}
                    {% else %}
                    无
                    {% endif %}</td>
                        </tr>
                    </table>
                </div>  
                <div class="quanguo_title_v2"><strong>{{ data.goods_name }}{{ pname }}价格</strong></div>
                <div class="zhuxing_v2" id='zhuxingEcharts' >
                    柱形图
                </div>
                <div class="quanguo_title_v2"><strong>{{ data.goods_name }}{{ pname }}走势图</strong></div>

                 <div class="wl-xianxcon">
                    <div class="wl-xianxtab clearfix">
                       
                            <li {% if zzs == 7 %}class="on"{% endif %}> <a href="{{ url }}zzs=7">最近7天 </a></li>
                       
                        
                            <li {% if zzs == 1 %}class="on"{% endif %}><a href="{{ url }}zzs=1">最近1月</a></li>
                        
                        
                            <li {% if zzs == 3 %}class="on"{% endif %}><a href="{{ url }}zzs=3">最近3月</a></li>
                        
                        
                            <li {% if zzs == 6 %}class="on"{% endif %}><a href="{{ url }}zzs=6">最近半年</a></li>
                        

                    </div>
                    <style>
                    .wl-xianxtab li{ float:left; margin-left: 15px; line-height: 40px;}
                    .wl-xianxtab li a{ color:#333;}
                    .wl-xianxtab li.on a{ color:#f9ab14;}
                    .wl-xianxtab li a:hover{ color:#f9ab14;}
                    </style>
                    <div class="wl-xianImgCon" style="font-size:40px; line-height: 340px; text-align:center;" id='zhexian'></div>
                </div>
                <div class="quanguo_title_v2"><strong>全国信息来源</strong></div>
                <div class="laiyuan_v2">
                    <table>
                        {% for key,item in getMarketSellList['items'] %}
                        <tr class="laiyuan_tr_v2">
                            <td width="25%">{{ item.goods_name }}</td>
                            <td width="25%">{{ item.market_name }}</td>
                            <td width="25%">{{ item.price}}元/{{ item.unit}}</td>
                            <td width="25%">{{ item.addtime }}</td>
                        </tr>
                        {% endfor %}
                    </table>
                </div>
            </div>
            <div class="fenlei_right_v2">
                <div class="fenlei_rightCon_v2">
                    <div class="fenlei_rightT_v2"><strong>热门供应产品</strong></div>
                    <div class="fenlei_rightBox_v2">
                   {% for key, item in hot%}
                        <dl>
                            <dt><a href="/sell/info/{{item['id']}}">
                                 {% if item['thumb'] %}
                                <img src="{{ constant('IMG_URL') }}{{item['thumb']}}" height="130" width="130">
                                {% else %}
                                <img src="{{ constant('STATIC_URL')}}/mdg/images/detial_b_img.jpg" height="130" width="130">
                                {% endif %}
                            </a></dt>
                            <dd>
                                <a href="/sell/info/{{item['id']}}" title="">{{item['title']}}</a>
                                <span>￥{{item['min_price']}}~￥{{item['max_price']}}</span>
                                <span><?php echo Lib\Utils::getC($item["areas_name"] ? $item['areas_name'] : ''); ?></span>
                            </dd>
                        </dl>
                    {% endfor %}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{ partial('layouts/footer') }}
<script type="text/javascript">
    $(function(){

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

        

        $('#zhexian').highcharts({
            chart : {
                height : 280,
                type : 'line',
                margin : [50]
            },
            credits : {
                enabled : false
            },
            subtitle : {
                text : '',
                x : -20
            },
            xAxis : {
                categories : ['{{ xianImgCon["keys"]}}']

            },
            title : {
                 text : ''
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
                data: [{{ xianImgCon["vals"]}} ],
                color : '#f9ab14'
            }]
        });


    // $('#zhexian').load('/market/fold/{{ data.category_id}}/{{ data.id}}/{{pid}}/{{ zzs }}');
        $('#zhuxingEcharts').highcharts({
            chart : {
                height : 280,
                type : 'column',
                margin : [50]
            },
            credits : {
                enabled : false
            },
            subtitle : {
                text : '',
                x : -20
            },
            xAxis : {
                categories : ['{{ keys }}']

            },
            title : {
                 text : ''
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
                data: [{{ vals }}],
                color : '#f9ab14'
            }]
        });





    })

</script>