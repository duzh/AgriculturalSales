<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}我的采购</span>
        </div>
        <!-- 左侧 -->
        
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
        <div class="center-right f-fr">

                <div class="my-purchase f-oh pb30">
                    <div class="title f-oh">
                        <span>我的采购</span>
                    </div>
                    <table cellpadding="0" cellspacing="0" width="875" class="list">
                        <tr height="41">
                            <th width="246">
                                <span class="m-left">商品信息</span>
                            </th>
                            <th width="172">采购数量</th>
                            <th width="184">报价截止时间</th>
                            <th width="143">
                                <select name="state" id="" onchange='onSelect(this.value)'>
                                    <option value="all">全部状态</option>
                                    {% for key, item in _state %}
                                    <option value="{{ key}}" <?php if(isset($_GET['state']) && $_GET['state'] == "$key" ){ echo 'selected';}?>>{{ item}}</option>
                                    {% endfor %}
                                </select>
                            </th>
                            <th width="129">
                                <font class="m-right">操作</font>
                            </th>
                        </tr>
                        {% for key, val in data %}
                        <tr height="28">
                            <td colspan="5">
                                <div class="m-box clearfix">
                                    <span class="bh f-fl">
                                        采购编号：{{ val.pur_sn }}&nbsp;&nbsp;&nbsp;&nbsp;
                                    </span>
                                    <em class="sj f-fr">发布时间：{{ date('Y-m-d H:i:s', val.createtime) }}</em>
                                </div>
                            </td>
                        </tr>
                        <tr height="123">
                            <td>
                                <span class="m-left">
                                    <em class="m-middle">采购商品：
                                    <?php echo \Lib\Func::sub_str($val->title, 10); ?>
                                    </em>
                                    <em class="m-middle clearfix">
                                        <font class="dq">采购地区：</font>
                                        <font class="dz">
                                        {{ val.areas_name }}
                                        {% if val.state == 2 %}
                                        <p>审核未通过原因:<?php echo Mdg\Models\PurchaseCheck::getPurchaseFailReason($val->id);?></p>
                                        {% endif %}
                                        </font>
                                    </em>
                                </span>
                            </td>
                            <td align="center">
                                {% if val.quantity > 0 %}
                                {{ val.quantity }}
                                {{ goods_unit[val.goods_unit] }}
                                {% else %}
                                    不限
                                {% endif %}
                            </td>
                            <td align="center">
                                <span class="rq">
                                    {{ date('Y-m-d', val.endtime) }}<br /> 
                                    {{ date('H:i:s', val.endtime) }}
                                </span>
                            </td>
                            <td align="center">
                                {% if val.is_del == 1 %}
                                    已删除
                                {% else %}
                                {{ _state[val.state]}}
                                {% endif %}
                            </td>
                            <td>
                                <?php $countquo=\Mdg\Models\PurchaseQuotation::countQuo($val->id);?>
                                <font class="m-right">
                                    <em class="bj">
                                        {% if countquo %}
                                        <a href="/member/purchase/quolist?purid={{ val.id }}"><i>{{ countquo }}</i>个供应商报价</a>
                                        {% else %}
                                        暂无供应商报价
                                        {% endif %}
                                    </em>
                                     {% if val.is_del==0  %}
                                     <a href="/member/purchase/remove/{{ val.id }}" onclick="return confirm('确定取消！');">取消采购</a>{% else %}
                                     已删除
                                     {% endif %}
                                </font>
                            </td>
                        </tr>
                        {% endfor %}
                    </table>
                    <!-- 分页 -->
                {% if total_count>1 and total_count!=0 %}
                <form action="/member/purchase/index" method="get">
                <div class="esc-page mt30 mb30 f-tac f-fr mr30">
                    {{ pages}}
                    <span>
                        <label>去</label>
                        <input type="text" name='p' id="p" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value='1'>
                        <label>页</label>
                    </span>
                    <input class="btn" type="submit" value="确定" onclick="go()"></div>

            </div>
            </form>
            {% endif %}
        </div>
    </div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/jquery.powertip.css" />
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/jquery.powertip.min.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/center_trHover.js"></script>
<script>
function go(){
     var p=$("#p").val();
 var count = {{total_count}};
 if(p>count){
    $("#p").val(count);
 }
}
function onSelect(Svalue) {
    location.href='/member/purchase/index?state='+Svalue;
}
</script>
<style>
#powerTip{ width: 206px; padding: 10px 10px 10px; color:#666; font-family:'宋体'; line-height:20px; background:#E5E5E5; white-space:normal; word-wrap:break-word;}
#powerTip.sw-alt:before, #powerTip.se-alt:before{ border-bottom:10px solid #E5E5E5;}
</style>
