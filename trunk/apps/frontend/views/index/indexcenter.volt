
    <!-- 蔬菜 -->
    <div class="great-category great-category-list1 w1185 mtauto clearfix">

        <div class="small-kind f-fl">
            <h6>蔬<br />菜</h6>
            <div class="links">
                {% for key, item in veg['cate'] %}
                <a href='/sell/index?mc={{item['parent_id']}}&first={{ item['abbreviation']}}&c={{ item['id'] }}' class='link{{ key + 1 }}'>{{item['title']}}</a>
                {% endfor %}
            </div>
            <img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/small-kind-img1.png"></div>

        <div class="supply-box f-fl">
            <div class="title clearfix">
                <span>供应</span>
                <a class="f-fr" href="/sell/index?mc={{  vegcid }}">更多</a>
            </div>
            <div class="list">
                <div class="small-title">
                    <span>产品名</span>
                    <span>供应地</span>
                    <span>供应量</span>
                    <span>
                        <img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/small-title-img.png"></span>
                    <span class="border-none">立即采购</span>
                </div>
                <ul class="small-box">
                {% for key, item in veg['sell'] %}
                    <li>
                        
                        <em class="mouse" >
                            <span class="w1" >
                                <a href="/sell/info/{{ item['id']}}">
                                    {{ item['title']}}
                                </a>
                            </span>
                            <span class="w2" >{{ item['areas_name']}}</span>
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
                            <span class="w2">{{ item['min_price']}}~{{ item['max_price']}}元/公斤</span>
                            <span class="w3">
                                <input type="button" value="采购" onclick="javascript:newWindows('newbuy', '确认采购信息', '/member/dialog/newbuy/{{ item['id']}}');"/>
                            </span>
                        </em>
                    </li>
                    {% endfor %}
                    
                </ul>
            </div>
        </div>

        <div class="purchase-box f-fr">
            <div class="title clearfix">
                <span>采购</span>
                <a class="f-fr" href="/purchase/index?mc={{ vegcid }}">更多</a>
            </div>
            <div class="list">
                <div class="small-title">
                    <span>产品名</span>
                    <span>采购量</span>
                    <span class="border-none">立即报价</span>
                </div>
                <ul class="small-box">
                    {% for key, item in veg['pur'] %}
                    <li>
                        <span class="w1">{{ item['title']}}</span>
                        <span class="w1">
                            
                                {% if item['quantity'] > 0 %}
                                {{ item['quantity']}}
                                <?php 
                                echo isset($goods_unit[$item['goods_unit']]) ? $goods_unit[$item['goods_unit']] : '';
                                ?>
                                {% else %}
                                不限
                                {% endif %}
                                
                            </span>
                        <span class="w2">
                            <a href="javascript:newWindows('newquo', '确定报价', '/member/dialog/newquo/{{ item['id']}}');">报价</a>
                        </span>
                    </li>
                    {% endfor %}
                    
                </ul>
            </div>
        </div>

    </div>

    <div class="advertising w1185 mtauto f-tac">
            <div id="ad20" attr-box="20"  class="bannerImg-only" >
            <input type="hidden" value="20" id="location_id" name="adSrc">
            </div>
            <script>fnAdMore($('#ad20'))</script>
    </div>

    <!-- 水果 -->
    <div class="great-category great-category-list2 w1185 mtauto clearfix">

        <div class="small-kind f-fl">
            <h6>水<br />果</h6>
            <div class="links">
                {% for key, item in fruit['cate'] %}
                <a href='/sell/index?mc={{item['parent_id']}}&first={{ item['abbreviation']}}&c={{ item['id'] }}' class='link{{ key + 1 }}'>{{item['title']}}</a>
                {% endfor %}

                <!-- <a href="#" class="link1 active">菠菜</a> -->
                
            </div>
            <img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/small-kind-img2.png"></div>

        <div class="supply-box f-fl">
            <div class="title clearfix">
                <span>供应</span>
                <a class="f-fr" href="/sell/index?mc={{ grainid }}">更多</a>
            </div>
            <div class="list">
                <div class="small-title">
                    <span>产品名</span>
                    <span>供应地</span>
                    <span>供应量</span>
                    <span>
                        <img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/small-title-img.png"></span>
                    <span class="border-none">立即采购</span>
                </div>
                <ul class="small-box">
                    {% for key, item in fruit['sell'] %}
                    <li>
                        <em class="mouse">
                            <span class="w1" >
                                <a href="/sell/info/{{ item['id']}}">
                                    {{ item['title']}}&nbsp;
                                </a>
                            </span>
                            <span class="w2">{{ item['areas_name']}}&nbsp;</span>
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
                            <span class="w2">{{ item['min_price']}}~{{ item['max_price']}}元/公斤&nbsp;</span>
                            <span class="w3">
                                <input type="button" value="采购" onclick="javascript:newWindows('newbuy', '确认采购信息', '/member/dialog/newbuy/{{ item['id']}}');"/>
                            &nbsp;</span>
                        </em>
                    </li>
                    {% endfor %}

                </ul>
            </div>
        </div>

        <div class="purchase-box f-fr">
            <div class="title clearfix">
                <span>采购</span>
                <a class="f-fr" href="/purchase/index?mc={{ grainid }}">更多</a>
            </div>
            <div class="list">
                <div class="small-title">
                    <span>产品名</span>
                    <span>采购量</span>
                    <span class="border-none">立即报价</span>
                </div>
                <ul class="small-box">
                    {% for key, item in fruit['pur'] %}
                    <li>
                        <span class="w1">{{ item['title']}}</span>
                        <span class="w1" onclick="location.href='/sell/info/{{ item['id']}}'">
                            
                            {% if item['quantity'] > 0 %}
                                {{ item['quantity']}}
                                <?php 
                                echo isset($goods_unit[$item['goods_unit']]) ? $goods_unit[$item['goods_unit']] : '';
                                ?>
                                {% else %}
                                不限
                                {% endif %}
                            </span>
                        <span class="w2">
                            <a href="javascript:newWindows('newquo', '确定报价', '/member/dialog/newquo/{{ item['id']}}');">报价</a>
                        </span>
                    </li>
                    {% endfor %}
                </ul>
            </div>
        </div>

    </div>

    <div class="advertising w1185 mtauto f-tac">
        <div id="ad21" attr-box="21"  class="bannerImg-only" >
        <input type="hidden" value="21" id="location_id" name="adSrc">
        </div>
        <script>fnAdMore($('#ad21'))</script>
    </div>

    <!-- 粮油 -->
    <div class="great-category great-category-list3 w1185 mtauto clearfix">

        <div class="small-kind f-fl">
            <h6>粮<br />油</h6>
            <div class="links">
                 {% for key, item in grain['cate'] %}
                <a href='/sell/index?mc={{item['parent_id']}}&first={{ item['abbreviation']}}&c={{ item['id'] }}' class='link{{ key + 1 }}'>{{item['title']}}</a>
                {% endfor %}

                <!-- <a href="#" class="link1 active">菠菜</a> -->
           
            </div>
            <img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/small-kind-img3.png"></div>

        <div class="supply-box f-fl">
            <div class="title clearfix">
                <span>供应</span>
                <a class="f-fr" href="/sell/index?mc={{ fruitid }}">更多</a>
            </div>
            <div class="list">
                <div class="small-title">
                    <span>产品名</span>
                    <span>供应地</span>
                    <span>供应量</span>
                    <span>
                        <img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/small-title-img.png"></span>
                    <span class="border-none">立即采购</span>
                </div>
                <ul class="small-box">
                    {% for key, item in grain['sell'] %}
                    <li>
                        <em class="mouse" >
                            <span class="w1">
                                <a href="/sell/info/{{ item['id']}}">
                                    {{ item['title']}}&nbsp;
                                </a>
                            </span>
                            <span class="w2">{{ item['areas_name']}}&nbsp;</span>
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
                            <span class="w2">{{ item['min_price']}}~{{ item['max_price']}}元/公斤&nbsp;</span>
                            <span class="w3">
                                <input type="button" value="采购" onclick="javascript:newWindows('newbuy', '确认采购信息', '/member/dialog/newbuy/{{ item['id']}}');"/>
                            &nbsp;</span>
                        </em>
                    </li>
                    {% endfor %}
                    
                </ul>
            </div>
        </div>

        <div class="purchase-box f-fr">
            <div class="title clearfix">
                <span>采购</span>
                <a class="f-fr" href="/purchase/index?mc={{ fruitid }}">更多</a>
            </div>
            <div class="list">
                <div class="small-title">
                    <span>产品名</span>
                    <span>采购量</span>
                    <span class="border-none">立即报价</span>
                </div>
                <ul class="small-box">
                    {% for key, item in grain['pur'] %}
                    <li>
                        <span class="w1">{{ item['title']}}</span>
                        <span class="w1">
                            {% if item['quantity'] > 0 %}
                            {{ item['quantity']}}
                            {% endif %}
                            <?php echo isset($goods_unit[$item['goods_unit']]) ? $goods_unit[$item['goods_unit']] : '';?></span>
                        <span class="w2">
                            <a href="javascript:newWindows('newquo', '确定报价', '/member/dialog/newquo/{{ item['id']}}');">报价</a>
                        </span>
                    </li>
                    {% endfor %}
         
                    
                </ul>
            </div>
        </div>

    </div>
    <script>
    $(function(){
        // 分类去底边框
        (function(){
            for(var i=1;i<$('.assort').length+1;i++){
                (function(i){
                    $('.assort' + i).parent().find('.gyCon li:last .box').css('border-bottom', 'none');
                })(i);
            };
        })();
    });
    </script>