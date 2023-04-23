<!--头部 start-->
{{ partial('layouts/member_header') }}
<!--头部 end-->
<!--主体 start-->
<div class="center-wrapper pb30">
    <div class="bread-crumbs w1185 mtauto">
        <span>{{ partial('layouts/ur_here') }}&nbsp;已整合的可信农场</span>
    </div>
    <div class="w1185 mtauto clearfix">
        <!-- 左侧 start-->
        {{ partial('layouts/navs_left') }}
        <!-- 左侧 end-->
            <!-- 右侧 -->
            <div class="center-right f-fr">

                <div class="wms-fbMsg f-oh pb30">
                    <div class="title f-oh">
                        <span>已整合的可信农场</span>
                    </div>
                    {% for item in credit_farm['items'] %}
                    <div class="ms-list">
                        <dl class="f-oh">
                            <dt class="f-fl">
                                {% if item['picture_path'] %}
                                <img src="http://yncmdg.b0.upaiyun.com/{{item['picture_path']}}" height="110" width="160" alt="">
                                {% else %}
                                <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" height="110" width="160" alt="">
                                {% endif %}
                            </dt>
                            <dd class="f-fr">
                                <div class="m-title f-oh">
                                    <font class="f-db f-fl">{{item['userfarm']['farm_name']}}农场</font>
                                    <span class="wms-icon f-db icon1 f-fl">可信农场</span>
                                </div>
                                <p>{% if item['userfarm'] %}{{item['userfarm']['describe'] ? item['userfarm']['describe'] : '-'}}{% else %}-{% endif %}</p>
                            </dd>
                        </dl>
                        <table cellspacing="0" cellpadding="0">
                            <tr height="40">
                                <th width="110">姓名</th>
                                <td width="325">
                                    <span>                                        
                                        {% if item and item['type']==1%}
                                        {{item['company_name']}}
                                        {% else %}
                                        {% endif %}
                                        {% if item and item['type']==0%}
                                        {{item['name']}}
                                        {% else %}
                                        {% endif %}
                                        {% if item and item['type']==3%}
                                        {{item['company_name']}}
                                        {% else %}
                                        {% endif %}
                                        {% if item and item['type']==2%}
                                        {{item['company_name']}}
                                        {% else %}
                                        {% endif %}
                                    </span>
                                </td>
                                <th width="110">农场名</th>
                                <td width="325">
                                    <span>{{item['userfarm']['farm_name']}}</span>
                                </td>
                            </tr>
                            <tr height="40">
                                <th width="110">身份类型</th>
                                <td width="325">
                                    <span>                                        
                                        {% if item and item['type']==1%}企业{% else %}
                                        {% endif %}
                                        {% if item and item['type']==0%}
                                        个人
                                        {% else %}
                                        {% endif %}
                                        {% if item and item['type']==3%}
                                        农村合作社
                                        {% else %}
                                        {% endif %}
                                        {% if item and item['type']==2%}
                                        家庭农场
                                        {% else %}
                                        {% endif %}
                                    </span>
                                </td>
                                <th width="110">农场地址</th>
                                <td width="325">
                                    <span>{{item['userfarm']['province_name']}}{{item['userfarm']['city_name']}}{{item['userfarm']['district_name']}}{{item['userfarm']['town_name']}}{{item['userfarm']['village_name']}}</span>
                                </td>
                            </tr>
                            <tr height="40">
                                <th width="110">手机号</th>
                                <td width="325">
                                    <span><?=Mdg\Models\Users::getUserMobile($item['user_id'])?></span>
                                </td>
                                <th width="110">农场面积</th>
                                <td width="325">
                                    <span>{% if item and item['userfarm']['farm_area'] %}{{item['userfarm']['farm_area']}}亩 {% else %}-{% endif %}</span>
                                </td>
                            </tr>
                            <tr height="40">
                                <th width="110">土地来源</th>
                                <td width="325">
                                    <span>{% if item['userfarm'] %}{% if item['userfarm']['source']==0%}自有{% else %}流转{% endif %}{% else %}-{% endif %}</span>
                                </td>
                                <th width="110">种植作物</th>
                                <td width="325">
                                    <span>{{item['category_name']}}</span>
                                </td>
                            </tr>
                            <tr height="40">
                                <th width="110">土地使用年限</th>
                                <td width="325">
                                    <span>{% if item['userfarm'] %}{{item['userfarm']['start_year'] ? item['userfarm']['start_year'] : '-'}}{% else %}0{% endif %}年
                                    {% if item['userfarm'] %}{{item['userfarm']['start_month'] ? item['userfarm']['start_month'] : '-'}}{% else %}0{% endif %}月&nbsp;—&nbsp;
                                    {% if item['userfarm'] %}{{item['userfarm']['year'] ? item['userfarm']['year'] : '-'}}{% else %}0{% endif %}年{% if item['userfarm'] %}{{item['userfarm']['month'] ? item['userfarm']['month'] : '-'}}{% else %}0{% endif %}月</span>
                                </td>
                                <th width="110">供应信息</th>
                                <td width="325">
                                    <span>
                                        {% if item['sell_total'] %}
                                        <i class="f-fl">{{item['sell_total']}} </i>
                                        <a class="f-fr" href="/member/conformity/sell_list?credit_id={{item['credit_id']}}">查看</a>
                                        {% else %}0{% endif %}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    {% endfor %}
                    <!-- 分页 -->
                <!-- 分页 start-->
                    {% if credit_farm['total'] > 1 %}
                    <div class="esc-page mt30 mb30 f-tac f-fr mr30">
                         {{ credit_farm['pages']}}                
                        <span>
                            <form action="/member/conformity/index" method="get">           
                                <label>去</label>
                                <input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " value="{{p}}" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
                                <label>页</label>     
                                <input type="hidden" name="total" value="{{credit_farm['total']}}">              
                                <input class="btn" type="submit" value="确定" onclick="go()"/>
                            </form>
                        </span>                  
                    </div>
                    {% endif %}
                <!-- 分页 end-->
                    <div style="clear:both;"></div>
                    <div class="wms-center-btns f-tac">
                        <a href="javascript:void(0);" class="btn" onclick="window.history.go(-1);">返回</a>
                    </div>
                </div>

            </div>       
        </div>
    </div>
</div>
<!--主体 end-->

<!--尾部 start-->
{{ partial('layouts/footer') }}
<!--尾部 end-->

</script>