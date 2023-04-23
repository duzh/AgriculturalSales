<link rel="stylesheet" type="text/css" href="{{ constant('WULIU_URL') }}/mdg/wuliu/css/style.css" /> 
<link rel="stylesheet" href="">
    <div class="wl-Con wl-auto clearfix">
        <div class="wl-conLeft wl-fl">
            <div class="wl-che-selectBox">
                <div class="wl-che-title">
                    价格行情详细信息
                </div>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="b-l-n"><strong>产名：</strong></td>
                        <td>{{data.goods_name}}</td>
                        <td><strong>所属分类：</strong></td>
                        <td class="b-r-n">

                        {% if category %}
                            {{category['0']['title']}}->
                            {{category['1']['title']}}
                            {% else %}
                            无
                        {% endif %}
                        </td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>地区：</strong></td>
                        <td><?php echo Mdg\Models\AreasFull::getAreasNametoid($data->province_id);?>
                        {% if data.city_id %}
                        <?php echo Mdg\Models\AreasFull::getAreasNametoid($data->city_id);?>
                        {% endif %}
                        {% if data.district_id %}
                        <?php echo Mdg\Models\AreasFull::getAreasNametoid($data->district_id);?>
                        {% endif %}


                        </td>
                        <td><strong>市场：</strong></td>
                        <td class="b-r-n">{{data.market_name}}</td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>报价：</strong></td>
                        <td>{{ data.price }}{{data.unit}}</td>
                        <td><strong>是否采集：</strong></td>
                        <td class="b-r-n">{% if data.source == 0 %}否{% else %}是{% endif %}</td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>报价人：</strong></td>
                        <td>{{ data.contact_name}}</td>
                        <td><strong>手机：</strong></td>
                        <td class="b-r-n">{{data.contact_phone}}</td>
                    </tr>
                    <tr>
                        <td class="b-l-n"><strong>行情分析：</strong></td>
                        <td class="b-r-n b-b-n" colspan="3">{{data.analyze}}</td>
                    </tr>
                </table>
            </div>
        </div>
