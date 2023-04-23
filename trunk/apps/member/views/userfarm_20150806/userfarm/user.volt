<!--头部 start-->
{{ partial('layouts/page_header') }}
<!--头部 end-->

<!--主体 start-->
<div class="center-wrapper pb30">
    <div class="bread-crumbs w1185 mtauto">
        <a href="/">首页</a>
        >
        <a href="/member">个人中心</a>
        > 可信农场
    </div>
    <div class="w1185 mtauto clearfix">
        <!-- 左侧 start-->
        {{ partial('layouts/navs_left') }}
        <!-- 左侧 end-->
        <form action="/member/userfarm/usersave" method="post" id='userForm'>
            <!-- 右侧 -->
            <div class="center-right f-fr">
                <div class="form-attest">
                    <div class="title">申请表单</div>
                    <div class="box">

                        <div class="m-title">基本信息</div>
                        <div class="message clearfix"> <font>您的身份</font>
                            <div class="radio-box">
                                <label>
                                    <input type="radio" name='member_type' value='0' checked> <em>农户</em>
                                </label>
                                <label>
                                    <input type="radio" name='member_type'  value='1' > <em>农业企业</em>
                                </label>
                                <label>
                                    <input type="radio" name='member_type' value='2' >
                                    <em>家庭农场</em>
                                </label>

                                <label>
                                    <input type="radio" name='member_type' value='3' >
                                    <em>农业合作社</em>
                                </label>

                            </div>
                        </div>
                        <!-- 可信农场 农户 -->
                        <div class="buyer-common"  id='member_buyer_0' style="display:block;" >

                            <div class="message clearfix"> <font>姓名</font>
                                <div class="input-box">
                                    <input type="text" name='name' value='' data-rule="required;chinese"  />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>身份证号</font>
                                <div class="input-box">
                                    <input type="text" name='credit_no' value=''   data-rule="required;ID_card;" />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>手机号</font>
                                <div class="input-box">
                                    <input type="text" name='mobile'  value=''   data-rule="required;mobile" />
                                </div>
                            </div>
                            <div class="m-title mt20">认证信息</div>
                            <div class="message clearfix">
                                <font>银行卡开户行</font>
                                <div class="select-box lang-select" >
                                    <select name='user_bank_name' data-rule="required;" >
                                        <option value=''>请选择</option>
                                        {% for key , item in  bankList %}
                                        <option value='{{ item['gate_id']}}'>{{ item['bank_name']}}</option>
                                        {% endfor %}
                                    </select>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>开户行所在地</font>
                                <div class="select-box lang-select">
                                    <select name='bank_province_id' class='class_bank_address'>
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='bank_city_id'  class='class_bank_address' >
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='bank_district_id'  class='class_bank_address' data-rule="required;" >
                                        <option value=''>请选择</option>
                                    </select>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>开户名</font>
                                <div class="input-box">
                                    <input type="text" name='bank_account' id='bank_account' data-rule="required;chinese" />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>卡号</font>
                                <div class="input-box">
                                    <input type="text" name='bank_cardno' data-rule="required;mark" />
                                </div>
                            </div>
                           <!-- <div class="message clearfix">
                                <font>银行卡证明照</font>
                                <div class="loadImg-box">
                                    <div class="imgs" id='user_show_bankcard_picture'>
                                    </div>
                                    
                                    <div class="file-btn">
                                       
                                        <input class="btn2" type="file" name='user_bankcard_picture' id='user_bankcard_picture'/>
                                        <input id="user_bankcard_picture_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#user_bankcard_picture_tip" />
                                        <i id='user_bankcard_picture_tip' style="position:absolute; left:176px; top:-6px;"></i>

                                        <a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img1.jpg">查看样照</a>
                                    </div>

                                </div>
                            </div>-->
                            
                            <!--<div class="message clearfix">
                                <font>个人手持身份证照片</font>
                                <div class="loadImg-box">
                                    <div class="imgs" id='user_show_person_picture'>
                                        </div>
                                    <div class="file-btn">
                                     
                                        <input class="btn2" type="file"  name='user_person_picture' id='user_person_picture'/>
                                        <input id="user_person_picture_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#user_person_picture_tip"  />
                                        <i id='user_person_picture_tip' style="position:absolute; left:176px; top:-6px;"></i>

                                        <a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img2.jpg">查看样照</a>
                                    </div>
                                </div>
                            </div>-->
                            <div class="message clearfix">
                                <font>身份证照</font>
                                <div class="loadImg-box">
                                    <div class="imgs" id='user_show_idcard_picture'>
                                        </div>
                                    <div class="file-btn">
                                       
                                        <input class="btn2" type="file" name='user_idcard_picture' id='user_idcard_picture' />
                                        <input id="user_idcard_picture_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;" data-target="#user_idcard_picture_tip" />
                                        <i id='user_idcard_picture_tip' style="position:absolute; left:176px; top:-6px;"></i>

                                        <a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg ">查看样照</a>
                                    </div>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>身份证背面</font>
                                <div class="loadImg-box">
                                    <div class="imgs" id='user_show_idcard_picture_back'>
                                        </div>
                                    <div class="file-btn">
                                        
                                        <input class="btn2" type="file" name='user_idcard_picture_back' id='user_idcard_picture_back' />
                                        <input id="user_idcard_picture_back_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#user_idcard_picture_back_tip"/>
                                        <i id='user_idcard_picture_back_tip' style="position:absolute; left:176px; top:-6px;"></i>
                                        <a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg ">查看样照</a>
                                    </div>
                                </div>
                            </div>
                            <div class="m-title mt20">农场信息</div>
                            <div class="message clearfix">
                                <font>农场名</font>
                                <div class="input-box">
                                    <input type="text" name='farm_name' id='farm_name' data-rule="required;" />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>农场地址</font>
                                <div class="select-box" >
                                    <select name='user_province_id'  class='selectAreas'>
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='user_city_id'  class='selectAreas' >
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='user_district_id'  class='selectAreas' >
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='user_town_id'  class='selectAreas' >
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='user_village_id'  class='selectAreas' data-rule="required;"  >
                                        <option value=''>请选择</option>
                                    </select>
                                    <input class="f-db" type="text" name='user_address' id='user_address' data-rule="required;" />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>农场面积</font>
                                <div class="input-box short-input">
                                    <input type="text" name='farm_areas'  data-rule="required;digits"  data-target="#user_farm_areas" /> <i>亩</i>
                                    <span id='user_farm_areas'></span>
                                </div>
                            </div>

                            <div class="message clearfix">
                                <font>种植作物</font>
                                <div class="select-box lang-select clearfix categrey-option">
                                    <div class="choose-box f-fl">
                                        <select name='category_name' onchange="selectBycate(this.value, '0' )">
                                            {% for key , item in cateList %}
                                            <option value="{{ item.id }}">{{ item.title}}</option>
                                            {% endfor %}
                                        </select>
                                        <div class="erji-box" id='show_cate_chid_0'></div>
                                    </div>
                                    <div class="btn-box f-fl">
                                        <a class="btn1" href="javascript:;">添加</a>
                                        <a class="btn2" href="javascript:;">删除</a>
                                    </div>
                                    <div class="result-box f-fl" id='result-box_0'></div>

                                    <input id="category_name_text_0" name='category_name_text_0' style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#category_name_0_tip" />
                                    <i id='category_name_0_tip'></i>
                                </div>                               
                            </div>

                            <div class="message clearfix">
                                <font>农场照片</font>
                                <div class="loadImg-box">
                                    <div class="imgs" style='width:1200px;border:solid 0px #e4e4e4' id='user_show_picture_path'>
                                        <ul id='user_show_picture_path'>
                                
                                        </ul>
                                    </div>
                                    <div class="file-btn">
                                        <input class="btn2" type="file"  name='user_picture_path' id='user_picture_path' data-rule="required;"/>
                                    </div>
                                </div>
                                <div style="margin-top: 20px;">（农场主手持租地合同或租地证明及身份证在所属土地面前的照片）</div>                                
                            </div>
                                <div class="message clearfix">
                                                <font>农场合同</font>
                                                <div class="loadImg-box">
                                                   
                        <div class="imgs"  style='width:1200px;border:solid 0px #e4e4e4' id='user_show_picture_path_contact'>
                            <ul id='user_show_picture_path_contact'>
                                
                            </ul>
                        </div>
                                            
                                                    <div class="file-btn">
                                                        
            <input class="btn2" type="file" name='user_picture_path_contact' id='user_picture_path_contact' />
            <input id="user_picture_path_hide_contact" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#user_picture_path_contact_hide_tip" />
            <i id='user_picture_path_contact_hide_tip' style="position:absolute; left:176px; top:-6px;"></i>
            
                                                    </div>
                                                </div>
                                            </div>


                            <div class="message clearfix">
                                <font>土地来源</font>
                                <div class="radio-box">
                                    <label>
                                        <input type="radio" name='source' value='0' checked>
                                        <em>自有</em>
                                    </label>
                                    <label>
                                        <input type="radio" name='source' value='1' >
                                        <em>流转</em>
                                    </label>
                                </div>
                            </div>

                            <div class="message clearfix">
                                <font>土地使用年限</font>
                                <div class="input-box">

               <input readonly="readonly" type="text" class="Wdate" name="user_stime" id="d4331" onfocus="WdatePicker({dateFmt: 'yyyy-MM',maxDate:'#F{$dp.$D(\'d4332\',{M:0,d:0})}'})" value=""  data-rule="required;" data-target="#date_tip">
               
               <input readonly="readonly" type="text" class="Wdate" name="user_etime" id="d4332" onfocus="WdatePicker({dateFmt: 'yyyy-MM',minDate:'#F{$dp.$D(\'d4331\',{M:0,d:1});}',maxDate:'2020-4-3'})" value="" data-rule="required;" data-target="#date_tip">
               <i id='date_tip'></i>

                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>农场简介</font>
                                <div class="textarea-box">
                                    <textarea name='user_describe' ></textarea>
                                    <i>（选填）</i>
                                </div>
                            </div>
                           
                                                                                 
                            <div class="message clearfix">
                                <font>推荐人</font>
                                <div class="input-box">
                                    <input type="text" name='seusername' data-rule="mobile"/>（选填）
                                                                                                          
                                </div>
                                 <div style="margin-top: 5px;">（可以是县域服务中心或责任服务工程师）</div>
                            </div>
                            
                            <div class="message clearfix">
                                <input type="checkbox" checked="true" onclick='checkSubmit(this)'  />
                                     我已阅读并同意<a href="/云农场IF协议.doc">《云农场可信农场协议》</a>
                            </div>
                            
                            
                        </div>

                        <!-- 可信农场 农业企业 -->
                        <div class="buyer-common"  id='member_buyer_1'  style='display:none'>
                            <div class="message clearfix">
                                <font>公司名称</font>
                                <div class="input-box">
                                    <input type="text" name='ent_company_name'  data-rule="required;"   />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>注册登记证号</font>
                                <div class="input-box">
                                    <input type="text" name='ent_register_no' data-rule="required;" />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>公司地址</font>
                                <div class="select-box">
                                    <select name='province_id' class='ent_selectAreas'>
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='city_id' class='ent_selectAreas'>
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='district_id'  class='ent_selectAreas'>
                                        <option value=''>请选择</option>
                                    </select>
                                    <select  name='town_id' class='ent_selectAreas' data-rule="required;">
                                        <option value=''>请选择</option>
                                    </select>
                                    <br>
                                    <input class="f-db" type="text" name='address' />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>企业法人</font>
                                <div class="input-box">
                                    <input type="text" name='ent_erprise_legal_person' data-rule="required;chinese" />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>身份证号</font>
                                <div class="input-box">
                                    <input type="text" name='ent_certificate_no'  data-rule="required;ID_card"  />
                                </div>
                            </div>
                            <div class="m-title mt20">联系人信息</div>
                            <div class="message clearfix">
                                <font>姓名</font>
                                <div class="input-box">
                                    <input type="text" name='ent_contact_name'  data-rule="required;chinese"/>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>手机号</font>
                                <div class="input-box">
                                    <input type="text" name='ent_contact_phone'  data-rule="required;mobile" />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>传真</font>
                                <div class="input-box">
                                    <input type="text"  name='ent_contact_fax'/>
                                    <i>（没有可不填）</i>
                                </div>
                            </div>
                            <div class="m-title mt20">认证信息</div>
                            <div class="message clearfix">
                                <font>银行卡开户行</font>
                                <div class="select-box lang-select">
                                    <select name='ent_bank_name'  data-rule="required;" >
                                        <option value=''>请选择</option>
                                        {% for key , item in  bankList %}
                                        <option value='{{ item['gate_id']}}'>{{ item['bank_name']}}</option>
                                        {% endfor %}
                                    </select>
                                </div>
                            </div>

                            <div class="message clearfix">
                                <font>开户行所在地</font>
                                <div class="select-box lang-select">
                                    <select name='ent_bank_province_id' class='ent_class_bank_address'>
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='ent_bank_city_id'  class='ent_class_bank_address' >
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='ent_bank_district_id'  class='ent_class_bank_address' data-rule="required;" >
                                        <option value=''>请选择</option>
                                    </select>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>开户名</font>
                                <div class="input-box">
                                    <input type="text" name='ent_bank_account'  data-rule="required;chinese" />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>卡号</font>
                                <div class="input-box">
                                    <input type="text"  name='ent_bank_cardno' data-rule="required;mark" />
                                </div>
                            </div>
                            <!--
                            <div class="message clearfix">
                                <font>银行卡证明照</font>
                                <div class="loadImg-box">
                                    <div class="imgs" id='ent_show_bankcard_picture'>
                                        </div>
                                    <div class="file-btn">
                                            <input class="btn2" type="file" name='ent_bankcard_picture' id='ent_bankcard_picture' />
                                                <input id="ent_bankcard_picture_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#ent_bankcard_picture_tip" />
                                                <i id='ent_bankcard_picture_tip' style="position:absolute; left:176px; top:-6px;"></i>

                                        <a target="_blank" href="{{ constant("STATIC_URL")}}/mdg/images/lodeImg_img1.jpg">查看样照</a>
                                    </div>
                                </div>
                            </div>
                            -->
                            <div class="message clearfix">
                                <font>个体工商营业执照</font>
                                <div class="loadImg-box">
                                    <div class="imgs" id='ent_show_identity_picture_lic'>
                                        </div>
                                    <div class="file-btn">
                                       
                                        <input class="btn2" type="file" id='ent_identity_picture_lic'/>
                                        <input id="ent_identity_picture_lic_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#ent_identity_picture_lic_tip" />
                                        <i id='ent_identity_picture_lic_tip' style="position:absolute; left:176px; top:-6px;"></i>

                                        <a target="_blank" href="{{ constant("STAIS_URL")}}/mdg/images/lodeImg_img4.jpg">查看样照</a>
                                    </div>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>身份证照</font>
                                <div class="loadImg-box">
                                    <div class="imgs" id='ent_show_idcard_picture'>
                                        </div>
                                    <div class="file-btn">
                                       
                                        <input class="btn2" type="file" id='ent_idcard_picture' />
                                        <input id="ent_idcard_picture_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#ent_idcard_picture_tip" />
                                        <i id='ent_idcard_picture_tip' style="position:absolute; left:176px; top:-6px;"></i>


                                        <a target="_blank" href="{{ constant("STAIS_URL")}}/mdg/images/lodeImg_img3.jpg">查看样照</a>
                                    </div>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>身份证背面</font>
                                <div class="loadImg-box">
                                    <div class="imgs" id='ent_show_idcard_picture_back'>
                                        </div>
                                    <div class="file-btn">
                                        
                                        <input class="btn2" type="file" name='ent_idcard_picture_back' id='ent_idcard_picture_back' />
                                        <input id="ent_idcard_picture_back_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#ent_idcard_picture_back_tip" />
                                        <i id='ent_idcard_picture_back_tip' style="position:absolute; left:176px; top:-6px;"></i>

                                        <a target="_blank" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg ">查看样照</a>
                                    </div>
                                </div>
                            </div>

                            <div class="m-title mt20">农场信息</div>
                            <div class="message clearfix">
                                <font>农场名</font>
                                <div class="input-box">
                                    <input type="text" name='ent_farm_name'  data-rule="required;chinese" />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>农场地址</font>
                                <div class="select-box">
                                    <select name='ent_province_id'  class='ent_farm_selectAreas'>
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='ent_city_id'  class='ent_farm_selectAreas' >
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='ent_district_id'  class='ent_farm_selectAreas' >
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='ent_town_id'  class='ent_farm_selectAreas' >
                                        <option value=''>请选择</option>
                                    </select>
                                    <select name='ent_village_id'  class='ent_farm_selectAreas'  data-rule="required;" >
                                        <option value=''>请选择</option>
                                    </select>
                                    <input class="f-db" type="text" name='ent_address' id='ent_address'  data-rule="required;" />
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>农场面积</font>
                                <div class="input-box short-input">
                                    <input type="text" name='ent_farm_area' data-rule="required;digits"  data-target="#ent_farm_areas"  />
                                    <i>亩</i>
                                     <span id='ent_farm_areas'></span>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>种植作物</font>
                                <div class="select-box lang-select clearfix categrey-option">
                                    <div class="choose-box f-fl">
                                        <select name='category_name' onchange="selectBycate(this.value, '1')">
                                            {% for key , item in cateList %}
                                            <option value="{{ item.id }}">{{ item.title}}</option>
                                            {% endfor %}
                                        </select>
                                        <div class="erji-box" id='show_cate_chid_1'></div>
                                    </div>
                                    <div class="btn-box f-fl">
                                        <a class="btn1" href="javascript:;">添加</a>
                                        <a class="btn2" href="javascript:;">删除</a>
                                    </div>
                                    <div class="result-box f-fl" id='result-box_1'></div>

                                    <input id="category_name_text_1" name='category_name_text_1' style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#category_name_1_tip" />
                                    <i id='category_name_1_tip'></i>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>农场照片</font>
                                <div class="loadImg-box">
                                    <div class="imgs" style='width:1200px;border:solid 0px #e4e4e4' id='user_show_picture_path'>
                                        <ul id='ent_show_picture_path'>
                                
                                        </ul>
                                    </div>

                                    
                                    <div class="file-btn">
                                       
                                        <input class="btn2" type="file"  name='ent_picture_path' id='ent_picture_path' data-rule="required;"/>
                                    </div>
                                </div>
                               <div style="margin-top: 20px;">（农场主手持租地合同或租地证明及身份证在所属土地面前的照片）</div>                                
                            </div>
                            <div class="message clearfix">
                                                <font>农场合同</font>
                                                <div class="loadImg-box">
                                                        <div class="imgs" style='width:1200px;border:solid 0px #e4e4e4' >
                                        <ul id='ent_show_picture_path_contact'>
                                
                                        </ul>
                                    </div>
                                    
                                                    <div class="file-btn">
                                                        
            <input class="btn2" type="file" name='ent_picture_path_contact' id='ent_picture_path_contact' />
            <input id="ent_picture_path_hide_contact" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="required;"  data-target="#ent_picture_path_contact_hide_tip" />
            <i id='ent_picture_path_contact_hide_tip' style="position:absolute; left:176px; top:-6px;"></i>
            
                                                    </div>
                                                </div>
                                            </div>

                            
                            <div class="message clearfix">
                                <font>土地来源</font>
                                <div class="radio-box">
                                    <label>
                                        <input type="radio" name='ent_source'  value='0' checked>
                                        <em>自有</em>
                                    </label>
                                    <label>
                                        <input type="radio"  name='ent_source' value='1' >
                                        <em>流转</em>
                                    </label>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>土地使用年限</font>
                                <div class="input-box" >

            <input readonly="readonly" type="text" class="Wdate" name="ent_stime" id="d43313" onfocus="WdatePicker({dateFmt: 'yyyy-MM',maxDate:'#F{$dp.$D(\'d4334\',{M:0,d:0})}'})" value=""  data-rule="required;" data-target="#dateent_tip">

               <input readonly="readonly" type="text" class="Wdate" name="ent_etime" id="d4334" onfocus="WdatePicker({dateFmt: 'yyyy-MM',minDate:'#F{$dp.$D(\'d43313\',{M:0,d:1});}',maxDate:'2020-4-3'})" value="" data-rule="required;" data-target="#dateent_tip">
               <i id='dateent_tip'></i>

                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>农场简介</font>
                                <div class="textarea-box">
                                    <textarea name='ent_describe'></textarea>
                                    <i>（选填）</i>
                                </div>
                            </div>
                            
                            <div class="message clearfix">
                                <font>推荐人</font>
                                <div class="input-box">
                                    <input type="text" name='ent_seusername' data-rule="mobile"/>
                                                                                                           
                                </div>（选填）
                                <div style="margin-top: 5px;">（可以是县域服务中心或责任服务工程师）</div>
                            </div>
                            
                            <div class="message clearfix">
                                <input type="checkbox" checked="true" onclick='checkSubmit(this)' />
                                     我已阅读并同意<a href="mdg/云农场IF协议.doc">《云农场可信农场协议》</a>
                            </div>                            
                        </div>

                    </div>
                    <input class="buyer-btn classSubmit" type="submit" value="提交申请" />
                </div>

            </div>
        </form>
    </div>
    

</div>

<!--尾部 start-->
{{ partial('layouts/footer') }}



<!--尾部 end-->
<script type="text/javascript">
$('#userForm').validator({
    ignore: ':hidden'

});

function checkSubmit(obj) {
    alert($(obj).prop('checked'));

    if(!$(obj).prop('checked')) {
        $('.classSubmit').attr('disabled', true);
    }else{
        $('.classSubmit').removeAttr('disabled');
    }
}
            
$(function () {


        $('input[name="member_type"]').click(function () {
            var val = $(this).val();
            if(val == 0 ) {
                $('#member_buyer_0').show();
                $('#member_buyer_1').hide();
                $('#category_name_text_0').val('');
                selectBycate(1, 0);
                $('#userForm').validator('cleanUp');
                

            }else{
                $('#member_buyer_1').show();
                $('#member_buyer_0').hide();
                selectBycate(1, 1);
                $('#category_name_text_1').val('');
                $('#userForm').validator('cleanUp');

            }
        })
    })

// 删除图片
function closeImg(obj, id, key ) {
    
    $.getJSON('/upload/deltmpfile', {id : id}, function(data) {
        alert(data.msg);
        if(data.state) {
            $(obj).parents('dl').slideUp();
        }
    });
}

</script>

<script type="text/javascript" src="/mdg/js/user_farm.js?sid={{ sid }}&rand=<?php echo rand(1,999);?>"></script>
<style>
.upload_btn {width:89px; height:25px; border: solid 1px #99be20; color:#99be20; background: #fff; text-align: center; line-height:25px;
  font-family: '微软雅黑';
  cursor: pointer;
  position: relative;}
.edui-default .edui-editor{ margin:10px auto;}
</style>