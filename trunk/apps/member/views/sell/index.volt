<!--头部-->
{{ partial('layouts/member_header') }}
<!-- add 2015.9.29 鼠标提示框 -->
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/jquery.powertip.css" />
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/version2.5/js/jquery.powertip.min.js"></script>
<style>
#powerTip{ padding: 10px; color:#666; font-family:'宋体'; line-height:16px; background:#E5E5E5; white-space:normal; word-wrap:break-word; max-width:430px;}
#powerTip.sw-alt:before, #powerTip.se-alt:before, #powerTip.s:before{ border-bottom:10px solid #E5E5E5;}
#powerTip.nw-alt:before, #powerTip.ne-alt:before, #powerTip.n:before{ border-top:10px solid #E5E5E5;}
</style>
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}我的供应</span>
        </div>
        <!-- 左侧 -->
        
        {{ partial('layouts/navs_left') }}
                    <!-- 右侧 -->
        <div class="center-right f-fr">

            <div class="my-supply f-oh pb30">
                <div class="title f-oh">
                    <span>我的供应</span>
                </div>
                <table cellpadding="0" cellspacing="0" width="875" class="list">
                    <tr height="41">
                        <th width="246">
                            <span class="m-left">商品信息</span>
                        </th>
                        <th width="172">供货价格</th>
                        <th width="184">供应数量</th>
                        <th width="143">
                            <select onchange="setcheck()"  id="selectcate" >
                                {% for key,val in state %}
                                <option value="{{key}}" {% if sellstate == key %} selected="selected" {% endif %}>{{val}}</option>
                                {% endfor %}
                            </select>
                        </th>
                        <th width="129">
                            <font class="m-right">操作</font>
                        </th>
                    </tr>
                    {% for key, sell in data %}
                        <tr height="28">
                            <td colspan="5">
                                <div class="m-box clearfix">
                                    <span class="bh f-fl">
                                        供应编号：{{ sell.sell_sn }}&nbsp;&nbsp;&nbsp;&nbsp;供应时间：
                                        {% if sell.stime %}{{ time_type[sell.stime]  }}~{{ time_type[sell.etime] }}{% endif %}
                                    </span>
                                    <em class="sj f-fr">发布时间：{{ date('Y-m-d H:i:s', sell.updatetime) }}</em>
                                </div>
                            </td>
                        </tr>
                        <tr height="123">
                            <td>
                                <span class="m-left">
                                    <a href="/sell/info/{{ sell.id }}"><em class="m-middle">供应商品：
                                     <?php echo \Lib\Func::sub_str($sell->title, 10); ?>
                                     </em>
                                     </a>
                                    <em class="m-middle clearfix">
                                        <font class="dq">供应地区：</font>
                                        <font class="dz">{{ sell.address }}</font>
                                    </em>
                                </span>
                            </td>
                            <td align="center">
                                <em class="bj">
                                    {% if sell.price_type == '1' %}
                                    <?php $arr=\Mdg\Models\SellStepPrice::getprice($sell->id,1);?>
                                    <i>{% if arr %}{{ arr[0]["price"] }}</i>元/{% if sell.goods_unit %}{{ goods_unit[sell.goods_unit] }}{% endif %} 起{% else %}暂无{% endif %}
                                    {% else %}
                                    <i>{{ sell.min_price }}~{{ sell.max_price }}</i>元/{% if sell.goods_unit %}{{ goods_unit[sell.goods_unit] }}{% endif %}
                                    {% endif %}
                                </em>
                                {% if sell.is_del==0 %}<a href="#"  onclick="javascript:newWindows('changeprice', '我的供应-修改价格', '/member/dialog/changeprice/{{ sell.id }}');">修改</a>{% endif %}
                            </td>
                            <td align="center">
                                <em class="bj">
                                {% if sell.quantity > 0 %}
                                {{ sell.quantity}}
                                <?php echo isset($goods_unit[$sell->goods_unit]) ? $goods_unit[$sell->goods_unit] : '';?>
                                {% else %}
                                不限
                                {% endif %}</em>
                                {% if sell.is_del==0 %}<a href="#" onclick="javascript:newWindows('changequantity', '我的供应-修改供应量', '/member/dialog/changequantity/{{ sell.id }}');" >修改</a>{% endif %}
                            </td>
                            <td class="mouse_title" align="center">
                                {% if sell.is_del==0  %}
                                    {% if (sell.state== 1 ) %}
                                        已发布
                                    {% endif %}
                                    {% if (sell.state== 2 )%}
                                        <a style="color:#333;" class="south-west-alt" title="<?php echo \Mdg\Models\SellCheck::getfailReason($sell->id);?>" href="javascript:;">审核失败</a> 
                                     
                                
                                    {% endif %}
                                    {% if (sell.state== 0 ) %}
                                        待审核
                                    {% endif %}
                                    {% else %}
                                        已删除
                                {% endif %}
                            </td>
                            <td>
                                <font class="m-right">
                                   {% if sell.is_del==0  %}
                                    <a href="/member/sell/edit?sellid={{ sell.id }}">重新编辑</a>
                                    <a href="/member/sell/delete?sellid={{ sell.id }}" onclick="return confirm('确定取消发布?')">取消发布</a>
                                    {% endif %}
                                    <?php if($sell->state == 1  && $sell->is_del== 0 &&  \Mdg\Models\Tag::checkSellBind($sell->id) && $userFarm  ) { ?>
                                    <a href="/member/tag/new?tid={{ sell.id }}">申请标签</a>
                                    <?php } ?>
                                    <?php if($sell->state == 1  && $sell->is_del== 0 &&  !\Mdg\Models\Tag::checkSellBind($sell->id) && $userFarm  ) { ?>
                                    <a href="javascript:void(0);">已申请</a>
                                    <?php } ?>
                                </font>
                            </td>
                        </tr>
                    {% endfor %}
                </table>
                <!-- 分页 -->
                {% if total_count>1 and total_count!=0  %}
                <div class="esc-page mt30 mb30 f-tac f-fr mr30">
                {{ pages }}
                    <span>
                    <form action="/member/sell/index" method="get">
                        <label>去</label>
                        <input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " value="1" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
                        <label>页</label>
                        <input type="hidden" name="total" value="{{total_count}}">
                        <input class="btn" type="submit" value="确定" />
                    </form>
                    </span>
                </div>
                {% endif %}
            </div>

        </div>
    </div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<script>
    $(function(){
        /* 鼠标悬停效果 */
        $('.south-west-alt').powerTip({
            placement: 's',
            smartPlacement: true
        });
    });
</script>
<script>
function go(){
     var p=$("#p").val();
 var count = {{total_count}};
 if(p>count){
    $("#p").val(count);
 }
}
function go(){
     var p=$("#p").val();
 var count = {{total_count}};
 if(p>count){
    $("#p").val(count);
 }
}
function setcheck(){
    var state=$("#selectcate").val();
    location.href='/member/sell/index?state='+state;
}
</script>
