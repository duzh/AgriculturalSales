
<!-- 头部 start -->
{% if shop.shop_status == 1 %}
{{ partial('layouts/shop_header') }}
{% else %}
{{ partial('layouts/page_header') }}
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
   {{ partial('layouts/navs_left') }}
   {% endif %}
    <!-- 左侧导航栏 end -->
    <!-- 右侧 start -->
    <div class="center_right f-fr">
        {{ form("shop/newsave", "method":"post","id":"newshop") }}
        <!-- 申请开通店铺 start -->
         <div class="open_shop">
            <h6 class="f-fs14 pl20">申请开通店铺</h6>
            <div class="formVal clearfix mt20">
                <div class="formBox clearfix">
                    <label><font class="label_bc"></font>店铺名称：</label>
                    <div class="inputTxt"><em style="display:block; line-height:37px;">{{shop.shop_name}}</em></div>
                </div>
                <div class="formBox clearfix">
                    <label><font class="label_bc"></font>店铺域名：</label>
                    <div class="inputTxt"><em style="display:block; line-height:37px;">http://{{shop.shop_link}}.5fengshou.com</em></div>
                </div>
                <div class="formBox clearfix">
                    <label><font class="label_bc"></font>您的身份：</label>
                    <div class="userBox">
                        <!-- 下拉框 start -->
                        <div class="selectBox">
                                  <em style="display:block; line-height:37px;">{{usertype[shop.business_type]}}-{{business_type[shop.user_type]}}-{{shop.shop_type }}</em>
                        </div>
                    </div>
                </div>
                <div class="formBox clearfix">
                    <label><font class="label_bc"></font>主营产品：</label>
                    <div class="goodsTxt">
                        <input type="text"  name="main_product1" <?php if(isset($shopgoods[0])){ echo "value='".$shopgoods[0]['goods_name']."'";}?> data-rule="主营产品:zw;" data-target="#main_product_tip" />
                        <input type="text"  name="main_product2" <?php if(isset($shopgoods[1])){ echo "value='".$shopgoods[1]['goods_name']."'";}?> data-rule="主营产品:zw;" data-target="#main_product_tip" />
                        <input type="text"  name="main_product3" <?php if(isset($shopgoods[2])){ echo "value='".$shopgoods[2]['goods_name']."'";}?> data-rule="主营产品:zw;" data-target="#main_product_tip" />
                        <input type="text"  name="main_product4" <?php if(isset($shopgoods[3])){ echo "value='".$shopgoods[3]['goods_name']."'";}?> data-rule="主营产品:zw;" data-target="#main_product_tip" />
                        <input type="text"  name="main_product5" <?php if(isset($shopgoods[4])){ echo "value='".$shopgoods[4]['goods_name']."'";}?> data-rule="主营产品:zw;" data-target="#main_product_tip" />
                        <i id="main_product_tip"></i>
                    </div>
                </div>
                <div class="formBox clearfix">
                    <label><font class="label_bc"></font>所在地区：</label>
                    <div class="areaSelect">
                            <select name="province" class="selectAreas" id="province">
                            <option value="" selected>省</option>
                            </select>

                            <select name="city" class="selectAreas" id="city">
                            <option value="">市</option>
                            </select>

                            <select name="county" class="selectAreas" id="town"   >
                            <option value="">县</option>
                            </select>

                            <select name="zhen" class="selectAreas" id="zhen" >
                            <option value="">镇</option>
                            </select>
                            <select name="district" class="selectAreas" id="district"  data-target="#address_tip">
                            <option value="">村</option>
                            </select>
                            <i id="address_tip"></i>
                    </div>
                </div>
                <div class="formBox clearfix">
                    <label><font class="label_bc"></font>联系人：</label>
                    <div class="inputTxt">
                        <em style="display:block; line-height:37px;">{{shop.contact_man}}</em>
                    </div>
                </div>
                <div class="formBox clearfix">
                    <label><font class="label_bc"></font>电话：</label>
                    <div class="inputTxt">
                        <input type="text" name="contact_phone" value="{{shop.contact_phone}}" />
                    </div>
                </div>
            </div>
         </div> 
         <!-- 申请开通店铺 end -->  
         <!-- 认证信息 start --> 
         <div class="cert_information">
            <h6 class="f-fs14 pl20">认证信息</h6>
            <div class="certTab" id="certTab">
                <!-- 公共信息 start -->
                <div class="certBox clearfix">
                    <label><font class="label_bc"></font>身份证号：</label>
                    <div class="inputTxt">
                        <em style="display:block; line-height:37px;">{{shop.credit ? shop.credit.identity_no : ''}}</em>
                    </div>
                </div>
                <div class="certBox clearfix">
                    <label><font class="label_bc"></font>银行卡开户行：</label>
                    <div class="inputTxt">
                        <em style="display:block; line-height:37px;">{{shop.credit ? shop.credit.bank_name : ''}}</em>
                       
                    </div>
                </div>
                <div class="certBox clearfix">
                    <label><font class="label_bc"></font>开户名：</label>
                    <div class="inputTxt">
                        <em style="display:block; line-height:37px;">{{shop.credit ? shop.credit.account_name : ''}}</em>
                       
                    </div>
                </div>
                <div class="certBox clearfix">
                    <label><font class="label_bc"></font>卡号：</label>
                    <div class="inputTxt">
                        <em style="display:block; line-height:37px;">{{shop.credit ? shop.credit.card_no : ''}}</em>
                      
                    </div>
                </div>
                <!-- 公共信息 end -->
               
                <!-- 个人认证 start -->
                <div class="formCert clearfix" style="display:none;{% if shop.user_type == 1 %}display:block; {% endif %}">
                    <!-- 银行卡正面照 start -->
                    <div class="certBox clearfix">
                        <label><font class="label_bc"></font>银行卡正面照：</label>
                        <div class="addImgBox clearfix">
                            <!--<img src="{% if shop.user_type == 1 %}{%if shop.credit and shop.credit.bank_card_picture %}{{shop.credit.bank_card_picture}}{% endif %}{% endif %}" id="bank_card_pictureshow_img" width="125px" height="95px"/>-->
                            <ul class="gallery">
                                <li>
                                    <a href="{% if shop.user_type == 1 %}{% if shop.credit and shop.credit.bank_card_picture %}{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}{% endif %}{% endif %}">
                                        <img src="{% if shop.user_type == 1 %}{%if shop.credit and shop.credit.bank_card_picture %}{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}{% endif %}{% endif %}" id="bank_card_pictureshow_img" width="125px" height="95px"/>
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                        </div>
                    </div>           
                    <!-- 银行卡正面照 end -->
                    <!-- 手持身份证正面头部照 start -->
                    <div class="certBox clearfix">
                        <label><font class="label_bc"></font>手持身份证正面头部照：</label>
                        <div class="addImgBox clearfix">
                            <!--<img src="{% if shop.user_type == 1 %}{%if shop.credit and shop.credit.identity_picture_lic %}{{shop.credit.identity_picture_lic}}{% endif %}{% endif %}"  id="identity_picture_licshow_img" width="125px" height="95px" />-->
                            <ul class="gallery">
                                <li>
                                    <a href="{% if shop.user_type == 1 %}{%if shop.credit and shop.credit.identity_picture_lic %}{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}{% endif %}{% endif %}">
                                        <img src="{% if shop.user_type == 1 %}{%if shop.credit and shop.credit.identity_picture_lic %}{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}{% endif %}{% endif %}"  id="identity_picture_licshow_img" width="125px" height="95px" />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- 手持身份证正面头部照 start -->
                    <!-- 身份证正面照 start -->
                    <div class="certBox clearfix">
                        <label><font class="label_bc"></font>身份证正面照：</label>
                        <div class="addImgBox clearfix">
                            <!--<img src="{% if shop.user_type == 1 %}{%if shop.credit and shop.credit.identity_card_front %}{{shop.credit.identity_card_front}}{% endif %}{% endif %}" id="identity_card_frontshow_img" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li>
                                    <a href="{% if shop.user_type == 1 %}{%if shop.credit and shop.credit.identity_card_front %}{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}{% endif %}{% endif %}">
                                        <img src="{% if shop.user_type == 1 %}{%if shop.credit and shop.credit.identity_card_front %}{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}{% endif %}{% endif %}" id="identity_card_frontshow_img" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- 身份证正面照 start -->
                </div>
                <!-- 个人认证 end -->
                
                
                <!-- 个体户认证 start -->
                <div class="formCert clearfix" style="display:none;{% if shop.user_type == 2 %}display:block; {% endif %}">
                    <!-- 银行卡正面照 start -->
                    <div class="certBox clearfix">
                        <label><font class="label_bc"></font>银行卡正面照：</label>
                        <div class="addImgBox clearfix">
                            <!--<img src="{% if shop.user_type == 2 %}{%if shop.credit and shop.credit.bank_card_picture %}{{shop.credit.bank_card_picture}}{% endif %}{% endif %}" id="bank_card_pictureshow_img-geti" width="125px" height="95px"/>-->
                            <ul class="gallery">
                                <li>
                                    <a href="{% if shop.user_type == 2 %}{%if shop.credit and shop.credit.bank_card_picture %}{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}{% endif %}{% endif %}">
                                        <img src="{% if shop.user_type == 2 %}{%if shop.credit and shop.credit.bank_card_picture %}{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}{% endif %}{% endif %}" id="bank_card_pictureshow_img-geti" width="125px" height="95px"/>
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- 银行卡正面照 end -->
                    <!-- 个体工商户营业执照 start -->
                    <div class="certBox clearfix">
                        <label><font class="label_bc"></font>个体工商户营业执照：</label>
                        <div class="addImgBox clearfix">
                             <!--<img src="{% if shop.user_type == 2 %}{%if shop.credit and shop.credit.identity_picture_lic %}{{shop.credit.identity_picture_lic}}{% endif %}{% endif %}"  id="identity_picture_licshow_img-geti" width="125px" height="95px" />-->
                             <ul class="gallery">
                                <li>
                                    <a href="{% if shop.user_type == 2 %}{%if shop.credit and shop.credit.identity_picture_lic %}{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}{% endif %}{% endif %}">
                                        <img src="{% if shop.user_type == 2 %}{%if shop.credit and shop.credit.identity_picture_lic %}{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}{% endif %}{% endif %}"  id="identity_picture_licshow_img-geti" width="125px" height="95px" />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- 个体工商户营业执照 start -->
                    <!-- 身份证正面照 start -->
                      <div class="certBox clearfix">
                        <label><font class="label_bc"></font>身份证正面照：</label>
                        <div class="addImgBox clearfix">
                            <!--<img src="{% if shop.user_type == 2 %}{%if shop.credit and shop.credit.identity_card_front %}{{shop.credit.identity_card_front}}{% endif %}{% endif %}" id="identity_card_frontshow_img-geti" width="125px" height="95px"  />-->
                            <ul class="gallery">
                                <li>
                                    <a href="{% if shop.user_type == 2 %}{%if shop.credit and shop.credit.identity_picture_lic %}{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}{% endif %}{% endif %}">
                                        <img src="{% if shop.user_type == 2 %}{%if shop.credit and shop.credit.identity_card_front %}{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}{% endif %}{% endif %}" id="identity_card_frontshow_img-geti" width="125px" height="95px"  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- 身份证正面照 start -->
                    <!-- 身份证背面照 start -->
                    <div class="certBox clearfix">
                        <label><font class="label_bc"></font>身份证背面照：</label>
                        <div class="addImgBox clearfix">
                            <!--<img src="{% if shop.user_type == 2 %}{%if shop.credit and shop.credit.identity_card_back %}{{shop.credit.identity_card_back}}{% endif %}{% endif %}" id="identity_card_backshow_img-geti"  width="125px" height="95px" />-->
                            <ul class="gallery">
                                <li>
                                    <a href="{% if shop.user_type == 2 %}{%if shop.credit and shop.credit.identity_card_back %}{{ constant('IMG_URL') }}{{shop.credit.identity_card_back}}{% endif %}{% endif %}">
                                        <img src="{% if shop.user_type == 2 %}{%if shop.credit and shop.credit.identity_card_back %}{{ constant('IMG_URL') }}{{shop.credit.identity_card_back}}{% endif %}{% endif %}" id="identity_card_backshow_img-geti"  width="125px" height="95px" />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- 身份证背面照 start -->
                </div>
                <!-- 个体户认证 end -->
                
                
                <!-- 企业认证 start -->
                <div class="formCert clearfix" style="display:none;{% if shop.user_type == 3 %} display:block; {% endif %}"  >
                    <!-- 银行开户许可证 start -->
                    <div class="certBox clearfix">
                        <label><font class="label_bc"></font>银行开户许可证：</label>
                        <div class="addImgBox clearfix">
                            <!--<img src="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.bank_card_picture %}{{shop.credit.bank_card_picture}}{% endif %}{% endif %}" id="bank_card_pictureshow_img-qiye" width="125px" height="95px" />-->
                            <ul class="gallery">
                                <li>
                                    <a href="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.bank_card_picture %}{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}{% endif %}{% endif %}">
                                        <img src="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.bank_card_picture %}{{ constant('IMG_URL') }}{{shop.credit.bank_card_picture}}{% endif %}{% endif %}" id="bank_card_pictureshow_img-qiye" width="125px" height="95px" />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- 银行开户许可证 end -->
                    <!-- 企业营业执照副本照 start -->
                    <div class="certBox clearfix">
                        <label><font class="label_bc"></font>企业营业执照副本照：</label>
                        <div class="addImgBox clearfix">
                            <!--<img src="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.identity_picture_lic %}{{shop.credit.identity_picture_lic}}{% endif %}{% endif %}" id="identity_picture_licshow_img-qiye" width='125px' height='95px'/>-->
                            <ul class="gallery">
                                <li>
                                    <a href="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.identity_picture_lic %}{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}{% endif %}{% endif %}">
                                        <img src="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.identity_picture_lic %}{{ constant('IMG_URL') }}{{shop.credit.identity_picture_lic}}{% endif %}{% endif %}" id="identity_picture_licshow_img-qiye" width='125px' height='95px'/>
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- 企业营业执照副本照 end -->
                    <!-- 企业税务登记证照 start -->
                    <div class="certBox clearfix">
                        <label><font class="label_bc"></font>企业税务登记证照：</label>
                        <div class="addImgBox clearfix">
                            <!--<img src="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.tax_registration %}{{shop.credit.tax_registration}}{% endif %}{% endif %}"  id="tax_registrationshow_img" width='125px' height='95px'/>-->
                            <ul class="gallery">
                                <li>
                                    <a href="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.tax_registration %}{{ constant('IMG_URL') }}{{shop.credit.tax_registration}}{% endif %}{% endif %}">
                                        <img src="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.tax_registration %}{{ constant('IMG_URL') }}{{shop.credit.tax_registration}}{% endif %}{% endif %}"  id="tax_registrationshow_img" width='125px' height='95px'/>
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- 企业税务登记证照 end -->
                    <!-- 组织机构代码证 start -->
                    <div class="certBox clearfix">
                        <label><font class="label_bc"></font>组织机构代码证：</label>
                        <div class="addImgBox clearfix">
                            <!--<img src="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.organization_code %}{{shop.credit.organization_code}}{% endif %}{% endif %}" id="organization_codeshow_img"  width='125px' height='95px' />-->
                            <ul class="gallery">
                                <li>
                                    <a href="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.organization_code %}{{ constant('IMG_URL') }}{{shop.credit.organization_code}}{% endif %}{% endif %}">
                                        <img src="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.organization_code %}{{ constant('IMG_URL') }}{{shop.credit.organization_code}}{% endif %}{% endif %}" id="organization_codeshow_img"  width='125px' height='95px' />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- 组织机构代码证 end -->
                    <div class="represent">
                        <div class="line"></div>
                        <span>法定代表人身份证照</span>
                    </div>
                    <!-- 身份证正面照 start -->
                    <div class="certBox clearfix">
                        <label><font class="label_bc"></font>身份证正面照：</label>
                        <div class="addImgBox clearfix">
                            <!--<img src="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.identity_card_front %}{{shop.credit.identity_card_front}}{% endif %}{% endif %}"  id="identity_card_frontshow_img-qiye" width='125px' height='95px'  />-->
                            <ul class="gallery">
                                <li>
                                    <a href="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.identity_card_front %}{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}{% endif %}{% endif %}">
                                        <img src="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.identity_card_front %}{{ constant('IMG_URL') }}{{shop.credit.identity_card_front}}{% endif %}{% endif %}"  id="identity_card_frontshow_img-qiye" width='125px' height='95px'  />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- 身份证正面照 start -->
                    <!-- 身份证背面照 start -->
                    <div class="certBox clearfix">
                        <label><font class="label_bc"></font>身份证背面照：</label>
                        <div class="addImgBox clearfix">
                            <!--<img src="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.identity_card_back %}{{shop.credit.identity_card_back}}{% endif %}{% endif %}" id="identity_card_backshow_img-qiye" width='125px' height='95px' />-->
                            <ul class="gallery">
                                <li>
                                    <a href="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.identity_card_back %}{{ constant('IMG_URL') }}{{shop.credit.identity_card_back}}{% endif %}{% endif %}">
                                        <img src="{% if shop.user_type == 3 %}{%if shop.credit and shop.credit.identity_card_back %}{{ constant('IMG_URL') }}{{shop.credit.identity_card_back}}{% endif %}{% endif %}" id="identity_card_backshow_img-qiye" width='125px' height='95px' />
                                    </a>
                                </li>
                                <li><a href="#"><img src="" /></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- 身份证背面照 start -->
                </div>
                <!-- 企业认证 end -->
           
            </div>
         </div> 
         <!-- 认证信息 end -->
         <!-- 网店介绍 start -->
         <div class="introduce_shop pb20">
            <h6 class="f-fs14 pl20">网店介绍</h6>
            <script id="editor" type="text/plain"  style="height:500px;" name="shop_desc" >
            {{shop.credit ? shop.credit.shop_desc : ''}}</script>
            <div class="shopdesc_box pl20">
                <input style="width:1px;opacity:0;filter:alpha(opacity:0);" value="" type="text" data-target="#shopdesc-tip" id="shopdesc" name="shopdesc" />
                <i id="shopdesc-tip"></i>
            </div>
         </div> 
         <!-- 网店介绍 end --> 
          <div class="shop_tj_sq">
              <input type="hidden" name="sid" value="{{ sid }}" />
                <input type="hidden" name="shopid" value="{{ shop.shop_id }}" />
            <input class="btn" type="submit" value="提交申请" />{{content()}}
          </div>  
    </div>
    </form>
    <!-- 右侧 end -->
</div>

<!-- 底部 start -->
{{ partial('layouts/footer') }}
<!-- 底部 end -->

<!-- 图片点击放大引入文件 -->
<link rel="stylesheet"  href="{{ constant('STATIC_URL') }}mdg/css/zoom.css" media="all" />
<script src="{{ constant('STATIC_URL') }}mdg/js/zoom.min.js"></script>

<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.config.sample.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/lang/zh-cn/zh-cn.js"></script> 
<!--编辑-->
<script type="text/javascript">
    var ue = UE.getEditor('editor');

    $('#newshop').on('submit', function(){
        if(UE.getEditor('editor').hasContents()){
            $('#shopdesc').val('1');
            //alert($('#shopdesc').val());
        }else{
            $('#shopdesc').val('');
            //alert($('#shopdesc').val());
        };
    });
</script>

<script>

$('#jiaose').bind('change', function(){
    $('#hidVal').val($(this).val());
    //alert($('#hidVal').val());
    if($('#hidVal').val() == '1'){
        $('#certTab .formCert').hide();
        $('#certTab .formCert').eq(0).show();
    }else if($('#hidVal').val() == '2'){
        $('#certTab .formCert').hide();
        $('#certTab .formCert').eq(1).show();
    }else if($('#hidVal').val() == '3'){
        $('#certTab .formCert').hide();
        $('#certTab .formCert').eq(2).show();
    };
});
$('#newshop').validator({
        ignore: ':hidden',
        groups: [{
            fields: "main_product1 main_product2 main_product3 main_product4 main_product5",
            callback: function($elements){
                var me = this, count = 0;
                $elements.each(function(){
                    //利用test方法调用内置规则required
                    if (me.test(this, 'required')) count+=1;
                });
                return count>=1 || '至少填写一种产品';
            },
            target: "#main_product_tip"
        }],
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
             nimei: [/^[\u4E00-\u9FA5A-Za-z0-9_]+$/,'店铺名称格式错误'],
             account_name:[/^[\u4E00-\u9FA5A-Za-z_]+$/,'开户名格式错误'],
             zw:[/^[\u4E00-\u9FA5]{1,}$/, "请输入中文"]
        },
        fields:  {
             shop_name:"店铺名称:required;nimei;remote[/member/ajax/checkedit]",
             district:"所在地区:required;",
             contact_man:"联系人:required;chinese;length[2~5];",
             contact_phone:"电话:required;mobile;",
             identity_no:"身份证号:required;ID_card;",
             bank_name:"银行卡开户行:required;chinese;",
             account_name:"开户名:required;account_name",
             card_no:"卡号:required;mark;",
             shopdesc:"网店介绍:required;"
        }
});

// 地区联动
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if (area) %}
    texts : [{{ area }}],
    {% endif %}
    style : {"width" : 140}
});
jQuery(document).ready(function(){
    function bankImg(id,type,show_img,tip_id){
            //银行正面照
            id.uploadify({
                'width'    : '95',
                'height'   : '31',
                'swf'      : '/uploadify/uploadify.swf',
                'uploader' : '/upload/tmpfile',
                'fileSizeLimit' : '2MB',
                'fileTypeExts' : '*.jpg;*.png;*.jpeg;*.bmp;*.png',
                'formData' : {
                    'sid' : '{{ sid }}',
                    'type' :type,
                },
                'buttonClass' : 'upload_btn',
                'buttonText'  : '上传',
                'multi'       : false,
                onDialogOpen : function() {
                    $('.gy_step').eq(1).addClass("active").siblings().removeClass("active");
                },
                onUploadSuccess  : function(file, data, response) {
                    data = $.parseJSON(data);
                    alert(data.msg);
                    if(data.status) {
                        //show_img.attr("src":data.path);
                        //show_img.append(data.html);
                        show_img.attr('src', data.path);
                        show_img.css('cursor', 'pointer');
                        show_img.next('span').find('img').attr('src', data.path);
                        tip_id.val('1');
                        tip_id.next('i').html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
                    }
                }
          });
    }

   function settime(){
        bankImg($('#bank_card_picture'),6,$('#bank_card_pictureshow_img'),$('#bank_card_pictureshow_img-hide'));
        bankImg($('#identity_card_front'),7,$('#identity_card_frontshow_img'),$('#identity_card_frontshow_img-hide'));
        bankImg($('#identity_picture_lic'),8,$('#identity_picture_licshow_img'),$('#identity_picture_licshow_img-hide'));
        bankImg($('#identity_card_back'),9,$('#identity_card_backshow_img'),$('#identity_card_backshow_img-hide'));
        bankImg($('#tax_registration'),10,$('#tax_registrationshow_img'),$('#tax_registrationshow_img-hide'));
        bankImg($('#organization_code'),11,$('#organization_codeshow_img'),$('#organization_codeshow_img-hide'));
        bankImg($('#bank_card_picture-geti'),6,$('#bank_card_pictureshow_img-geti'),$('#bank_card_pictureshow_img-geti-hide'));
        bankImg($('#identity_picture_lic-geti'),8,$('#identity_picture_licshow_img-geti'),$('#identity_picture_licshow_img-geti-hide'));
        bankImg($('#identity_card_front-geti'),7,$('#identity_card_frontshow_img-geti'),$('#identity_card_frontshow_img-geti-hide'));
        bankImg($('#identity_card_back-geti'),9,$('#identity_card_backshow_img-geti'),$('#identity_card_backshow_img-geti-hide'));
        bankImg($('#bank_card_picture-qiye'),6,$('#bank_card_pictureshow_img-qiye'),$('#bank_card_pictureshow_img-qiye-hide'));
        bankImg($('#identity_picture_lic-qiye'),8,$('#identity_picture_licshow_img-qiye'),$('#identity_picture_licshow_img-qiye-hide'));
        bankImg($('#identity_card_front-qiye'),7,$('#identity_card_frontshow_img-qiye'),$('#identity_card_frontshow_img-qiye-hide'));
        bankImg($('#identity_card_back-qiye'),9,$('#identity_card_backshow_img-qiye'),$('#identity_card_backshow_img-qiye-hide'));
    }
    var time1=setTimeout(settime(),100);
    
});
</script> 
<style>
.upload_btn {  width: 92px;
  height: 30px;
  text-align: center;
  line-height: 30px;
  color: #808080;
  font-family: '微软雅黑';
  font-size: 14px;
  background: url(../images/file_btn.png) no-repeat;
  background-position: 0 0;
  cursor: pointer;
  margin: 10px auto 0;
  position: relative;}
.edui-default .edui-editor{ margin:10px auto;}
</style>
</body>
</html>
