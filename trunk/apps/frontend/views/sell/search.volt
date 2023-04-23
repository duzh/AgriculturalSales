
<table class="cg_table" >
        <tr height="30" class="title">
            <th style="text-align:center;" width="134">报价时间</th>
            <th width="106">品名</th>
            <th width="87">供应地</th>
            <th width="67">供应量</th>
            <th width="233">规格</th>
            <th width="104">单价（元）</th>
            <th width="229">供应商</th>
        </tr>
         {% if data.items is defined %}
            {% for sell in data.items %}

        <tr height="40">
            <td style="text-align:center;">{{ date("Y-m-d H:i:s ",sell.createtime)}}</td>
            <td>{{ sell.title }}</a></td>
            <td>{{ sell.areas_name }}</td>
            <td>{{ sell.quantity }}</td>
            <td class="mouse_title">
             <a class="south-west-alt" title="采购芒果 台农一号 单个重:5两以 台农一号 单个重:5两以台农一号 单个重:5两以 台农一号 | 长度:10cm以上" href="javascript:;">
                {{ sell.spec }}</a></td>
            <td><strong>{{ sell.min_price~sell.max_price }}</strong></td>
            <td><font>{{ sell.uname }}</font><a class="btn cg_btn"  href="javascript:;" name="{{sell.id}}">采购</a><a class="btn" href="javascript:;">详情</a><input type="p{{sell.id}}" value="<?php echo Mdg\Models\Sell::$type2['2']?> "></td>
        </tr>
          {% endfor %}
    {% endif %}
    </table>
    <!-- 块 end -->
    <!-- 块 start -->

        <div class="page">
            {{ pages }}
            <span>共{{ data.total_pages }}页</span>
        </div>


<!-- 采购弹框 start -->
{{ partial('sell/alert') }}
<!-- 弹框 end -->
{{ partial('sell/showuser') }}