<div class="message">
<ul class="gy_list_nav clearfix"  id="gy_list_nav">
    <li class="active"><span>商品详情</span></li>

    <li><span>服务商介绍</span></li>
</ul>
<div class="gy_tabBox" id="gy_tabBox">
    <div class="box" style="display:block;">
        <table class="m_table">
            <tr height="24">
                <td width="223">供应编号：{{ sell.sell_sn }}</td>
                <td width="224">产品品名：{{ sell.title }}</td>
                <td>产品品种：{{ sell.breed }}</td>
            </tr>
            <tr height="24">
                <td>供应时间：{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}</td>
                {% if sell.spec %}<td colspan="2">产品规格：{{ sell.spec }}</td>{% endif %}
            </tr>
        </table>
        <p class="line">&nbsp; </p>
        <p>
           {% if sell.scontent  %}
              {{ sell.scontent.content }}
           {% else %}
             {{contents}}
           {% endif %}
       </p>
    </div>
    <div class="box">
        <!--服务商信息-->
        {{service['shop_desc']}}
    </div>
</div>


</div>
<?php if(isset($service) && isset($service['picList']) && $service['picList']) {?>
<!--  实地查看 start -->
        <div class="message">
            <div class="m_title">实地查看</div>
            <div style="padding:20px 0;">
            <p>{{service['desc']}}</p>
            <br>

                {% for val in service['picList'] %}
                <li  style='float:left;'>
                    <a href="#">
                            <img src="{{ val['pic_path'] }}" width="180" height="180" />
                    </a>
                </li>
                {% endfor %}
            </div>
        </div>
<!-- 实地查看 end -->
<?php } ?>
