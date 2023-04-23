<!-- 头部 start -->
{{ partial('layouts/page_header') }}
<!-- 头部 end -->

<div class="ur_here w960">
    <span>{{ partial('layouts/ur_here') }}服务站管理</span>
</div>
<div class="personal_center w960 mb20">
    <!-- 左侧导航栏 start -->
    {{ partial('layouts/navs_left') }}
    <!-- 左侧导航栏 end -->
    <!-- 右侧 start -->
    <div class="center_right f-fr">
        {{ form("service/create", "method":"post","id":"newshop") }}
        <!-- 申请开通店铺 start -->
        <div class="open_shop">
            <h6 class="f-fs14 pl20">
                申请服务站
                <!-- <a class="what f-fr" href="/member/service/servicearticle">什么是服务站？</a> -->
            </h6>
            <div class="formVal clearfix mt20">

                <div class="formBox clearfix">
                    <label> <font class="label_bc"></font>
                        您的身份：
                    </label>
                    <div class="userBox">
                        <!-- 下拉框 start -->
                        <div class="selectBox">
                            <select id="jiaose" class="user_jiao" name="business_type" >
                                {% for key,val in servicetype %}
                                <option value="{{key}}" >{{val}}</option>
                                {% endfor %}
                            </select>
                            <input id="hidVal" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" />
                        </div>
                        <!-- 下拉框 end --> </div>
                </div>
                <div class="formBox clearfix">
                    <label> <font class="label_bc"></font>
                        主营产品：
                    </label>
                    <div class="goodsTxt">
                        <input type="text"  name="main_product1" id="main_product1" data-rule="zw" data-target="#main_product_tip" />
                        <input type="text"  name="main_product2" data-rule="zw" data-target="#main_product_tip" />
                        <input type="text"  name="main_product3" data-rule="zw" data-target="#main_product_tip" />
                        <input type="text"  name="main_product4" data-rule="zw" data-target="#main_product_tip" />
                        <input type="text"  name="main_product5" data-rule="zw" data-target="#main_product_tip" /> <i id="main_product_tip"></i> <i id="btn_main_product_tip"></i>
                    </div>
                </div>
                <div class="formBox clearfix">
                    <label>
                        <font class="label_bc"></font>
                        所在地区：
                    </label>
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
                    <label><font class="label_bc"></font>申请负责区域：</label>
                    <div class="inputBtn">
                            <input type="button" value="选择" id="newaddress" onclick="newService('newaddress', '申请负责区域', '/member/dialog/newaddress')" />
                        <input type="hidden" name='service_area' id='service_area' value='' >
                        <font id='showAreas'></font>
                    </div>
                </div>
                <div class="formBox clearfix">
                    <label>
                        <font class="label_bc"></font>
                        联系人：
                    </label>
                    <div class="inputTxt">
                        <input type="text" name="contact_man" value="{{ users.ext ?  users.ext.name : '' }}" />
                    </div>
                </div>
                <div class="formBox clearfix">
                    <label>
                        <font class="label_bc"></font>
                        电话：
                    </label>
                    <div class="inputTxt">
                        <input type="text" name="contact_phone" value="{{ users.ext ?  users.username  : '' }}"/>
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
                    <label>
                        <font class="label_bc"></font>
                        身份证号：
                    </label>
                    <div class="inputTxt">
                        <input type="text" name="identity_no"  />
                    </div>
                </div>
                <div class="certBox clearfix">
                    <label>
                        <font class="label_bc"></font>
                        银行卡开户行：
                    </label>
                    <div class="inputTxt">
                        <input type="text" name="bank_name" />
                    </div>
                </div>
                <div class="certBox clearfix">
                    <label>
                        <font class="label_bc"></font>
                        开户名：
                    </label>
                    <div class="inputTxt">
                        <input type="text" name="account_name" value='{{ users.ext ?  users.ext.name : '' }}' />
                    </div>
                </div>
                <div class="certBox clearfix">
                    <label>
                        <font class="label_bc"></font>
                        卡号：
                    </label>
                    <div class="inputTxt">
                        <input type="text" name="card_no" />
                    </div>
                </div>
                <!-- 公共信息 end -->

                <!-- 个体户认证 start -->
                <div class="formCert" style="display:block;">

                    <!-- 个人照 start -->
                    <div class="certBox clearfix">
                        <label>
                            <font class="label_bc"></font>
                            个人照片：
                        </label>
                        <div class="addImgBox clearfix">
                            <div class="lodeImg clearfix f-fl">
                                <div class="fileBtn">
                                    <div class="imgBox" >

                                        <ul class="gallery">
                                            <li>
                                                <a href="">
                                                    <img src="{{ constant('STATIC_URL')}}/mdg/images/dt111.png" id="personal_logo_pictureshow_img-geti" width="125px" height="95px"/>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="" style="opacity:0; filter:alpha(opacity:0);" />
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn">
                                        <input type="file"  id="personal_logo_picture"></div>
                                </div>
                                <div class="images">
                                    <img src="{{ constant('STATIC_URL') }}mdg/images/service/lodeImg_img10.jpg" />
                                    <p>证件上文字清晰可见</p>
                                </div>
                            </div>
                            <div class="addImg_tip f-fl">
                                <input id="personal_logo_pictureshow_img-geti-hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="预览图:required;" data-target="#personal_logo_pictureshow_img-geti-tip" />
                                <i id="personal_logo_pictureshow_img-geti-hide"></i>
                            </div>
                        </div>
                    </div>
                    <!-- 个人照 end -->

                    <!-- 银行卡正面照 start -->
                    <div class="certBox clearfix">
                        <label>
                            <font class="label_bc"></font>
                            银行卡正面照：
                        </label>
                        <div class="addImgBox clearfix">
                            <div class="lodeImg clearfix f-fl">
                                <div class="fileBtn">
                                    <div class="imgBox" >

                                        <ul class="gallery">
                                            <li>
                                                <a href="">
                                                    <img src="{{ constant('STATIC_URL')}}/mdg/images/dt111.png" id="bank_card_pictureshow_img-geti" width="125px" height="95px"/>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src=""  style="opacity:0; filter:alpha(opacity:0);" />
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn">
                                        <input type="file"  id="bank_card_picture-geti"></div>
                                </div>
                                <div class="images">
                                    <img src="{{ constant('STATIC_URL') }}mdg/images/lodeImg_img1.jpg" />
                                    <p>证件上文字清晰可见</p>
                                </div>
                            </div>
                            <div class="addImg_tip f-fl">
                                <input id="bank_card_pictureshow_img-geti-hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="预览图:required;" data-target="#bank_card_pictureshow_img-geti-tip" />
                                <i id="bank_card_pictureshow_img-geti-tip"></i>
                            </div>
                        </div>
                    </div>
                    <!-- 银行卡正面照 end -->

                    <!-- 个体工商户营业执照 start -->
                    <div class="certBox clearfix">
                        <label>
                            <font class="label_bc"></font>
                            个体工商户营业执照：
                        </label>
                        <div class="addImgBox clearfix">
                            <div class="lodeImg clearfix f-fl">
                                <div class="fileBtn">
                                    <div class="imgBox">
                                        <!--<img src=""  id="identity_picture_licshow_img-geti" width="125px" height="95px" />
                                        -->
                                        <ul class="gallery">
                                            <li>
                                                <a href="">
                                                    <img src="{{ constant('STATIC_URL')}}/mdg/images/dt111.png"  id="identity_picture_licshow_img-geti" width="125px" height="95px" />
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="" style="opacity:0; filter:alpha(opacity:0);" />
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn">
                                        <input type="file"  id="identity_picture_lic-geti"/>
                                    </div>
                                </div>
                                <div class="images">
                                    <img src="{{ constant('STATIC_URL') }}mdg/images/lodeImg_img4.jpg" />
                                    <p>证件上文字清晰可见</p>
                                </div>
                            </div>
                            <div class="addImg_tip f-fl">
                                <input id="identity_picture_licshow_img-geti-hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="预览图:required;" data-target="#identity_picture_licshow_img-geti-tip" />
                                <i id="identity_picture_licshow_img-geti-tip"></i>
                            </div>
                        </div>
                    </div>
                    <!-- 个体工商户营业执照 start -->
                    <!-- 身份证正面照 start -->
                    <div class="certBox clearfix">
                        <label>
                            <font class="label_bc"></font>
                            身份证正面照：
                        </label>
                        <div class="addImgBox clearfix">
                            <div class="lodeImg clearfix f-fl">
                                <div class="fileBtn">
                                    <div class="imgBox">
                                        <!--<img src="" id="identity_card_frontshow_img-geti" width="125px" height="95px"  />
                                        -->
                                        <ul class="gallery">
                                            <li>
                                                <a href="">
                                                    <img src="{{ constant('STATIC_URL')}}/mdg/images/dt111.png" id="identity_card_frontshow_img-geti" width="125px" height="95px"  />
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src=""  style="opacity:0; filter:alpha(opacity:0);" />
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn">
                                        <input type="file" id="identity_card_front-geti" />
                                    </div>
                                </div>
                                <div class="images">
                                    <img src="{{ constant('STATIC_URL') }}mdg/images/lodeImg_img3.jpg" />
                                    <p>证件上文字清晰可见</p>
                                </div>
                            </div>
                            <div class="addImg_tip f-fl">
                                <input id="identity_card_frontshow_img-geti-hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="预览图:required;" data-target="#identity_card_frontshow_img-geti-tip" />
                                <i id="identity_card_frontshow_img-geti-tip"></i>
                            </div>
                        </div>
                    </div>
                    <!-- 身份证正面照 start -->
                    <!-- 身份证背面照 start -->
                    <div class="certBox clearfix">
                        <label>
                            <font class="label_bc"></font>
                            身份证背面照：
                        </label>
                        <div class="addImgBox clearfix">
                            <div class="lodeImg clearfix f-fl">
                                <div class="fileBtn">
                                    <div class="imgBox">
                                        <!--<img src="" id="identity_card_backshow_img-geti"  width="125px" height="95px" />
                                        -->
                                        <ul class="gallery">
                                            <li>
                                                <a href="">
                                                    <img src="{{ constant('STATIC_URL')}}/mdg/images/dt111.png" id="identity_card_backshow_img-geti"  width="125px" height="95px" />
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="" style="opacity:0; filter:alpha(opacity:0);" />
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn">
                                        <input type="file" id='identity_card_back-geti' />
                                    </div>
                                </div>
                                <div class="images">
                                    <img src="{{ constant('STATIC_URL') }}mdg/images/lodeImg_img9.jpg" />
                                    <p>证件上文字清晰可见</p>
                                </div>
                            </div>
                            <div class="addImg_tip f-fl">
                                <input id="identity_card_backshow_img-geti-hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="预览图:required;" data-target="#identity_card_backshow_img-geti-tip" />
                                <i id="identity_card_backshow_img-geti-tip"></i>
                            </div>
                        </div>
                    </div>
                    <!-- 身份证背面照 start -->
                </div>
                <!-- 个体户认证 end -->

                <!-- 企业认证 start -->
                <div class="formCert" style="display:none;">

                    <div class="certBox clearfix">
                        <label>
                            <font class="label_bc"></font>
                            企业名称：
                        </label>
                        <div class="inputTxt">
                            <input type="text" name="shop_name" data-rule="required;"  />
                        </div>
                    </div>

                    <!-- 个人照 start -->
                    <div class="certBox clearfix">
                        <label>
                            <font class="label_bc"></font>
                            企业logo：
                        </label>
                        <div class="addImgBox clearfix">
                            <div class="lodeImg clearfix f-fl">
                                <div class="fileBtn">
                                    <div class="imgBox" >
                                        <ul class="gallery">
                                            <li>
                                                <a href="">
                                                 <img src="{{ constant('STATIC_URL')}}/mdg/images/dt111.png" id="logo_pictureshow_img-geti" width="125px" height="95px"/>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src=""  style="opacity:0; filter:alpha(opacity:0);" />
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn">
                                        <input type="file"  id="logo_picture"></div>
                                </div>
                                <div class="images">
                                    <img src="{{ constant('STATIC_URL') }}mdg/images/service/lodeImg_img11.jpg" />
                                    
                                    <p>证件上文字清晰可见</p>
                                </div>
                            </div>
                            <div class="addImg_tip f-fl">
                                <input id="logo_pictureshow_img-geti-hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="预览图:required;" data-target="#logo_pictureshow_img-geti-tip" />
                                <i id="logo_pictureshow_img-geti-hide"></i>
                            </div>
                        </div>
                    </div>
                    <!-- 个人照 end -->


                    <!-- 银行开户许可证 start -->
                    <div class="certBox clearfix">
                        <label>
                            <font class="label_bc"></font>
                            银行开户许可证：
                        </label>
                        <div class="addImgBox clearfix">
                            <div class="lodeImg clearfix f-fl">
                                <div class="fileBtn">
                                    <div class="imgBox">
                                        <!--<img src="" id="bank_card_pictureshow_img-qiye" width="125px" height="95px" />
                                        -->
                                        <ul class="gallery">
                                            <li>
                                                <a href="">
                                                    <img src="{{ constant('STATIC_URL')}}/mdg/images/dt111.png" id="bank_card_pictureshow_img-qiye" width="125px" height="95px" />
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src=""  style="opacity:0; filter:alpha(opacity:0);" />
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn">
                                        <input type="file"  id="bank_card_picture-qiye" />
                                    </div>
                                </div>
                                <div class="images">
                                    <img src="{{ constant('STATIC_URL') }}mdg/images/lodeImg_img5.jpg" />
                                    <p>证件上文字清晰可见</p>
                                </div>
                            </div>
                            <div class="addImg_tip f-fl">
                                <input id="bank_card_pictureshow_img-qiye-hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="预览图:required;" data-target="#bank_card_pictureshow_img-qiye-tip" />
                                <i id="bank_card_pictureshow_img-qiye-tip"></i>
                            </div>
                        </div>
                    </div>
                    <!-- 银行开户许可证 end -->
                    <!-- 企业营业执照副本照 start -->
                    <div class="certBox clearfix">
                        <label>
                            <font class="label_bc"></font>
                            企业营业执照副本照：
                        </label>
                        <div class="addImgBox clearfix">
                            <div class="lodeImg clearfix f-fl">
                                <div class="fileBtn">
                                    <div class="imgBox">
                                        <!--<img src="" id="identity_picture_licshow_img-qiye" width='125px' height='95px'/>
                                        -->
                                        <ul class="gallery">
                                            <li>
                                                <a href="">
                                                    <img src="{{ constant('STATIC_URL')}}/mdg/images/dt111.png" id="identity_picture_licshow_img-qiye" width='125px' height='95px'/>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="" style="opacity:0; filter:alpha(opacity:0);" />
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn">
                                        <input type="file" id="identity_picture_lic-qiye" />
                                    </div>
                                </div>
                                <div class="images">
                                    <img src="{{ constant('STATIC_URL') }}mdg/images/lodeImg_img6.jpg" />
                                    <p>证件上文字清晰可见</p>
                                </div>
                            </div>
                            <div class="addImg_tip f-fl">
                                <input id="identity_picture_licshow_img-qiye-hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="预览图:required;" data-target="#identity_picture_licshow_img-qiye-tip" />
                                <i id="identity_picture_licshow_img-qiye-tip"></i>
                            </div>
                        </div>
                    </div>
                    <!-- 企业营业执照副本照 end -->
                    <!-- 企业税务登记证照 start -->
                    <div class="certBox clearfix">
                        <label>
                            <font class="label_bc"></font>
                            企业税务登记证照：
                        </label>
                        <div class="addImgBox clearfix">
                            <div class="lodeImg clearfix f-fl">
                                <div class="fileBtn">
                                    <div class="imgBox">
                                        <!--<img src=""  id="tax_registrationshow_img" width='125px' height='95px'/>
                                        -->
                                        <ul class="gallery">
                                            <li>
                                                <a href="">
                                                    <img src="{{ constant('STATIC_URL')}}/mdg/images/dt111.png"  id="tax_registrationshow_img" width='125px' height='95px'/>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="" style="opacity:0; filter:alpha(opacity:0);" />
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn">
                                        <input type="file" id="tax_registration" />
                                    </div>
                                </div>
                                <div class="images">
                                    <img src="{{ constant('STATIC_URL') }}mdg/images/lodeImg_img7.jpg" />
                                    <p>证件上文字清晰可见</p>
                                </div>
                            </div>
                            <div class="addImg_tip f-fl">
                                <input id="tax_registrationshow_img-hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="预览图:required;" data-target="#tax_registrationshow_img-tip" />
                                <i id="tax_registrationshow_img-tip"></i>
                            </div>
                        </div>
                    </div>
                    <!-- 企业税务登记证照 end -->
                    <!-- 组织机构代码证 start -->
                    <div class="certBox clearfix">
                        <label>
                            <font class="label_bc"></font>
                            组织机构代码证：
                        </label>
                        <div class="addImgBox clearfix">
                            <div class="lodeImg clearfix f-fl">
                                <div class="fileBtn">
                                    <div class="imgBox">
                                        <!--<img src="" id="organization_codeshow_img"  width='125px' height='95px' />
                                        -->
                                        <ul class="gallery">
                                            <li>
                                                <a href="">
                                                    <img src="{{ constant('STATIC_URL')}}/mdg/images/dt111.png" id="organization_codeshow_img"  width='125px' height='95px' />
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src=""   style="opacity:0; filter:alpha(opacity:0);" />
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn">
                                        <input type="file" id="organization_code"/>
                                    </div>
                                </div>
                                <div class="images">
                                    <img src="{{ constant('STATIC_URL') }}mdg/images/lodeImg_img8.jpg" />
                                    <p>证件上文字清晰可见</p>
                                </div>
                            </div>
                            <div class="addImg_tip f-fl">
                                <input id="organization_codeshow_img-hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="预览图:required;" data-target="#organization_codeshow_img-tip" />
                                <i id="organization_codeshow_img-tip"></i>
                            </div>
                        </div>
                    </div>
                    <!-- 组织机构代码证 end -->
                    <div class="represent">
                        <div class="line"></div>
                        <span>法定代表人身份证照</span>
                    </div>
                    <!-- 身份证正面照 start -->
                    <div class="certBox clearfix">
                        <label>
                            <font class="label_bc"></font>
                            身份证正面照：
                        </label>
                        <div class="addImgBox clearfix">
                            <div class="lodeImg clearfix f-fl">
                                <div class="fileBtn">
                                    <div class="imgBox">
                                        <!--<img src=""  id="identity_card_frontshow_img-qiye" width='125px' height='95px'  />
                                        -->
                                        <ul class="gallery">
                                            <li>
                                                <a href="">
                                                    <img src="{{ constant('STATIC_URL')}}/mdg/images/dt111.png"  id="identity_card_frontshow_img-qiye" width='125px' height='95px'  />
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="" style="opacity:0; filter:alpha(opacity:0);" />
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn">

                                        <input type="file" id="identity_card_front-qiye"    />
                                    </div>
                                </div>
                                <div class="images">
                                    <img src="{{ constant('STATIC_URL') }}mdg/images/lodeImg_img3.jpg" />
                                    <p>证件上文字清晰可见</p>
                                </div>
                            </div>
                            <div class="addImg_tip f-fl">
                                <input id="identity_card_frontshow_img-qiye-hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="预览图:required;" data-target="#identity_card_frontshow_img-qiye-tip" />
                                <i id="identity_card_frontshow_img-qiye-tip"></i>
                            </div>
                        </div>
                    </div>
                    <!-- 身份证正面照 start -->
                    <!-- 身份证背面照 start -->
                    <div class="certBox clearfix">
                        <label>
                            <font class="label_bc"></font>
                            身份证背面照：
                        </label>
                        <div class="addImgBox clearfix">
                            <div class="lodeImg clearfix f-fl">
                                <div class="fileBtn">
                                    <div class="imgBox">
                                        <!--<img src="" id="identity_card_backshow_img-qiye" width='125px' height='95px' />
                                        -->
                                        <ul class="gallery">
                                            <li>
                                                <a href="">
                                                    <img src="{{ constant('STATIC_URL')}}/mdg/images/dt111.png" id="identity_card_backshow_img-qiye" width='125px' height='95px' />
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="" style="opacity:0; filter:alpha(opacity:0);" />
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn">
                                        <input type="file" id="identity_card_back-qiye" />
                                    </div>
                                </div>
                                <div class="images">
                                    <img src="{{ constant('STATIC_URL') }}mdg/images/lodeImg_img9.jpg" />
                                    <p>证件上文字清晰可见</p>
                                </div>
                            </div>
                            <div class="addImg_tip f-fl">
                                <input id="identity_card_backshow_img-qiye-hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" data-rule="预览图:required;" data-target="#identity_card_backshow_img-qiye-tip" />
                                <i id="identity_card_backshow_img-qiye-tip"></i>
                            </div>
                        </div>
                    </div>
                    <!-- 身份证背面照 start --> 
                </div>
                <!-- 企业认证 end --> 
        </div>
        <!-- 认证信息 end -->
        <!-- 网店介绍 start -->
        <div class="introduce_shop pb20">
            <h6 class="f-fs14 pl20" id='onete' >个人介绍</h6>
            <script id="editor" type="text/plain" style="height:500px;" name="shop_desc" ></script>
            <div class="shopdesc_box pl20">
                <input style="width:1px;opacity:0;filter:alpha(opacity:0);" value="" type="text" data-target="#shopdesc-tip" id="shopdesc" name="shopdesc" />
                <i id="shopdesc-tip"></i>
            </div>
        </div>
        <!-- 网店介绍 end -->
        <div class="shop_tj_sq">
            <input class="btn" type="submit" value="提交申请" />
        </div>
    </div>
</form>
<!-- 右侧 end -->
</div>
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

<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<!--编辑-->
<script type="text/javascript">
    var ue = UE.getEditor('editor');

    $('#newshop').on('submit', function(){
        if(UE.getEditor('editor').hasContents()){
            $('#shopdesc').val('1');
            $('#shopdesc').hide();
            $("#shopdesc-tip").hide();

            //alert($('#shopdesc').val());
        }else{
            $('#shopdesc').show();
            $('#shopdesc').val('');

           // alert($('#shopdesc').val());
        };
    });
    var dialogservice = null;
    function newService(id,title,url){
       
        dialogservice = $.dialog({
            id    : id,
            title : title,
            min   : false,
            max   : false,
            fixed : true,
            left  : 657, 
            top   : 270,
            lock  : true,
            content: 'url:'+url
        });
    }
    function closeDialog(){
        dialogservice.close();
        window.location.reload();
    }
</script>
<script>


$('#radioBtn .xiang input').click(function(){
    $(this).parent().next('input').show();
    $('#qitaTip').html('');
    $('#qitaTip').show();
});

$('#radioBox .xiang input').click(function(){
    $('#radioBtn .xiang').next('input').val('');
    $('#radioBtn .xiang').next('input').hide();
    $('#qitaTip').html('<span class="msg-box n-right" style="" for="shop_name"><span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span></span>');
    $('#qitaTip').hide();
});


$('#jiaose').bind('change', function(){
    $('#hidVal').val($(this).val());
    //alert($('#hidVal').val());
    if($('#hidVal').val() == '2'){
        $('#onete').text('个人介绍：');
        $('#certTab .formCert').hide();
        $('#certTab .formCert').eq(0).show();
    }else if($('#hidVal').val() == '3'){
        $('#onete').text('企业介绍：');
        $('#certTab .formCert').hide();
        $('#certTab .formCert').eq(1).show();
    };
});

//验证
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
            target: "#btn_main_product_tip"
        }],
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
            nimei: [/^[\u4E00-\u9FA5A-Za-z0-9_]+$/,'企业名称格式错误'],
            account_name:[/^[\u4E00-\u9FA5A-Za-z_]+$/,'开户名格式错误'],
            //nimei  : [/^([0-9])+(\.([0-9])+)?$/, '请输入数字'],
            zw:[/^[\u4E00-\u9FA5]{1,}$/, "请输入中文"]
        },
        fields:  {
             shop_name:"企业名称:required;nimei;remote[/member/ajax/check]",
             district:"所在地区:required;",
             contact_man:"联系人:required;chinese;length[2~5];",
             contact_phone:"电话:required;mobile;",
             identity_no:"身份证号:required;ID_card;",
             bank_name:"银行卡开户行:required;zw;",
             account_name:"开户名:required;account_name",
             card_no:"卡号:required;mark;",
             shopdesc:"介绍:required;",
             qita:"您的身份:required;zw;"
        }
});

// 地区联动
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if (curAreas) %}
    texts : [{{ curAreas }}],
    {% endif %}
    style : {"width" : 102}
});
jQuery(document).ready(function(){

    /**
     * 实例化图片
     * @param  {[type]} id       对象id
     * @param  {[type]} type     类型 
     * @param  {[type]} show_img 显示位置
     * @param  {[type]} tip_id   错误提示位置
     * @return 
     */
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
                    'type' :type
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
                        show_img.parent().attr('href', data.path);
                        tip_id.val('1');
                        tip_id.next('i').html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
                    }
                }
          });
    }
    function settime(){
        bankImg($('#bank_card_picture'),6,$('#bank_card_pictureshow_img'),$('#bank_card_pictureshow_img-hide'));
        //身份证正面照
        bankImg($('#identity_card_front'),7,$('#identity_card_frontshow_img'),$('#identity_card_frontshow_img-hide'));
        //持身份证正面头部照
        bankImg($('#identity_picture_lic'),8,$('#identity_picture_licshow_img'),$('#identity_picture_licshow_img-hide'));
        //身份证反面照
        bankImg($('#identity_card_back'),9,$('#identity_card_backshow_img'),$('#identity_card_backshow_img-hide'));
        //企业税务登记证照
        bankImg($('#tax_registration'),10,$('#tax_registrationshow_img'),$('#tax_registrationshow_img-hide'));
        //组织机构代码证
        bankImg($('#organization_code'),11,$('#organization_codeshow_img'),$('#organization_codeshow_img-hide'));
        //个体户
        bankImg($('#bank_card_picture-geti'),6,$('#bank_card_pictureshow_img-geti'),$('#bank_card_pictureshow_img-geti-hide'));
        //营业执照
        bankImg($('#identity_picture_lic-geti'),8,$('#identity_picture_licshow_img-geti'),$('#identity_picture_licshow_img-geti-hide'));
        //身份证正面照
        bankImg($('#identity_card_front-geti'),7,$('#identity_card_frontshow_img-geti'),$('#identity_card_frontshow_img-geti-hide'));
        //身份证反面照
        bankImg($('#identity_card_back-geti'),9,$('#identity_card_backshow_img-geti'),$('#identity_card_backshow_img-geti-hide'));

        //企业bank_card_pictureshow_img
        bankImg($('#bank_card_picture-qiye'),6,$('#bank_card_pictureshow_img-qiye'),$('#bank_card_pictureshow_img-qiye-hide'));
        //企业营业执照副本照
        bankImg($('#identity_picture_lic-qiye'),8,$('#identity_picture_licshow_img-qiye'),$('#identity_picture_licshow_img-qiye-hide'));
        //身份证正面照
        bankImg($('#identity_card_front-qiye'),7,$('#identity_card_frontshow_img-qiye'),$('#identity_card_frontshow_img-qiye-hide'));
        //身份证反面照
        bankImg($('#identity_card_back-qiye'),9,$('#identity_card_backshow_img-qiye'),$('#identity_card_backshow_img-qiye-hide'));

        bankImg($('#logo_picture') ,21,$('#logo_pictureshow_img-geti'),$('#logo_pictureshow_img-geti-hide'));

        bankImg($('#personal_logo_picture'),21,$('#personal_logo_pictureshow_img-geti'),$('#personal_logo_pictureshow_img-geti-hide'));
   };
   setTimeout(settime(),10);
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
.fileBtn .btn object{ left:0; top:0;}
</style>