
<!-- 头部 start -->
{% if shop.shop_status == 1 %}
{{ partial('layouts/shop_header') }}
{% else %}
{{ partial('layouts/page_header_old') }}
{% endif %}
<!-- 头部 end -->

<div class="ur_here w960">
     <span>{{ partial('layouts/ur_here') }}我的店铺</span>
</div>
<div class="personal_center w960 mb20">
    <!-- 左侧导航栏 start -->
   {% if shop.shop_status == 1 %}
   {{ partial('layouts/shop_left') }}
   {% else %}
   {{ partial('layouts/navs_old_left') }}
   {% endif %}
    <!-- 左侧导航栏 end -->
    <!-- 右侧 start -->
    <div class="center_right f-fr">
        <div class="open_shop">
            <h6 class="f-fs14 pl20 clearfix">
                <span>申请开通店铺
                    <em style='color:rgb(235,171,52);font-weight:bold;'>
                        {{shopstate[shop.shop_status]}}
                        {% if shop.shop_status == 2 %}：
                        {{ shopcheck ? shopcheck.failure_desc : '' }}
                        {% endif %}
                </em>
            </span>
                {% if shop.shop_status == 1  %}<a class="f-fr" href="/member/shop/newedit">编辑</a>{% endif %}
                {% if shop.shop_status == 2  %}<a class="f-fr" href="/member/shop/edit">编辑</a>{% endif %}
            </h6>
            <ul class="basic_information">
                <li>
                    <font>店铺名称：</font>
                    <div class="message">{{shop.shop_name ? shop.shop_name : '-' }}</div>
                </li>
                {% if shop.shop_status == 1 %}
                <li>
                    <font>店铺域名：</font>
                    <div class="message">
                        <a href="http://{{shop.shop_link}}.5fengshou.com/store/index" target="_blank">http://{{shop.shop_link}}.5fengshou.com</a>
                    </div>
                </li>
                {% endif %}
                <li>
                    <font>您的身份：</font>
                    <div class="message">{{usertype[shop.business_type]}}-{{business_type[shop.user_type]}}-{{shop.shop_type }}</div>
                </li>
                <li>
                    <font>主营产品：</font>
                    <div class="message">{% if shopgoods%} {{ shopgoods }} {% else %} - {% endif %}</div>
                </li>
                <li>
                    <font>所在地区：</font>
                    <div class="message">{{area ?  area : '-' }}</div>
                </li>
                <li>
                    <font>联系人：</font>
                    <div class="message">{{shop.contact_man ? shop.contact_man : '-' }}</div>
                </li>
                <li>
                    <font>电话：</font>
                    <div class="message">{{shop.contact_phone ? shop.contact_phone :'-' }}</div>
                </li>
                <li>
                    <font>身份证号：</font>
                    <div class="message">{{shop.credit ? shop.credit.identity_no : '-'}}</div>
                </li>
                <li>
                    <font>银行卡开户行：</font>
                    <div class="message">{{shop.credit ? shop.credit.bank_name : '-'}}</div>
                </li>
                <li>
                    <font>开户名：</font>
                    <div class="message">{{shop.credit ? shop.credit.account_name : '-'}}</div>
                </li>
                <li>
                    <font>卡号：</font>
                    <div class="message">{{shop.credit ? shop.credit.card_no : '-' }}</div>
                </li>
                {% if shop.user_type == 1 %}
                <li>
                    <font>银行卡正面照：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.bank_card_picture %}
                            <!--<img src="{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                <li>
                    <font>手持身份证正面头部照：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.identity_picture_lic %}
                            <!--<img src="{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                <li>
                    <font>身份证正面照：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.identity_card_front %}
                            <!--<img src="{{shop.credit.identity_card_front}}" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                {% endif %}
                {% if shop.user_type == 2 %}
                <li>
                    <font>个体工商户营业执照：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.identity_picture_lic %}
                            <!--<img src="{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                <li>
                    <font>银行卡正面照：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.bank_card_picture %}
                            <!--<img src="{{shop.credit.bank_card_picture}}" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                <li>
                    <font>身份证正面照：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.identity_card_front %}
                            <!--<img src="{{shop.credit.identity_card_front}}" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                <li>
                    <font>身份证背面照：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.identity_card_back %}
                            <!--<img src="{{shop.credit.identity_card_back}}" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_card_back}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_card_back}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                {% endif %}
                {% if shop.user_type == 3 %}
                  <li>
                    <font>银行开户许可证：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.bank_card_picture %}
                            <!--<img src="{{shop.credit.bank_card_picture}}" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                <li>
                    <font>企业营业执照副本照：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.identity_picture_lic %}
                            <!--<img src="{{shop.credit.identity_picture_lic}}" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                <li>
                    <font>企业税务登记证照：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.tax_registration %}
                            <!--<img src="{{shop.credit.tax_registration}}" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.tax_registration}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.tax_registration}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                <li>
                    <font>组织机构代码证：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.organization_code %}
                            <!--<img src="{{shop.credit.organization_code}}" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.organization_code}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.organization_code}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                <li>
                    <div class="represent" style="margin-left:-103px;">
                        <div class="line"></div>
                        <span>法定代表人身份证照</span>
                    </div>
                </li>
                <li>
                    <font>身份证正面照：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.identity_card_front %}
                            <!--<img src="{{shop.credit.identity_card_front}}" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                <li>
                    <font>身份证背面照：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.identity_card_back %}
                            <!--<img src="{{shop.credit.identity_card_back}}" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_card_back}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_card_back}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                {% endif %}

                <li>
                    <font>网店介绍：</font>
                    <div class="message">
                        <p style="line-height:18px;">{% if shop.credit and shop.credit.shop_desc %}{{shop.credit.shop_desc}}{% else %} -{% endif %}</p>
                    </div>
                </li>
            </ul>

            <!-- 图片点击放大引入文件 -->
            <link rel="stylesheet"  href="{{ constant('STATIC_URL') }}mdg/css/zoom.css" media="all" />
            <script src="{{ constant('STATIC_URL') }}mdg/js/zoom.min.js"></script>

            <script>
            (function(){
                $('.message > em').on('click', function(event){
                    var emBig = $('<div class="emBigImg"></div>');
                    var emSrc = $(this).children('img').attr('src');
                    emBig.html('<img src=' +  emSrc + ' />');
                    $(this).after(emBig);

                    var emBigW = $('.emBigImg img').width(),
                        emBigH = $('.emBigImg img').height();

                    $('.emBigImg').css({
                        'position' : 'fixed',
                        'left' : '50%',
                        'top' : '50%',
                        'margin-left' : -(emBigW/2),
                        'margin-top' : -(emBigH/2)
                    });

                    event.stopPropagation();
                })

                $(document).click(function(){
                    $('.emBigImg').remove();
                })

            })();
            </script>
        </div>
    </div>
    <!-- 右侧 end -->
</div>

<!-- 底部 start -->
{{ partial('layouts/footer') }}
<!-- 底部 end -->
</body>
</html>
p