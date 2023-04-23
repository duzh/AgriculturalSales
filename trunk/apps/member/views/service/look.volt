<!-- 头部 start -->
{{ partial('layouts/page_header_old') }}
<!-- 头部 end -->

<div class="ur_here w960">
    <span>{{ partial('layouts/ur_here') }}服务站管理</span>
</div>
<div class="personal_center w960 mb20">
    <!-- 左侧导航栏 start -->
    {{ partial('layouts/navs_old_left') }}
    <!-- 左侧导航栏 end -->
    <!-- 右侧 start -->
    <div class="center_right f-fr">
        <div class="open_shop"> 
            <h6 class="f-fs14 pl20 clearfix">
                <em style='color:rgb(235,171,52);font-weight:bold;'>
                            {{shopstate[shop.shop_status]}} 
                            {% if shop.shop_status == 2 %}
                            <?php echo isset($shopcheck->
                            failure_desc) ?$shopcheck->failure_desc : ''; ?>
                            {% endif %}
                </em>

                
                <!-- {% if shop.shop_status == 1  %}<a class="f-fr" href="/member/service/edit">编辑</a>
            {% endif %} -->
                {% if shop.shop_status == 2  %}
            <a class="f-fr" href="/member/service/edit">编辑</a>
            {% endif %}
        </h6>

        <form action="/member/service/savesuccessstatus" method="post" id='createShop'>
            <ul class="basic_information">
                <li> <font>您的身份：</font>
                    <div class="message">
                        <?php echo isset($business_type[$shop->user_type]) ? $business_type[$shop->user_type] : '-'; ?></div>
                </li>
                <li> <font>主营产品：</font>
                    <div class="message">{% if shopgoods%} {{ shopgoods }} {% else %} - {% endif %}</div>
                </li>
                <li>
                    <font>所在地区：</font>
                    <div class="message">{{area ?  area : '-' }}</div>
                </li>
                <li>
                    <font>申请负责地区：</font>
                    <div class="message">
                         {{province}} {{city }} {{distinct}}
                            <br>
                            {{ Viewareas }}
                    </div>
                </li>
                {% if shop.shop_status == 1 %}
                <li>
                    <font style="line-height:32px;">联系人：</font>
                    <div class="message">
                        <input style="border:solid 1px #ccc; height:30px; line-height:30px\9; width:150px; padding-left:4px;" type="text" name='contact_man' value='{{shop.contact_man ? shop.contact_man : '-' }}'></div>
                </li>
                <li>
                    <font style="line-height:32px;">电话：</font>
                    <div class="message">
                        <input style="border:solid 1px #ccc; height:30px; line-height:30px\9; width:150px; padding-left:4px;" type="text" name='contact_phone' value='{{shop.contact_phone ? shop.contact_phone : '-' }}'></div>
                </li>
                {% else %}
                <li>
                    <font>联系人：</font>
                    <div class="message">{{shop.contact_man ? shop.contact_man : '-' }}</div>
                </li>
                <li>
                    <font>电话：</font>
                    <div class="message">{{shop.contact_phone ? shop.contact_phone : '-' }}</div>
                </li>
                {% endif %}
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
                {% if shop.user_type == 2 %}
                <li>
                    <font>个人照片：</font>
                    <div class="message"> <em>{% if shop.credit and shop.credit.personal_logo_picture %}
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}/{{shop.credit.personal_logo_picture}}">
                                        <img src="{{ constant('IMG_URL') }}/{{shop.credit.personal_logo_picture}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="" />
                                    </a>
                                </li>
                            </ul>
                            {% endif %}</em> 
                    </div>
                </li>
                <li>
                    <font>个体工商户营业执照：</font>
                    <div class="message"> <em>{% if shop.credit and shop.credit.identity_picture_lic %}
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="" />
                                    </a>
                                </li>
                            </ul>
                            {% endif %}</em> 
                    </div>
                </li>
                <li>
                    <font>银行卡正面照：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.bank_card_picture %}
                            <!--<img src="{{shop.credit.bank_card_picture}}" width="125px" height="95px"  />
                            -->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="" />
                                    </a>
                                </li>
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
                            <!--<img src="{{shop.credit.identity_card_front}}" width="125px" height="95px"  />
                            -->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="" />
                                    </a>
                                </li>
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
                            <!--<img src="{{shop.credit.identity_card_back}}" width="125px" height="95px"  />
                            -->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_card_back}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_card_back}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="" />
                                    </a>
                                </li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                {% endif %}

                {% if shop.user_type == 3 %}
                <li>
                    <font>企业名称：</font>
                    <div class="message">{{shop.shop_name ? shop.shop_name : '-' }}</div>
                </li>

                <li>
                    <font>企业logo：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.personal_logo_picture %}
                            <!--<img src="{{shop.credit.personal_logo_picture}}" width="125px" height="95px"  />
                            -->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.personal_logo_picture}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.personal_logo_picture}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="" />
                                    </a>
                                </li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>

                <li>
                    <font>银行开户许可证：</font>
                    <div class="message">
                        <em>
                            {% if shop.credit and shop.credit.bank_card_picture %}
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="" />
                                    </a>
                                </li>
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
                            <!--<img src="{{shop.credit.identity_picture_lic}}" width="125px" height="95px"  />
                            -->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="" />
                                    </a>
                                </li>
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
                            <!--<img src="{{shop.credit.tax_registration}}" width="125px" height="95px"  />
                            -->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.tax_registration}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.tax_registration}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="" />
                                    </a>
                                </li>
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
                            <!--<img src="{{shop.credit.organization_code}}" width="125px" height="95px"  />
                            -->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.organization_code}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.organization_code}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="" />
                                    </a>
                                </li>
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
                            <!--<img src="{{shop.credit.identity_card_front}}" width="125px" height="95px"  />
                            -->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="" />
                                    </a>
                                </li>
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
                            <!--<img src="{{shop.credit.identity_card_back}}" width="125px" height="95px"  />
                            -->
                            <ul class="gallery">
                                <li style=" padding-left:0; margin-top:0; margin-left:0; font-size:12px;">
                                    <a href="{{ constant('IMG_URL') }}{{shop.credit.identity_card_back}}">
                                        <img src="{{ constant('IMG_URL') }}{{shop.credit.identity_card_back}}" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="" />
                                    </a>
                                </li>
                            </ul>
                            {% endif %}
                        </em>
                    </div>
                </li>
                {% endif %}

                {% if shop.shop_status == 1 %}
                <li>
                    <font id='onete'>{% if shop.user_type == 2 %}
                        个人介绍：
                        {% endif %}
                        {% if shop.user_type == 3 %}
                        企业介绍：
                        {% endif %}</font> 

                    <div class="message">
                        <script id="editor" type="text/plain" style="width:621px; height:500px;" name="shop_desc" >
                        {% if shop.credit and shop.credit.shop_desc %}{{shop.credit.shop_desc}}{% else %} -{% endif %}
                        </script>
                    </div>
                </li>

                <li>
                    <font></font>
                    <div class="message">
                        <input type="hidden" name='shopid' value='{{ shop.shop_id}}'>
                        <input type="submit" value='&nbsp;&nbsp;提交&nbsp;&nbsp;'></div>
                </li>
            </ul>
            {% else %}
            <li>

                <font id='onete'>{% if shop.user_type == 2 %}
                        个人介绍：
                        {% endif %}
                        {% if shop.user_type == 3 %}
                        企业介绍：
                        {% endif %}</font> 
                <div class="message">

                    <p style="line-height:18px;">
                        {% if shop.credit and shop.credit.shop_desc %}{{shop.credit.shop_desc}}{% else %} -{% endif %}
                    </p>
                </div>

            </li>
        </ul>

        <input type="hidden" name='shopid' value='{{ shop.shop_id}}'>{% endif %}</form>

    <script type="text/javascript" src="/uploadify/jquery.uploadify.min.js" ></script>
    <script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.config.sample.js"></script>
    <script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.all.js"></script>
    <script type="text/javascript" charset="utf-8" src="/ueditor1/lang/zh-cn/zh-cn.js"></script>
    <!--编辑-->
    <script>


//验证
$('#createShop').validator({
        ignore: ':hidden',
        fields:  {
            
             contact_man:"联系人:required;chinese;length[2~5];",
             contact_phone:"电话:required;mobile;",
             shopdesc:"网店介绍:required;",
        }
});
{% if shop.shop_status == 1 %}
   // var ue = UE.getEditor('editor');
    UE.getEditor('editor',{
        initialFrameWidth :621,
        //initialFrameHeight: 
    });
    $('#newshop').on('submit', function(){
        if(UE.getEditor('editor').hasContents()){
            $('#shopdesc').val('1');
            $('#shopdesc').hide();
            $("#shopdesc-tip").hide();
            //alert($('#shopdesc').val());
        }else{
            $('#shopdesc').show();
            $('#shopdesc').val('');
            //alert($('#shopdesc').val());
        };
    });

{% endif %}
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

<link rel="stylesheet"  href="http://yncstatic.b0.upaiyun.com/mdg/css/zoom.css" media="all" />
<script src="http://yncstatic.b0.upaiyun.com/mdg/js/zoom.min.js"></script>

</body>
</html>