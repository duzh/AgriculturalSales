<!-- 头部 -->
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/trust-farm.css">
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/trust-farm.js"></script>
{{ partial('layouts/orther_herder') }}
<div class="wrapper">
        <div class="w1190 mtauto f-oh">
             <!--农场头部--> 
            {{ partial('layouts/farm_header') }}
            <!--广告位-->
            {% if logo %}
            <div class="trustFarm-slider">
                <div class="slide-img">
                    {% for key,val in logo %}
                    <div class="imgs focusIn">
                        <a href="#1">
                            <img src="{{ constant('IMG_URL') }}{{ val['picture_path'] }}" height="374" width="1164" alt="{{ val['title'] }}">
                        </a>
                    </div>
                    {% endfor %}
                </div>
                <ul class="slide-btn">
                    {% for key, ad in logo %}
                    <li {% if key == 0 %}class="active"{% endif %}>{{ key+1 }}</li>
                    {% endfor %}
                </ul>
                <a class="prev" href="javascript:;"></a>
                <a class="next" href="javascript:;"></a>
            </div>
            {% endif %}
            {% if farm %}
                <div class="trustFarm-cate">
                    {% for key,val in farm %}
                        {% if key % 2 == 0 %}
                            <div class="box clearfix color{{key+1}}">
                                <a href="#">
                                    <div class="imgs f-fl">
                                        <img src="{{ constant('IMG_URL') }}{{ val['picture_path'] }}" height="250" width="830">
                                    </div>
                                    <div class="content f-fl f-pr clearfix">
                                        <div class="m-border pa"></div>
                                        <h6>{{ val['title'] }}</h6>
                                        <div class="m-box">
                                            {{ val['desc'] }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                            {% else %}
                            <div class="box clearfix color{{key+1}}">
                                <a href="#">
                                    <div class="content f-fl f-pr clearfix">
                                        <div class="m-border pa"></div>
                                        <h6>{{ val['title'] }}</h6>
                                        <div class="m-box">
                                           {{ val['desc'] }}
                                        </div>
                                    </div>
                                    <div class="imgs f-fl">
                                        <img src="{{ constant('IMG_URL') }}{{ val['picture_path'] }}" height="250" width="830">
                                    </div>
                                </a>
                            </div>
                        {% endif %}
                    {% endfor %}
                </div>
            {% endif %}
                {% if goods %}
                <div class="trustFarm-mainProduct f-oh">
                    <div class="title">主营产品</div>                   
                    {% for key,val in goods %}
                    <dl class="product-box f-fl f-oh">
                        <dt class="imgs f-fl">
                            <img src="{{ val['thumb'] }}" height="234" width="234" alt="">
                        </dt>
                        <dd class="name f-fr">{{ val['title'] }}</dd>
                        <dd class="m-box f-fr">
                            <div class="message">价   格：<i>{{ val['min_price'] }} ~ {{ val['max_price'] }}</i> 元/
                            <?php if($val['goods_unit']){ echo $goods_unit[$val['goods_unit']]; }else{ echo "不限";} ?> </div>
                            <div class="message">供应量：
                            {% if val['quantity'] > 0 %}
                             <?php echo Lib\Func::conversion($val['quantity']); ?>
                             <?php if($val['goods_unit']){ echo $goods_unit[$val['goods_unit']]; }else{ echo "不限";} ?>        
                             {% else %}
                              不限
                             {% endif %}       
                            </div>
                            <div class="message">起购量：
                             {% if val['min_number'] > 0 %}
                                {{ val['min_number'] }} {{ goods_unit[val['goods_unit']] }} 
                                {% else %}
                                 不限
                            {% endif %}
                             </div>
                            <div class="message">供货地：<?php echo Lib\Utils::getC($val["areas_name"]); ?></div>
                        </dd>
                        <dd class="btn f-fr">
                            <a href="/sell/info/{{ val['id'] }}">查看详情</a>
                        </dd>
                    </dl>
                    {% endfor %}
                    
                </div>
            {% endif %}
            {% if footprint %}
            <div class="trustFarm-point">
                <div class="title">发展足迹</div>
                 {% for key,val in footprint %}
                      {% if key % 2 == 0 %}
                        <!-- 左 类名point-left-box -->
                        <div class="point-left-box">
                            <div class="time"></div>
                            <div class="m-box">
                                <div class="m-border"></div>
                                <dl class="clearfix">
                                    <dt class="f-fl">
                                        <img src="{{ constant('IMG_URL') }}{{ val['picture_path'] }}" width="116" height="116" alt="">
                                    </dt>
                                    <dd class="name f-fr">{{date('Y/m/d',val['picture_time'])}}</dd>
                                    <dd class="content f-fr">
                                       {{ val['desc'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                      {% else %}
                        <!-- 右 类名point-right-box -->
                        <div class="point-right-box">
                            <div class="time"></div>
                            <div class="m-box">
                                <div class="m-border"></div>
                                <dl class="clearfix">
                                    <dt class="f-fl">
                                        <img src="{{ constant('IMG_URL') }}{{ val['picture_path'] }}" width="116" height="116" alt="">
                                    </dt>
                                    <dd class="name f-fr">{{date('Y/m/d',val['picture_time'])}}</dd>
                                    <dd class="content f-fr">
                                        {{ val['desc'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        {% endif %}
                {% endfor %}
            </div>
            {% endif %}
        </div>
    </div>
{{ partial('layouts/orther_footer') }}

    