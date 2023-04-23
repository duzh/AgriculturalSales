<!-- 蔬菜 -->
    <div class="macrot f-oh">
                <!-- 左 -->
                <div class="assort assort1 f-fl">
                    <div class="box">
                        <div class="imgs f-oh">
                            <img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/assort1-img.jpg" alt="">
                        </div>
                        <div class="setDiv"></div>
                        <div class="list">
                            {% for key, item in veg['cate'] %}
                             {% if key < 9 %}
                               <!-- <a href='/sell/index?mc={{item['parent_id']}}&first={{ item['abbreviation']}}&c={{ item['id'] }}'><?php echo \Lib\Func::sub_str($item['title'], 10); ?></a> -->
                               <a href="/sell/mc{{item['parent_id']}}_a0_c{{item['id']}}_f{{item['abbreviation']}}_p1"><?php echo \Lib\Func::sub_str($item['title'], 10); ?></a>
                            {% endif %}
                            {% endfor %}
                        </div>
                    </div>
                    <div class="spread">
                    <!--
                       <div class="links">
                            <a href="#">特惠活动一快参加</a>
                        </div>
                        <div class="links">
                            <a href="#">推广活动推广活动额额</a>
                        </div>
                    -->
                    </div>
                </div>
                <!-- 中 -->
                <div class="gy-list f-fl">
                    <div class="title f-oh">
                        <span>供应</span>
                        <a href="/sell/mc{{  vegcid }}_a0_c0_f_p1">更多&gt;</a>
                    </div>
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr height="27">
                            <th width="27"></th>
                            <th align="left" width="124">产品名</th>
                            <th width="171">供应地</th>
                            <th width="96">供应量</th>
                            <th width="144">价格</th>
                            <th width="78">立即采购</th>
                            <th width="14"></th>
                        </tr>
                    </table>
                    <ul class="gyCon">
                         {% for key, item in veg['sell'] %}
                        <li>
                            <div class="box f-oh">
                                <span class="w1">
                                    <a href="/sell/info/{{ item['id']}}">
                                    {{ item['title']}}
                                    </a>
                                </span>
                                <span class="w2">{{ item['areas_name']}}</span>
                                <span class="w3">
                                    {% if item['quantity'] > 0 %}
                                {{ item['quantity']}}
                                <?php echo isset($goods_unit[$item['goods_unit']]) ? $goods_unit[$item['goods_unit']] : '';?>

                                {% else %}
                                不限
                                {% endif %}
                                </span>
                                <span class="w4">
                                    <i>{{ item['min_price']}}-{{ item['max_price']}}</i>元/<?php echo isset($goods_unit[$item['goods_unit']]) ? $goods_unit[$item['goods_unit']] : '';?>
                                </span>
                                <span class="w5">
                                    <a href="javascript:newWindows('newbuy', '确认采购信息', '/member/dialog/newbuy/{{ item['id']}}');">采购</a>
                                </span>
                            </div>
                        </li>
                        {% endfor %}
                    </ul>
                </div>
                <!-- 右 -->
                <div class="cg-list f-fr">
                    <div class="title f-oh">
                        <span>采购</span>
                        <a href="/purchase/mc{{  vegcid }}_a0_c0_f_p1">更多&gt;</a>
                    </div>
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr height="27">
                            <th width="24"></th>
                            <th align="left" width="116">产品名</th>
                            <th width="88">采购量</th>
                            <th width="46">报价</th>
                            <th width="14"></th>
                        </tr>
                    </table>
                    <ul class="cgCon">
                        {% for key, item in veg['pur'] %}
                        <li>
                            <div class="box f-oh">
                                <span class="w1">{{ item['title']}}</span>
                                <span class="w2"> {% if item['quantity'] > 0 %}
                                {{ item['quantity']}}
                                <?php 
                                echo isset($goods_unit[$item['goods_unit']]) ? $goods_unit[$item['goods_unit']] : '';
                                ?>
                                {% else %}
                                不限
                                {% endif %}</span>
                                <span class="w3">
                                   <a href="javascript:newWindows('newquo', '确定报价', '/member/dialog/newquo/{{ item['id']}}');">报价</a>
                                </span>
                            </div>
                        </li>
                        {% endfor %}
                    </ul>
                </div>

    </div>
<!-- 蔬菜 -->
<!-- 调用 -->
    <div class="advertising w1185 mtauto f-tac">
            <div id="ad20" attr-box="20"  class="bannerImg-only" >
            <input type="hidden" value="20" id="location_id" name="adSrc">
            </div>
            <!--<script>fnAdMore($('#ad20'))</script>-->
    </div>
<!-- 调用 -->
<!--广告位-->
    <div class="home-adBox f-oh mt20">
        <a href="#"><img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.5/images/home-adImg1.jpg" alt=""></a>
    </div>
<!--广告位-->
<!-- 水果 -->
    <div class="macrot f-oh">
        <!-- 左 -->
        <div class="assort assort2 f-fl">
            <div class="box">
                <div class="imgs f-oh">
                    <img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/assort2-img.jpg" alt="">
                </div>
                <div class="setDiv"></div>
                <div class="list">
                    {% for key, item in fruit['cate'] %}
                    {% if key < 9 %}
                      <a href="/sell/mc{{item['parent_id']}}_a0_c{{item['id']}}_f{{item['abbreviation']}}_p1">
                        <?php echo \Lib\Func::sub_str($item['title'], 10); ?></a>
                    {% endif %}
                    {% endfor %}
                </div>
            </div>
            <div class="spread">
                <!--
                <div class="links">
                    <a href="#">特惠活动一快参加</a>
                </div>
                <div class="links">
                    <a href="#">推广活动推广活动额额</a>
                </div>
                -->
            </div>
        </div>
        <!-- 中 -->
        <div class="gy-list f-fl">
            <div class="title f-oh">
                <span>供应</span>
                <a href="/sell/mc{{  fruitid }}_a0_c0_f_p1">更多&gt;</a>
            </div>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr height="27">
                    <th width="27"></th>
                    <th align="left" width="124">产品名</th>
                    <th width="171">供应地</th>
                    <th width="96">供应量</th>
                    <th width="144">价格</th>
                    <th width="78">立即采购</th>
                    <th width="14"></th>
                </tr>
            </table>
            <ul class="gyCon">
                {% for key, item in fruit['sell'] %}
                <li>
                    <div class="box f-oh">
                        <span class="w1">
                            <a href="/sell/info/{{ item['id']}}">{{ item['title']}}</a>
                        </span>
                        <span class="w2">{{ item['areas_name']}}</span>
                        <span class="w3">
                                {% if item['quantity'] > 0 %}
                                {{ item['quantity']}}
                                <?php 
                                echo isset($goods_unit[$item['goods_unit']]) ? $goods_unit[$item['goods_unit']] : '';
                                ?>
                                {% else %}
                                不限
                                {% endif %}
                        </span>
                        <span class="w4">
                            <i>{{ item['min_price']}}-{{ item['max_price']}}</i>元/
                            <?php echo isset($goods_unit[$item['goods_unit']]) ? $goods_unit[$item['goods_unit']] : ''; ?>
                        </span>
                        <span class="w5">
                            <a href="javascript:newWindows('newbuy', '确认采购信息', '/member/dialog/newbuy/{{ item['id']}}');" >采购
                            </a>
                        </span>
                    </div>
                </li>
                {% endfor %}
            </ul>
        </div>
        <!-- 右 -->
        <div class="cg-list f-fr">
            <div class="title f-oh">
                <span>采购</span>
                <a href="/purchase/mc{{  fruitid }}_a0_c0_f_p1">更多&gt;</a>
            </div>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr height="27">
                    <th width="24"></th>
                    <th align="left" width="116">产品名</th>
                    <th width="88">采购量</th>
                    <th width="46">报价</th>
                    <th width="14"></th>
                </tr>
            </table>
            <ul class="cgCon">
                {% for key, item in fruit['pur'] %}
                <li>
                    <div class="box f-oh">
                        <span class="w1">{{ item['title']}}</span>
                        <span class="w2">
                                {% if item['quantity'] > 0 %}
                                {{ item['quantity']}}
                                <?php 
                                echo isset($goods_unit[$item['goods_unit']]) ? $goods_unit[$item['goods_unit']] : '';
                                ?>
                                {% else %}
                                不限
                                {% endif %}
                        </span>
                        <span class="w3">
                            <a href="javascript:newWindows('newquo', '确定报价', '/member/dialog/newquo/{{ item['id']}}');">报价</a>
                        </span>
                    </div>
                </li>
                {% endfor %}
            </ul>
        </div>

    </div>
<!-- 水果 -->
<!-- 调用 -->
    <div class="advertising w1185 mtauto f-tac">
        <div id="ad21" attr-box="21"  class="bannerImg-only" >
        <input type="hidden" value="21" id="location_id" name="adSrc">
        </div>
        <!--<script>fnAdMore($('#ad21'))</script>-->
    </div>
<!-- 调用 -->
<!-- 水果广告位 -->
<div class="home-adBox f-oh mt20">
    <a href="#"><img class="f-fl" src="{{ constant('STATIC_URL') }}mdg/version2.5/images/home-adImg2.jpg" alt=""></a>
</div>
<!-- 水果广告位 -->
<!--粮油-->
<div class="macrot f-oh">
    <!-- 左 -->
    <div class="assort assort3 f-fl">
        <div class="box">
            <div class="imgs f-oh">
                <img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/assort3-img.jpg" alt="">
            </div>
            <div class="setDiv"></div>
            <div class="list">
                {% for key, item in grain['cate'] %}
                 {% if key < 9 %}
                 <a href="/sell/mc{{item['parent_id']}}_a0_c{{item['id']}}_f{{item['abbreviation']}}_p1"><?php echo \Lib\Func::sub_str($item['title'], 10); ?></a>
                <!-- <a href='/sell/index?mc={{item['parent_id']}}&first={{ item['abbreviation']}}&c={{ item['id'] }}' ><?php echo \Lib\Func::sub_str($item['title'], 10); ?></a> -->
                {% endif %}
                {% endfor %}
            </div>
        </div>
        <div class="spread">
            <!--
            <div class="links">
                <a href="#">特惠活动一快参加</a>
            </div>
            <div class="links">
                <a href="#">推广活动推广活动额额</a>
            </div>
            -->
        </div>
    </div>
    <!-- 中 -->
    <div class="gy-list f-fl">
        <div class="title f-oh">
            <span>供应</span>
            <a href="/sell/mc{{  grainid }}_a0_c0_f_p1">更多&gt;</a>
        </div>
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr height="27">
                <th width="27"></th>
                <th align="left" width="124">产品名</th>
                <th width="171">供应地</th>
                <th width="96">供应量</th>
                <th width="144">价格</th>
                <th width="78">立即采购</th>
                <th width="14"></th>
            </tr>
        </table>
        <ul class="gyCon">
             {% for key, item in grain['sell'] %}
            <li>
                <div class="box f-oh">
                    <span class="w1">
                        <a href="/sell/info/{{ item['id']}}">
                            {{ item['title']}}
                        </a>
                    </span>
                    <span class="w2">{{ item['areas_name']}}</span>
                    <span class="w3">
                        {% if item['quantity'] > 0 %}
                        {{ item['quantity']}}
                        <?php 
                        echo isset($goods_unit[$item['goods_unit']]) ? $goods_unit[$item['goods_unit']] : '';
                        ?>
                        {% else %}
                        不限
                        {% endif %}
                    </span>
                    <span class="w4">
                        <i>{{ item['min_price']}}-{{ item['max_price']}}</i>元/ 
                        <?php 
                        echo isset($goods_unit[$item['goods_unit']]) ? $goods_unit[$item['goods_unit']] : '';
                        ?>
                    </span>
                    <span class="w5">
                        <a href="javascript:newWindows('newbuy', '确认采购信息', '/member/dialog/newbuy/{{ item['id']}}');">采购</a>
                    </span>
                </div>
            </li>
            {% endfor %}
        </ul>
    </div>
    <!-- 右 -->
    <div class="cg-list f-fr">
        <div class="title f-oh">
            <span>采购</span>
            <a href="/purchase/mc{{  grainid }}_a0_c0_f_p1">更多&gt;</a>
        </div>
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr height="27">
                <th width="24"></th>
                <th align="left" width="116">产品名</th>
                <th width="88">采购量</th>
                <th width="46">报价</th>
                <th width="14"></th>
            </tr>
        </table>
        <ul class="cgCon">
            {% for key, item in grain['pur'] %}
            <li>
                <div class="box f-oh">
                    <span class="w1">{{ item['title']}}</span>
                    <span class="w2">
                    {% if item['quantity'] > 0 %}
                    {{ item['quantity']}}
                    {% endif %}
                    <?php echo isset($goods_unit[$item['goods_unit']]) ? $goods_unit[$item['goods_unit']] : '';?>
                    </span>
                    <span class="w3">
                        <a href="javascript:newWindows('newquo', '确定报价', '/member/dialog/newquo/{{ item['id']}}');">报价</a>
                    </span>
                </div>
            </li>
            {% endfor %}
        </ul>
    </div>

</div>
<!--粮油-->