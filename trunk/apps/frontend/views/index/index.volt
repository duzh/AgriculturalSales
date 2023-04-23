{{ partial('layouts/page_header') }}  
    <div class="wrapper">
        <div class="w1190 mtauto clearfix">

            <!-- 轮播 -->
            <div class="carousel f-oh">     
                    <div class="slider f-fl">
                        <div class="slide-images">
                           {% for key, ad in adimg %}
                                <div class="img {% if key == 0 %}showImg{% endif %}">
                                       <a href="{{ ad.adsrc }}">
                                        <img {% if (!key) %}style="display:block;"{% endif %} src="{{ constant('IMG_URL') }}{{ ad.imgpath }}" height="380" width="770">
                                        </a>
                                </div>
                            {% endfor %}
                    </div>
                    <ul class="slide-btns f-oh">
                        {% for key, ad in adimg %}
                        <li {% if key == 0 %}class="active"{% endif %}>{{ key+1 }}</li>
                        {% endfor %}
                    </ul>
					<a class="prev-btn" href="javascript:;"></a>
					<a class="next-btn" href="javascript:;"></a>
            </div>
            <!-- 轮播 -->
            <!-- 右侧 -->
            <div class="carou-right f-fr">
                 {% if is_brokeruser and is_brokeruser.is_broker==1 %}
                 {% else %}
                <a class="free-gyBtn" onclick="member_new_sell()"  >免费发布供应</a>
               
                <a class="free-cgBtn" href="javascript:newWindows('newpur', '发布采购', '/member/dialog/newpur');">免费发布采购</a>
                {% endif %}
                <!-- 今日行情 -->
                <div class="today-market f-oh">
                    <div class="title f-oh">
                        <span class="f-fl">今日行情</span>
                        <font class="f-fr">{{ date('Y.m.d')}}{{ getN}}</font>
                    </div>
                    <div class="box f-oh">
                        <ul class="market-list">
                            {% for key ,item in dayAdvisory %}
                           
                            <li>
                                
                                    <span>
                                        <font>[{{ item['title'] }}]</font> {{ item['description'] }}
                                    </span>
                                    <em> {{date("H:m",item['updatetime'])}} </em>
                               
                            </li>
                            {% endfor %}
                        </ul>
                    </div>
                </div>

            </div>
            </div>
            <!-- 统计 -->
            <!--  <div class="statistical f-oh">
                <div class="box">
					<div class="s1 f-oh">
						<span>注册用户</span>
						<strong>{{ usercount }}</strong>
					</div>
                </div>
                <div class="box">
					<div class="s2 f-oh">
						<span>供求信息</span>
						<strong>{{ shopcount }}</strong>
					</div>
                </div>
                <div class="box">
					<div class="s3 f-oh">
						<span>可信农场</span>
						<strong>{{ storecount }}</strong>
					</div>
                </div>
                <div class="box">
					<div class="s4 f-oh">
						<span>服务站</span>
						<strong>{{ servicecount }}</strong>
					</div>                  
                </div>
                <div class="box">
					<div class="s5 f-oh">
						<span>成交订单</span>
						<strong>{{ ordercount }}</strong>
					</div>
                </div>                                              
            </div>  -->
			<!-- 
            **
            **  add 2015.9.25 添加产地直销版块
            **
             -->
             <div class="direct-market f-oh">
                <div class="title f-oh">
                    <strong class="f-db f-fl">特色推荐</strong>
                    <div class="gjz f-fl">
                        {% for k ,cs in chapter %}
                        <a href="{{cs['adsrc']}}">{{cs['adtitle']}}</a>
                        {% if k!='4' %}
                        <em>|</em>
                        {% endif %}
                        {% endfor %}
                    </div>
                    <a  style="font-size:12px; color:#979797; margin-top:9px;" class="dm-more f-fr" href="/sell/mc0_a0_c0_f_p1?ib=1">查看全部直销商品&gt;</a>
                </div>
                <div class="dm-box clearfix">
                    <div class="m-box f-fl">
                    {% for ds in dsimg %}
                    {% if ds['position']=='2' %}
                        <a href="{{ds['adsrc']}}">
                            <div class="imgs">
                                <img src="{{ constant('IMG_URL') }}{{ds['imgpath'] }}" height="198" width="198" alt="">
                            </div>
                        </a>
                    {% endif %}
                    {% endfor %}
                    </div>
                    <!--直营轮播-->
                    <div class="m-focus f-fl f-pr">
                        <div class="slide-imgs f-pr">
                            {% for k,ds in dscar %}                        
                            <a href="{{ds['adsrc']}}">
                                <img {% if k=='0' %}style="opacity:1; filter:alpha(opacity:100); z-index:4;"{% endif %} src="{{ constant('IMG_URL') }}{{ds['imgpath'] }}" height="198" width="398" alt="">
                            </a>
                            {% endfor %}                                                          
                        </div>
                        <?php $adcount=count($dscar);?>
                        {% if adcount>1  %}
                        <a class="dm-prev" href="javascript:;"></a>
                        <a class="dm-next" href="javascript:;"></a>
                        {% endif %}
                    </div>

                    <div class="m-box f-fl">
                    {% for ds in dsimg %}
                    {% if ds['position']=='3' %}
                        <a href="{{ds['adsrc']}}">
                            <div class="imgs">
                                <img src="{{ constant('IMG_URL') }}{{ds['imgpath'] }}" height="198" width="198" alt="">
                            </div>
                        </a>
                    {% endif %}
                    {% endfor %}
                    </div>

                    <div class="m-box f-fl">
                    {% for ds in dsimg %}
                    {% if ds['position']=='4' %}
                        <a href="{{ds['adsrc']}}">
                            <div class="imgs">
                                <img src="{{ constant('IMG_URL') }}{{ds['imgpath'] }}" height="198" width="198" alt="">
                            </div>
                        </a>
                    {% endif %}
                    {% endfor %}
                    </div>

                    <div class="m-box f-fl">
                    {% for ds in dsimg %}
                    {% if ds['position']=='5' %}
                        <a href="{{ds['adsrc']}}">
                            <div class="imgs">
                                <img src="{{ constant('IMG_URL') }}{{ds['imgpath'] }}" height="198" width="198" alt="">
                            </div>
                        </a>
                    {% endif %}
                    {% endfor %}
                    </div>

                    <div class="m-box f-fl">
                    {% for ds in dsimg %}
                    {% if ds['position']=='6' %}
                        <a href="{{ds['adsrc']}}">
                            <div class="imgs">
                                <img src="{{ constant('IMG_URL') }}{{ds['imgpath'] }}" height="198" width="198" alt="">
                            </div>
                        </a>
                    {% endif %}
                    {% endfor %}
                    </div>

                    <div class="m-box f-fl">
                    {% for ds in dsimg %}
                    {% if ds['position']=='7' %}
                        <a href="{{ds['adsrc']}}">
                            <div class="imgs">
                                <img src="{{ constant('IMG_URL') }}{{ds['imgpath'] }}" height="198" width="198" alt="">
                            </div>
                        </a>
                    {% endif %}
                    {% endfor %}
                    </div>

                    <div class="m-box f-fl">
                    {% for ds in dsimg %}
                    {% if ds['position']=='8' %}
                        <a href="{{ds['adsrc']}}">
                            <div class="imgs">
                                <img src="{{ constant('IMG_URL') }}{{ds['imgpath'] }}" height="198" width="198" alt="">
                            </div>
                        </a>
                    {% endif %}
                    {% endfor %}
                    </div>

                    <div class="b-box f-fl">
                    {% for ds in dsimg %}
                    {% if ds['position']=='9' %}
                        <a href="{{ds['adsrc']}}">
                            <div class="imgs">
                                <img src="{{ constant('IMG_URL') }}{{ds['imgpath'] }}" height="198" width="398" alt="">
                            </div>
                        </a>
                    {% endif %}
                    {% endfor %}
                    </div>

                    <div class="m-box f-fl">
                    {% for ds in dsimg %}
                    {% if ds['position']=='10' %}
                        <a href="{{ds['adsrc']}}">
                            <div class="imgs">
                                <img src="{{ constant('IMG_URL') }}{{ds['imgpath'] }}" height="198" width="198" alt="">
                            </div>
                        </a>
                    {% endif %}
                    {% endfor %}
                    </div>
                </div>
             </div>
            <!-- 
            **
            **  add 2015.9.25 代码到这结束
            **
             -->
            <!-- 可信农场推荐 -->
           {% if farmhome %}
            <div class="recom-trustFarm f-oh">
                <div class="title f-oh">
                    <span>可信农场推荐</span>
                    <a href="farmlist/index">更多&gt;</a>
                </div>
                <div class="box f-oh">
                    {% for key,item in farmhome %}
                    <div class="trustFarm-list f-fl">                       
                            <div class="imgs">
                                <a href="http://{{item['url']}}{{ constant('CUR_DEMAIN') }}/indexfarm/index">
                                {% if item['img_pic'] %}<img src="{{ constant('IMG_URL') }}{{item['img_pic']}}" alt="">
                                {% else %}<img src="http://yncstatic.b0.upaiyun.com/mdg/version2.4/images/detial_b_img.jpg" alt="">{% endif %}
                                </a>
                            </div>
                            <div class="setDiv"></div>
                            <div class="imgName">
                                <a href="/indexfarm/index?url={{item['url']}}">{{item['farm_name']}}</a>
                            </div>
                    </div>
                    {% endfor %}
                </div>
            </div>
    
            {% endif %}
            
			
            <!-- 中间部分 -->
            <div id='indexcenter'></div>
            <!-- 中间部分 -->
            <!-- 发布流程 -->
            <div class="step-publish f-oh">
                <div class="steps f-fl">
                    <img src="{{ constant('STATIC_URL') }}mdg/version2.5/images/step-publish-img.png" alt="">
                </div>
                <div class="btns f-fr">
                    {% if is_brokeruser and is_brokeruser.is_broker==1 %}
                    {% else %}
                    <a class="gy-btn" onclick="member_new_sell()"  >发布供应</a>
                    <a class="cg-btn" href="javascript:newWindows('newpur', '发布采购', '/member/dialog/newpur');">发布采购</a>
                    {% endif %}
                </div>
            </div>

        </div>
    </div>
<!-- 底部 -->
{{ partial('layouts/footer') }}
<script type="text/javascript">
$('#indexcenter').load('ajax/indexcenter');
</script>
</body>
</html>