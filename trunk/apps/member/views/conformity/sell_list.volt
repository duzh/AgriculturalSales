<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}已整合的可信农场发布的供应信息</span>
        </div>
        <!-- 左侧 -->       
        {{ partial('layouts/navs_left') }}
            <!-- 右侧 -->
            <div class="center-right f-fr">

                <div class="wms-fbMsg-list f-oh pb30">
                    <div class="title f-oh">
                        <span>已整合的可信农场发布的供应信息</span>
                    </div>
                    <div class="m-title f-oh">
                        <font class="f-db f-fl">{{farm_name}}农场</font>
                        <span class="wms-icon f-db icon1 f-fl">可信农场</span>
                    </div>
                    <table cellspacing="0" cellpadding="0" class="list">
                        <tr height="28">
                            <th width="253">供应产品</th>
                            <th width="115">供应量</th>
                            <th width="116">供应的</th>
                            <th width="115">供应价格</th>
                            <th width="139">发布时间</th>
                            <th width="130">交易笔数</th>
                        </tr>
                    {% if sell_info %}
                    {% for key,sell in sell_info['sell'] %}  
                        <tr height="80">
                            <td>
                                <a href="#">
                                    <dl class="f-oh">
                                        <a href="/sell/info/{{ sell['id']}}">
                                        <dt class="f-fl">
                                        {% if sell and sell['thumb'] %}
                                            <img src="http://yncmdg.b0.upaiyun.com/{{sell['thumb']}}" height="58" width="58" alt="">
                                        {% else %}
                                            <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" height="58" width="58" alt="">
                                        {% endif %}
                                        </dt>
                                        <dd class="f-fl"><?php echo \Lib\Func::sub_str($sell['title'], 10); ?></dd>
                                        </a>
                                    </dl>
                                </a>
                            </td>
                            <td align="center">
                                {% if sell['quantity'] > 0 %}
                                {{ sell['quantity'] }}
                                <?php echo isset($goods_unit[$sell['goods_unit']]) ? $goods_unit[$sell['goods_unit']] : '';?>
                                {% else %}
                                不限
                                {% endif %}
                            </td>
                            <td align="center"><?php echo Lib\Utils::getC($sell['areas_name'] ? $sell['areas_name']: ''); ?></td>
                            <td align="center">
                                <font>
                                    <i>{{ sell['min_price'] }}~{{ sell['max_price'] }}</i><br>元/{{ goods_unit[sell['goods_unit']] }}</td>
                                </font>
                            </td>
                            <td align="center">{{ date("Y-m-d ",sell['createtime'])}}</td>
                            <td align="center">{{sell['orders'] ? sell['orders'] : 0 }}</td>
                        </tr>
                        {% endfor %}
                        {% endif %}
                    </table>
                <!-- 分页 -->
                {% if sell_info['total'] > 1   %}
                <div class="esc-page mt30 mb30 f-tac f-fr mr30">
                {{ sell_info['pages'] }}
                    <span>
                    <form action="/member/conformity/sell_list" method="get">
                        <label>去</label>
                        <input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " value="{{p}}" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
                        <label>页</label>
                        <input type="hidden" name="total" value="{{sell_info['total']}}">
                        <input class="btn" type="submit" value="确定" />
                    </form>
                    </span>
                </div>
                {% endif %}
                <div style="clear:both;"></div>
                <div class="wms-center-btns f-tac">
                    <a href="javascript:void(0);" class="btn" onclick="window.history.go(-1);">返回</a>
                </div>            
            </div>
        </div>
    
    </div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}

