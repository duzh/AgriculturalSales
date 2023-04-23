{{ partial('layouts/page_header') }}
<!-- 主体内容开始 -->
<div class="ur_here w960">
    <span><a href="/">首页</a>&nbsp;>&nbsp;<a href="/sell/index">供应大厅</a>&nbsp;>&nbsp;<a href="/sell/index?category={{ category.id }}">{{category.title}}</a>&nbsp;>&nbsp;{{sell.title}}</span>
</div>
<div class="gy_detial">
    <!-- 左侧 start -->
    <div class="left f-fl">
        <!-- 块 start -->
        <div class="canshu">
            <h6 class="mt20 clear">{{sell.title}}<span>[供应编号：{{sell.sell_sn}}]</span></h6>
            <div class="dl_box">
                <!-- 块 start -->
                <dl class="f-fl">
                    <?php if($img){ ?>
                    <dt><img src="<?php echo $imgs['path']; ?>" width="300px" height="300px" /></dt>
                    <dd>
                        <a class="prev_btn" href="javascript:;">上一个</a>
                        <div class="ul_box">
                            <ul>
                                {% for key, val in img %}
                                {% if key==p %}
                                <li class="active">
                                {% else %}
                                <li>
                                {% endif %}
                                 <a href="/sell/edit/{{sellid}}?p={{key}}"><img src="{{val.path}}" width="50px" height="50px"/></a></li>
                                {% endfor %}
                            </ul>
                        </div>
                        <a class="next_btn" href="javascript:;">下一个</a>
                    </dd>
                    <?php }else{?>
                    <dt><img src="{{ constant('STATIC_URL') }}mdg/images/detial_b_img.jpg" /></dt>
                    <dd>
                        <a class="prev_btn" href="javascript:;">上一个</a>
                        <div class="ul_box">
                            <ul>
                                <li class="active"><a href="javascript:;"><img src="{{ constant('STATIC_URL') }}mdg/images/detial_s_img.jpg" /></a></li>
                                <li><a href="javascript:;"><img src="{{ constant('STATIC_URL') }}mdg/images/detial_s_img.jpg" /></a></li>
                                <li><a href="javascript:;"><img src="{{ constant('STATIC_URL') }}mdg/images/detial_s_img.jpg" /></a></li>
                                <li><a href="javascript:;"><img src="{{ constant('STATIC_URL') }}mdg/images/detial_s_img.jpg" /></a></li>
                                <li><a href="javascript:;"><img src="{{ constant('STATIC_URL') }}mdg/images/detial_s_img.jpg" /></a></li>
                            </ul>
                        </div>
                        <a class="next_btn" href="javascript:;">下一个</a>
                    </dd>
                    <?php }?>
                     
                </dl>
                <!-- 块 end -->
                <!-- 块 end -->
                <div class="cs_right f-fl">
                    <p>供应时间：<strong><?php echo Mdg\Models\Sell::$type[$sell->stime]?>
                                ~<?php echo Mdg\Models\Sell::$type[$sell->etime]?></strong></p>
                    <p><span>供应地区：</span><em>{{sell.areas_name}}</em></p>
                    <p>产品报价：<strong>{{sell.min_price}}~{{sell.max_price}}/<?php echo Mdg\Models\Sell::$type3[$sell->goods_unit]?></strong></p>
                    <p>{{date("Y-m-d H:i:s",sell.updatetime)}}</p>
                    <p class="line">&nbsp;</p>
                    <p>该供应商还供应： {% for sups in sup %}
                                         <a href="/sell/edit/{{sups.id}}">{{sups.title}}、</a>
                                         {% endfor %}</p>
                    <div class="wtcg_btn">
                        <img src="{{ constant('STATIC_URL') }}mdg/images/register_icon2.png" />
                        <a href="javascript:newWindows('newbuy', '确认采购信息', '/member/dialog/newbuy/{{ sell.id }}');" >立即委托采购</a>
                    </div>
                </div>
                <!-- 块 start -->
            </div>
        </div>
        <!-- 块 end -->
        <!-- 块 start -->
        <div class="message">
            <div class="m_title">详细描述</div>
            <table class="m_table">
                <tr height="24">
                    <td width="223">供应编号：{{sell.sell_sn}}</td>
                    <td width="224">产品品名：{{sell.title}}</td>
                    <td>产品品种：{{category.title}}</td>
                </tr>
                <tr height="24">
                    <td>供应时间：<?php echo Mdg\Models\Sell::$type[$sell->stime]?>
                                ~<?php echo Mdg\Models\Sell::$type[$sell->etime]?></td>
                    <td colspan="2">产品规格：{{sell.spec}}</td>
                </tr>
            </table>
            <p class="line">&nbsp;</p>
            <p><?php if($content){ echo $content->content;}else{echo "暂无描述!";} ?></p>
        </div>
        <!-- 块 end -->
    </div>
    <!-- 左侧 end -->
    <!-- 右侧 start -->
    <div class="right f-fr mt20">
        <!-- 块 start -->
        <div class="other_message">
            <h6>供应商信息</h6>

            <ul>
                <li><span>名称：</span><em>{{UsersExt.name}}</em></li>
                <li><span>地区：</span><em>{{UsersExt.address}}</em></li>
            </ul>
        </div>
        <!-- 块 end -->
        <!-- 块 start -->
        <div class="other_message">
            <h6>他还供应<a href="/sell/index?uid={{ sell.uid }}">查看全部</a></h6>
            <ul class="other_box">
                {% for sups in sup %}
                <li>
                    <a href="/sell/save/{{sup.id}}">{{sups.title}}</a><br />
                 <strong>{{sups.min_price}}~{{sups.max_price}}
                          元</strong>
                          <font>{{date("Y-m-d ",sups.updatetime)}}</font>
                </li>
                {% endfor %}
            </ul>
        </div>
        <!-- 块 end -->
    </div>
    <!-- 右侧 end -->
</div>
<!-- 主体内容结束 -->
<!-- 采购弹框 start -->

<!-- 弹框 end -->

{{ partial('sell/showuser') }}
<!-- 底部开始 -->
{{ partial('layouts/footer') }}
<!-- 底部结束 -->
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js"></script>

</body>
</html>
