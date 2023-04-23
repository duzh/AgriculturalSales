{% if data['keys']%}
    <div class="zuijinEcharts">
        <div id="zuijinEcharts" class="zuijinEcharts">
            
        </div>
</div>
<script type="text/javascript">
	$(function () {
        $('#zuijinEcharts').highcharts({
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
                categories : ['{{ data["keys"]}}']

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
                backgroundColor : '#7ac543',
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
                data: [{{ data["vals"]}} ],
                color : '#7ac543'
            }]
        });

	})
</script>

{% else %}
    <div class="zuijinEcharts">
        <div  class="zuijinEcharts" style="font-size:48px; color:#666; text-align:center; padding-top:80px;">
            暂无数据
        </div>
    </div>
{% endif %}