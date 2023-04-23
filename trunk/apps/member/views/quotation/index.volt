<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}我的报价</span>
        </div>
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
        <div class="center-right f-fr">

            <div class="my-sell f-oh pb30">
                <div class="title f-oh">
                    <span>我的报价</span>
                </div>
                <table cellpadding="0" cellspacing="0" width="875" class="list">
                    <tr height="41">
                        <th width="231">
                            <span class="m-left">采购商品信息</span>
                        </th>
                        <th width="231">采购商</th>
                        <th width="240">我的报价</th>
                        <th width="173">
                            <font class="m-right">报价数量</font>
                        </th>
                    </tr>
                    {% for key, quo in data.items %}
                    <tr height="28">
                        <td colspan="4">
                            <div class="m-box clearfix">
                                <span class="sj f-fl">截止时间：{{ quo.purchase ? date('Y-m-d H:i:s', quo.purchase.endtime) : '' }}</span>
                            </div>
                        </td>
                    </tr>
                    <tr height="123">
                        <td>
                            <span class="m-left">
                                商品名称： {{ quo.purchase ? quo.purchase.title : '' }}<br />
                                采购数量：
                                {% if quo.purchase and quo.purchase.quantity > 0  %}
                                {{ quo.purchase ? quo.purchase.quantity : '' }}
                                {{ quo.purchase ? goods_unit[quo.purchase.goods_unit] : '' }}
                                {% else %}
                                    不限
                                {% endif %}
                                <br />
                                商品规格：
                                <?php if($quo->purchase){ echo  @Lib\Utils::c_strcut($quo->purchase->pcontent->content, 42);
                                      }else{ echo '';}
                                ?>
                            </span>
                        </td>
                        <td>
                            <span class="m-middle">姓名：{{ quo.purchase ? quo.purchase.username : '' }}</span>
                            <span class="m-middle">电话：{{ quo.purchase ? quo.purchase.mobile : ''}}</span>
                            <span class="m-middle clearfix">
                                <font class="dq">供应地区：</font>
                                <font class="dz">{{ quo.purchase ? quo.purchase.address : '' }}</font>
                            </span>
                        </td>
                        <td align="center">
                            <i> {{ quo.price ? quo.price : '' }}</i>元/ {{ quo.purchase ? goods_unit[quo.purchase.goods_unit] : ''}}
                        </td>
                        <td>
                            <font class="m-right">
                                共<i>{{ quo.countQuo }}</i>家报价
                            </font>
                        </td>
                    </tr>
                    {% endfor %}
                </table>
                <!-- 分页 -->

                {% if total_count>1 and total_count!=0 %}
                <form action="/member/quotation/index" method="get">
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
<script>
    function go(){
     var p=$("#p").val();
 var count = {{total_count}};
 if(p>count){
    $("#p").val(count);
 }
}
    
</script>
